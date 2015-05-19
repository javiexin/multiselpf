<?php
/**
 *
 * Multi Selection Profile Field [Spanish]
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
	'FIELD_MULTISEL'				=> 'Selecciones múltiples',
	'MULTISEL_ENTRIES_EXPLAIN'		=> 'Introduzca sus opciones, una opción por línea.',
	'EDIT_MULTISEL_LANG_EXPLAIN'	=> 'Tenga en cuenta que puede cambiar el texto de sus opciones y también añadir nuevas opciones al final. No se recomienda añadir opciones entre las ya existentes - esto podría traer como consecuencia la asignación errónea de opciones a sus usuarios. Esto también puede suceder si se eliminan opciones intermedias.',

	'MAX_FIELD_OPTIONS'				=> 'Número máximo de opciones a seleccionar',
	'MIN_FIELD_OPTIONS'				=> 'Número mínimo de opciones a seleccionar',

	'FIELD_MULTISEL_TOO_FEW'		=> array(
		1	=>	'No ha seleccionado suficientes opciones para el campo “%2$s”. Al menos debe seleccionar %1$d opción.',
		2	=>	'No ha seleccionado suficientes opciones para el campo “%2$s”. Al menos debe seleccionar %1$d opciones.',
	),
	'FIELD_MULTISEL_TOO_MANY'		=> array(
		1	=>	'Ha seleccionado demasiadas opciones para el campo “%2$s”. Debe seleccionar %1$d opción como máximo.',
		2	=>	'Ha seleccionado demasiadas opciones para el campo “%2$s”. Debe seleccionar %1$d opciones como máximo.',
	),
));
