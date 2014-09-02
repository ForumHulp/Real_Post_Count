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
	'POST_COUNT' 			=> 'Real counting posts',
	'POST_COUNT_EXPLAIN'	=> 'This extension will count new posts since the day it\'s enabled and give you the opportunity to add en random number of posts here.',
	'POST_COUNT_REAL'		=> 'Overall our users have posted: <strong>%1$s</strong> posts on this board.<br />',
	'TOTAL_POSTS_COUNTER'	=> 'Currently <strong>%d</strong> posts'
));
