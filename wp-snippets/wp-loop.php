<?php
/**
 * Loop
 * ----------------------------------------------------------------------
 * Contém exemplos de uso do WordPress loop, além de exemplos de uso da
 * paginação do WordPress.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */
?>

<!-- WP LOOP : BÁSICO -->
<?php if ( have_posts() ): ?>
    <?php while ( have_posts() ): ?>
        <?php the_post(); ?>

        <!-- EXIBIR CONTEÚDO AQUI -->
        <h4><?php the_title(); ?><h4>

        <?php the_content(); ?>
    <?php endwhile; ?>

    <!-- PAGINAÇÃO -->
    <?php echo paginate_links(); ?>
<?php else: ?>
    <!-- ERRO 404 / SEM POSTS -->
    <?php get_404_template(); ?>
<?php endif; ?>



<!-- WP LOOP : BÁSICO (COMPACTO) -->
<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
    <!-- EXIBIR CONTEÚDO AQUI -->
    <h4><?php the_title(); ?><h4>

    <?php the_content(); ?>
<?php endwhile; ?>
    <!-- PAGINAÇÃO -->
    <?php echo paginate_links(); ?>
<?php else: ?>
    <!-- ERRO 404 / SEM POSTS -->
    <?php get_404_template(); ?>
<?php endif; ?>



<!-- WP LOOP : AVANÇADO : WP_QUERY -->
<?php
// Argumentos para query
$args = array(
    'numberposts'       => -1,
    'post_type'         => 'post',
    'orderby'           => 'date',
    'order'             => 'DESC',
    #'posts_per_page'    => 5,
    #'offset'            => 0,
    #'category'          => '',
    #'category_name'     => '',
    #'include'           => '',
    #'exclude'           => '',
    #'meta_key'          => '',
    #'meta_value'        => '',
    #'post_mime_type'    => '',
    #'post_parent'       => '',
    #'author'            => '',
    #'post_status'       => 'publish',
    #'suppress_filters'  => true
);

