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

		/* 
		 	$fs = $ui->append( 'fieldset', 'fs_enable', _t( 'Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_tumblr', __CLASS__ . '__enable_tumblr', _t( 'Enable Tumblr', 'syte' ) );
			$fs->append( 'checkbox', 'enable_twitter', __CLASS__ . '__enable_twitter', _t( 'Enable Twitter', 'syte' ) );
			$fs->append( 'checkbox', 'enable_github', __CLASS__ . '__enable_github', _t( 'Enable GitHub', 'syte' ) );
			$fs->append( 'checkbox', 'enable_dribbble', __CLASS__ . '__enable_dribbble', _t( 'Enable dribbble', 'syte' ) );
			$fs->append( 'checkbox', 'enable_instagram', __CLASS__ . '__enable_instagram', _t( 'Enable Instagram', 'syte' ) );
			*/
		$fs = $ui->append( 'fieldset', 'fs_other_config', _t( 'Other Settings', 'syte' ) );
			/*$fs->append( 'text', 'syte_title', __CLASS__ . '__syte_title', _t( 'Syte Title:', 'syte' ) );
			$fs->syte_title->helptext = _t( 'If this is left blank, the site-wide title set under Options will be used.' );
			$fs->append( 'text', 'syte_tagline', __CLASS__ . '__syte_tagline', _t( 'Syte Tagline:', 'syte' ) );
			$fs->syte_tagline->helptext = _t( 'If this is left blank, the site-wide tagline set under Options will be used.' );*/

		$fs = $ui->append( 'fieldset', 'fs_appearance', _t( 'Appearance Settings', 'syte' ) );
			$fs->append( 'text', 'pri_color', __CLASS__ . '__pri_color', _t( 'Primary Color', 'syte' ) );
			$fs->append( 'text', 'txt_color', __CLASS__ . '__txt_color', _t( 'Text Color', 'syte' ) );
			$fs->append( 'text', 'alt_color', __CLASS__ . '__alt_color', _t( 'Alternate Color', 'syte' ) );
			$fs->append( 'text', 'lnk_color', __CLASS__ . '__lnk_color', _t( 'Link Color', 'syte' ) );
			
		
		
		$ui->append( 'submit', 'save', _t( 'Save' ) );
		$ui->set_option( 'success_message', _t( 'Options saved', 'syte' ) );
		$ui->on_success( array( $this, 'enable_integrations' ) );
		$ui->out();
	}
	
	/**
	 * Save the configuration form and activate the blocks requested in the configuration.
	 */
	public function enable_integrations( $ui )
	{
		// Save our config
		$ui->save();
		
		// TODO: Need to find a better method of doing this.  Maybe we can only use a cache file if in dev mode, else we do the compiling outselves.
		// Write the variables file to cache - we could write this to the theme dir, but we can't guarantee it'll be writeable.
		// Grab the theme options
		$theme_opts = Options::get_group( __CLASS__ );
		Utils::debug($theme_opts);
	}
	
	/**
	 * Add some variables to the template output
	 */
	public function add_template_vars()
	{
		// i18n
		$this->load_text_domain( 'syte' );
		
		if ( !$this->template_engine->assigned( 'pages' ) ) {
            $this->assign('pages', Posts::get( array( 'content_type' => 'page', 'status' => Post::status( 'published' ), 'nolimit' => 1 ) ) );
        }
        if ( !$this->template_engine->assigned( 'user' ) ) {
            $this->assign('user', User::identify() );
        }
		if ( !$this->template_engine->assigned( 'loggedin' ) ) {
            $this->assign('loggedin', User::identify()->loggedin );
        }
	
		// Make posts an instance of Posts if it's just one
		if ( $this->posts instanceof Post ) {
			$this->posts = new Posts( array( $this->posts ) );
		}
		
		$theme_opts = Options::get_group( __CLASS__ );
		// Add CSS
		if ( $theme_opts['dev_mode'] ) {
			// TODO: Need to change the "rel" - at the moment this is hard-coded into the header
			//Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/less/styles.less', 'screen, projection' ), 'style' );
			//<link rel="stylesheet/less" type="text/css" href="{{ MEDIA_URL }}less/styles.less">
			Stack::add( 'template_header_javascript', Site::get_url( 'theme' ) . '/css/less/less-1.1.5.min.js', 'less' );
			
			// Load the dev libs.  Not sure if they all require jquery at the moment
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/jquery.url.js', 'jquery_url', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/require.js', 'require', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/handlebars.js', 'handlebars', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/moment.min.js', 'moment', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/bootstrap-modal.js', 'bootstrap', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/spin.min.js', 'spin', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/prettify.js', 'prettyfy', 'jquery' );
			
			
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/base.js', 'base', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/mobile.js', 'mobile', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/blog-posts.js', 'blog-posts', 'jquery' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/links.js', 'links', 'jquery' );

		} else {
			Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/styles-{{ COMPRESS_REVISION_NUMBER }}.min.css', 'screen, projection' ), 'style' );
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/min/scripts-{{ COMPRESS_REVISION_NUMBER }}.min.js', 'links', 'jquery' );
		}
		
		// Add other javascript support files
		Stack::add( 'template_footer_javascript', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'jquery' );
		
		// Now for some magic.  Lets generate the intergration related variables and load the necessary scripts in one go
		$int_var_str = 'var site_path = "' . Site::get_url( 'habari' ) .'",'."\n";
		
		$plugin_opts = Options::get_group( 'Syte' );
		foreach( $plugin_opts as $option => $val ) {
			if ( preg_match('/^enable_(.+)$/', $option, $matches ) ) {
				$int_var_str .= $matches[1] . '_integration_enabled = ';
				if ( $val == 1 ) {
					$int_var_str .= 'true,';
					Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/' . $matches[1] . '.js', $matches[1] . '_integration_js', 'intergration_vars' );
				} else {
					$int_var_str .= 'false,';
				}
			}
		}
		
		// Add the integration variables to the stack.
		Stack::add( 'template_footer_javascript', '
			/*<![CDATA[*/
			' . rtrim( $int_var_str, ',' ) .';
			/*]]>*/
			', 'integration_vars', 'jquery' );
		
		Stack::add( 'template_footer_javascript', '
			$(function() {
				/* {% if post_id %}
					fetchBlogPosts("{{post_id}}")
					{% elif tag_slug %}
					fetchBlogPosts(null, "{{tag_slug}}");
					{% else %}
					fetchBlogPosts();
					{% endif %}
				*/
				fetchBlogPosts();
			});

			', 'extra_js', 'integration_vars' );
		
	}
	
	/**
	 * Convert a post's tags array into a usable list of links
	 *
	 * @param array $array The tags array from a Post object
	 * @return string The HTML of the linked tags
	 */
	public function filter_post_tags_out( $array )
	{
		$fn = function($a) {return "<li><a href=\"" . URL::get( "display_entries_by_tag", array( "tag" => $a->term ) ) . "\">" . $a->term_display . "</a></li>";};
		$array = array_map( $fn, (array)$array );
		$out = implode( ' ', $array );
		return '<ul class="tags">' . $out . '</ul>';
	}
	
	
	
	
	
	
	/*********************** Helper Functions *************************************
	 * Most of these functions should probably go into a plugin.  We'll see about
	 * that as I develop the theme.
	 */
	
		 
}
?>
