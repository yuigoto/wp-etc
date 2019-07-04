<?php
/**
 * Popular Posts
 * ----------------------------------------------------------------------
 * Funções, hooks e shortcodes para exibição de postagens "populares", devem
 * ser carregadas pelo `functions.php`.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Verifica/define/incrementa o hit count de um post.
 *
 * @param int $post_id
 *      ID do post
 */
function yx_popular_posts( $post_id )
{
    // Puxa meta dado 'popular_posts'
    $count_key = 'popular_posts';
    $count     = get_post_meta( $post_id, $count_key, true );

    // Verifica contador
    if ( $count === '' ) {
        // Vazio
        $count = 0;
        delete_post_meta( $post_id, $count_key );
        add_post_meta( $post_id, $count_key, $count );
    } else {
        // Incrementa
        $count ++;
        update_post_meta( $post_id, $count_key, $count );
    }
}

/**
 * Action Hook para `wp_head`. Verifica se o usuário está visualizando um
 * único post/página e executa a checagem de hit points para popularidade.
 *
 * @param int $post_id
 *      ID do post
 */
function yx_track_posts( $post_id )
{
    // Se não for single, não executa
    if ( ! is_single() ) return;

    // Se ID for vazio, puxa a global
    if ( empty( $post_id ) ) {
        global $post;
        $post_id = $post->ID;
    }

    // Executa
    yx_popular_posts( $post_id );
}
// Registra Action Hook
add_action( 'wp_head', 'yx_track_posts' );

/**
 * Define valores de query string para exibir posts populares.
 *
 * Utilizado para modificadores de query via URI.
 *
 * @param WP_Query $query
 *      Instância de WP_Query
 * @since 1.0.0
 */
function yx_display_popular_posts( $query )
{
    // Verifica variáveis GET
    $_get = ( isset( $_GET['popular-posts'] ) && ! empty( $_GET['popular-posts'] ) )
        ? true : false;

    // Se a query estiver definida
    if ( $_get && is_main_query() && ! is_admin() ) {
        // Define query global para posts populares em ordem decrescente
        $query->set( 'meta_key', 'popular_posts' );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'DESC' );

        // Registra Action Hook para antes do loop
        add_action( 'loop_start', 'yx_popular_before_loop' );
    }
}
// Registra Action Hook
add_action( 'pre_get_posts', 'yx_display_popular_posts' );

/**
 * Executa antes do loop do WP, quando busca por posts populares.
 *
 * Apenas exibe o título de "Posts Populares".
 *
 * @since 1.0.0
 */
function yx_popular_before_loop()
{
    echo '<h4>Posts Populares</h4>';
}

/**
 * Adiciona um shortcode para exibir uma lista de posts populares, com algumas
 * opções e argumentos:
 * - `num`: integer, número de posts para serem exibidos;
 * - `cat`: string, lista de categorias para filtrar, separadas por vírgula;
 * - `order`: string, ordem para exibição (DESC ou ASC);
 * - `title`: string, título da lista (não exibido se vazio);
 * - `title_class`: string, classe a ser adicionada ao título, apenas se declarado;
 * - `class`: string, classe do objeto wrapper para o conteúdo do shortcode;
 * - `list_class`: string, classe da lista;
 *
 * @param array $attr
 *      Argumentos para o shortcode
 * @since 1.0.0
 */
function yx_popular_posts_shortcode( $attr )
{
    // Define valores e extrai
    extract(
        shortcode_atts(
            [
                'num'         => 10,
                'cat'         => '',
                'order'       => 'DESC',
                'title'       => '',
                'title_class' => '',
                'class'       => '',
                'list_class'  => ''
            ],
            $attr
        )
    );

    // Puxa lista temporária
    $temps = explode( ',', $cat );
    $array = array();

    // Trim
    foreach ( $temps as $temp ) {
        $array[] = trim( $temp );
    }

    // Define valor final de categorias
    $cats = ! empty( $cat ) ? $array : '';

    // Monta o corpo
    $class  = ( trim( $class ) !== '' )
        ? ' class="' . $class . '"' : '';
    $list   = array();
    $list[] = '<div' . $class . '>';

    // Define título
    if ( trim( $title ) !== '' ) {
        $title_class = ( trim( $title_class ) !== '' )
            ? ' class="' . $title_class . '"' : '';
        $list[]      = '<h3' . $title_class . '>' . $title . '</h3>';
    }
    $list_class = ( trim( $list_class ) !== '' )
        ? ' class="' . $list_class . '"' : '';
    $list[]     = '<ul' . $list_class . '>';

    // Preenche com posts
    $popular = new WP_Query(
        array(
            'posts_per_page' => $num,
            'meta_key'       => 'popular_posts',
            'orderby'        => 'meta_value_num',
            'order'          => $order,
            'category__in'   => $cats
        )
    );

    // WP Loop
    while ( $popular->have_posts() ) {
        $popular->the_post();
        $list[] = '<li>';
        $list[] = '<a href="' . get_the_permalink() . '">'
                  . get_the_title() . '</a>';
        $list[] = '</li>';
    }

    // Reseta query
    wp_reset_postdata();

    // Fecha lista
    $list[] = '</ul>';
    $list[] = '</div>';

    // Imprime
    echo implode( "", $list );
}
// Registra Shortcode
add_shortcode( 'yx_popular_posts', 'yx_popular_posts_shortcode' );