// Executa Query
$post_list = new WP_Query( $args );
?>
<!-- EXECUTA O LOOP COM A QUERY -->
<?php if ( $post_list->have_posts() ): ?>
    <?php while ( $post_list->have_posts() ): $post_list->the_post(); ?>
        <!-- EXIBIR CONTEÚDO AQUI -->
        <h4><?php the_title(); ?><h4>

        <?php the_content(); ?>
    <?php endwhile; ?>

    <!-- PAGINAÇÃO -->
    <?php
    // Precisamos definir um número improvavel e grande para URL
    $big = 999999999;

    // Argumentos de paginação
    $paginate_args = array(
        'base'      => str_replace(
            $big,
            '%#%',
            esc_url( get_pagenum_link( $big ) )
        ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $post_list->max_num_pages
    );

    // Exibe a paginação
    echo paginate_links( $paginate_args );

    // Resetando Query (IMPORTANTE)
    wp_reset_query();
    ?>
<?php else: ?>
    <!-- ERRO 404 / SEM POSTS -->
    <?php get_404_template(); ?>
<?php endif; ?>



<!-- WP LOOP : AVANÇADO : GET_POSTS -->
<?php
// Argumentos para query (identico `wp_query`)
$args = array(
    'numberposts'       => -1,
    'post_type'         => 'post',
    'orderby'           => 'date',
    'order'             => 'DESC',
    #'posts_per_page'    => 5,
    #'offset'            => 0,
    #'category'          => '',
    #'category_name'     => '',
    #'include'           => '',
    #'exclude'           => '',
    #'meta_key'          => '',
    #'meta_value'        => '',
    #'post_mime_type'    => '',
    #'post_parent'       => '',
    #'author'            => '',
    #'post_status'       => 'publish',
    #'suppress_filters'  => true
);

// Query
$post_list = get_posts( $args );
?>
<!-- LOOP PELOS POSTS SOLICITADOS -->
<?php foreach ( $post_list as $post_item ): ?>
    <!-- EXIBIR POST AQUI -->
<?php endforeach; ?>



<!-- PAGINAÇÃO : LOOP PADRÃO (C/ ARGUMENTOS) -->
<?php
// Argumentos (modifique text-domain de acordo com o tema)
$page_args = array(
    // URL base
    'base' => '%_%',
    // Formato da URL (para SEO: '/paged/%#%')
    'format' => '?paged=%#%',
    // Total de Páginas
    'total' => 100,
    // Página atual
    'current' => 0,
    // Exibir todos os links de paginação?
    'show_all' => false,
    // Número de itens para exibir no começo/final da lista
    'end_size' => 1,
    // Número de itens para exibir em cada lado da página atual
    'mid_size' => 2,
    // Texto do Link Anterior
    'prev_text' => __( '&laquo; Anterior', 'text-domain' ),
    // Texto do Link Próximo
    'next_text' => __( 'Pr&oacute;ximo &raquo;', 'text-domain' ),
    // Tipo de retorno (plain, list)
    'type' => 'plain',
    // Array com argumentos de query
    'add_args' => false,
    // String para adicionar à cada link
    'add_fragment' => '',
    // Exibe antes do número da página
    'before_page_number'=> '',
    // Exibe após o número da página
    'after_page_number' => ''
);
echo paginate_links( $paginate_args );
?>



<!-- PAGINAÇÃO : QUANDO USAR WP_QUERY (C/ ARGUMENTOS) -->
<?php
// Defina um número improvável e grande
$big = 999999999;

// Argumentos (modifique text-domain de acordo com o tema)
$page_args = array(
    // URL base
    'base' => str_replace(
        $big,
        '%#%',
        esc_url( get_pagenum_link( $big ) )
    ),
    // Formato da URL (para SEO: '/paged/%#%')
    'format' => '?paged=%#%',
    // Total de Páginas
    'total' => 100,
    // Página atual
    'current' => 0,
    // Exibir todos os links de paginação?
    'show_all' => false,
    // Número de itens para exibir no começo/final da lista
    'end_size' => 1,
    // Número de itens para exibir em cada lado da página atual
    'mid_size' => 2,
    // Texto do Link Anterior
    'prev_text' => __( '&laquo; Anterior', 'text-domain' ),
    // Texto do Link Próximo
    'next_text' => __( 'Pr&oacute;ximo &raquo;', 'text-domain' ),
    // Tipo de retorno (plain, list)
    'type' => 'plain',
    // Array com argumentos de query
    'add_args' => false,
    // String para adicionar à cada link
    'add_fragment' => '',
    // Exibe antes do número da página
    'before_page_number'=> '',
    // Exibe após o número da página
    'after_page_number' => ''
);
echo paginate_links( $paginate_args );
?>



<!-- PAGINAÇÃO : NEXT/PREVIOUS -->
<?php
if ( ! function_exists( 'yx_content_nav' ) ) {
    /**
     * Exibe a navegação para páginas anteriores/próximas, quando aplicável.
     *
     * @param string $nav_id
     *      ID da navegação, para identificá-la no template
     */
    function yx_content_nav( $nav_id ) {
        // Globais de query e post
        global $wp_query, $post;

        // Não exibe se não houver posts adjacentes
        if ( is_single() ) {
            // Verifica post anterior/próximo
            $prev = ( is_attachment() )
                ? get_post( $post->post_parent )
                : get_adjacent_post( false, '', true );
            $next = get_adjacent_post( false, '', false );
            // Se ambos forem falsos, para
            if ( !$next && !$prev ) return;
        }

        // Não exibe na home/arquivo/busca se houver apenas 1 página
        if (
            $wp_query->max_num_pages < 2
            && ( is_home() || is_archive() || is_search() )
        ) {
            return;
        }

        // Define classe
        $nav_class = 'site-navigation';

        // Se single, modifica
        $nav_class.= ( is_single() ) ? ' post-navigation' : ' paging-navigation';

        // Array para navegação
        $nav_list = array();

        // ABRE LISTA
        // --------------------------------------------------------------
        $nav_list[] = '<nav role="navigation" id="" class="">';

        // Texto para screen readers
        $nav_list[] = '<h1 class="assistive-text">'
            . __( 'Navegação', 'text-domain' ) . '</h1>';

        // MONTANDO LINKS
        // --------------------------------------------------------------

        // Verifica se Single ou Não
        if ( is_single() ) {
            // Post anterior
            $nav_list[] = get_previous_post_link(
                '<div class="nav-previous">%link</div>',
                '<span class="meta-nav">'
                    . _x( '&larr;', 'Post Anterior', 'text-domain' )
                    . '</span> %title'
            );

            // Próximo post
            $nav_list[] = get_next_post_link(
                '<div class="nav-next">%link</div>',
                '%title <span class="meta-nav">'
                    . _x( '&rarr;', 'Próximo Post', 'text-domain' )
                    . '</span>'
            );
        } elseif (
            $wp_query->max_num_pages > 1
            && ( is_home() || is_archive() || is_search() )
        ) {
            // Link Anterior
            if ( get_next_posts_link() ) {
                $list[] = '<div class="nav-previous">';
                $list[] = get_next_posts_link(
                    __(
                        '<span class="meta-nav">&larr;</span> Postagens antigas',
                        'text-domain'
                    )
                );
                $list[] = '</div>';
            }

            // Link Próximo
            if ( get_previous_posts_link() ) {
                $list[] = '<div class="nav-next">';
                $list[] = get_previous_posts_link(
                    __(
                        'Postagens recentes <span class="meta-nav">&rarr;</span>',
                        'text-domain'
                    )
                );
                $list[] = '</div>';
            }
        }

        // FECHA LISTA
        // --------------------------------------------------------------
        $nav_list[] = '</nav><!-- #' . $nav_id . ' -->';

        // Exibe
        echo implode( "", $nav_list );
    }
}
?>



<!-- MODIFICADORS DE NAVEGAÇÃO -->
<?php
/**
 * Adiciona classes ao link de `Posts Anteriores`.
 *
 * @return string
 *      String modificada
 */
function yx_previous_posts_link()
{
    return 'class="btn yx-button-outline-ltgray yx-paging__button left"';
}
add_filter( 'previous_posts_link_attributes', 'yx_previous_posts_link' );

/**
 * Adiciona classes ao link de `Próximos Posts`.
 *
 * @return string
 *      String modificada
 */
function yx_next_posts_link()
{
    return 'class="btn yx-button-outline-ltgray yx-paging__button right"';
}
add_filter( 'next_posts_link_attributes', 'yx_next_posts_link' );

/**
 * Adiciona classes ao link de `Post Anterior` (singular).
 *
 * @param string $output
 *      String a ser modificada
 * @return string
 *      String modificada
 */
function yx_previous_post_link( $output )
{
    $code = 'class="yx-paging-post__item right"';
    return str_replace( '<a href=', '<a ' . $code . ' href=', $output );
}
add_filter( 'previous_post_link', 'yx_previous_post_link' );

/**
 * Adiciona classes ao link de `Próximo Post` (singular).
 *
 * @param string $output
 *      String a ser modificada
 * @return string
 *      String modificada
 */
function yx_next_post_link( $output )
{
    $code = 'class="yx-paging-post__item left"';
    return str_replace( '<a href=', '<a ' . $code . ' href=', $output );
}
add_filter( 'next_post_link', 'yx_next_post_link' );
