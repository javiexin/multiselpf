<?php
/**
 *
 * Multi Selection Profile Field
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

namespace javiexin\multiselpf;

class ext extends \phpbb\extension\base
{
	/**
	* Overwrite enable_step to enable multi selection profile fields
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

				// Enable multi selection profile fields
//				$pfmgr = $this->container->get('profilefields.manager');
				$this->enable_profilefield_type('javiexin.multiselpf.', 'profilefields.type.multisel');
				return 'profilefields';

			break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite disable_step to disable multi selection profile fields
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

				// Disable multi selection profile fields
//				$pfmgr = $this->container->get('profilefields.manager');
				$this->disable_profilefield_type('javiexin.multiselpf.', 'profilefields.type.multisel');
				return 'profilefields';

			break;

			default:

				// Run parent disable step method
				return parent::disable_step($old_state);

			break;
		}
	}

	/**
	* Overwrite purge_step to purge multi selection profile fields
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

				// Purge multi selection profile fields
//				$pfmgr = $this->container->get('profilefields.manager');
				$this->purge_profilefield_type('javiexin.multiselpf.', 'profilefields.type.multisel');
				return 'profilefields';

			break;

			default:

				// Run parent purge step method
				return parent::purge_step($old_state);

			break;
		}
	}

	/**
	* Enable multi selection profile fields that were disabled before.
	*
	* @param mixed $type Type to be enabled, as string, or array of strngs
	* @return void
	*/
	protected function enable_profilefield_type($prefix, $type)
	{
		global $db;
		$config_text = $this->container->get('config_text');

		// Make sure we get an array, and with fully qualified type names (prepend prefix.)
		$types = (!is_array($type)) ? array($prefix . $type) : array_map(function ($a) use ($prefix) { return $prefix . $a; }, $type);

		// Read the modified information saved on disable from a config_text field to recover values, then remove it
		$pfs = $config_text->get($prefix . 'saved_on_disable');
		$config_text->delete($prefix . 'saved_on_disable');

		// If nothing saved, then nothing to do
		if ($pfs == '')
		{
			return;
		}

		$pfs = unserialize(base64_decode($pfs));

		// Get list of Profile Fields affected by this operation, if any
		$change_pfs = array();
		$sql = 'SELECT field_id, field_ident, field_type, field_active
			FROM ' . PROFILE_FIELDS_TABLE . '
			WHERE ' . $db->sql_in_set('field_id', array_keys($pfs));
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			// Only restore Profile Field if not changed since it was saved
			if ($row['field_type'] === 'profilefields.type.string' && substr($row['field_ident'], 0, 2) === 'X_')
			{
				$change_pfs[(int) $row['field_id']] = $pfs[(int) $row['field_id']];
			}
		}
		$db->sql_freeresult($result);

		// If no Profile Fields exist, then nothing to do
		if (!sizeof($change_pfs))
		{
			return;
		}

		// Update the affected Profile Fields to recover the values at the time of disabling
		foreach ($change_pfs as $pf)
		{
			$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . '
				SET field_active = ' . $pf['field_active'] . ', field_type = "' . $pf['field_type'] . '", field_ident = "' . $pf['field_ident'] . '"
				WHERE field_id = ' . $pf['field_id'];
			$db->sql_query($sql);
			// Log activity
			if ($pf['field_active'])
			{
				add_log('admin', 'LOG_PROFILE_FIELD_ACTIVATE', $pf['field_ident']);
			}
		}
	}

	/**
	* Disable multi selection profile fields before the extension is disabled.
	*
	* @param string $prefix Name of the extension, or empty if none required
	* @param mixed $type Type(s) to be disabled (without vendor.extension.), as string, or array of strngs
	* @return void
	*/
	protected function disable_profilefield_type($prefix, $type)
	{
		global $db;
		$config_text = $this->container->get('config_text');

		// Make sure we get an array, and with fully qualified type names (prepend prefix.)
		$types = (!is_array($type)) ? array($prefix . $type) : array_map(function ($a) use ($prefix) { return $prefix . $a; }, $type);

		// Get list of Profile Fields affected by this operation, if any
		$pfs = array();
		$sql = 'SELECT field_id, field_ident, field_type, field_active
			FROM ' . PROFILE_FIELDS_TABLE . '
			WHERE ' . $db->sql_in_set('field_type', $types);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$pfs[(int) $row['field_id']] = $row;
		}
		$db->sql_freeresult($result);

		// If no Profile Fields affected, then nothing to do
		if (!sizeof($pfs))
		{
			return;
		}

		// Update the affected Profile Fields to "inactive", to a fake type and change the identification (workaround for a core limitation)
		$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . '
			SET field_active = 0, field_type = "profilefields.type.string", field_ident = ' . $db->sql_concatenate('"X_"' , 'field_id') . '
			WHERE ' . $db->sql_in_set('field_type', $types);
		$db->sql_query($sql);

		// Save modified information into a config_text field to recover on enable
		$config_text->set($prefix . 'saved_on_disable', base64_encode(serialize($pfs)));

		// Log activity
		foreach ($pfs as $row)
		{
			if ($row['field_active'])
			{
				$field_ident = (string) $row['field_ident'];
				add_log('admin', 'LOG_PROFILE_FIELD_DEACTIVATE', $field_ident);
			}
		}
	}

	/**
	* Purge multi selection profile fields before the extension is effectively removed.
	*
	* @param mixed $type Type to be enabled, as string, or array of strngs
	* @return void
	*/
	protected function purge_profilefield_type($prefix, $type)
	{
		global $db;
		$db_tools = $this->container->get('dbal.tools');
		$config_text = $this->container->get('config_text');

		// Make sure we get an array, and with fully qualified type names (prepend prefix.)
		$types = (!is_array($type)) ? array($prefix . $type) : array_map(function ($a) use ($prefix) { return $prefix . $a; }, $type);

		// Read the modified information saved on disable from a config_text field, then remove the field
		$pfs = $config_text->get($prefix . 'saved_on_disable');
		$config_text->delete($prefix . 'saved_on_disable');

		// If nothing saved, then nothing to do
		if ($pfs == '')
		{
			return;
		}

		$pfs = unserialize(base64_decode($pfs));

		// Get list of Profile Fields affected by this operation, if any
		$field_idents = $field_ids = array();
		$sql = 'SELECT field_id, field_ident, field_type, field_active
			FROM ' . PROFILE_FIELDS_TABLE . '
			WHERE ' . $db->sql_in_set('field_id', array_keys($pfs));
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			// Only delete Profile Field if not changed since it was saved
			if ($row['field_type'] === 'profilefields.type.string' && substr($row['field_ident'], 0, 2) === 'X_')
			{
				$field_idents[] = (string) $pfs[(int) $row['field_id']]['field_ident'];
				$field_ids[] = (string) $pfs[(int) $row['field_id']]['field_id'];
			}
		}
		$db->sql_freeresult($result);

		// If no Profile Fields exist, then nothing to do
		if (!sizeof($field_ids))
		{
			return;
		}

		$db->sql_transaction('begin');

		// Delete entries from all Profile Field definition tables
		$db->sql_query('DELETE FROM ' . PROFILE_FIELDS_TABLE . ' WHERE ' . $db->sql_in_set('field_id', $field_ids));
		$db->sql_query('DELETE FROM ' . PROFILE_FIELDS_LANG_TABLE . ' WHERE ' . $db->sql_in_set('field_id', $field_ids));
		$db->sql_query('DELETE FROM ' . PROFILE_LANG_TABLE . ' WHERE ' . $db->sql_in_set('field_id', $field_ids));

		// Drop columns from the Profile Fields data table
		foreach ($field_idents as $field_ident)
		{
			$db_tools->sql_column_remove(PROFILE_FIELDS_DATA_TABLE, 'pf_' . $field_ident);
		}

		// Reset the order of the remaining fields
		$order = 0;

		$sql = 'SELECT *
			FROM ' . PROFILE_FIELDS_TABLE . '
			ORDER BY field_order';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$order++;
			if ($row['field_order'] != $order)
			{
				$sql = 'UPDATE ' . PROFILE_FIELDS_TABLE . "
					SET field_order = $order
					WHERE field_id = {$row['field_id']}";
				$db->sql_query($sql);
			}
		}
		$db->sql_freeresult($result);

		$db->sql_transaction('commit');

		// Log activity
		foreach ($field_idents as $field_ident)
		{
			add_log('admin', 'LOG_PROFILE_FIELD_REMOVED', $field_ident);
		}
	}
}
