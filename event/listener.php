<?php
/**
 *
 * Advanced Profile Fields Pack
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\finder */
	protected $finder;

	/** @var string phpEx */
	protected $php_ext;

	/** @var string pf_prefix */
	protected $pf_prefix;

	/**
	 * Constructor of event listener
	 *
	 * @param \phpbb\template\template						$template			Template object
	 * @param \phpbb\template\template						$template			Template object
	 * @param \phpbb\template\template						$template			Template object
	 * @param \phpbb\template\template						$template			Template object
	 * @param \phpbb\template\template						$template			Template object
	 */
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\extension\manager $ext_manager, $php_ext, $pf_prefix)
	{
		$this->template = $template;
		$this->user = $user;
		$this->php_ext = $php_ext;
		$this->pf_prefix = $pf_prefix;

		$this->finder = $ext_manager->get_finder();
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array<string,string>
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.generate_profile_fields_template_data'	=> 'remove_individual_fields_from_block',
			'core.acp_profile_create_edit_init'				=> 'manage_additional_column_in_profilefields_init',
			'core.acp_profile_create_edit_after'			=> 'manage_additional_column_in_profilefields_after',
			'core.acp_profile_create_edit_before_save'		=> 'manage_additional_column_in_profilefields_save',

			'core.user_setup' => 'load_language_on_setup',
		);
	}

	/**
	 * Removes profile fields classified as individual from profile fields block
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function remove_individual_fields_from_block($event)
	{
		$profile_row = $event['profile_row'];
		$tpl_fields = $event['tpl_fields'];

		$new_blockrow = array();
		foreach ($tpl_fields['blockrow'] as $field_block)
		{
			$ident = $field_block['PROFILE_FIELD_IDENT'];
			if ($profile_row[$ident]['data']['field_individual'])
			{
				continue;
			}
			$new_blockrow[] = $field_block;
		}
		$tpl_fields['blockrow'] = $new_blockrow;

		$event['tpl_fields'] = $tpl_fields;
	}

	/**
	 * Manage new column in profile fields table (create/edit), init section
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function manage_additional_column_in_profilefields_init($event)
	{
		$action = $event['action'];
		$field_row = $event['field_row'];
		$exclude = $event['exclude'];
		$visibility_ary = $event['visibility_ary'];

		if ($action == 'create')
		{
			$field_row['field_individual'] = 0;
		}
		$exclude[1][] = 'field_individual';
		$visibility_ary[] = 'field_individual';

		$event['field_row'] = $field_row;
		$event['exclude'] = $exclude;
		$event['visibility_ary'] = $visibility_ary;
	}

	/**
	 * Manage new column in profile fields table (create/edit), template vars
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function manage_additional_column_in_profilefields_after($event)
	{
		$field_data = $event['field_data'];

		if ($event['step'] == 1)
		{
			$this->template->assign_vars(array('S_FIELD_INDIVIDUAL' => ($field_data['field_individual']) ? true : false));
		}
	}

	/**
	 * Manage new column in profile fields table (create/edit), save section
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function manage_additional_column_in_profilefields_save($event)
	{
		$field_data = $event['field_data'];
		$profile_fields = $event['profile_fields'];

		$profile_fields['field_individual'] = $field_data['field_individual'];

		$event['profile_fields'] = $profile_fields;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param object $event The event object
	 * @return void
	 */
	public function load_language_on_setup($event)
	{
		// Find pf language files if any
		$pf_lang_files = $this->finder
			->set_extensions(array('javiexin\advancedpf'))
			->prefix($this->pf_prefix)
			->suffix('.' . $this->php_ext)
			->extension_directory('/language/' . $this->user->lang_name)
			->find();

		$lang_set = ($pf_lang_files) ? substr_replace(array_map('basename', array_unique(array_keys($pf_lang_files))), '', -strlen($this->php_ext)-1) : array();

		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'javiexin\advancedpf',
			'lang_set' => $lang_set,
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}
