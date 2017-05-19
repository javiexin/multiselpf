<?php
/**
 *
 * Advanced Profile Fields Pack - Image Profile Fields Base
 *
 * @copyright (c) 2015-2017 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\profilefields\type;

abstract class type_img_base extends \phpbb\profilefields\type\type_base
{
	/**
	* Request object
	* @var \phpbb\request\request
	*/
	protected $request;

	/**
	* Template object
	* @var \phpbb\template\template
	*/
	protected $template;

	/**
	* User object
	* @var \phpbb\user
	*/
	protected $user;

	/**
	* Current $phpbb_root_path
	* @var string
	*/
	protected $phpbb_root_path;

	/**
	* Current $php_ext
	* @var string
	*/
	protected $php_ext;

	/**
	* Path Helper
	* @var \phpbb\path_helper
	*/
	protected $path_helper;

	/**
	* Files Factory
	* @var \phpbb\files\factory
	*/
	protected $files_factory;

	/**
	* Array of allowed image extensions
	* @var array
	*/
	protected $allowed_extensions = array(
		'gif',
		'jpg',
		'jpeg',
		'png',
	);
	
	/**
	* Vendor name
	* @var string
	*/
	protected $vendor_name = 'javiexin';

	/**
	* Extension name
	* @var string
	*/
	protected $extension_name = 'advancedpf';

	/**
	* Error array kept between immediate steps
	* @var array
	*/
	protected $error = array();	

	/**
	* Phpbb switch to keep compatibility across versions
	* @var boolean
	*/
	protected $is_version_32;

	/**
	* Construct
	*
	* @param	\phpbb\request\request				$request			Request object
	* @param	\phpbb\template\template			$template			Template object
	* @param	\phpbb\user							$user				User object
	* @param	string								$phpbb_root_path	Path to the phpBB root
	* @param	string								$php_ext			PHP file extension
	* @param	\phpbb\path_helper					$path_helper		phpBB path helper
	* @param	\phpbb\files\factory				$files_factory		phpBB files factory (optional, 3.2 only)
	*/
	public function __construct(\phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext, \phpbb\path_helper $path_helper, \phpbb\files\factory $files_factory = null)
	{
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->path_helper = $path_helper;
		$this->files_factory = $files_factory;
		$this->is_version_32 = phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');
	}

	/**
	* {@inheritDoc}
	*/
	public function get_service_name()
	{
		return $this->vendor_name . '.' . $this->extension_name . '.' . 'profilefields.type' . '.' . $this->get_name_short();
	}

	/**
	* {@inheritDoc}
	*/
	public function get_template_filename()
	{
		return '@' . $this->vendor_name . '_' . $this->extension_name . '/profilefields/' . $this->get_name_short() . '.html';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_default_option_values()
	{
		return array(
			'field_length'		=> 0,
			'field_validation'	=> 'images/' . $this->get_name_short(),
			'field_novalue'		=> '',
			'field_default_value'	=> '',
		);
	}

	/**
	* {@inheritDoc}
	*/
	public function get_default_field_value($field_data)
	{
		return $field_data['field_default_value'];
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_profile_field(&$field_value, $field_data)
	{
		$error = array();

		if (isset($this->error[$field_data['field_ident']]))
		{
			$error = $this->error[$field_data['field_ident']];
			if (sizeof($error))
			{
				$field_value = '';
			}
			unset($this->error[$field_data['field_ident']]);
		}

		if ($field_data['field_required'] && (!$field_value || $field_value == $field_data['field_novalue']))
		{
			$error[] = $this->user->lang('FIELD_REQUIRED', $this->get_field_name($field_data['lang_name']));
		}

		$filename = preg_replace('#\|.*$#', '', $field_value);
		$path = $this->phpbb_root_path . $field_data['field_validation'] . '/' . $filename;

		if ($filename && (!file_exists($path) || !is_readable($path)))
		{
			$error[] = $this->user->lang('FIELD_INVALID_VALUE', $this->get_field_name($field_data['lang_name']));
		}

		return (sizeof($error)) ? implode('<br />', $error) : false;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_value($field_value, $field_data)
	{
		if ($field_value == $field_data['field_novalue'] && !$field_data['field_show_novalue'])
		{
			return null;
		}

		if (!$field_value && $field_data['field_show_novalue'])
		{
			$field_value = $field_data['field_novalue'];
		}

		if (!$field_value)
		{
			return null;
		}

		return $this->get_html_from_value($field_data['field_validation'], $field_value, $this->field_title($field_data));
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_value_raw($field_value, $field_data)
	{
		if ($field_value == $field_data['field_novalue'] && !$field_data['field_show_novalue'])
		{
			return null;
		}

		if (!$field_value && $field_data['field_show_novalue'])
		{
			$field_value = $field_data['field_novalue'];
		}

		if (!$field_value)
		{
			return null;
		}

		list($file_name, $width, $height) = explode('|', $field_value);

		return $this->get_name_from_filename($file_name);
	}

	/**
	* Get the HTML code to display an image, used as base for generate_field
	* @param array $profile_row Data for the profile field
	* @param mixed $preview_options preview|false
	* @return string value for the field
	*/
	protected function generate_field_base($profile_row, $preview_options)
	{
		$profile_row['field_ident'] = (isset($profile_row['var_name'])) ? $profile_row['var_name'] : 'pf_' . $profile_row['field_ident'];
		$field_ident = $profile_row['field_ident'];
		$default_value = $profile_row['field_default_value'];

		$value = (isset($this->user->profile_fields[$field_ident]) && $preview_options === false) ? $this->user->profile_fields[$field_ident] : $default_value;
		$value = ($this->request->is_set($field_ident)) ? $this->request->variable($field_ident, $default_value) : $value;

		$profile_row['field_value'] = $value;
		$profile_row['field_display'] = $value ? $this->get_html_from_value($profile_row['field_validation'], $value, $this->field_title($profile_row)) : '';

		$cp_vars = array_change_key_case($profile_row, CASE_UPPER);
		$cp_vars['LANG_NAME'] = $this->user->lang($profile_row['lang_name']);
		$cp_vars['LANG_EXPLAIN'] = $this->user->lang($profile_row['lang_explain']);
		$cp_vars['LANG_DEFAULT_VALUE'] = $this->user->lang($profile_row['lang_default_value']);

		$this->template->assign_block_vars($this->get_name_short(), $cp_vars);

		return $value;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_database_column_type()
	{
		return 'VCHAR';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_language_options($field_data)
	{
		$options = array(
			'lang_name' => 'string',
		);

		if ($field_data['lang_explain'])
		{
			$options['lang_explain'] = 'text';
		}

		if (strlen($field_data['lang_default_value']))
		{
			$options['lang_default_value'] = 'string';
		}

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function display_options(&$template_vars, &$field_data)
	{
		$template_vars = array_merge($template_vars, array(
			'S_IMAGE'					=> true,
			'LANG_NOTSPECIFIED_VALUE'	=> $field_data['lang_default_value'],
		));
	}

	/**
	* {@inheritDoc}
	*/
	public function prepare_options_form(&$exclude_options, &$visibility_options)
	{
		$exclude_options[1][] = 'lang_default_value';

		return $this->request->variable('lang_options', '', true);
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_options_on_submit($error, $field_data)
	{
		// If previous checks failed, we fail here
		if (isset($this->error[$field_data['field_ident']]))
		{
			$error = array_merge($error, $this->error[$field_data['field_ident']]);
			unset($this->error[$field_data['field_ident']]);
		}

		return $error;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_excluded_options($key, $action, $current_value, &$field_data, $step)
	{
		if ($step == 2 && $key == 'field_validation' && $this->request->is_set('field_storage_path'))
		{
			$storage_path = $this->request->variable('field_storage_path', $current_value, true);

			// No directory specified
			if (!$storage_path && !$current_value)
			{
				$this->error[$field_data['field_ident']][] = $this->user->lang['IMG_NO_PATH'];
				return $current_value;
			}

			// Adjust storage path (no trailing slash, no ./ or ../)
			if (substr($storage_path, -1, 1) == '/' || substr($storage_path, -1, 1) == '\\')
			{
				$storage_path = substr($storage_path, 0, -1);
			}
			$storage_path = str_replace(array('../', '..\\', './', '.\\'), '', $storage_path);
			if ($storage_path && ($storage_path[0] == '/' || $storage_path[0] == "\\"))
			{
				$this->error[$field_data['field_ident']][] = $this->user->lang['IMG_PATH_INCORRECT'];
				return $current_value;
			}
			$storage_path = trim($storage_path);

			// No null chars allowed
			if (strpos($storage_path, "\0") !== false || strpos($storage_path, '%00') !== false)
			{
				$this->error[$field_data['field_ident']][] = $this->user->lang['IMG_PATH_INCORRECT'];
				return $current_value;
			}

			$path = $this->phpbb_root_path . $storage_path;

			// Path must exist, be a directory and be writable
			if (!file_exists($path))
			{
				$this->error[$field_data['field_ident']][] = sprintf($this->user->lang['IMG_DIR_DOES_NOT_EXIST'], $storage_path);
			}
			else if (!is_dir($path))
			{
				$this->error[$field_data['field_ident']][] = sprintf($this->user->lang['IMG_DIR_NOT_DIR'], $storage_path);
			}
			else if (!$this->is_writable($path))
			{
				$this->error[$field_data['field_ident']][] = sprintf($this->user->lang['IMG_DIR_NOT_WRITABLE'], $storage_path);
			}
			else
			{
				$current_value = $storage_path;
			}

			return $current_value;
		}
		if ($step == 2 && $key == 'field_novalue' && $this->request->is_set('field_default_value'))
		{
			return $this->request->variable('field_default_value', '', true);
		}

		return parent::get_excluded_options($key, $action, $current_value, $field_data, $step);
	}

	/**
	* {@inheritDoc}
	*/
	public function prepare_hidden_fields($step, $key, $action, &$field_data)
	{
		if ($key == 'field_validation' && isset($field_data[$key]))
		{
			return $field_data[$key];
		}
		return parent::prepare_hidden_fields($step, $key, $action, $field_data);
	}

	/**
	* Get title to use when displaying image
	* @param array $field_data Data for the field
	* @return string Title for image, empty to calculate based on filename
	*/
	protected function field_title($field_data)
	{
		return '';
	}

	/**
	* Get the HTML code to display an image
	* @param string $dir_name Directory where images are stored
	* @param mixed $value String (filename|width|height) or array
	* @return string Html code to show image
	*/
	protected function get_html_from_value($dir_name, $value, $title = '')
	{
		if (!is_array($value))
		{
			list($image, $width, $height) = explode('|', $value);
			$name = $title ?: $this->get_name_from_filename($image);
			$image = rawurlencode($image);
		}
		else
		{
			$image = $value['filename'];
			$name = $title ?: $value['name'];
			$width = $value['width'];
			$height = $value['height'];
		}

		$path = $this->get_url_from_filename($dir_name, $image);

		$html = '<img src="' . $path . '" ' .
			((isset($width) && $width) ? ('width="' . $width . '" ') : '') .
			((isset($height) && $height) ? ('height="' . $height . '" ') : '') .
			((isset($name) && $name) ? ('title="' . $name . '" ') : '') .
			'alt="' . ((isset($name) && $name) ? $name : '') . '" />';

		return $html;
	}

	/**
	* Get name from filename
	* @param string $filename Filename of image, without directory
	* @return string Name fo image
	*/
	protected function get_name_from_filename($filename)
	{
		return ucfirst(str_replace('_', ' ', preg_replace('#^(?:[0-9]+_)?(.*)\..*$#', '\1', $filename)));
	}

	/**
	* Get url from filename
	* @param string $dir_name Directory where images are stored
	* @param string $filename Filename of image, without directory
	* @return string Name fo image
	*/
	protected function get_url_from_filename($dir_name, $filename)
	{
		$root_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? generate_board_url() . '/' : $this->path_helper->get_web_root_path();
		return $root_path . $dir_name . '/' . $filename;
	}
}
