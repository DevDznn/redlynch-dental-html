<?php
/**
 * Comments (closed on the imported content; shown only if comments are turned on for a post).
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area prose">
	<?php if ( have_comments() ) : ?>
		<h2><?php esc_html_e( 'Comments', 'redlynch-dental' ); ?></h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</div>
