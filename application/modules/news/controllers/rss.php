<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rss extends Public_Controller {

	function __construct() {
        parent::Public_Controller();
        
        $this->load->model('news_m');
        $this->load->helper('xml');
		$this->load->helper('date');
    }
    
    function index()
    {
        $posts = $this->cache->call('news_m', 'getNews', array(
        	array(
        		'status' 	=> 'live',
        		'limit'		=> $this->settings->item('rss_feed_items')
        	)
        ), $this->settings->item('rss_cache'));
        
        $this->_build_feed( $posts );
        
        $this->data->rss->feed_name .= ' News';
        
        $this->output->set_header('Content-Type: application/rss+xml');
        $this->load->view('rss', $this->data);
    }
    
    function category( $slug = '')
    { 
        $this->load->module_model('categories', 'categories_m');
        
        if(!$category = $this->categories_m->getCategory($slug))
        {
        	redirect('news/rss/index');
        }
        
        $posts = $this->cache->call('news_m', 'getNews', array(
        	array(
        		'status' 	=> 'live',
        		'category'	=> $slug,
        		'limit' 	=> $this->settings->item('rss_feed_items') 
        	)
        ), $this->settings->item('rss_cache'));
        
        $this->_build_feed( $posts );
        
        $this->data->rss->feed_name .= ' '. $category->title .' News';
        
        $this->output->set_header('Content-Type: application/rss+xml');
        $this->load->view('rss', $this->data);
    }
    
    function _build_feed( $posts = array() )
    {
    	$this->data->rss->encoding = $this->config->item('charset');
        $this->data->rss->feed_name = $this->settings->item('site_name');
        $this->data->rss->feed_url = base_url();
        $this->data->rss->page_description = 'News articles for '.$this->settings->item('site_name');
        $this->data->rss->page_language = 'en-gb';
        $this->data->rss->creator_email = $this->settings->item('contact_email');

		if(!empty($posts))
		{        
			foreach($posts as $row)
			{
				//$row->created_on = human_to_unix($row->created_on);
				$row->link = site_url('news/' .date('Y/m', $row->created_on) .'/'. $row->slug);
				$row->created_on = standard_date('DATE_RSS', $row->created_on);
				
				$item = array(
				   //'author' => $row->author,
				   'title' => xml_convert($row->title),
				   'link' => $row->link,
				   'guid' => $row->link,
				   'description'  => str_replace('/img/post_resources/', base_url() . 'img/post_resources/', $row->body),
				   'date' => $row->created_on
				 );
	
				$this->data->rss->items[] = (object) $item;
			} 
        }
        
    }
}
?>
