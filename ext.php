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
				$pfmgr = $this->container->get('profilefields.manager');
				$pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$pfmgr->enable_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
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
				$pfmgr = $this->container->get('profilefields.manager');
				$pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$pfmgr->disable_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
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
				$pfmgr = $this->container->get('profilefields.manager');
				$pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.multisel');
				$pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.imgsel');
				$pfmgr->purge_profilefields('javiexin.advancedpf.profilefields.type.imgupl');
				return 'profilefields';

			break;

			default:

				// Run parent purge step method
				return parent::purge_step($old_state);

			break;
		}
	}
}
