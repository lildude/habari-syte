<?php if ( !defined( 'HABARI_PATH' ) ) { die( 'No direct access' ); }

class SyteTheme extends Theme
{
	/**
	 * Execute on theme init to apply these filters to output
	 */
	public function action_init_theme()
	{
		// Apply Format::autop() to comment content...
		Format::apply( 'autop', 'comment_content_out' );
		// Truncate content excerpt at "more" or 56 characters...
		Format::apply( 'autop', 'post_content_excerpt' );
		Format::apply_with_hook_params( 'more', 'post_content_excerpt', 'Continue reading...', 200, 1 );
	}

	/**
	 * On theme activation, do the following
	 */
	public function action_theme_activation( )
	{
		// Add activation actions here
	}

	/**
	 * On theme de-activation, do the following
	 */
	public function action_theme_deactivation() 
	{
		// Add deactivation actions here
	}

	/**
	 * Is this theme configurable?
	 */
	public function filter_theme_config( $configurable )
	{
		$configurable = true;
		return $configurable;
	}

	/**
	 * Present a configuration form for the theme. 
	 * 
	 * @todo Implement decent looking FormControl elements
	 */
	public function action_theme_ui( $theme )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$fs = $ui->append( 'fieldset', 'fs_mode', '' );
			$fs->append( 'checkbox', 'dev_mode', __CLASS__ . '__dev_mode', _t( 'Development Deployment Mode:', 'syte' ) );

