<?php
/**
 * Functions
 * ----------------------------------------------------------------------
 * Exemplo de um arquivo `functions.php` com alguns itens básicos, para
 * auxiliar na criação de um, verificar o tema `__yx` para exemplo melhor.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// Carrega constantes do tema
//require( get_template_directory() . '/inc/constants.php' );

/**
 * Define a largura de conteúdo (ex.: imagens), com base no design do tema e
 * stylesheet, para que não estoure o layout.
 *
 * Largura em píxels.
 */
if ( !isset( $content_width ) ) $content_width = 654;

if ( !function_exists( 'yx_template_setup' ) ) {
    /**
     * Define valores padrão para o tema, e registra o suporte à diversos
     * recursos do WordPress.
     *
     * IMPORTANTE:
     * Esta função é ligada ao hook "after_setup_theme", que acontece ANTES do
     * hook de inicialização. Isso é feito assim, pois o hook inicial vem tarde
     * demais para recursos, como suporte à thumbnails em postagens.
     */
    function yx_template_setup()
    {
        /**
         * Template tags personalizadas para o tema.
         */
        //require( get_template_directory() . '/inc/template-tags.php' );

        /**
         * Funções personalizadas, que agem de forma independente às dos
         * templates de temas.
         */
        //require( get_template_directory() . '/inc/tweaks.php' );

        /**
         * Permite que o tema esteja disponível para traduções.
         *
         * Traduções podem ser adicionadas ao diretório '/languages/'.
         *
         * Se está a montar um tema com base neste projeto, utilize uma busca e
         * subsitua 'text-domain' para o nome de seu tema em todos os arquivos do
         * template.
         */
        //load_theme_textdomain( 'text-domain', get_template_directory() . '/languages' );

        /**
         * Adiciona um link para RSS com posts e comentários ao cabeçalho, por
         * padrão.
         */
        //add_theme_support( 'automatic-feed-links' );

        /**
         * Adiciona suporte ao post format 'Aside'.
         */
        add_theme_support( 'post-formats', array( 'aside' ) );

        /**
         * Adiciona suporte à imagem de destaque.
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * Adiciona suporte à tag title.
         */
        add_theme_support( 'title-tag' );

        /**
         * Adiciona suporte à custom logos para o template.
         *
         * Funciona apenas em WordPress 4.5 ou maior.
         */
        if ( function_exists( 'get_custom_logo' ) ) {
            add_theme_support( 'custom-logo' );
        }

        // Suporte a elementos HTML5
        add_theme_support(
            'htlm5',
            array(
                'comment-list',
                'comment-form',
                'search-form',
                'gallery',
                'caption'
            )
        );

        /**
         * Este tema utiliza o wp_nav_menu() em um local.
         */
        register_nav_menus(
            array(
                'primary'   => __( 'Menu Principal', 'text-domain' )
            )
        );
    }
}
// Registra Action Hook
add_action( 'after_setup_theme', 'yx_template_setup' );



/**
 * Solicita scripts e arquivos CSS do template.
 */
function yx_scripts()
{
    // Puxando stylesheet principal
    wp_enqueue_style( 'style', get_stylesheet_uri() );

    // Carrega "comment-reply.js" apenas em páginas com comentários
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // Carrega apenas quando necessário
        wp_enqueue_script( 'comment-reply' );
    }

    // Carregando navigation.js (opcional)
    wp_enqueue_script(
        'navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        '20120206',
        true
    );

    // Script de navegação usando setas, em páginas de imagens (opcional)
    if ( is_singular() && wp_attachment_is_image() ) {
        wp_enqueue_script(
            'keyboard-image-navigation',
            get_template_directory_uri() . '/js/keyboard-image-navigation.js',
            array(
                'jquery'
            ),
            '20120202'
        );
    }
}
// Registra Action Hook
add_action( 'wp_enqueue_scripts', 'yx_scripts' );

/**
 * Oculta a barra de admin no front-end, quando o usuário estiver logado.
 *
 * @return bool
 *      Status de exibição
 */
function yx_remove_front_adminbar()
{
    return false;
}
add_filter( 'show_admin_bar' , 'yx_remove_front_adminbar' );

/**
 * Remove o CSS da barra de admin do front-end.
 */
function mb_remove_adminbar_styles()
{
    remove_action( 'wp_head', '_admin_bar_bumb_cb' );
}
add_action( 'get_header', 'mb_remove_adminbar_styles' );

// Desabilita CSS padrão da galeria, quando não em modo HTML5
// add_filter( 'use_default_gallery_style', '__return_false' );
