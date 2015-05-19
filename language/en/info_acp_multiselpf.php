<?php
/**
 *
 * Multi Selection Profile Field [English]
 *
 * @copyright (c) 2015 javiexin ( www.exincastillos.es )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author Javier Lopez (javiexin)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'FIELD_MULTISEL'				=> 'Multiple selections',
	'MULTISEL_ENTRIES_EXPLAIN'		=> 'Enter your options now, every option in one line.',
	'EDIT_MULTISEL_LANG_EXPLAIN'	=> 'Please note that you are able to change your options text and also able to add new options to the end. It is not advised to add new options between existing options - this could result in wrong options assigned to your users. This can also happen if you remove options in-between.',

	'MAX_FIELD_OPTIONS'				=> 'Maximum number of options to select',
	'MIN_FIELD_OPTIONS'				=> 'Minimum number of options to select',

	'FIELD_MULTISEL_TOO_FEW'		=> array(
		1	=>	'You have not selected enough options for field “%2$s”. At least %1$d option must be selected.',
		2	=>	'You have not selected enough options for field “%2$s”. At least %1$d options must be selected.',
	),
	'FIELD_MULTISEL_TOO_MANY'		=> array(
		1	=>	'You have selected too many options on field “%2$s”. At most %1$d option may be selected.',
		2	=>	'You have selected too many options on field “%2$s”. At most %1$d options may be selected.',
	),
));
