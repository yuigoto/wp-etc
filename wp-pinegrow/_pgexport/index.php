<?php
get_header(); ?>

<div class="container">
    <div class="row">
        <?php if ( have_posts() ) : ?>
            <?php $item_number = 0; ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card"> 
                        <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'thumbnail', array(
                                'class' => 'card-img-top'
                            ) );
                            }
                         ?> 
                        <div class="card-body"> 
                            <h4 class="card-title"><?php the_title(); ?></h4> 
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo get_the_time( 'd M Y' ) ?></h6> 
                            <?php if ( has_excerpt() ) : ?>
                                <?php the_excerpt( ); ?>
                            <?php endif; ?> 
                            <a href="<?php echo esc_url( the_permalink() ); ?>" class="btn btn-primary"><?php _e( 'Leia Mais', '__temp' ); ?></a> 
                        </div>                                     
                    </div>                                                                  
                </div>
                <?php $item_number++; ?>
                <?php if( $item_number % 3 == 0 ) echo '<div class="clearfix visible-md-block visible-lg-block"></div>'; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.', '__temp' ); ?></p>
        <?php endif; ?>
    </div>
</div>            

<?php get_footer(); ?>