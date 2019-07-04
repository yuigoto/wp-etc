<?php if ( ! defined( 'ABSPATH' ) ) die ( 'Acesso direto ao arquivo negado.' );

/**
 * Static
 * ----------------------------------------------------------------------
 * Utilize este arquivo para solicitar os scripts e folhas de estilo usadas
 * no tema,
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/* JAVASCRIPT
 * ------------------------------------------------------------------- */

// Libs
wp_deregister_script( 'bundle' );
wp_enqueue_script(
    'bundle',
    get_template_directory_uri() . '/assets/js/bundle.min.js',
    false,
    null,
    true
);

// JS
wp_deregister_script( 'build' );
wp_enqueue_script(
    'build',
    get_template_directory_uri() . '/assets/js/build.min.js',
    false,
    null,
    true
);

// Comment Reply (script interno do WP)
if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    // Carrega apenas se necessário
    wp_enqueue_script( 'comment-reply' );
}

// Keyboard image navigation
if ( is_singular() && wp_attachment_is_image() ) {
    wp_deregister_script( 'image-navigation' );
    wp_enqueue_script(
        'image-navigation',
        get_template_directory_uri() . '/assets/js/keyboard-image-navigation.js',
        array(
            'jquery'
        ),
        '20180930'
    );
}

/* STYLESHEETS
 * ------------------------------------------------------------------- */

// Stylesheet principal (WordPress)
wp_deregister_style( 'style' );
wp_enqueue_style( 'style', get_stylesheet_uri() );

// Stylesheet
wp_deregister_style( 'build' );
wp_enqueue_style(
    'build',
    get_template_directory_uri() . '/assets/css/build.min.css',
    false,
    null,
    'all'
);
