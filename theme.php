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
	 * Add FormUI elements as you need them.
	 * 
	 * This is only shown if filter_theme_config() has $configurable = true.
	 */
	public function action_theme_ui( $theme )
	{
		$ui = new FormUI( strtolower( __CLASS__ ) );


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
		Stack::add( 'template_stylesheet', array( Site::get_url( 'theme' ) . '/css/style.css', 'screen' ), 'style' );
		
		// Add javascript support files
		
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
