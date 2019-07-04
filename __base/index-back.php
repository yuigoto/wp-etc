<?php
/**
 * __BASE : Index
 * ----------------------------------------------------------------------
 * Template principal.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>
<body>

<?php
    yx_breadcrumbs(
        array(
            'wrap_before' => '<div class="breadcrumb-wrap">',
            'wrap_after' => '</div>',
            'show_on_home' => true
        )
    );
?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post() ?>
        <h3>
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
    
        <?php the_author_posts_link(); ?>
    
        <?php
        echo get_post_format_link( 'aside' );
        comments_template('', true);
        ?>
    
        <?php if ( is_attachment() && wp_attachment_is_image() ): ?>
        <?php endif; ?>
    
    <?php endwhile; ?>
    
    <?php yx_content_nav( 'demo' ); ?>
    
    <?php yx_bootstrap4_pagination(); ?>
<?php else: ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
