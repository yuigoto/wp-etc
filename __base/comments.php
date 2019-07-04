<?php
if ( post_password_required() ) return;
?>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ): ?>
        <h3 class="comments-title">
            <?php yx_comment_title(); ?>
        </h3>
    
        <?php
            wp_list_comments(
                array(
                    'callback' => 'yx_comment_template',
                    'style' => 'div',
                    'depth' => 3
                )
            );
        ?>
        <?php paginate_comments_links( $args ) ?>
    <?php else: ?>
        <?php if ( !comments_open() ): ?>
            <?php _e( 'Comentários desativados para este post.', THEME_DOMAIN ); ?>
        <?php else: ?>
            <?php _e( 'Nenhum comentário.', THEME_DOMAIN ); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
