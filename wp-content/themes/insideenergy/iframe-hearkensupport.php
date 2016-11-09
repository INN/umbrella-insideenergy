<?php
/**
 * Template Name: Iframe Page With Hearken Support
 * Single Page Template: Home page to be embedded as iframe in content partner site
 *
 * How to set up a page for embedding:
 * 1. Create a new page
 * 2. Set the 'template' option to "Iframe page with hearken support"
 * 3. In the box labeled "Iframe Category":
 *    - Choose what category of posts are to be displayed in the iframe
 *    - Give that category a display label
 * 4. Publish the page
 * 5. Copy the page's URL
 *
 * How to embed the page:
    <div id="embed-iframe-container" data-pym-src="//example.com/embed"></div>
    <script src="//assets.wearehearken.com/production/thirdparty/p.m.js"></script>
 *
 * In the above code block replace `example.com` with your domain name and `example.com/embed` with the URL from step 5 in the previous section.
 * Paste the edited code block into the page that will contain the embed. If you're pasting into Wordpress, make sure to use the HTML editor.
 *
 * @since 0.1
 */

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$ids = array();
$tags = of_get_option ('tag_display');
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php
	// get the current page url (used for rel canonical and open graph tags)
	global $current_url;
	$current_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<title>
	<?php
		global $page, $paged;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' ); // Add the blog name.

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . 'Page ' . max( $paged, $page );
	?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
?>
<base target="_blank" />
</head>

<body <?php body_class( 'iframe' ); ?>>
	<div id="top"></div>
	<span class="visuallyhidden">
		<a href="#main" title="Skip to content"><?php _e('Skip to content', 'largo'); ?></a>
	</span>

