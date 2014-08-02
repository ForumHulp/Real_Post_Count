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
			array('config.add', array('real_postcount_version', '3.1.0')),
			array('config.add', array('real_postcount', 0, 1)),
			array('custom', array(array($this, 'copy__postcount_data'))),
		);
	}
	public function copy__postcount_data()
	{
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_real_posts =  user_posts';
		$this->db->sql_query($sql);
		$sql = 'SELECT SUM(user_posts) AS user_real_posts FROM ' . USERS_TABLE;
		$result = $this->db->sql_query($sql);
		$user_real_posts = (int) $this->db->sql_fetchfield('user_real_posts');
		set_config('real_postcount', $user_real_posts);
	}
}