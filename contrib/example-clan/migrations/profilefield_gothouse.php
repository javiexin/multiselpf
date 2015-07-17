<?php
/**
 *
 * Advanced Profile Fields Pack - Game of Thrones House example
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\migrations;

class profilefield_gothouse extends \phpbb\db\migration\profilefield_base_migration
{
	static public function depends_on()
	{
		return array(
			'\javiexin\advancedpf\migrations\profilefield_individual',
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'create_custom_field'))),
		);
	}

	protected $profilefield_name = 'jx_got_house';

	protected $profilefield_database_type = array('VCHAR', '');

	protected $profilefield_data = array(
		'field_name'			=> 'jx_got_house',
		'field_type'			=> 'javiexin.advancedpf.profilefields.type.imgsel',
		'field_ident'			=> 'jx_got_house',
		'field_length'			=> '0',
		'field_minlen'			=> '0',
		'field_maxlen'			=> '0',
		'field_novalue'			=> '',
		'field_default_value'	=> '',
		'field_validation'		=> 'ext/javiexin/advancedpf/images/got_house',
		'field_required'		=> 1,
		'field_show_novalue'	=> 0,
		'field_show_on_reg'		=> 1,
		'field_show_on_pm'		=> 1,
		'field_show_on_vt'		=> 1,
		'field_show_on_ml'		=> 1,
		'field_show_profile'	=> 0,
		'field_hide'			=> 0,
		'field_no_view'			=> 0,
		'field_active'			=> 1,
		'field_individual'		=> 1,
		'field_is_contact'		=> 0,
		'field_contact_desc'	=> '',
		'field_contact_url'		=> '',
	);

	public function create_custom_field()
	{
		parent::create_custom_field();

		$lang_name = (strpos($this->profilefield_name, 'phpbb_') === 0) ? strtoupper(substr($this->profilefield_name, 6)) : strtoupper($this->profilefield_name);
		$lang_update = array(
			'lang_explain'			=> $lang_name . '_EXPLAIN',
			'lang_default_value'	=> $lang_name . '_DEFAULT',
		);

		$sql = 'SELECT field_id
			FROM ' . PROFILE_FIELDS_TABLE . '
			WHERE field_ident = "' . $this->profilefield_data['field_ident'] . '"';
		$result = $this->db->sql_query($sql);
		$field_id = (int) $this->db->sql_fetchfield('field_id');
		$this->db->sql_freeresult($result);

		$this->db->sql_transaction('begin');

		$sql = 'SELECT lang_id
			FROM ' . LANG_TABLE;
		$result = $this->db->sql_query($sql);
		while ($lang_id = (int) $this->db->sql_fetchfield('lang_id'))
		{
			$sql = 'UPDATE ' . PROFILE_LANG_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $lang_update) . '
				WHERE field_id = ' . $field_id;
			$this->db->sql_query($sql);
		}
		$this->db->sql_freeresult($result);

		$this->db->sql_transaction('commit');
	}
}
