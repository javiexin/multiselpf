<?php
/**
 *
 * Multi Selection Profile Field
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\multiselpf\profilefields\type;

define('FIELD_SEPARATOR', ';');

class type_multisel extends \phpbb\profilefields\type\type_base
{
	/**
	* Profile fields language helper
	* @var \phpbb\profilefields\lang_helper
	*/
	protected $lang_helper;

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
	* Construct
	*
	* @param	\phpbb\profilefields\lang_helper		$lang_helper	Profile fields language helper
	* @param	\phpbb\request\request		$request	Request object
	* @param	\phpbb\template\template	$template	Template object
	* @param	\phpbb\user					$user		User object
	*/
	public function __construct(\phpbb\profilefields\lang_helper $lang_helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->lang_helper = $lang_helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_name_short()
	{
		return 'multisel';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_name()
	{
		return $this->user->lang('FIELD_MULTISEL');
	}

	/**
	* {@inheritDoc}
	*/
	public function get_service_name()
	{
		return 'javiexin.multiselpf.profilefields.type.multisel';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_template_filename()
	{
		return '@javiexin_multiselpf/profilefields/' . $this->get_name_short() . '.html';
	}

	/**
	* {@inheritDoc}
	*/
	public function get_options($default_lang_id, $field_data)
	{
		$num_options = sizeof($field_data['lang_options']);
		$maxlen = min($num_options, $field_data['field_maxlen']);
		$options = array(
			0 => array('TITLE' => $this->user->lang['MIN_FIELD_OPTIONS'],	'FIELD' => '<input type="number" min="0" max="' . $num_options . '" name="field_minlen" size="5" value="' . $field_data['field_minlen'] . '" />'),
			1 => array('TITLE' => $this->user->lang['MAX_FIELD_OPTIONS'],	'FIELD' => '<input type="number" min="0" max="' . $num_options . '" name="field_maxlen" size="5" value="' . $maxlen . '" />'),
		);

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_default_option_values()
	{
		return array(
			'field_length'		=> 0,
			'field_minlen'		=> 0,
			'field_maxlen'		=> 100,
			'field_validation'	=> '',
			'field_novalue'			=> '',
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
	public function get_profile_field($profile_row)
	{
		$var_name = 'pf_' . $profile_row['field_ident'];
		return implode(FIELD_SEPARATOR, $this->request->variable($var_name, explode(FIELD_SEPARATOR, $profile_row['field_default_value'])));
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_profile_field(&$field_value, $field_data)
	{
		$field_size = empty($field_value) ? 0 : (int) sizeof(explode(FIELD_SEPARATOR, $field_value));

		if ($field_data['field_minlen'] && $field_size < $field_data['field_minlen'])
		{
			return $this->user->lang('FIELD_MULTISEL_TOO_FEW', (int) $field_data['field_minlen'], $this->get_field_name($field_data['lang_name']));
		}

		if ($field_data['field_maxlen'] && $field_size > $field_data['field_maxlen'])
		{
			return $this->user->lang('FIELD_MULTISEL_TOO_MANY', (int) $field_data['field_maxlen'], $this->get_field_name($field_data['lang_name']));
		}

		return false;
	}

	/**
	* {@inheritDoc}
	*/
	public function get_profile_value($field_value, $field_data)
	{
		$field_id = $field_data['field_id'];
		$lang_id = $field_data['lang_id'];
		if (!$this->lang_helper->is_set($field_id, $lang_id))
		{
			$this->lang_helper->load_option_lang($lang_id);
		}

		if ($field_value == $field_data['field_novalue'] && !$field_data['field_show_novalue'])
		{
			return null;
		}

		if (empty($field_value))
		{
			return '';
		}

		$field_values = explode(FIELD_SEPARATOR, $field_value);
		$field_value = '';

		foreach ($field_values as $value)
		{
			if (!$this->lang_helper->is_set($field_id, $lang_id, $value))
			{
				continue;
			}
			$field_value .= ((empty($field_value)) ? '' : ', ') . $this->lang_helper->get($field_id, $lang_id, $value);
		}
		return $field_value;
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

		return $field_value;
	}

	/**
	* {@inheritDoc}
	*/
	public function generate_field($profile_row, $preview_options = false)
	{
		$profile_row['field_ident'] = (isset($profile_row['var_name'])) ? $profile_row['var_name'] : 'pf_' . $profile_row['field_ident'];
		$field_ident = $profile_row['field_ident'];
		$default_value = $profile_row['field_default_value'];

		$value = ($this->request->is_set($field_ident)) ? $this->request->variable($field_ident, $default_value) : ((!isset($this->user->profile_fields[$field_ident]) || $preview_options !== false) ? $default_value : $this->user->profile_fields[$field_ident]);

		if (gettype($value) == 'string')
		{
			$value = utf8_normalize_nfc($value);
		}

		$value = explode(FIELD_SEPARATOR, $value);

		if (!$this->lang_helper->is_set($profile_row['field_id'], $profile_row['lang_id'], 1))
		{
			if ($preview_options)
			{
				$this->lang_helper->load_preview_options($profile_row['field_id'], $profile_row['lang_id'], $preview_options);
			}
			else
			{
				$this->lang_helper->load_option_lang($profile_row['lang_id']);
			}
		}

		$this->template->assign_block_vars('multisel', array_change_key_case($profile_row, CASE_UPPER));

		$options = $this->lang_helper->get($profile_row['field_id'], $profile_row['lang_id']);
		foreach ($options as $option_id => $option_value)
		{
			$this->template->assign_block_vars('multisel.options', array(
				'OPTION_ID'	=> $option_id,
				'SELECTED'	=> (in_array($option_id, $value)) ? ' checked="checked"' : '',
				'VALUE'		=> $option_value,
			));
		}
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
			'lang_name'		=> 'string',
			'lang_options'	=> 'optionfield',
		);

		if ($field_data['lang_explain'])
		{
			$options['lang_explain'] = 'text';
		}

		return $options;
	}

	/**
	* {@inheritDoc}
	*/
	public function prepare_options_form(&$exclude_options, &$visibility_options)
	{
		$exclude_options[1][] = 'lang_options';

		return $this->request->variable('lang_options', '', true);
	}

	/**
	* {@inheritDoc}
	*/
	public function validate_options_on_submit($error, $field_data)
	{
		if (!sizeof($field_data['lang_options']))
		{
			$error[] = $this->user->lang['NO_FIELD_ENTRIES'];
		}
		if ($field_data['field_minlen'] > $field_data['field_maxlen'])
		{
			$error[] = $this->user->lang['MAX_LOWER_MIN'];
		}

		return $error;
	}

	/**
	* {@inheritDoc}
	*/
	public function display_options(&$template_vars, &$field_data)
	{
		// Initialize these array elements if we are creating a new field
		if (!sizeof($field_data['lang_options']))
		{
			// No options have been defined for the dropdown menu
			$field_data['lang_options'] = array();
		}

		$template_vars = array_merge($template_vars, array(
			'S_DROPDOWN'					=> true, // workaround for lack of configurability of acp_profile.html
			'S_MULTISEL'					=> true, // not used due to the above
			'L_EDIT_DROPDOWN_LANG_EXPLAIN'	=> $this->user->lang['EDIT_MULTISEL_LANG_EXPLAIN'], // same workaround
			'L_LANG_OPTIONS_EXPLAIN'		=> $this->user->lang['MULTISEL_ENTRIES_EXPLAIN'], // and more
			'LANG_OPTIONS'					=> implode("\n", $field_data['lang_options']),
		));
	}
}
