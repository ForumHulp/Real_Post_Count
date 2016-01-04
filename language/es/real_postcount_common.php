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
	'FH_HELPER_NOTICE'		=> 'Forumhulp helper application does not exist!<br />Download <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> and copy the helper folder to your forumhulp extension folder.',
	'REALPOSTCOUNT_NOTICE'	=> '<div class="phpinfo"><p class="entry">Config settings are in %1$s » %2$s » %3$s » %4$s.</p></div>'
));
