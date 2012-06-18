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
		$this->add_template( 'block.twitter', dirname( __FILE__ ) . '/blocks/block.twitter.php' );
		$this->add_template( 'block.tumbler', dirname( __FILE__ ) . '/blocks/block.tumbler.php' );
		$this->add_template( 'block.github', dirname( __FILE__ ) . '/blocks/block.github.php' );
		$this->add_template( 'block.dribbble', dirname( __FILE__ ) . '/blocks/block.dribbble.php' );
		$this->add_template( 'block.instagram', dirname( __FILE__ ) . '/blocks/block.instagram.php' );
		
		
		// i18n
		$this->load_text_domain( 'syte' );
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

		$fs = $ui->append( 'fieldset', 'fs_enable', _t( 'Integration', 'syte' ) );
			$fs->append( 'checkbox', 'enable_tumblr', __CLASS__ . '__enable_tumblr', _t( 'Enable Tumblr', 'syte' ) );
			$fs->append( 'checkbox', 'enable_twitter', __CLASS__ . '__enable_twitter', _t( 'Enable Twitter', 'syte' ) );
			$fs->append( 'checkbox', 'enable_github', __CLASS__ . '__enable_github', _t( 'Enable GitHub', 'syte' ) );
			$fs->append( 'checkbox', 'enable_dribbble', __CLASS__ . '__enable_dribbble', _t( 'Enable dribbble', 'syte' ) );
			$fs->append( 'checkbox', 'enable_instagram', __CLASS__ . '__enable_instagram', _t( 'Enable Instagram', 'syte' ) );

		$fs = $ui->append( 'fieldset', 'fs_other_config', _t( 'Other Settings', 'syte' ) );
			/*$fs->append( 'text', 'syte_title', __CLASS__ . '__syte_title', _t( 'Syte Title:', 'syte' ) );
			$fs->syte_title->helptext = _t( 'If this is left blank, the site-wide title set under Options will be used.' );
			$fs->append( 'text', 'syte_tagline', __CLASS__ . '__syte_tagline', _t( 'Syte Tagline:', 'syte' ) );
			$fs->syte_tagline->helptext = _t( 'If this is left blank, the site-wide tagline set under Options will be used.' );*/

		$fs = $ui->append( 'fieldset', 'fs_appearance', _t( 'Appearance Settings', 'syte' ) );
			$fs->append( 'text', 'syte_color', __CLASS__ . '__syte_color', _t( 'Primary Color', 'syte' ) );
		
		
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

		// Get the current blocks list
		$blocks = $this->get_blocks( 'sidebar', 0, $this );
		// I think we need a has() function for blocks to make this easier.
		// Parse the blocks and grab just the types into an array
		$blocks_types = array();
		foreach( $blocks as $block ) {
			$block_types[] = $block->type;
		}

		// Check if we have the requested block enabled or not. If not, enable it.
		// TODO: Do we want to remove the block if the config form has the field unchecked?
		foreach( $ui->controls['fs_enable']->controls as $component ) {
			$comp_name = explode( '_', $component->name );
			$block_name = $comp_name[1];
			if ( $component->value === true && !in_array( 'syte_' . $block_name, $block_types ) ) {
				$block = new Block( array(
					'title' => ucfirst( $block_name ),
					'type' => 'syte_' . $block_name,
				) );
			
				$block->add_to_area( 'sidebar' );
				Session::notice( _t( 'Added ' . ucfirst( $block_name ) . ' block to sidebar area.' ) );
			}
		}
		
		// Force a full refresh to show our new blocks.
		Utils::redirect();
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
	
		$theme_opts = Options::get_group( __CLASS__ );
		// Add CSS
		if ( $theme_opts['dev_mode'] ) {
			// TODO: Need to change the "rel"
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
		
		// Now for some magic.  Lets generate the intergration variables and load the necessary scripts in one go
		$int_var_str = 'var ';
		foreach( $theme_opts as $option => $val ) {
			if ( preg_match('/^enable_(.+)$/', $option, $matches ) ) {
				$int_var_str .= $matches[1] . '_integration_enabled = ';
				if ( $val == 1 ) {
					$int_var_str .= 'true,';
					Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/' . $matches[1] . '.js', 'integration_js', 'intergration_vars' );
				} else {
					$int_var_str .= 'false,';
				}
			}
		}
		
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
		$fn = function($a) {return "<li><a href=\"" . URL::get("display_entries_by_tag", array( "tag" => $a->term) ) . "\">" . $a->term_display . "</a></li>";};
		$array = array_map( $fn, (array)$array );
		$out = implode( ' ', $array );
		return '<ul class="tags">' . $out . '</ul>';
	}
	
	/**
	 * Add the blocks to the list of selectable blocks
	 */
	public function filter_block_list( $block_list )
	{
		$block_list[ 'syte_tumblr' ] = _t( 'Syte - Tumblr Integration', 'syte' );
		$block_list[ 'syte_twitter' ] = _t( 'Syte - Twitter Integration', 'syte' );
		$block_list[ 'syte_github' ] = _t( 'Syte - Github Integration', 'syte' );
		$block_list[ 'syte_dribbble' ] = _t( 'Syte - dribbble Integration', 'syte' );
		$block_list[ 'syte_instagram' ] = _t( 'Syte - Instagram Integration', 'syte' );
		
		return $block_list;
	}
	
	/**
	 * Configure the tumblr block
	 */
	public function action_block_form_syte_tumblr( $form, $block )
	{
		$form->append( 'text', 'tumbler_blog_url', __CLASS__ . '__tumbler_blog_url', _t( 'Tumbler Blog URL', 'syte' ) );
		$form->append( 'text', 'tumbler_api_key', __CLASS__ . '__tumbler_api_key', _t( 'Tumbler API Key', 'syte' ) );
	}
	
	/**
	 * Populate the tumblr block with some content
	 **/
	public function action_block_content_syte_tumblr( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the twitter block
	 * 
	 * @todo: Implement Twitter authentication as used by the Twitter plugin. For the moment everything is hard coded.
	 */
	public function action_block_form_syte_twitter( $form, $block )
	{
		$form->append( 'text', 'twitter_consumer_key', $block, _t( 'Twitter Consumer Key', 'syte' ) );
		$form->append( 'text', 'twitter_consumer_secret', $block, _t( 'Twitter Consumer Secret', 'syte' ) );
		$form->append( 'text', 'twitter_user_key', $block, _t( 'Twitter User Key', 'syte' ) );
		$form->append( 'text', 'twitter_user_secret', $block, _t( 'Twitter User Secret', 'syte' ) );
	}
	
	/**
	 * Populate the twitter block with some content
	 **/
	public function action_block_content_syte_twitter( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the github block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_syte_github( $form, $block )
	{
		$form->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token', 'syte' ) );
		$form->append( 'text', 'github_client_id', __CLASS__ . '__github_client_id', _t( 'GitHub Client ID', 'syte' ) );
		// TODO: I think these should be hardcoded and specific to this plugin
		$form->append( 'text', 'github_client_secret', __CLASS__ . '__github_client_secret', _t( 'GitHub Client Secret', 'syte' ) );
		$form->append( 'text', 'github_access_token', __CLASS__ . '__github_access_token', _t( 'GitHub Access Token', 'syte' ) );
	}
	
	/**
	 * Populate the github block with some content
	 **/
	public function action_block_content_syte_github( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the dribbble block
	 * 
	 */
	public function action_block_form_syte_dribbble( $form, $block )
	{

	}
	
	/**
	 * Populate the dribbble block with some content
	 **/
	public function action_block_content_syte_dribbble( $block, $theme )
	{
		
	}
	
	/**
	 * Configure the instagram block
	 * 
	 * @todo: See if we can obtain this information like we can with Twitter
	 */
	public function action_block_form_syte_instagram( $form, $block )
	{
		$form->append( 'text', 'instagram_access_token', __CLASS__ . '__instagram_access_token', _t( 'Instagram Access Token', 'syte' ) );
		$form->append( 'text', 'instagram_user_id', __CLASS__ . '__instagram_user_id', _t( 'Instagram User ID', 'syte' ) );
		// TODO: I think these should be hardcoded and specific to this plugin
		$form->append( 'text', 'instagram_client_id', __CLASS__ . '__instagram_client_id', _t( 'Instagram Client ID', 'syte' ) );
		$form->append( 'text', 'instagram_client_secret', __CLASS__ . '__instagram_client_secret', _t( 'Instagram Client Secret', 'syte' ) );
	}
	
	/**
	 * Populate the instagram block with some content
	 **/
	public function action_block_content_syte_instagram( $block, $theme )
	{
		
	}
	
	
	
	/*********************** Helper Functions *************************************
	 * Most of these functions should probably go into a plugin.  We'll see about
	 * that as I develop the theme.
	 */
	
		 
}
?>
