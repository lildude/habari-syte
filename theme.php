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
		// Apply Format::autop() to post excerpt content...
		Format::apply( 'autop', 'post_content_excerpt' );
		// Truncate content excerpt at "more" or 200 characters or the first paragraph...
		Format::apply_with_hook_params( 'more', 'post_content_excerpt', 'Continue reading...', 200, 1 );
	}

	/**
	 * On theme activation, do the following
	 */
	public function action_theme_activation( )
	{
		// Write the custom colors and other options to the user/cache/variables.less file
		self::save_less_vars();
	}

	/**
	 * On theme de-activation, do the following
	 */
	public function action_theme_deactivation() 
	{
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
	 */
	public function action_theme_ui( $theme )
	{	
		$ui = new FormUI( strtolower( __CLASS__ ) );
		$fs = $ui->append( 'fieldset', 'fs_mode', '' );
			$fs->append( 'checkbox', 'dev_mode', __CLASS__ . '__dev_mode', _t( 'Development Deployment Mode:', 'syte' ) );

		$fs = $ui->append( 'fieldset', 'fs_appearance', _t( 'Appearance Settings', 'syte' ) );
			$fs->append( 'static', 'desc', '<p>' . _t( 'Adjust the colors used on your implementation of this theme. Specify the value using valid HEX, RGB or RGBa values, eg "#AA0000" or "rgb(255,0,0)" or "rgba(0,255,0,0.5)". Leaving a field blank to use the theme\'s default.', 'syte' ). '<br><br></p>' );
			$fs->append( 'text', 'pri_color', __CLASS__ . '__adjacent-color', _t( 'Primary Color', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'txt_color', __CLASS__ . '__text-color', _t( 'Text Color', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'alt_color', __CLASS__ . '__alternate-text-color', _t( 'Alternate Text Color', 'syte' ), 'syte_text' );
			$fs->append( 'text', 'lnk_color', __CLASS__ . '__link-color', _t( 'Link Color', 'syte' ), 'syte_text' );
			
		$ui->append( 'submit', 'save', _t( 'Save' ) );
		$ui->set_option( 'success_message', _t( 'Options saved', 'syte' ) );
		$ui->on_success( array( $this, 'save_config' ) );
		$ui->out();
	}
	
	/**
	 * Save the configuration form and activate the blocks requested in the configuration.
	 */
	public function save_config( $ui )
	{
		// Save our config
		$ui->save();
		
		// Write the custom colors to the user/cache/variables.less file
		self::save_less_vars();
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
				
		// Custom formui elements
		$this->add_template( 'my_form', dirname( __FILE__ ) . '/formcontrols/form.php' );
		$this->add_template( 'my_text', dirname( __FILE__ ) . '/formcontrols/text.php' );
		$this->add_template( 'my_textarea', dirname( __FILE__ ) . '/formcontrols/textarea.php' );
		$this->add_template( 'my_submit', dirname( __FILE__ ) . '/formcontrols/submit.php' );
		
	}
	
	/**
	 * Add Stylesheets to theme header
	 */
	public function action_template_header( $theme ) 
	{
		if ( Options::get( __CLASS__ . '__dev_mode' ) ) {
			Stack::add( 'template_header_javascript', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'jquery' );
			Stack::add( 'template_header_javascript', $theme->get_url( '/css/less/less-1.1.5.min.js' ), 'less' );
			Stack::add( 'template_stylesheet', array( $theme->get_url( '/css/less/styles.less' ), null, array( 'type'=> null, 'rel' => 'stylesheet/less' ) ), 'style' );
		} 
		else {
			Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/min/style.min.css', 'screen, projection' ), 'style' );
		}
	}
	
	/**
	 * Add Javascript to footer
	 */
	public function action_template_footer( $theme )
	{
		if ( Options::get( __CLASS__ . '__dev_mode' ) ) {
			// Load the dev libs we need.
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/jquery.url.js', 'jquery_url', 'jquery' );	// url parser
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/bootstrap-modal.js', 'bootstrap', 'jquery' );// Modal library - def need this
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/spin.min.js', 'spin', 'jquery' );			// jquery spinner
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/libs/prettify.js', 'prettyfy', 'jquery' );		// syntax highlighter
			
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/base.js', 'base', 'jquery' );			// doesn't actually do much
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/mobile.js', 'mobile', 'jquery' );		// mobile detection
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/blog-posts.js', 'blog-posts', 'jquery' );// sets things up
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/components/links.js', 'links', 'jquery' );		// changes onclick behaviour for links
		} 
		else {
			Stack::add( 'template_footer_javascript', Site::get_url( 'theme' ) . '/js/min/scripts.min.js', 'links', 'jquery' );
		}
		
		// Add jquery
		Stack::add( 'template_footer_javascript', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'jquery' );
			
		// Define site_path and call "fetchBlogPosts()" to set all the Javascript up		
		Stack::add( 'template_footer_javascript', '
			/*<![CDATA[*/
			var site_path = "' . Site::get_url( 'habari' ) .'";
			/*]]>*/
			$(function() {
				fetchBlogPosts();
			});
			', 'extra_js', 'links' );
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
	
	
	/**
	 * Customise the FormUI based comment form to suit my needs
	 */
	public function action_form_comment( $form )
	{
		$form->set_option( 'template', 'my_form' );
		$form->name = 'comment-form';
		$form->cf_commenter->caption = _t( 'Name' );
		if ( Options::get( 'comments_require_id' ) == 1 ) {
			$form->cf_commenter->required = true;
		}
		$form->cf_commenter->template = 'my_text';
		$form->cf_commenter->title = 'Enter your name';
		$form->cf_commenter->placeholder = 'Your Name';
		if ( User::identify()->loggedin ) {
			$form->cf_commenter->type = 'hidden';
		}
		$form->cf_email->caption = _t( 'Email' );
		if ( Options::get( 'comments_require_id' ) == 1 ) {
			$form->cf_email->required = true;
		}
		$form->cf_email->template = 'my_text';
		if ( User::identify()->loggedin ) {
			$form->cf_email->type = 'hidden';
		} else {
			$form->cf_email->type = 'email';
		}
		$form->cf_email->title = 'Enter your email address';
		$form->cf_email->placeholder = 'your@email.com';
		$form->cf_url->template = 'my_text';
		if ( User::identify()->loggedin ) {
			$form->cf_url->type = 'hidden';
		} else {
			$form->cf_url->type = 'url';
		}
        $form->cf_url->title = 'Enter your URL starting with http://';
		$form->cf_url->placeholder = 'http://yourwebsite.com';
		//$form->cf_content->caption = '';
		$form->cf_content->template = 'my_textarea';
		$form->cf_content->required = true;
		$form->cf_content->rows = 10;
		$form->cf_submit->caption = _t( 'Post Comment' );
		$form->cf_submit->template = 'my_submit';
		$form->cf_submit->class = 'btn';
	}
	
	
	
	/*********************** Helper Functions *************************************/
	 
	/**
	 * Function that saves the theme configured colors to a file in the cache directory.
	 * We do this because we can't guarantee the theme's directory will be writeable 
	 * by the web server and we probably don't want people playing with permissions just
	 * to install and use a theme.
	 * 
	 * @todo: Need to find a better way of doing this.  This is the only way I can
	 * find to have LESS implement our theme configured colors without actually
	 * manually modifying the variables.less file directly.
	 * 
	 */
	public static function save_less_vars()
	{
		if ( !defined( 'FILE_CACHE_LOCATION' ) ) {
			define( 'FILE_CACHE_LOCATION', HABARI_PATH . '/user/cache/' );
		}
		
		$file = FILE_CACHE_LOCATION . '/variables.less';
		
		// Get the theme options
		$opts = Options::get_group( __CLASS__ );
		
		// Pull out just the *-color keys
		$str = "// variables used by the Syte theme in dev mode.\n";
		foreach ( $opts as $key => $value ) {
			if ( ( preg_match( '/.+-color$/', $key ) && ( $value != '' ) ) ) {
				$str .= "@{$key}: {$value};\n";
			}
		}
		
		// Add the portrait URL from the Admin user's profile
		$user = User::get_by_name( 'admin' );
		if ( $user->info->imageurl != '' ) {
			$str .= '@pic_url: "'.$user->info->imageurl.'";';
		}
		
		// Write the data to file
		file_put_contents( $file, $str );
		
		// If we're not in dev mode, regenerate the CSS file from the less files.
		// The syte/css/min/style.min.css file needs to be rw by the web server
		$min_css = Site::get_dir( 'theme' ) . '/css/min/style.min.css';
		if ( ! $opts['dev_mode'] ) {
			
			if ( is_writable( $min_css ) ) {
				require Site::get_dir( 'theme' ) . '/lib/lessc.inc.php';			
				try {
					$less = new lessc();
					$less->setFormatter("compressed");
					file_put_contents( $min_css, $less->compileFile( Site::get_dir( 'theme' ) . '/css/less/styles.less' ) );
				}
				catch ( exception $ex ) {
					EventLog::log( $ex->getMessage(), 'err' );
				}
			} 
			else {
				Options::set( __CLASS__ . '__dev_mode', true );
				Session::notice( $min_css . " is not writable. Reverting to Dev Mode" );
				Utils::redirect(); // So the user sees the dev mode has been enabled.
			}
		}
		
	}
}
?>
