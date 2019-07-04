<?php if ( ! defined( 'ABSPATH' ) ) die ( 'Acesso direto ao arquivo negado.' );

/**
 * Menus
 * ----------------------------------------------------------------------
 * Utilize este arquivo para registrar os menus do tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/**
 * Lista com as posições de menus do website.
 *
 * @var array
 */
$yx_nav_menus = array(
    'menu-main'           => esc_html__( 'Menu Principal', THEME_DOMAIN ),
    'menu-main-in'        => esc_html__( 'Menu Principal (Logado)', THEME_DOMAIN ),
    'menu-main-sticky'    => esc_html__( 'Menu Principal (Sticky)', THEME_DOMAIN ),
    'menu-main-sticky-in' => esc_html__( 'Menu Principal (Sticky Logado)', THEME_DOMAIN ),
    'menu-news'           => esc_html__( 'Menu Notícias', THEME_DOMAIN )
);

/**
 * Registrando os nav menus.
 */
register_nav_menus( $yx_nav_menus );
