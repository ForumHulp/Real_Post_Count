<?php
/**
*
* @package Real post count
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\real_postcount\migrations;

class install_real_postcount extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['real_postcount_version']) && version_compare($this->config['real_postcount_version'], '3.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				USERS_TABLE	=> array(
					'user_real_posts' => array('INT:11', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				USERS_TABLE	=> array(
					'user_real_posts',
				),
			)
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('real_postcount', 0, 1)),
			array('config.add', array('real_postcount_version', '3.1.0'))
		);
	}
	
}