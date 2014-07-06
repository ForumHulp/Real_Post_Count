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

    /**
    * Constructor
    */
    public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\user $user, \phpbb\template\template $template)
    {
        $this->config = $config;
		$this->helper = $helper;
		$this->user = $user;
		$this->template = $template;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.acp_board_config_edit_add'	=> 'load_config_on_setup',
			'core.user_setup'					=> 'load_language_on_setup',
			'core.submit_post_end'				=> 'add_post_count',
			'core.index_modify_page_title'		=> 'real_post_count'	
		);
    }

	public function real_post_count($event)
	{
		$this->template->assign_vars(array(
			'REAL_POST_COUNT' => ($this->config['real_postcount'] > $this->config['num_posts']) ? sprintf($this->user->lang['POST_COUNT_REAL'], $this->config['real_postcount']) : '',
			'TOTAL_POSTS' => $this->user->lang(($this->config['real_postcount'] > $this->config['num_posts']) ? 'TOTAL_POSTS_COUNTER' : 'TOTAL_POSTS_COUNT', (int) $this->config['num_posts']),
		));
	}

	public function add_post_count()
	{
		set_config('real_postcount', $this->config['real_postcount'] + 1, true);
	}
	
    public function load_config_on_setup($event)
    {
		if ($event['mode'] == 'features')
		{
			$config_set_ext = $event['display_vars'];
			$config_set_vars = array_slice($config_set_ext['vars'], 0, 16, true);
			
			$config_set_vars['real_postcount'] = 
				array(
					'lang' 		=> 'POST_COUNT',
					'validate'	=> 'int',
					'type'		=> 'number:0',
					'explain'	=> true
				);

			$config_set_vars += array_slice($config_set_ext['vars'], 16, count($config_set_ext['vars']) - 1, true);
			$event['display_vars'] = array('title' => $config_set_ext['title'], 'vars' => $config_set_vars);
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