<?php
/**
 * Copied from Largo's Top Stories homepage layout
 * Modified to not show the related posts"
 */

include_once get_template_directory() . '/homepages/homepage-class.php';

class TopStory extends Homepage {

	function __construct($options=array()) {
		$defaults = array(
			'template' 			=> get_stylesheet_directory() . '/homepages/templates/top-story.php',
			'assets' 			=> array(
										array( 'homepage-slider', get_template_directory_uri() . '/homepages/assets/css/top-story.css', array() )
									),
			'name' 				=> __( 'Top Story', 'largo' ),
			'type' 				=> 'top-story',
			'description' 		=> __( 'A newspaper-like layout highlighting one Top Story.', 'largo' ),
			'rightRail' 		=> true,
			'prominenceTerms' 	=> array(
				array(
					'name' 			=> __( 'Homepage Top Story', 'largo' ),
					'description' 	=> __( 'If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage', 'largo' ),
					'slug' 			=> 'top-story'
				),
				array(
					'name' 			=> __( 'Homepage Featured', 'largo' ),
					'description' 	=> __( 'If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage.', 'largo' ),
					'slug' 			=> 'homepage-featured'
				)
			)
		);
		$options = array_merge( $defaults, $options );
		parent::__construct( $options );
	}

}

/**
 * Unregister some of the default homepage templates
 * Register our custom one
 *
 * @since 0.1
 */
function ie_custom_homepage_layouts() {
	$unregister = array(
		'TopStories',
	);

	foreach ( $unregister as $layout )
		unregister_homepage_layout( $layout );

	register_homepage_layout( 'TopStory' );
}
add_action( 'init', 'ie_custom_homepage_layouts', 10 );
