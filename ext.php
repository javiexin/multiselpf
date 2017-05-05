<?php
/**
 *
 * Advanced Profile Fields Pack
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\advancedpf;

class ext extends \phpbb\extension\base
{
	/**
	* Profile fields manager
	* @var \phpbb\profilefields\manager
	*/
	protected $pfmgr;

	/**
	* Database object
	* @var \phpbb\db\driver\driver_interface
	*/
	protected $db;

	/**
	* Database tools object
	* @var \phpbb\db\tools\tools
	*/
	protected $db_tools;

	/**
	* Config_text object
	* @var \phpbb\config\db_text
	*/
	protected $config_text;

	/**
	* Log object
	* @var \phpbb\log\log_interface
	*/
	protected $log;

	/**
	* User object
	* @var \phpbb\user
	*/
	protected $user;

	/**
	* Overwrite enable_step to enable advanced profile fields
	* that were disabled before.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Enable advanced profile fields
				$this->get_global_services();
				$this->pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$this->pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$this->pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
				return 'profilefields';

			break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite disable_step to disable advanced profile fields
	* before the extension is disabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Disable advanced profile fields
				$this->get_global_services();
				$this->pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$this->pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$this->pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
				return 'profilefields';

			break;

			default:

				// Run parent disable step method
				return parent::disable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite purge_step to purge advanced profile fields
	* before the extension is effectively removed.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Purge advanced profile fields
				$this->get_global_services();
				$this->pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$this->pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$this->pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
				return 'profilefields';

			break;

			default:

				// Run parent purge step method
				return parent::purge_step($old_state);

			break;
		}
	}

	/**
	* Gets the global services needed through the container
	*/
	protected function get_global_services()
	{
		$this->pfmgr = $this->container->get('profilefields.manager');
		// If core change has not been merged, we use code here; otherwise, we use the core code
		if (!method_exists($this->pfmgr, 'enable_profilefields'))
		{
			$this->pfmgr = $this;
			$this->db = $this->container->get('dbal.conn');
			$this->db_tools = $this->container->get('dbal.tools');
			$this->config_text = $this->container->get('config_text');
			$this->log = $this->container->get('log');
			$this->user = $this->container->get('user');
		}
	}

	/**
	* Disable all profile fields of a certain type
	*
	* This should be called when an extension which has profile field types
	* is disabled so that all those profile fields are hidden and do not
	* cause errors
	*
	* @param string $profilefield_type_name Type identifier of the profile fields
	*/
	protected function disable_profilefields($profilefield_type_name)
	{
		// Get list of profile fields affected by this operation, if any
		$pfs = array();
		$sql = 'SELECT field_id, field_ident
			FROM ' . PROFILE_FIELDS_TABLE . "
			WHERE field_active = 1
				AND field_type = '" . $this->db->sql_escape($profilefield_type_name) . "'";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$pfs[(int) $row['field_id']] = $row['field_ident'];
		}
		$this->db->sql_freeresult($result);

		// If no profile fields affected, then nothing to do
		if (!sizeof($pfs))
		{
			return;
		}

		// Update the affected profile fields to "inactive"
		$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . "
			SET field_active = 0
			WHERE field_active = 1
				AND field_type = '" . $this->db->sql_escape($profilefield_type_name) . "'";
		$this->db->sql_query($sql);

		// Save modified information into a config_text field to recover on enable
		$this->config_text->set($profilefield_type_name . '.saved', json_encode($pfs));

		// Log activity
		foreach ($pfs as $field_ident)
		{
			$this->log->add('admin', $this->user->data['user_id'], (empty($this->user->ip)) ? '' : $this->user->ip, 'LOG_PROFILE_FIELD_DEACTIVATE', time(), array($field_ident));
		}
	}

	/**
	* Purge all profile fields of a certain type
	*
	* This should be called when an extension which has profile field types
	* is purged so that all those profile fields are removed
	*
	* @param string $profilefield_type_name Type identifier of the profile fields
	*/
	protected function purge_profilefields($profilefield_type_name)
	{
		// Remove the information saved on disable in a config_text field, not needed any longer
		$this->config_text->delete($profilefield_type_name . '.saved');

		// Get list of profile fields affected by this operation, if any
		$pfs = array();
		$sql = 'SELECT field_id, field_ident
			FROM ' . PROFILE_FIELDS_TABLE . "
			WHERE field_type = '" . $this->db->sql_escape($profilefield_type_name) . "'";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$pfs[(int) $row['field_id']] = $row['field_ident'];
		}
		$this->db->sql_freeresult($result);

		// If no profile fields exist, then nothing to do
		if (!sizeof($pfs))
		{
			return;
		}

		$this->db->sql_transaction('begin');

		// Delete entries from all profile field definition tables
		$where = $this->db->sql_in_set('field_id', array_keys($pfs));
		$this->db->sql_query('DELETE FROM ' . PROFILE_FIELDS_TABLE . ' WHERE ' . $where);
		$this->db->sql_query('DELETE FROM ' . PROFILE_FIELDS_LANG_TABLE . ' WHERE ' . $where);
		$this->db->sql_query('DELETE FROM ' . PROFILE_LANG_TABLE . ' WHERE ' . $where);

		// Drop columns from the Profile Fields data table
		foreach ($pfs as $field_ident)
		{
			$this->db_tools->sql_column_remove(PROFILE_FIELDS_DATA_TABLE, 'pf_' . $field_ident);
		}

		// Reset the order of the remaining fields
		$order = 0;

		$sql = 'SELECT *
			FROM ' . PROFILE_FIELDS_TABLE . '
			ORDER BY field_order';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$order++;
			if ($row['field_order'] != $order)
			{
				$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . "
					SET field_order = $order
					WHERE field_id = {$row['field_id']}";
				$this->db->sql_query($sql);
			}
		}
		$this->db->sql_freeresult($result);

		$this->db->sql_transaction('commit');

		// Log activity
		foreach ($pfs as $field_ident)
		{
			$this->log->add('admin', $this->user->data['user_id'], (empty($this->user->ip)) ? '' : $this->user->ip, 'LOG_PROFILE_FIELD_REMOVED', time(), array($field_ident));
		}
	}

	/**
	* Enable the profile fields of a certain type
	*
	* This should be called when an extension which has profile field types
	* that was disabled is re-enabled so that all those profile fields that
	* were disabled are enabled again
	*
	* @param string $profilefield_type_name Type identifier of the profile fields
	*/
	protected function enable_profilefields($profilefield_type_name)
	{
		// Read the information saved on disable from a config_text field to recover original values
		$pfs = $this->config_text->get($profilefield_type_name . '.saved');

		// If nothing saved, then nothing to do
		if ($pfs == '')
		{
			return;
		}

		$pfs = json_decode($pfs, true);

		// Restore the affected profile fields to "active"
		$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . "
			SET field_active = 1
			WHERE field_active = 0
				AND " . $this->db->sql_in_set('field_id', array_keys($pfs));
		$this->db->sql_query($sql);

		// Remove the information saved in the config_text field, not needed any longer
		$this->config_text->delete($profilefield_type_name . '.saved');

		// Log activity
		foreach ($pfs as $field_ident)
		{
			$this->log->add('admin', $this->user->data['user_id'], (empty($this->user->ip)) ? '' : $this->user->ip, 'LOG_PROFILE_FIELD_ACTIVATE', time(), array($field_ident));
		}
	}
}
