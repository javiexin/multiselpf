<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace javiexin\advancedpf\profilefields\type;

class type_intbar extends \phpbb\profilefields\type\type_int
{
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
	public function get_name_short()
	{
		return 'intbar';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_options($default_lang_id, $field_data)
	{
		$options = parent::get_options($default_lang_id, $field_data);
//		array(
//			0 => array('TITLE' => $this->user->lang['FIELD_LENGTH'],		'FIELD' => '<input type="number" min="0" max="99999" name="field_length" size="5" value="' . $field_data['field_length'] . '" />'),
//			1 => array('TITLE' => $this->user->lang['MIN_FIELD_NUMBER'],	'FIELD' => '<input type="number" min="0" max="99999" name="field_minlen" size="5" value="' . $field_data['field_minlen'] . '" />'),
//			2 => array('TITLE' => $this->user->lang['MAX_FIELD_NUMBER'],	'FIELD' => '<input type="number" min="0" max="99999" name="field_maxlen" size="5" value="' . $field_data['field_maxlen'] . '" />'),
//			3 => array('TITLE' => $this->user->lang['DEFAULT_VALUE'],		'FIELD' => '<input type="number" name="field_default_value" value="' . $field_data['field_default_value'] . '" />'),
//		);

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_options_on_submit($error, $field_data)
	{
		if ($field_data['field_minlen'] > $field_data['field_maxlen'])
		{
			$error[] = $this->user->lang['INTBAR_MAX_LOWER_MIN'];
		}
		if ($field_data['field_minlen'] == $field_data['field_maxlen'])
		{
			$error[] = $this->user->lang['INTBAR_NO_INTERVAL'];
		}
		if ($field_data['field_minlen'] > $field_data['field_default_value'] || $field_data['field_default_value'] > $field_data['field_maxlen'])
		{
			$error[] = $this->user->lang['INTBAR_DEFAULT_NOT_VALID'];
		}

		return parent::validate_options_on_submit($error, $field_data);
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_value($field_value, $field_data)
	{
		if (($field_value === '' || $field_value === null) && !$field_data['field_show_novalue'])
		{
			return null;
		}
		else if ($field_value === '' || $field_value === null)
		{
			$field_value = $field_data['field_default_value'];
		}
		return $this->get_html_from_value($field_data['field_maxlen'], $field_data['field_minlen'], $field_value);
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_value_raw($field_value, $field_data)
	{
		if (($field_value === '' || $field_value === null) && !$field_data['field_show_novalue'])
		{
			return null;
		}
		else if ($field_value === '' || $field_value === null)
		{
			$field_value = $field_data['field_default_value'];
		}
		return (int) 100 * ($field_value - $field_data['field_minlen']) / ($field_data['field_maxlen'] - $field_data['field_minlen']); // Return percent value within specified range
	}

	/**
	* Get the HTML code to display a relative sized bar
	* @param int $max Maximum value in range
	* @param int $min Minimum value in range
	* @param int $value Value to represent
	* @return string Html code to show bar
	*/
	protected function get_html_from_value($max, $min, $value)
	{
		$pct = (int) 100 * ($value - $min) / ($max - $min); // Percentage
		$range = ($pct > 0) ? ceil(($pct - 1) / 20.0) : 1; // Range 1 to 5 to assign potentially different colours to the bar

		$html = '<span class="intbar" style="max-width: 150px;"><span class="intbar' . $range . '" style="width: ' . $pct . '%;">' . $value . '</span></span>';

		return $html;
	}
}
