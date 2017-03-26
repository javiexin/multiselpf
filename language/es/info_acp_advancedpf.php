<?php
/**
 *
 * Advanced Profile Fields Pack [Spanish]
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
	'FIELD_INDIVIDUAL'				=> 'Mostrar el campo individualmente',
	'FIELD_INDIVIDUAL_EXPLAIN'		=> 'Hay que incluir los campos individuales en las plantillas de forma específica (via eventos) utilizando las variables de plantillas específicas de este campo; no se muestran con el resto de campos de perfil; si se selecciona esta opción y no se hacen cambios en las plantillas, este campo no será visible.',

// Multiple Selection
	'FIELD_MULTISEL'				=> 'Selecciones múltiples',
	'MULTISEL_ENTRIES_EXPLAIN'		=> 'Introduzca sus opciones, una opción por línea.',
	'EDIT_MULTISEL_LANG_EXPLAIN'	=> 'Tenga en cuenta que puede cambiar el texto de sus opciones y también añadir nuevas opciones al final. No se recomienda añadir opciones entre las ya existentes - esto podría traer como consecuencia la asignación errónea de opciones a sus usuarios. Esto también puede suceder si se eliminan opciones intermedias.',

	'MAX_FIELD_OPTIONS'				=> 'Número máximo de opciones a seleccionar',
	'MIN_FIELD_OPTIONS'				=> 'Número mínimo de opciones a seleccionar',

	'MAX_LOWER_MIN'					=> 'Opciones no coherentes: máximo menor que mínimo.',
	'FIELD_MULTISEL_TOO_FEW'		=> array(
		1	=>	'No ha seleccionado suficientes opciones para el campo “%2$s”. Al menos debe seleccionar %1$d opción.',
		2	=>	'No ha seleccionado suficientes opciones para el campo “%2$s”. Al menos debe seleccionar %1$d opciones.',
	),
	'FIELD_MULTISEL_TOO_MANY'		=> array(
		1	=>	'Ha seleccionado demasiadas opciones para el campo “%2$s”. Debe seleccionar %1$d opción como máximo.',
		2	=>	'Ha seleccionado demasiadas opciones para el campo “%2$s”. Debe seleccionar %1$d opciones como máximo.',
	),

// Image Common
	'NOTSPECIFIED_VALUE'			=> 'Valor no especificado',
	'NOTSPECIFIED_VALUE_EXPLAIN'	=> 'Literal que se muestra cuando no se ha especificado ningún valor (puede estar vacío); seleccione esta opción para borrar el valor actual.',
	'IMG_NO_PATH'					=> 'No se ha especificado una ruta para las imágenes',
	'IMG_PATH_INCORRECT'			=> 'La ruta introducida es incorrecta',
	'IMG_DIR_DOES_NOT_EXIST'		=> 'La ruta introducida "%s" no existe.',
	'IMG_DIR_NOT_DIR'				=> 'La ruta introducida "%s" no es un directorio.',
	'IMG_DIR_NOT_WRITABLE'			=> 'La ruta introducida "%s" no se puede escribir.',

// Image Selector
	'FIELD_IMGSEL'					=> 'Selector de imágenes',
	'IMGSEL_STORAGE_PATH'			=> 'Ruta a las imágenes',
	'IMGSEL_STORAGE_PATH_EXPLAIN'	=> 'Ruta desde su directorio raíz de phpBB donde se almacenan las imágenes de este campo de perfil, por ejemplo <samp>images/fields/name</samp>. Los puntos dobles como <samp>../</samp> se eliminarán de la ruta por motivos de seguridad.',
	'IMGSEL_SELECT_AS'				=> 'Método de selección',
	'IMGSEL_SELECT_AS_EXPLAIN'		=> 'Método para seleccionar la opción elegida entre las imágenes disponibles, bien mediante un desplegable en el que se muestra tan solo la imagen seleccionada, o bien mediante un panel en el que se muestran todas las imágenes a la vez.',
	'IMGSEL_AS_DROPDOWN'			=> 'Desplegable',
	'IMGSEL_AS_PANEL'				=> 'Panel',
	'NO_IMAGES_IMGSEL'				=> 'No hay imágenes',

// Image Upload
	'FIELD_IMGUPL'					=> 'Cargador de imágenes',
	'IMGUPL_STORAGE_PATH'			=> 'Ruta a las imágenes',
	'IMGUPL_STORAGE_PATH_EXPLAIN'	=> 'Ruta desde su directorio raíz de phpBB donde se cargan las imágenes de este campo de perfil, por ejemplo <samp>images/fields/name</samp>, debe ser escribible. Los puntos dobles como <samp>../</samp> se eliminarán de la ruta por motivos de seguridad.',
	'IMGUPL_MAX_FILESIZE'			=> 'Tamaño máximo de fichero',
	'IMGUPL_MAX_FILESIZE_EXPLAIN'	=> 'Tamaño máximo en bytes de los ficheros de imágenes cargados.',
	'IMGUPL_MIN_SIZE'				=> 'Dimensiones mínimas',
	'IMGUPL_MIN_SIZE_EXPLAIN'		=> 'Anchura y altura mínima para las imágenes cargadas, en pixeles.',
	'IMGUPL_MAX_SIZE'				=> 'Dimensiones máximas',
	'IMGUPL_MAX_SIZE_EXPLAIN'		=> 'Anchura y altura máxima para las imágenes cargadas, en pixeles.',
	'IMGUPL_MAX_LOWER_MIN'			=> 'Opciones no coherentes: dimensión máxima menor que dimensión mínima.',
	'DELETE_IMGUPL'					=> 'Eliminar imágen',

));
