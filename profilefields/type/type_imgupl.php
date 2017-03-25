<?php
/**
 *
 * Advanced Profile Fields Pack - Image Upload PF
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\profilefields\type;

class type_imgupl extends type_img_base
{
	/**
	* {@inheritDoc}
	*/
	public function get_name_short()
	{
		return 'imgupl';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_options($default_lang_id, $field_data)
	{
		list($img_min_width, $img_min_height) = array_map('intval', explode('|', $field_data['field_minlen']));
		list($img_max_width, $img_max_height) = array_map('intval', explode('|', $field_data['field_maxlen']));

		$options = array(
			0 => array(
					'TITLE'		=> $this->user->lang['IMGUPL_STORAGE_PATH'],
					'EXPLAIN'	=> $this->user->lang['IMGUPL_STORAGE_PATH_EXPLAIN'],
					'FIELD'		=> '<input type="text" size="20" maxlength="255" name="field_validation" value="' . $field_data['field_validation'] . '" onchange="document.getElementById(\'add_profile_field\').submit();" />',
				),
			1 => array(
					'TITLE'		=> $this->user->lang['IMGUPL_MAX_FILESIZE'],
					'EXPLAIN'	=> $this->user->lang['IMGUPL_MAX_FILESIZE_EXPLAIN'],
					'FIELD'		=> '<input type="number" min="0" maxlength="255" name="field_length" value="' . $field_data['field_length'] . '" /> ' . $this->user->lang['BYTES'],
				),
			2 => array(
					'TITLE'		=> $this->user->lang['IMGUPL_MIN_SIZE'],
					'EXPLAIN'	=> $this->user->lang['IMGUPL_MIN_SIZE_EXPLAIN'],
					'FIELD'		=> '<input type="number" min="0" maxlength="255" name="field_minlen[0]" value="' . $img_min_width . '" /> x <input type="number" min="0" maxlength="255" name="field_minlen[1]" value="' . $img_min_height . '" /> ' . $this->user->lang['PIXEL'],
				),
			3 => array(
					'TITLE'		=> $this->user->lang['IMGUPL_MAX_SIZE'],
					'EXPLAIN'	=> $this->user->lang['IMGUPL_MAX_SIZE_EXPLAIN'],
					'FIELD'		=> '<input type="number" min="0" maxlength="255" name="field_maxlen[0]" value="' . $img_max_width . '" /> x <input type="number" min="0" maxlength="255" name="field_maxlen[1]" value="' . $img_max_height . '" /> ' . $this->user->lang['PIXEL'],
				),
		);

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_default_option_values()
	{
		return array_merge(parent::get_default_option_values(), array(
			'field_minlen'		=> '20|20',
			'field_maxlen'		=> '200|200',
		));
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_field($profile_row)
	{
		$var_name = 'pf_' . $profile_row['field_ident'];

		list($img_min_width, $img_min_height) = array_map('intval', explode('|', $profile_row['field_minlen']));
		list($img_max_width, $img_max_height) = array_map('intval', explode('|', $profile_row['field_maxlen']));
		$img_max_filesize = (int) $profile_row['field_length'];

		if (!class_exists('fileupload'))
		{
			include($this->phpbb_root_path . 'includes/functions_upload.' . $this->php_ext);
		}
		$upload = new \fileupload('', $this->allowed_extensions, $img_max_filesize, $img_min_width, $img_min_height, $img_max_width, $img_max_height);

		$upload_file = $this->request->file($var_name);
		$field_value = $this->request->variable($var_name . '_raw', $profile_row['field_default_value'], true);

		$filename = preg_replace('#\|.*$#', '', $field_value);
		$destination = $profile_row['field_validation'];

		$delete_file = false;

		if (!empty($upload_file['name']))
		{
			$file = $upload->form_upload($var_name);

			$file->clean_filename('avatar', $var_name . '_', (isset($profile_row['var_name'])) ? $profile_row['var_name'] : $this->user->data['user_id']);

			// Move file and overwrite any existing image
			$file->move_file($destination, true);

			if (sizeof($file->error))
			{
				$file->remove();
				$this->error[$profile_row['field_ident']] = $file->error;
				return $field_value;
			}

			$new_filename = $var_name . '_' . ((isset($profile_row['var_name'])) ? $profile_row['var_name'] : $this->user->data['user_id']) . '.' . $file->get('extension');
			$new_field_value = $new_filename . '|' . $file->get('width') . '|' . $file->get('height');

			$delete_file = strcmp($new_filename, $filename) ? true : false;
		}
		else if (!$profile_row['field_required'])
		{
			$delete_file = $this->request->variable($var_name . '_delete', 0) ? true : false;
			if ($delete_file)
			{
				$new_field_value = '';
			}
		}

		if ($delete_file && $filename)
		{
			$path = $destination . '/' . $filename;

			if (file_exists($path))
			{
				@unlink($path);
			}
		}

		return isset($new_field_value) ? $new_field_value : $field_value;
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_options_on_submit($error, $field_data)
	{
		list($img_min_width, $img_min_height) = array_map('intval', explode('|', $field_data['field_minlen']));
		list($img_max_width, $img_max_height) = array_map('intval', explode('|', $field_data['field_maxlen']));

		if ($img_min_width > $img_max_width || $img_min_height > $img_max_height)
		{
			$error[] = $this->user->lang['IMGUPL_MAX_LOWER_MIN'];
		}

		return parent::validate_options_on_submit($error, $field_data);
	}

	/**
	* {@inheritDoc}
	*/
	public function generate_field($profile_row, $preview_options = false)
	{
		$this->template->assign_var('S_FORM_ENCTYPE', ' enctype="multipart/form-data"');
		$this->generate_field_base($profile_row, $preview_options);
	}

	/**
	* {@inheritDoc}
	*/
	public function display_options(&$template_vars, &$field_data)
	{
		parent::display_options($template_vars, $field_data);
		$template_vars = array_merge($template_vars, array(
			'S_IMAGE_UPLOAD'			=> true,
		));
	}

	/**
	* Check if a directory is writable
	* @param string $path Directory to check
	* @return bool
	*/
	protected function is_writable($path)
	{
		return phpbb_is_writable($path);
	}

	/**
	* Get title to use when displaying image
	* @param array $field_data Data for the field
	* @return string Title for image
	*/
	protected function field_title($field_data)
	{
		return $field_data['lang_name'];
	}
}
