<?php
get_header(); ?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="container">
            <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( null, array(
                    'class' => 'img-fluid'
                ) );
                }
             ?>
            <h3><?php the_title(); ?></h3>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.', '__temp' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>