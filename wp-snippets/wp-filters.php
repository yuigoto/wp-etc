<?php
/**
 * Filters
 * ----------------------------------------------------------------------
 * Filtros diversos para uso no back-end (`functions.php`) ou no template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Filtra o conteúdo da função `wp_title`, para adicionar descrição,
 * nome das páginas/posts ou número da página.
 *
 * @param string $title
 *      Título do blog/site
 * @return string
 *      String modificada
 */
function yx_filter_title( $title )
{
    // Globais de página/paginação
    global $page, $paged;

    // Título do site/blog
    $title = $title . bloginfo( 'name' );

    // Se front/home, exibe descrição do site
    $blog_desc = get_bloginfo( 'description', 'display' );
    if ( $blog_desc && ( is_home() || is_front_page() ) ) {
        $title.= " | {$blog_desc}";
    }

    // Adiciona número da página
    if ( $paged >= 2 || $page >= 2 ) {
        $title.= " | " . sprintf(
            // Substitua o text domain de acordo com seu tema
            __( 'Página %s', 'text-domain' ),
            max( $paged, $page )
        );
    }
    return $title;
}
// Registra Filter Hook
add_filter( 'wp_title', 'yx_filter_title' );



if ( ! function_exists( 'yx_posted_on' ) ) {
    /**
     * Exibe HTML com informações da data de postagem e autor da postagem,
     * com permalinks.
     */
    function yx_posted_on()
    {
        /**
         * String para replacement. Possui os seguintes wildcards:
         * - %1$s: Permalink do post;
         * - %2$s: Timestamp do post;
         * - %3$s: Data no formato ISO 8601;
         * - %4$s: Data completa por extenso;
         * - %5$s: URL para a página do autor;
         * - %6$s: String com título do link e nome do autor;
         * - %7$s: Nome do autor;
         */
        $repl = 'Postado em: '
            . '<a href="%1$s" title="%2$s" rel="bookmark">'
            . '<time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>'
            . '<span class="byline"> by <span class="author vcard">'
            . '<a class="url fn n" href="%5$s" title="%6$s" rel="author">'
            . '%7$s</a></span></span>';

        // Exibindo
        printf(
            // String para replacement
            __(
                $repl,
                // Substitua o text domain de acordo com seu tema
                'text-domain'
            ),
            // Argumentos
            esc_url( get_the_permalink() ),
            esc_attr( get_the_time() ),
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
            esc_attr(
                sprintf(
                    // Substitua o text domain de acordo com seu tema
                    __( 'Visualizar todos os posts de %s', 'text-domain'),
                    get_the_author()
                )
            ),
            esc_html( get_the_author() )
        );
    }
}



/**
 * Verifica o número de categorias no site, retornando um valor `boolean` caso
 * tenha mais de 1 categoria.
 *
 * Para uso no front-end/template.
 *
 * @return bool
 *      Se possui mais de uma categoria
 */
function yx_categorized_blog()
{
    // Verifica transientes (copiado do tutorial)
    if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
        // Cria array com as categorias vinculadas aos posts
        $all_the_cool_cats = get_categories(
            array(
                'hide_empty' => 1
            )
        );

        // Conta categorias
        $all_the_cool_cats = count( $all_the_cool_cats );

        // Define transiente
        set_transient( 'all_the_cool_cats', $all_the_cool_cats );
    }

    // Verifica transiente
    if ( '1' != $all_the_cool_cats ) {
        // Se houver mais de 1 categoria, retorna true
        return true;
    }

    // Se houver 1 ou nenhuma categoria
    return false;
}



/**
 * Como `yx_categorized_blog` utiliza transientes no front-end, é necessário
 * utilizar uma action para limpá-los ao editar posts/categorias.
 *
 * Este filtro cuida disso, devendo ser colocado no `functions.php`.
 */
function yx_category_transient_flusher()
{
    // Deleta o transiente ao salvar/atualizar posts/categorias
    delete_transient( 'all_the_cool_cats' );
}
// Registrando Action Hooks
add_action( 'edit_category', 'yx_category_transient_flusher' );
add_action( 'save_post', 'yx_category_transient_flusher' );



/**
 * Retorna quanto tempo se passou desde uma determinada data, similar ao
 * que é feito em redes como Facebook/Twitter.
 *
 * IMPORTANTE:
 * Output sempre em português (Brasil)
 *
 * @param int $datetime
 *      Timestamp UNIX, mesmo esquema da função `time()` do PHP
 * @return string
 *      String com informações
 */
function yx_time_passed( $datetime )
{
    // Timestamp precisa ser numérica
    if ( ! is_numeric( $datetime ) ) return "Timestamp inválida.";

    // Não pode ser negativa
    $time = time() - $datetime;
    if ( $time < 0 ) return "Timestamp inválida.";

    // Quanto tempo se passou?
    switch ( $time ) {
        // Agora
        case 0:
            $text = "agora mesmo";
            break;
        // Alguns segundos
        case ( $time >= 0 && $time < 30 ):
            $text = "h&aacute; alguns segundos";
            break;
        // Menos de um minuto
        case ( $time < 60 ):
            $text = "h&aacute; menos de um minuto";
            break;
        // Um minuto (aprox.)
        case ( $time < 120 ):
            $text = "h&aacute; um minuto";
            break;
        // X minutos
        case ( $time < ( 60 * 60 ) ):
            $text = "h&aacute; " . floor( $time / 60 ) . " minutos";
            break;
        // Há uma hora (aprox.)
        case ( $time < ( 120 * 60 ) ):
            $text = "h&aacute; uma hora";
            break;
        // X horas
        case ( $time < ( 24 * 60 * 60 ) ):
            $text = "h&aacute; " . floor( $time / 3600 ) . " horas";
            break;
        // Um dia (aprox.)
        case ( $time < ( 48 * 60 * 60 ) ):
            $text = "h&aacute; um dia";
            break;
        default:
            // Data
            $date = yx_parse_date( date( 'r', $datetime ), 2 );
            $text = "{$date[0]} de {$date[1]} de {$date[2]}";
            break;
    }
    return ucfirst( $text );
}

