<?php
/**
 * Home Template: Top Stories
 * Description: A newspaper-like layout highlighting one Top Story on the left
 * Sidebars: Homepage Left Rail (An optional widget area that, when enabled, appears to the left of the main content area on the homepage)
 *
 * Copied from largo's top-stories.php, with the optional related stories removed
 */

global $largo, $shown_ids, $tags;
$topstory_classes = 'top-story span12';
?>
<div id="homepage-featured" class="row-fluid clearfix">

	<div <?php post_class( $topstory_classes ); ?>>

	<?php
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
			while ( $topstory->have_posts() ) : $topstory->the_post(); $shown_ids[] = get_the_ID();
		?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<h5 class="byline"><?php largo_byline(); ?></h5>
				<?php largo_excerpt( $post, 4, false ); ?>
			<?php endwhile;
		endif; // end top story ?>
	</div>
</div>
