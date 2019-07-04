<?php
/**
 * Front End Filters
 * ----------------------------------------------------------------------
 * Hooks de filtros para registro no arquivo `functions.php`.
 *
 * Modificam a forma como a área pública do website se comporta, modificando
 * a para uso com o tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Filtra os tipos de post que a query de busca do WordPress pode retornar.
 *
 * Bom caso queira retornar, por exemplo, apenas postagens de blog na
 * busca, sem retornar páginas e outros tipos de posts.
 *
 * @param WP_Query $query
 *      Handle para objeto WP_Query
 * @return WP_Query
 *      Query modificada
 */
function frontend_search_filter( $query )
{
    // Se for query de busca...
    if ( $query->is_search ) {
        // Define post_type para retorno
        $query->set(
            // Define que o filtro é para tipo de post
            'post_type',
            // Pode ser string ou array, para mais de um `post_type`
            'post'
        );
    }
    return $query;
}
// Registra Filter Hook
add_filter( 'pre_get_posts', 'frontend_search_filter' );



/**
 * Modifica menus de navegação para que links de taxonomia/categoria tenha
 * os seus slugs como nomes de classe.
 *
 * Útil para quando links de menu precisam ter cores diferentes definidas
 * pela categoria, por exemplo.
 *
 * @param array $classes
 *      Array contendo classes para links do menu de navegação
 * @param object $item
 *      Item do menu de navegação
 * @return array
 *      Array com as classes modificadas
 */
function navigate_menu_category( $classes, $item )
{
    // Se for categoria/taxonomia
    if ( 'category' == $item->object ) {
        // Pega a categoria e monta o array de classes
        $cats = get_category( $item->object_id );
        $classes = array( $cats->slug );
    }
    return $classes;
}
// Registra Filter Hook
add_filter( 'nav_menu_css_class', 'navigate_menu_category', 10, 2 );



/**
 * Por padrão, o WordPress não permite que seja utilizado templates com
 * a nomenclatura `single-[categoria].php`, este filtro torna isso possível.
 *
 * @param string $template
 *      Template padrão a ser utilizado, retornado caso não haja template
 *      específico
 * @return string
 *      Nome do template file
 */
function template_category_page( $template )
{
    // Previne uso fora do WordPress
    if ( !defined( 'TEMPLATEPATH' ) ) die( 'Wrong usage of script.' );

    // Verifica categorias e se há arquivo com post
    foreach ( ( array ) get_the_category() as $cats ) {
        // Define caminho para o arquivo
        $file = TEMPLATEPATH . '/single-' . $cats->slug . '.php';
        // Retorna se existir
        if ( file_exists( $file ) ) return $file;
    }
    // Retorno padrão
    return $template;
}
// Registra Filter Hook
add_filter( 'single_template', 'template_category_page' );
