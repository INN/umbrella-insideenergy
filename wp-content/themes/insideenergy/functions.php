<?php
/**
 * Constants
 */
// This site is an INN Member
if ( ! defined( 'INN_MEMBER' ) ) {
        define( 'INN_MEMBER', true );
}
// This site is hosted by INN
if ( ! defined( 'INN_HOSTED' ) ) {
        define( 'INN_HOSTED', true );
}	

/**
 * Include theme files
 *
 * Based off of how Largo loads files: https://github.com/INN/Largo/blob/master/functions.php#L358
 *
 * 1. hook function Largo() on after_setup_theme
 * 2. function Largo() runs Largo::get_instance()
 * 3. Largo::get_instance() runs Largo::require_files()
 *
 * This function is intended to be easily copied between child themes, and for that reason is not prefixed with this child theme's normal prefix.
 *
 * @link https://github.com/INN/Largo/blob/master/functions.php#L145
 * @since Largo 0.5.4
 * @since 0.2
 */
function largo_child_require_files() {
	$includes = array(
		'/inc/metaboxes.php',
		'/homepages/layouts/TopStory.php'
	);
	foreach ( $includes as $include ) {
		require_once( get_stylesheet_directory() . $include );
	}
}
add_action( 'after_setup_theme', 'largo_child_require_files' );

/**
 * Include compiled style.css
 * @since Largo 0.5.4
 * @since 0.2
 */
function ie_styles() {
	wp_dequeue_style( 'largo-child-styles' );
	$suffix = (LARGO_DEBUG)? '' : '.min';
	wp_enqueue_style( 'insideenergy', get_stylesheet_directory_uri().'/css/largo-child' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'ie_styles', 20 );

/**
 * output div#super-footer
 * @since Largo 0.5.4
 * @since 0.2
 */
function ie_super_footer() {
	get_template_part('partials/footer-collaborators');
}
add_action( 'largo_before_footer', 'ie_super_footer' );

/**
 * Override Largo's Google Analytics function, adding some GA Classic custom variables
 *
 * @link https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingCustomVariables#overview
 * @see largo_google_analytics
 * @since Largo 0.5.4
 * @since 2016-06-07
 */
	function largo_google_analytics() {
		if ( !current_user_can('edit_posts') ) : // don't track editors ?>
			<script>
			    var _gaq = _gaq || [];
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
				<?php
					/*
					 * Add the series as a custom variable on the Google Analytics Object
					 * This occurs in between _setAccount and _trackPageView because of order requirements.
					 */
					$string = '';

					// Find the series for this page, if it is in one

					if ( is_tax('series') ) { // it's an archive of a single term
						$series = get_queried_object();
						$string = $series->name;
					} else if ( is_single() ) { // it's a post, and may have multiple series terms
						$terms = wp_get_object_terms(
							get_the_ID(), // this post
							'series', // the taxonomy to fetch
							array( 'orderby' => 'term_id', 'order' => 'DESC') // use newer series first (higher ID numbers)
						);
						foreach ( $terms as $term ) {
							if ( !empty( $term->name ) ) {
								$string = $term->name;
								break; // only hit the first term, ordered by the results of wp_get_post_terms
							}
						}
					}

					// Generate the analytics output iff the post is in a series
					if ( !empty( $string ) ) {
						$gaq = sprintf(
							"_gaq.push(['_setCustomVar', 1, 'series', '%1\$s', 3 ]);",
							$string
						);
						echo $gaq;
					}

				?>
				_gaq.push(['_trackPageview']);
			<?php endif; ?>

				<?php if (defined('INN_MEMBER') && INN_MEMBER) { ?>
				_gaq.push(
					["inn._setAccount", "UA-17578670-2"],
					["inn._setCustomVar", 1, "MemberName", "<?php bloginfo('name') ?>"],
					["inn._trackPageview"]
				);
				<?php } ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo('name') ?>"],
					["largo._setDomainName", "<?php echo parse_url( home_url(), PHP_URL_HOST ); ?>"],
					["largo._setAllowLinker", true],
					["largo._trackPageview"]
				);

			    (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
	<?php endif;
	}
