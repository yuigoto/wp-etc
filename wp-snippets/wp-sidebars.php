<?php
/**
 * Sidebars
 * ----------------------------------------------------------------------
 * Contém exemplos de como registrar e colocar sidebars em seu template
 * para o WordPress.
 *
 * Normalmente, registra-se a sidebar no arquivo `functions.php` do template
 * ou outro arquivo de bootstrap do mesmo.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */
?>

<!-- REGISTRANDO UMA ÚNICA SIDEBAR -->
<?php
if ( function_exists( 'register_sidebar' ) ) {
    // Argumentos da Sidebar
    $args = array(
        'id'            => 'home-slides',
        'name'          => 'Home : Slides',
        'description'   => 'Slides da Home',
        'class'         => 'home-slides',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>'
    );

    // Registrando
    register_sidebar( $args );
}
?>

<!-- REGISTRANDO MÚLTIPLAS SIDEBARS (NUMERADAS) -->
<?php
if ( function_exists( 'register_sidebars' ) ) {
    // Argumentos da Sidebar
    $args = array(
        'id'            => 'site-widget',
        // `%d` será substituído por um número no admin do WP
        'name'          => 'Site : Widget %d',
        'description'   => 'Widget de uso genérico para o template',
        'class'         => 'site-widget',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>'
    );

    // Registrando quatro sidebars do tipo `site-widget`
    register_sidebars( 4, $args );
}
?>

<!-- RESERVANDO LOCAL PARA UMA SIDEBAR NO TEMPLATE (EXEMPLO 1ª SIDEBAR) -->
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Home : Slides' ) ): ?>
    <!-- CONTEÚDO ALTERNATIVO AQUI, PARA QUANDO A SIDEBAR ESTIVER VAZIA -->
<?php endif; ?>

<!-- CASO TENHA REGISTRADO MÚLTIPLAS, VOCÊ DEVE COLOCÁ-LAS ASSIM -->
<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Site : Widget 1' ) ): ?>
    <!-- CONTEÚDO ALTERNATIVO AQUI, PARA QUANDO A SIDEBAR ESTIVER VAZIA -->
<?php endif; ?>
