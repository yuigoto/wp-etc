<?php
/**
 * Menu Snippets
 * ----------------------------------------------------------------------
 * Como registrar menus no admin e exibí-los no template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// PARA USO NO FUNCTIONS.PHP
// ----------------------------------------------------------------------

// Ativa suporte aos menus
add_theme_support( 'menu' );

// Registra um único menu
if ( function_exists( 'register_nav_menu' ) ) {
    register_nav_menu(
        // Slug/ID único do menu
        'menu-main',
        // Descrição
        'Menu Principal'
    );
}

// Registra múltiplos menus
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
            // [Id/Slug] => Descrição do Menu
            'menu-side'     => 'Menu Lateral',
            'menu-info'     => 'Menu de Informações'
        )
    );
}

// Filtro para classes de item de menu
if ( ! function_exists( 'yx_menu_item_class' ) ) {
    /**
     * Adiciona classes para cada item de menu num nav do WordPress.
     *
     * @param array $classes
     *      Classes para inserir no menu
     * @param WP_Post $item
     *      Objeto WP_Post representadno o item do menu
     * @param array $args
     *      Argumentos do Nav Menu
     * @param int $depth
     *      Nível de profundidade do item
     * @return array
     *      Array com classes
     */
    function yx_menu_item_class( $classes, $item, $args, $depth )
    {
        $classes[] = 'nav-item';
        return $classes;
    }
}
add_filter( 'nav_menu_css_class', 'yx_menu_item_class', 10, 4 );

// Filtro para classes de link de menu
if ( ! function_exists( 'yx_menu_link_class' ) ) {
    /**
     * Adiciona classes para cada link num nav do WordPress.
     *
     * @param array $atts
     *      Atributos passados para cada item em um menu
     * @return array
     *      Array modificado
     */
    function yx_menu_link_class( $atts )
    {
        $atts['class'] = "nav-link";
        return $atts;
    }
}
add_filter( 'nav_menu_link_attributes', 'yx_menu_link_class' );



// PARA USO NO TEMPLATE/FRONT
// ----------------------------------------------------------------------

// Lista todos os menus registrados
$menu_list = get_registered_nav_menus();



// EXIBINDO MENU NO TEMPLATE
// ----------------------------------------------------------------------

// Argumentos
$menu_args = array(
    // Localização deste menu (qual menu registrado ele representa)
    'theme_location'    => 'menu-main',
    // Como serão ordenados os links?
    'sort_column'       => 'menu_order',
    // Utiliza container?
    'container'         => false,
    // Classe do container (`container` true apenas)
    'container_class'   => '',
    // ID do container (`container` true apenas)
    'container_id'      => '',
    // Profundidade para submenus (níveis de submenu)
    'depth'             => 2,
    // Classe do menu
    'menu_class'        => 'menu-body',
    // ID do menu
    'menu_id'           => 'menu-main',
    // HTML para ser exibido antes do menu
    'before'            => '',
    // HTML para ser exibido após do menu
    'after'             => '',
    // HTML para ser exibido antes dos links
    'link_before'       => '',
    // HTML para ser exibido após os links
    'link_after'        => '',
    // Wrapper dos links de menu (nível 1)
    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>'
);

// Exibindo Menu com argumentos
wp_nav_menu( $menu_args );
