<?php
/**
*
* @package Real post count
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'POST_COUNT' 			=> 'Recuento de mensajes real',
	'POST_COUNT_EXPLAIN'	=> 'Esta extensión contará los nuevos mensajes desde el día que está habilitada, y le dará la oportunidad de añadir en número aleatorio de mensajes aquí.',
	'POST_COUNT_REAL'		=> 'En general, nuestros usuarios han publicado: <strong>%1$s</strong> mensajes en este foro.<br />',
	'TOTAL_POSTS_COUNTER'	=> 'Actualmente <strong>%d</strong> mensajes'
));
