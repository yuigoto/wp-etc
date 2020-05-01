<?php
get_header(); ?>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="container">
            <?php the_post_thumbnail( null, array(
                    'class' => 'img-fluid'
            ) ); ?>
            <h3><?php the_title(); ?></h3>
            <p class="text-muted text-uppercase"><?php the_date( 'd M Y' ); ?></p>
            <?php if ( has_excerpt() ) : ?>
                <p class="lead text-muted"><?php the_excerpt( ); ?></p>
            <?php endif; ?>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.', '__temp' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>