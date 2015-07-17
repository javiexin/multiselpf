<?php
/**
 *
 * Advanced Profile Fields Pack - Extra Rank example
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\migrations;

class profilefield_rankextra extends \phpbb\db\migration\profilefield_base_migration
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

	protected $profilefield_name = 'jx_rank_extra';

	protected $profilefield_database_type = array('VCHAR', '');

	protected $profilefield_data = array(
		'field_name'			=> 'jx_rank_extra',
		'field_type'			=> 'javiexin.advancedpf.profilefields.type.imgsel',
		'field_ident'			=> 'jx_rank_extra',
		'field_length'			=> '0',
		'field_minlen'			=> '0',
		'field_maxlen'			=> '0',
		'field_novalue'			=> '',
		'field_default_value'	=> '',
		'field_validation'		=> 'images/ranks',
		'field_required'		=> 0,
		'field_show_novalue'	=> 0,
		'field_show_on_reg'		=> 0,
		'field_show_on_pm'		=> 0,
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
}