/**
 * Analisa uma data e retorna um array contendo a mesma fragmentada, sendo
 * os seguintes valores:
 * - [0]: Dia;
 * - [1]: Mês;
 * - [2]: Ano;
 * - [3]: Horas;
 * - [4]: Minutos;
 * - [5]: Dia da Semana;
 *
 * Aceita strings que também servem em `strtotime()`, incluindo:
 * - "2014-01-22 10:29:14 am" (ano-mes-dia hora:minuto:segundo am/pm);
 * - "Wed, 22 Jan 2014 10:29:41 -0200" (Data RFC);
 * - "20140122102949" (ano-mes-dia-hora-minutos-segundos);
 *
 * IMPORTANTE:
 * Output sempre em português (Brasil)
 *
 * @param string $date
 *      String com a data
 * @param int $size
 *      Opcional, tamanho da data a ser retornado entre: 0 (abreviado)
 *      até 2 (por extenso), padrão: 0
 * @return array|bool
 *      Array com data ou false, se inválido
 */
function yx_parse_date( $date, $size = 0 )
{
    // Date type
    $date = strtotime( $date );
    if ( ! $date ) {
        false;
    }

    // Verifica o tamanho
    $size = ( is_numeric( $size ) && $size >= 0 && $size < 3 ) ? $size : 0;

    // Meses e dias da semana
    $name = array(
        "mes" => array(
            "abbr" => array(
                "Jan",
                "Fev",
                "Mar",
                "Abr",
                "Mai",
                "Jun",
                "Jun",
                "Ago",
                "Set",
                "Out",
                "Nov",
                "Dez"
            ),
            "full" => array(
                "Janeiro",
                "Fevereiro",
                "Mar&ccedil;o",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            )
        ),
        "dow" => array(
            "abbr" => array(
                "Dom",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "S&aacute;b"
            ),
            "full" => array(
                "Domingo",
                "Segunda-feira",
                "Ter&ccedil;a-feira",
                "Quarta-feira",
                "Quinta-feira",
                "Sexta-feira",
                "S&aacute;bado"
            )
        )
    );

    // Define lista inicial
    $list = array(
        date( "d", $date ),
        date( "m", $date ),
        date( "Y", $date ),
        date( "H", $date ),
        date( "i", $date ),
        date( "w", $date ),
    );

    // Verifica abreviação do mês
    if ( $size > 0 ) {
        $list[1] = ( $size > 1 )
            ? $name["mes"]["full"][ $list[1] - 1 ]
            : $name["mes"]["abbr"][ $list[1] - 1 ];
    }

    // Verifica abreviação do dia da semana
    $list[5] = ( $size > 1 )
        ? $name["dow"]["full"][ $list[5] ]
        : $name["dow"]["abbr"][ $list[5] ];

    // Returning
    return $list;
}

/**
 * Retorna uma data completa, em português (Brasil).
 *
 * @return string
 */
function yx_get_the_date()
{
    // Define dia da semana
    $dow = date( 'w' );
    switch ( $dow ) {
        case 0:
            $dow = 'domingo';
            break;
        case 1:
            $dow = 'segunda-feira';
            break;
        case 2:
            $dow = 'terça-feira';
            break;
        case 3:
            $dow = 'quarta-feira';
            break;
        case 4:
            $dow = 'quinta-feira';
            break;
        case 5:
            $dow = 'sexta-feira';
            break;
        case 6:
            $dow = 'sábado';
            break;
    }

    // Define Mês
    $month = date( 'n' );
    switch ( $month ) {
        case 1:
            $month = 'Janeiro';
            break;
        case 2:
            $month = 'Fevereiro';
            break;
        case 3:
            $month = 'Março';
            break;
        case 4:
            $month = 'Abril';
            break;
        case 5:
            $month = 'Maio';
            break;
        case 6:
            $month = 'Junho';
            break;
        case 7:
            $month = 'Julho';
            break;
        case 8:
            $month = 'Agosto';
            break;
        case 9:
            $month = 'Setembro';
            break;
        case 10:
            $month = 'Outubro';
            break;
        case 11:
            $month = 'Novembro';
            break;
        case 12:
            $month = 'Dezembro';
            break;
    }

    // Retorna data
    return ucfirst(
        $dow . ', ' . date( 'd' ) . ' de ' . $month . ' de ' . date( 'Y' )
    );
}

// Postado em...
if ( ! function_exists( 'yx_posted_time' ) ) {
    /**
     * Exibe o horário de postagem.
     */
    function yx_posted_time()
    {
        printf(
            __(
                'Postado às %1s',
                'text-domain'
            ),
            esc_attr( get_the_time( 'H\hi' ) )
        );
    }
}
