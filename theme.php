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
			$fs->append( 'checkbox', 'dev_mode', __CLASS__ . '__dev_mode', _t( 'Development Deployment Mode:' ) );
			
		$fs = $ui->append( 'fieldset', 'fs_tumbler', _t( 'Tumbler Integration' ) );
			$fs->append( 'checkbox', 'enable_tumbler', __CLASS__ . '__enable_tumbler', _t( 'Enable Tumbler Integration?' ) );
			// TODO: Make these appear only when above checkbox is ticked
			$fs->append( 'text', 'tumbler_blog_url', __CLASS__ . '__tumbler_blog_url', _t( 'Tumbler Blog URL' ) );
			$fs->append( 'text', 'tumbler_api_key', __CLASS__ . '__tumbler_api_key', _t( 'Tumbler API Key' ) );
		
		// TODO: Implement Twitter authentication as used by the Twitter plugin. For the moment everything is hard coded.
		$fs = $ui->append( 'fieldset', 'fs_twitter', __CLASS__ . '__enable_twitter', _t( 'Twitter Integration' ) );
			$fs->append( 'checkbox', 'enable_twitter', _t( 'Enable Twitter Integration?' ) );
			// TODO: Make these appear only when above checkbox is ticked
			$fs->append( 'text', 'twitter_consumer_key', __CLASS__ . '__twitter_consumer_key', _t( 'Twitter Consumer Key' ) );
			$fs->append( 'text', 'twitter_consumer_secret', __CLASS__ . '__twitter_consumer_secret', _t( 'Twitter Consumer Secret' ) );
			$fs->append( 'text', 'twitter_user_key', __CLASS__ . '__twitter_user_key', _t( 'Twitter User Key' ) );
			$fs->append( 'text', 'twitter_user_secret', __CLASS__ . '__twitter_user_secret', _t( 'Twitter User Secret' ) );
			
		// TODO: See if we can obtain this information like we can with Twitter
		$fs = $ui->append( 'fieldset', 'fs_github', _t( 'GitHub Integration' ) );
			$fs->append( 'checkbox', 'enable_github', __CLASS__ . '__enable_github', _t( 'Enable GitHub Integration?' ) );
			// TODO: Make these appear only when above checkbox is ticked
			$fs->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token' ) );
			$fs->append( 'text', 'github_client_id', __CLASS__ . '__github_client_id', _t( 'GitHub Client ID' ) );
			// TODO: I think these should be hardcoded and specific to this plugin
			$fs->append( 'text', 'github_client_secret', __CLASS__ . '__github_client_secret', _t( 'GitHub Client Secret' ) );
			$fs->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token' ) );
			
		$fs = $ui->append( 'fieldset', 'fs_dribble', _t( 'Dribble Integration' ) );
			$fs->append( 'checkbox', 'enable_dribble', __CLASS__ . '__enable_dribble', _t( 'Enable Dribble Integration?' ) );
		
		// TODO: See if we can obtain this information like we can with Twitter
		$fs = $ui->append( 'fieldset', 'fs_instagram', _t( 'Instagram Integration' ) );
			$fs->append( 'checkbox', 'enable_instagram', __CLASS__ . '__enable_instagram', _t( 'Enable Instagram Integration?' ) );
			// TODO: Make these appear only when above checkbox is ticked
			$fs->append( 'text', 'instagram_access_token', __CLASS__ . '__instagram_access_token', _t( 'Instagram Access Token' ) );
			$fs->append( 'text', 'instagram_user_id', __CLASS__ . '__instagram_user_id', _t( 'Instagram User ID' ) );
			// TODO: I think these should be hardcoded and specific to this plugin
			$fs->append( 'text', 'instagram_client_id', __CLASS__ . '__instagram_client_id', _t( 'Instagram Client ID' ) );
			$fs->append( 'text', 'instagram_client_secret', __CLASS__ . '__instagram_client_secret', _t( 'Instagram Client Secret' ) );

		
		// TODO: See if we can obtain this information like we can with Twitter. We can probably take this from the Flickr plugin
		$fs = $ui->append( 'fieldset', 'fs_flickr', _t( 'Flickr Integration' ) );
			$fs->append( 'checkbox', 'enable_flickr', __CLASS__ . '__enable_flickr', _t( 'Enable Flickr Integration?' ) );
			// TODO: Make these appear only when above checkbox is ticked
			
		// TODO: Make this extensible by other plugins here.
		
		$fs = $ui->append( 'fieldset', 'fs_appearance', _t( 'Appearance Settings' ) );
		
		
		$ui->append( 'submit', 'save', _t( 'Save' ) );
		$ui->set_option( 'success_message', _t( 'Options saved' ) );
		$ui->out();
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
	
	
	/*********************** Helper Functions *************************************
	 * Most of these functions should probably go into a plugin.  We'll see about
	 * that as I develop the theme.
	 */

		 
}
?>
