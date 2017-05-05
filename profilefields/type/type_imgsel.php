<?php
/**
 *
 * Advanced Profile Fields Pack - Image Selector PF
 *
 * @copyright (c) 2015-2017 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\profilefields\type;

class type_imgsel extends type_img_base
{
	/**
	* {@inheritDoc}
	*/
	public function get_name_short()
	{
		return 'imgsel';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_options($default_lang_id, $field_data)
	{
		$profile_row[0] = array(
			'var_name'				=> 'field_default_value',
			'field_id'				=> 1,
			'lang_name'				=> $field_data['lang_name'],
			'lang_explain'			=> $field_data['lang_explain'],
			'lang_id'				=> $default_lang_id,
			'field_default_value'	=> $field_data['field_default_value'],
			'field_validation'		=> $field_data['field_validation'],
			'field_length'			=> $field_data['field_length'],
			'field_ident'			=> 'field_default_value',
			'field_type'			=> $this->get_service_name(),
			'lang_options'			=> $field_data['lang_options'],
			'lang_default_value'	=> $field_data['lang_default_value'],
		);

		$options = array(
			0 => array('TITLE' => $this->user->lang['IMGSEL_STORAGE_PATH'], 'EXPLAIN' => $this->user->lang['IMGSEL_STORAGE_PATH_EXPLAIN'], 'FIELD' => '<input type="text" size="20" maxlength="255" name="field_storage_path" value="' . $field_data['field_validation'] . '" onchange="document.getElementById(\'add_profile_field\').submit();" />'),
			1 => array('TITLE' => $this->user->lang['IMGSEL_SELECT_AS'], 'EXPLAIN' => $this->user->lang['IMGSEL_SELECT_AS_EXPLAIN'], 'FIELD' => '<label><input type="radio" class="radio" name="field_length" value="0"' . (($field_data['field_length'] == 0) ? ' checked="checked"' : '') . ' onchange="document.getElementById(\'add_profile_field\').submit();" /> ' . $this->user->lang['IMGSEL_AS_DROPDOWN'] . '</label><label><input type="radio" class="radio" name="field_length" value="1"' . (($field_data['field_length'] == 1) ? ' checked="checked"' : '') . ' onchange="document.getElementById(\'add_profile_field\').submit();" /> ' . $this->user->lang['IMGSEL_AS_PANEL'] . '</label>'),
			2 => array('TITLE' => $this->user->lang['DEFAULT_VALUE'], 'FIELD' => $this->process_field_row('preview', $profile_row[0])),
		);

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_default_option_values()
	{
		return array_merge(parent::get_default_option_values(), array(
			'field_minlen'		=> 0,
			'field_maxlen'		=> 0,
		));
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_field($profile_row)
	{
		$var_name = 'pf_' . $profile_row['field_ident'];
		return $this->request->variable($var_name, $profile_row['field_default_value']);
	}

	/**
	* {@inheritDoc}
	*/
	public function generate_field($profile_row, $preview_options = false)
	{
		$value = parent::generate_field_base($profile_row, $preview_options);

		$value_raw = $value ? preg_replace('#\|.*$#', '', $value) : '';

		$options = $this->get_image_list($profile_row['field_validation']);
		foreach ($options as $option_value)
		{
			$this->template->assign_block_vars($this->get_name_short() . '.options', array(
				'S_CURRENT'	=> strcmp($value_raw, $option_value['filename']) ? 0 : 1,
				'NAME'		=> $option_value['name'],
				'VALUE'		=> $option_value['filename'] . '|' . $option_value['width'] . '|' . $option_value['height'],
				'DISPLAY'	=> $this->get_html_from_value($profile_row['field_validation'], $option_value),
			));
		}
	}

	/**
	* {@inheritDoc}
	*/
	public function display_options(&$template_vars, &$field_data)
	{
		parent::display_options($template_vars, $field_data);
		$template_vars = array_merge($template_vars, array(
			'S_IMAGE_SELECTOR'			=> true,
		));
	}

	/**
	* Check if a directory is writable
	* @param string $path Directory to check
	* @return bool Always true as we do not care
	*/
	protected function is_writable($path)
	{
		return true;
	}

	/**
	* List of images in configured directory
	* @var mixed Array of images per directory
	*/
	protected $image_list = array();

	/**
	* Get a list of images that are available in the configured directory
	* Results are stored within the class for further use
	* @param string $dir_name Directory where images are stored
	* @return array Array containing the locally available images
	*/
	protected function get_image_list($dir_name)
	{
		if (!isset($this->image_list[$dir_name]))
		{
			$this->image_list[$dir_name] = array();
			$path = $this->phpbb_root_path . $dir_name;

			if (file_exists($path) && is_dir($path) && is_readable($path))
			{
				$iterator = new \IteratorIterator(new \DirectoryIterator($path /*, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS*/));
				foreach ($iterator as $file_info)
				{
					$file_path = $file_info->getPath();
					$image = $file_info->getFilename();

					// Match all images in the folder
					if (preg_match('#^[^&\'"<>]+\.(?:' . implode('|', $this->allowed_extensions) . ')$#i', $image) && is_file($file_path . '/' . $image))
					{
						if (function_exists('getimagesize'))
						{
							$dims = getimagesize($file_path . '/' . $image);
						}
						else
						{
							$dims = array(0, 0);
						}
						$this->image_list[$dir_name][$image] = array(
							'filename'  => rawurlencode($image),
							'name'      => $this->get_name_from_filename($image),
							'width'     => $dims[0],
							'height'    => $dims[1],
						);
					}
				}
				ksort($this->image_list[$dir_name]);
			}
		}

		return $this->image_list[$dir_name];
	}
}