<div id="page" class="hfeed clearfix">
	<?php // @todo: this should probably just be a static banner image ?>
	<header id="site-header" class="clearfix" itemscope itemtype="http://schema.org/Organization">
		<?php largo_header(); ?>
			<a href="http://insideenergy.us10.list-manage.com/subscribe?u=d72986e338f1e1dea558fc29e&id=05955fd1d5" class="btn btn-large center newsletter-signup">
				Sign up for the Inside Energy newsletter
			</a>
	</header>

	<div id="main" class="row-fluid clearfix">

		<div id="content" class="iframe stories span12 <?php echo $layout; ?>" role="main">

			<div id="homepage-featured" class="row-fluid clearfix">
				<div class="top-story span12">
					<h6 class="today-ie">Latest from Inside Energy</h6>
					<?php
						global $ids;
						$topstory = largo_get_featured_posts( array(
							'tax_query' => array(
								array(
									'taxonomy' 	=> 'prominence',
									'field' 	=> 'slug',
									'terms' 	=> 'top-story'
								)
							),
							'showposts' => 1
						) );
						if ( $topstory->have_posts() ) :
							while ( $topstory->have_posts() ) : $topstory->the_post(); $ids[] = get_the_ID();
						?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<h5 class="byline"><?php largo_byline(); ?></h5>
								<?php largo_excerpt( $post, 4, false ); ?>
						<?php
							endwhile;
						endif; // end top story ?>
				</div>
			</div>

			<div id="iframe-bottom">
				<div id="iframe-recent-custom" class="widget">
				<?php
				// bottom section, a single column list of recent posts from an arbitrary category
				the_post();

				$args = array(
					'paged'					=> $paged,
					'post_status'			=> 'publish',
					'posts_per_page'		=> 5,
					'post__not_in' 			=> $ids
				);

				if ( $tag = get_post_meta( get_the_id(), 'iframe_tax', true ) ) {
					$args['tag_id'] = $tag;
				}
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					if ( $title = get_post_meta( get_the_id(), 'iframe_cat_name', true ) ) {
						echo '<h3 class="widgettitle">' . $title . '</h3>';
					} else {
						echo '<h3 class="widgettitle">Featured Today</h3>';
					}
					while ( $query->have_posts() ) : $query->the_post();
						//if the post is in the array of post IDs already on this page, skip it
						if ( in_array( get_the_ID(), $ids ) ) {
							continue;
						} else {
							$ids[] = get_the_ID();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
								<header>
									<h2 class="entry-title">
										<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h2>
								</header><!-- / entry header -->

								<div class="entry-content">
									<?php largo_excerpt( $post, 5, true, __('', 'largo'), true, false ); ?>
								</div><!-- .entry-content -->

							</article><!-- #post-<?php the_ID(); ?> -->
							<?php
						}
					endwhile;
					largo_content_nav( 'nav-below' );
				} else {
					get_template_part( 'content', 'not-found' );
				}
				?>
				</div>

				<div id="iframe-ie-now" class="widget">
				<?php
				// bottom section, the three "Inside Energy Now" posts
				$args = array(
					'paged'					=> $paged,
					'post_status'			=> 'publish',
					'posts_per_page'		=> 3,
					'post__not_in' 			=> $ids,
					'ignore_sticky_posts' 	=> true,
					'category_name'         => 'inside-energy-news'
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					echo '<h3 class="widgettitle">Inside Energy News</h3>';
					while ( $query->have_posts() ) : $query->the_post();
						//if the post is in the array of post IDs already on this page, skip it
						if ( in_array( get_the_ID(), $ids ) ) {
							continue;
						} else {
							$ids[] = get_the_ID();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
								<header>
									<h2 class="entry-title">
										<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h2>
								</header><!-- / entry header -->

								<div class="entry-content">
									<?php largo_excerpt( $post, 5, true, __('', 'largo'), true, false ); ?>
								</div><!-- .entry-content -->

							</article><!-- #post-<?php the_ID(); ?> -->
							<?php
						}
					endwhile;
					largo_content_nav( 'nav-below' );
				} else {

				}
				?>
				</div>

				<div id="iframe-ie-investigations" class="widget">
				<?php
				// bottom section, the single "In Case You Missed It" post
				$args = array(
					'paged'					=> $paged,
					'post_status'			=> 'publish',
					'posts_per_page'		=> 1,
					'post__not_in' 			=> $ids,
					'ignore_sticky_posts' 	=> true,
					'category_name'         => 'ie-investigations'
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					echo '<h3 class="widgettitle">In Case You Missed It</h3>';
					while ( $query->have_posts() ) : $query->the_post();
						//if the post is in the array of post IDs already on this page, skip it
						if ( in_array( get_the_ID(), $ids ) ) {
							continue;
						} else {
							$ids[] = get_the_ID();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
								<header>
									<h2 class="entry-title">
										<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h2>
								</header><!-- / entry header -->

								<div class="entry-content">
									<?php largo_excerpt( $post, 5, true, __('', 'largo'), true, false ); ?>
								</div><!-- .entry-content -->

							</article><!-- #post-<?php the_ID(); ?> -->
							<?php
						}
					endwhile;
					largo_content_nav( 'nav-below' );
				} else {

				}
				?>
				</div>

				<p class="more-link"><a href="http://insideenergy.org">More on InsideEnergy.org &gt;</a></p>
			</div><!-- #homepage-bottom -->
		</div><!-- #content-->

	</div> <!-- #main -->

</div><!-- #page -->

<div class="footer-bg clearfix">
	<div class="super-footer">
		<h5> Inside Energy is a collaborative journalism initiative of partners across the US and supported by the Corporation for Public Broadcasting</h5>
	</div>
	<footer id="site-footer">
		<div id="boilerplate" class="row-fluid clearfix">
			<ul id="menu-footer-bottom" class="menu">
				<li class="menu-item"><a href="/about/">About</a></li>
				<li class="menu-item"><a href="/contact/">Meet the Team</a></li>
				<li class="menu-item"><a href="/partners/">Partners</a></li>
				<li class="menu-item"><a href="/contact/">Contact</a></li>
			</ul>
			<ul class="social-icons">
				<?php largo_social_links(); ?>
			</ul>
		</div><!-- /#boilerplate -->
	</footer>
</div>

<?php wp_footer(); ?>
<script src="//assets.wearehearken.com/production/thirdparty/p.m.js"></script>
<script type="text/javascript">
(function() {
var $ = jQuery,
    pymChild = new pym.Child({polling:500});
$(document).ready(function(){
	$(window).load(function(){
		pymChild.sendHeight();
	});
});
})();
</script>
</body>
</html>
