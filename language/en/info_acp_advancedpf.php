<?php
/**
 *
 * Advanced Profile Fields Pack [English]
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
// Field is independent
	'FIELD_INDIVIDUAL'				=> 'Display field individually',
	'FIELD_INDIVIDUAL_EXPLAIN'		=> 'Individual fields require specific placement in the style templates (via events) by using the template variables specific to this field; they are not displayed with other profile fields; if this option is selected and no changes are made to the templates, it will not be visible.',

// Multiple Selection
	'FIELD_MULTISEL'				=> 'Multiple selections',
	'MULTISEL_ENTRIES_EXPLAIN'		=> 'Enter your options now, every option in one line.',
	'EDIT_MULTISEL_LANG_EXPLAIN'	=> 'Please note that you are able to change your options text and also able to add new options to the end. It is not advised to add new options between existing options - this could result in wrong options assigned to your users. This can also happen if you remove options in-between.',

	'MAX_FIELD_OPTIONS'				=> 'Maximum number of options to select',
	'MIN_FIELD_OPTIONS'				=> 'Minimum number of options to select',

	'MAX_LOWER_MIN'					=> 'Inconsistent options: maximum lower than minimum.',
	'FIELD_MULTISEL_TOO_FEW'		=> array(
		1	=>	'You have not selected enough options for field “%2$s”. At least %1$d option must be selected.',
		2	=>	'You have not selected enough options for field “%2$s”. At least %1$d options must be selected.',
	),
	'FIELD_MULTISEL_TOO_MANY'		=> array(
		1	=>	'You have selected too many options on field “%2$s”. At most %1$d option may be selected.',
		2	=>	'You have selected too many options on field “%2$s”. At most %1$d options may be selected.',
	),

// Image Common
	'NOTSPECIFIED_VALUE'			=> 'No value specified',
	'NOTSPECIFIED_VALUE_EXPLAIN'	=> 'Label shown when no image value has been specified (may be empty); select this label to delete current field value.',
	'IMG_NO_PATH'					=> 'No image path specified',
	'IMG_PATH_INCORRECT'			=> 'The specified path is incorrect',
	'IMG_DIR_DOES_NOT_EXIST'		=> 'The entered path “%s” does not exist.',
	'IMG_DIR_NOT_DIR'				=> 'The entered path “%s” is not a directory.',
	'IMG_DIR_NOT_WRITABLE'			=> 'The entered path “%s” is not writable.',

// Image Selector
	'FIELD_IMGSEL'					=> 'Image selector',
	'IMGSEL_STORAGE_PATH'			=> 'Image storage path',
	'IMGSEL_STORAGE_PATH_EXPLAIN'	=> 'Path under your phpBB root directory where the images for this profile field are stored, e.g. <samp>images/fields/name</samp>. Double dots like <samp>../</samp> will be stripped from the path for security reasons.',
	'IMGSEL_SELECT_AS'				=> 'Select method',
	'IMGSEL_SELECT_AS_EXPLAIN'		=> 'Method to select the option within the available images, either as a name dropdown with a single image being shown, or as a panel with all images shown.',
	'IMGSEL_AS_DROPDOWN'			=> 'As dropdown',
	'IMGSEL_AS_PANEL'				=> 'As panel',
	'NO_IMAGES_IMGSEL'				=> 'No images',

// Image Upload
	'FIELD_IMGUPL'					=> 'Image upload',
	'IMGUPL_STORAGE_PATH'			=> 'Image storage path',
	'IMGUPL_STORAGE_PATH_EXPLAIN'	=> 'Path under your phpBB root directory where the images for this profile field are uploaded, e.g. <samp>images/fields/name</samp>, must be writable. Double dots like <samp>../</samp> will be stripped from the path for security reasons.',
	'IMGUPL_MAX_FILESIZE'			=> 'Maximum file size',
	'IMGUPL_MAX_FILESIZE_EXPLAIN'	=> 'Maximum file size in bytes of uploaded images.',
	'IMGUPL_MIN_SIZE'				=> 'Minimum dimensions',
	'IMGUPL_MIN_SIZE_EXPLAIN'		=> 'Minimum width and height of uploaded images, in pixels.',
	'IMGUPL_MAX_SIZE'				=> 'Maximum dimensions',
	'IMGUPL_MAX_SIZE_EXPLAIN'		=> 'Maximum widht and height of uploaded images, in pixels.',
	'IMGUPL_MAX_LOWER_MIN'			=> 'Inconsistent options: maximum dimension smaller than minimum dimension.',
	'DELETE_IMGUPL'					=> 'Delete image',

));
