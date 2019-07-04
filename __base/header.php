<?php
/**
 * __BASE : Header
 * ----------------------------------------------------------------------
 * Cabeçalho principal do template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/**
 * Atributos usados neste template, em específico. É um costume meu, portanto
 * opcional.
 *
 * Se usá-lo em múltiplos template, lembre-se que é necessário renomear para
 * evitar problemas com sobreposição de variáveis.
 *
 * @var array
 */
$header_attr = array();
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <?php /* Meta tags */ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <?php /* Define profile XFN e URL de pingback */ ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    
    <?php /* WordPress head */ ?>
    <?php wp_head(); ?>
</head>
<?php /* Exibe classes especiais para o corpo */ ?>
<body <?php body_class(); ?>>

<?php /* `hfeed` e `site` indicam que o site possui updates */ ?>
<div id="wrap" class="site-wrap hfeed site">
    <!-- TOPBAR (OPCIONAL) -->
    <div id="topbar">
        <?php get_template_part( 'topbar' ); ?>
    </div>
    
    <!-- HEADER -->
    <header id="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <!-- LOGO -->
                <a href="#" class="navbar-brand">
                    <?php bloginfo( 'name' ); ?>
                </a>
                
                <!-- TOGGLE -->
                <button class="navbar-toggler" type="button"
                        data-toggle="collapse"
                        data-target="#navbarMain"
                        aria-controls="navbarMain"
                        aria-expanded="false"
                        aria-label="Exibir Navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- COLLAPSE -->
                <div id="navbarMain" class="collapse navbar-collapse">
                    <!-- MENU -->
                    <?php
                    // Usamos o Navwalker para menu Bootstrap simples ;)
                    wp_nav_menu(
                        array(
                            'container'         => '',
                            'depth'             => 2,
                            'theme_location'    => 'menu-main',
                            'menu_class'        => 'navbar-nav mr-auto',
                            'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                            'walker'            => new WP_Bootstrap_Navwalker()
                        )
                    );
                    ?>
                    
                    <!-- SEARCH -->
                    <form class="form-inline my-2 my-lg-0" action="/">
                        <input class="form-control mr-sm-2" type="search"
                               placeholder="Search" aria-label="Search"
                               name="s">
                        <button class="btn btn-outline-light my-2 my-sm-0"
                                type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        
        <?php if ( is_user_logged_in() ): ?>
            <ul>
                <li>
                    <a href="<?php echo wp_logout_url( get_permalink() ); ?>">
                        Sair
                    </a>
                </li>
            </ul>
        <?php else: ?>
            <?php yx_custom_login(); ?>
        <?php endif; ?>
    </header>
    
    <!-- MAIN -->
    <main id="content" class="content-wrap" role="main">
        <!-- SLIDESHOW/SLIDER (HOME APENAS) -->
        <?php if ( is_home() || is_front_page() ): ?>
            <div id="slides" class="slides-home">
                <?php if ( ! dynamic_sidebar( 'slider-main' ) ): ?>
                    <!-- COLOQUE O FALLBACK PARA SLIDES AQUI -->
                <?php endif; ?>
            </div>
        <?php endif; ?>