		// TODO: Automatically add these to the Theme's block list on save
		$fs = $ui->append( 'fieldset', 'fs_enable', _t( 'Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_tumbler', __CLASS__ . '__enable_tumbler', _t( 'Enable Tumbler Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_twitter', __CLASS__ . '__enable_twitter', _t( 'Enable Twitter Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_github', __CLASS__ . '__enable_github', _t( 'Enable GitHub Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_dribble', __CLASS__ . '__enable_dribble', _t( 'Enable Dribble Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_instagram', __CLASS__ . '__enable_instagram', _t( 'Enable Instagram Integration', 'syte' ) );


		$fs = $ui->append( 'fieldset', 'fs_appearance', _t( 'Appearance Settings', 'syte' ) );
		
		
		$ui->append( 'submit', 'save', _t( 'Save' ) );
		//$ui->set_option( 'success_message', _t( 'Options saved', 'syte' ) );
		$ui->on_success( array( $this, 'store_options' ) );
		$ui->out();
	}
	
	/**
	 * Save the configuration form an activate the blocks requested in the configuration.
	 */
	public function store_options( $ui )
	{
		
		// Save our config
		Options::set_group( __CLASS__, $options );
		
		// Force a full refresh to show our new blocks.
		//Utils::redirect();
	}
	
	/**
	 * Add some variables to the template output
	 */
	public function add_template_vars()
	{
		if ( !$this->template_engine->assigned( 'pages' ) ) {
            $this->assign('pages', Posts::get( array( 'content_type' => 'page', 'status' => Post::status( 'published' ), 'nolimit' => 1 ) ) );
        }
        if ( !$this->template_engine->assigned( 'user' ) ) {
            $this->assign('user', User::identify() );
        }
		if ( !$this->template_engine->assigned( 'loggedin' ) ) {
            $this->assign('loggedin', User::identify()->loggedin );
        }
	
		// Add CSS
		if ( Options::get( 'SyteTheme__dev_mode' ) ) {
			Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/less/styles.less', 'screen, projection' ), 'style' );
			//<link rel="stylesheet/less" type="text/css" href="{{ MEDIA_URL }}less/styles.less">
			Stack::add( 'template_header_javascript', Site::get_url( 'theme' ) . '/css/less/less-1.1.5.min.js', 'less' );
		} else {
			Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/styles-{{ COMPRESS_REVISION_NUMBER }}.min.css', 'screen, projection' ), 'style' );
		}
		
		// Add other javascript support files
		
	}
	
	public function filter_post_tags_out( $terms )
	{
		$array = array();
		if ( !$terms instanceof Terms ) {
			$terms = new Terms( $terms );
		}

		foreach ( $terms as $term ) {
			$array[$term->term] = $term->term_display;
		}
		
		ksort( $array );
		
		$fn = create_function( '$a,$b', 'return "<a href=\\"" . URL::get("display_entries_by_tag", array( "tag" => $b) ) . "\\" rel=\\"tag\\">" . $a . "</a>";' );
		$array = array_map( $fn, $array, array_keys( $array ) );
		// $last = array_pop( $array );
		$out = implode( ', ', $array );
		return $out;
	}
	
	/**
	 * Add the blocks to the list of selectable blocks
	 */
	public function filter_block_list( $block_list )
	{
		$block_list[ 'tumblr' ] = _t( 'Tumblr Integration', 'syte' );
		$block_list[ 'twitter' ] = _t( 'Twitter Integration', 'syte' );
		$block_list[ 'github' ] = _t( 'Github Integration', 'syte' );
		$block_list[ 'dribble' ] = _t( 'Dribble Integration', 'syte' );
		$block_list[ 'instagram' ] = _t( 'Instagram Integration', 'syte' );
		
		return $block_list;
	}
	
	/**
	 * Configure the tumblr block
	 */
	public function action_block_form_tumblr( $form, $block )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$ui->append( 'text', 'tumbler_blog_url', __CLASS__ . '__tumbler_blog_url', _t( 'Tumbler Blog URL', 'syte' ) );
		$ui->append( 'text', 'tumbler_api_key', __CLASS__ . '__tumbler_api_key', _t( 'Tumbler API Key', 'syte' ) );
		$ui->append( 'submit', 'save', _t( 'Save' ) );
	}
	
	/**
	 * Configure the twitter block
	 * 
	 * @todo: Implement Twitter authentication as used by the Twitter plugin. For the moment everything is hard coded.
	 */
	public function action_block_form_twitter( $form, $block )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$ui->append( 'text', 'twitter_consumer_key', __CLASS__ . '__twitter_consumer_key', _t( 'Twitter Consumer Key', 'syte' ) );
		$ui->append( 'text', 'twitter_consumer_secret', __CLASS__ . '__twitter_consumer_secret', _t( 'Twitter Consumer Secret', 'syte' ) );
		$ui->append( 'text', 'twitter_user_key', __CLASS__ . '__twitter_user_key', _t( 'Twitter User Key', 'syte' ) );
		$ui->append( 'text', 'twitter_user_secret', __CLASS__ . '__twitter_user_secret', _t( 'Twitter User Secret', 'syte' ) );
		$ui->append( 'submit', 'save', _t( 'Save' ) );
	}
	
	/**
	 * Configure the github block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_github( $form, $block )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$ui->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token', 'syte' ) );
		$ui->append( 'text', 'github_client_id', __CLASS__ . '__github_client_id', _t( 'GitHub Client ID', 'syte' ) );
		// TODO: I think these should be hardcoded and specific to this plugin
		$ui->append( 'text', 'github_client_secret', __CLASS__ . '__github_client_secret', _t( 'GitHub Client Secret', 'syte' ) );
		$ui->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token', 'syte' ) );
		$ui->append( 'submit', 'save', _t( 'Save' ) );
	}
	
	/**
	 * Configure the dribble block
	 * 
	 */
	public function action_block_form_dribble( $form, $block )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$ui->append( 'submit', 'save', _t( 'Save' ) );
	}
	
	/**
	 * Configure the instagram block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_instagram( $form, $block )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$ui->append( 'text', 'instagram_access_token', __CLASS__ . '__instagram_access_token', _t( 'Instagram Access Token', 'syte' ) );
		$ui->append( 'text', 'instagram_user_id', __CLASS__ . '__instagram_user_id', _t( 'Instagram User ID', 'syte' ) );
		// TODO: I think these should be hardcoded and specific to this plugin
		$ui->append( 'text', 'instagram_client_id', __CLASS__ . '__instagram_client_id', _t( 'Instagram Client ID', 'syte' ) );
		$ui->append( 'text', 'instagram_client_secret', __CLASS__ . '__instagram_client_secret', _t( 'Instagram Client Secret', 'syte' ) );
		$ui->append( 'submit', 'save', _t( 'Save' ) );
	}
	
	
	/*********************** Helper Functions *************************************
	 * Most of these functions should probably go into a plugin.  We'll see about
	 * that as I develop the theme.
	 */

		 
}
?>
