<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Bootstrap core CSS -->
        <!-- Custom styles for this template -->
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php wp_head(); ?>
    </head>
    <body class="<?php echo implode(' ', get_body_class()); ?>">
        <!-- Bootstrap core JavaScript
    ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light"> 
                <a class="navbar-brand" href="#"><?php _e( 'Brand', '__temp' ); ?></a> 
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler48" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span> 
                </button>                 

                <div class="collapse navbar-collapse" id="navbarToggler48"> 
                    <?php wp_nav_menu( array(
                            'menu' => 'menu-main',
                            'menu_class' => 'navbar-nav mr-auto mt-2 mt-lg-0',
                            'container' => '',
                            'fallback_cb' => 'wp_bootstrap4_navwalker::fallback',
                            'walker' => new wp_bootstrap4_navwalker()
                    ) ); ?> 
                    <form class="form-inline my-2 my-lg-0"> 
                        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search"> 
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                            <?php _e( 'Search', '__temp' ); ?>
                        </button>                         
                    </form>                     
                </div>                 
            </nav>
            <?php if ( is_home() || is_front_page() ) : ?>
                <?php if ( is_active_sidebar( 'slider-home' ) ) : ?>
                    <div class="jumbotron">
                        <?php dynamic_sidebar( 'slider-home' ); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </header>
        <section>