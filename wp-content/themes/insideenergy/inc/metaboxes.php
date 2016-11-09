<?php
/**
 * Adds custom meta for iframe pages
 * @since 0.1
 */
require_once( get_template_directory() . '/largo-apis.php' );
largo_add_meta_box(
	'iframe_tax_box',
	'Iframe Settings',
	array('iframe_tax_choice', 'iframe_category_name'),
	'page',
	'normal',
	'core'
);
function iframe_tax_choice() {
	global $post;
	$value = get_post_meta( $post->ID, 'iframe_tax', true );
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	echo '<p>The following settings are only relevant for the iframe custom page template used to embed Inside Energy content on partner sites.</p>';
	echo '<p><strong>Select a tag to filter the posts in the custom section of the iframe.</strong> The iframe will show 5 posts from the selected tag in a featured area unique to each partner site.</p>';
	wp_dropdown_categories( array(
		'show_option_all' => 'All categories',
		'show_option_none' => '',
		'orderby' => 'NAME',
		'order' => 'ASC',
		'show_count' => true,
		'hide_empty' => false,
		'exclude_tree' => '',
		'id' => 'iframe_tax',
		'name' => 'iframe_tax',
		'hierarchical' => true,
		'taxonomy' => 'post_tag',
		'selected' => $value,
	));

	largo_register_meta_input( 'iframe_tax' );
}
function iframe_category_name() {
	global $post;
	$value = get_post_meta( $post->ID, 'iframe_cat_name', true );
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	<p><label for="iframe_cat_name"><?php _e('Title to be used for the custom section:', 'largo'); ?></label></p>
	<input type="text" id="iframe_cat_name" name="iframe_cat_name" value="<?php echo $value; ?>" />
	<?php
	largo_register_meta_input( 'iframe_cat_name' );
}
