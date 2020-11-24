<?php
if ( get_theme_mod( 'comment_link' ) == 'no' ) return;

$classes = 'post-comments-link';
$icon = 'far fa-comment';
if ( !comments_open() ) {
	$classes .= ' closed';
	$icon = 'fas fa-comment';
}
?>
<span class="<?php echo esc_attr( $classes ); ?>">
	<?php
	echo '<a href="' . esc_url( get_comments_link() ) . '">';
		echo '<i class="' . esc_attr( $icon ) . '" title="' . esc_attr__( "comment icon", "startup-blog" ) . '"></i>';
		echo '<span>';
			comments_number( 0, 1, '%' );
		echo '</span>';
	echo '</a>';
	?>
</span>