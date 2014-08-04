<?php
/**
*
* @package Real post count
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\real_postcount\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	protected $config;
    protected $helper;
	protected $user;
	protected $template;
	protected $db;

    /**
    * Constructor
    */
    public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\user $user, \phpbb\template\template $template, \phpbb\db\driver\driver_interface $db)
    {
        $this->config = $config;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
		$this->db = $db;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.acp_board_config_edit_add'	=> 'load_config_on_setup',
			'core.user_setup'					=> 'load_language_on_setup',
			'core.submit_post_end'				=> 'add_post_count',
			'core.index_modify_page_title'		=> 'real_post_count',
			'core.viewtopic_modify_post_row'	=> 'display_real_post_count',
			'core.ucp_pm_view_messsage'			=> 'display_pm_real_rank',
			
//			'core.memberlist_view_profile'		=> 'display_memberlist_view_profile',
			'core.memberlist_prepare_profile_data' => 'display_real_post_count_memberlist',
			
			'core.modify_user_rank'			=> 'modify_user_rank'
		);
    }

	public function modify_user_rank($event)
	{
		$real_rank = $event['user_data'];
		if (!isset($real_rank['user_real_posts']))
		{
			$sql = 'SELECT user_real_posts FROM ' . USERS_TABLE . ' WHERE user_id = ' . $real_rank['user_id'];
			$result = $this->db->sql_query($sql);
			$real_rank['user_real_posts'] = (int) $this->db->sql_fetchfield('user_real_posts');
		}		
		$real_rank['user_posts'] = $real_rank['user_real_posts'];
		$event['user_posts'] = $real_rank;
	}



	public function display_memberlist_view_profile($event)
	{
		$real_rank = $event['member'];		
		$real_rank['user_posts'] = $real_rank['user_real_posts'];
		$event['member'] = $real_rank;
	}
	
	public function display_real_post_count_memberlist($event)
	{
		$real_rank = $event['data'];

//		$real_rank['user_posts'] = $real_rank['user_real_posts'];
//		$event['data'] = $real_rank;
		
		$template_data = $event['template_data'];

		$template_data += array('REAL_POSTCOUNT' => $real_rank['user_real_posts']);
		$event['template_data'] = $template_data;
	}

	public function display_pm_real_rank($event)
	{
		$real_rank = $event['message_row'];
		
		$template_data = $event['msg_data'];
		$template_data += array('REAL_POSTCOUNT' => $real_rank['user_real_posts']);
		$event['msg_data'] = $template_data;
	}

	public function display_real_rank($event)
	{
		$real_rank = $event['row'];
		$real_rank['user_posts'] = $real_rank['user_real_posts'];
		$event['row'] = $real_rank;
	}

	public function display_real_post_count($event)
	{
		$template_data = $event['post_row'];
		$sql = 'SELECT user_real_posts FROM ' . USERS_TABLE . ' WHERE user_id = ' . $event['poster_id'];
		$result = $this->db->sql_query($sql);
		$user_real_posts = (int) $this->db->sql_fetchfield('user_real_posts');

		$template_data += array('REAL_POSTCOUNT' => $user_real_posts);
		$event['post_row'] = $template_data;
	}
	
	public function real_post_count($event)
	{
		$this->template->assign_vars(array(
			'REAL_POST_COUNT' => ($this->config['real_postcount'] > $this->config['num_posts']) ? sprintf($this->user->lang['POST_COUNT_REAL'], $this->config['real_postcount']) : '',
			'TOTAL_POSTS' => $this->user->lang(($this->config['real_postcount'] > $this->config['num_posts']) ? 'TOTAL_POSTS_COUNTER' : 'TOTAL_POSTS_COUNT', (int) $this->config['num_posts']),
		));
	}

	public function add_post_count($post)
	{
		set_config('real_postcount', $this->config['real_postcount'] + 1, true);
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_real_posts = user_real_posts + 1 WHERE user_id = ' . $this->user->data['user_id'];
		$this->db->sql_query($sql);
	}
	
    public function load_config_on_setup($event)
    {
		if ($event['mode'] == 'features')
		{
			$display_vars = $event['display_vars'];
			
			$add_config_var['real_postcount'] = 
				array(
					'lang' 		=> 'POST_COUNT',
					'validate'	=> 'int',
					'type'		=> 'number:0',
					'explain'	=> true
				);

			$display_vars['vars'] = insert_config_array($display_vars['vars'], $add_config_var, array('after' =>'allow_quick_reply'));
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
		}
    }
	
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'forumhulp/real_postcount',
			'lang_set' => 'real_postcount_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}