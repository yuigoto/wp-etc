<?php
/**
 * Post
 * ----------------------------------------------------------------------
 * Snippets para metadados adicionais e listagem de posts, entre outras coisas.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// PARA USO EM FUNCTIONS.PHP
// ----------------------------------------------------------------------

// Adiciona suporte à excerpt/resumo
add_post_type_support( 'page', 'excerpt' );

/**
 * Utilize dentro do loop, em `while = have_posts`, quando estiver listando
 * postagens, para verificar se ainda há mais posts a serem exibidos na
 * query atual.
 *
 * Para uso estético.
 *
 * @return bool
 *      True, se tiver mais posts, false se não houver
 */
function more_post()
{
    global $wp_query;
    return ( ( $wp_query->current_post + 1 ) < $wp_query->post_count );
}

// PARA USO NO TEMPLATE
// ----------------------------------------------------------------------

// Exibindo campo personalizado
echo get_post_meta( $post_id, 'test-field', true );



// Verifica método de template de comentários
if ( ! function_exists( 'yx_comment_template' ) ) {
    /**
     * Template para comentários e pingbacks.
     *
     * Utilizado como callback function para `wp_list_comments`, para exibir
     * os comentários no template.
     *
     * Importante notar que, embora o método abra uma tag `<li>`, não há a
     * necessidade fechá-la, pois `wp_list_comments` cuida disso. ;)
     *
     * @param WP_Comment $comment
     *      Objeto WP_Comment
     * @param array $args
     *      Array com os argumentos para exibição de comentários
     * @param int $depth
     *      Nível de profundide máximo para nesting de comentários
     */
    function yx_comment_template( $comment, $args, $depth )
    {
        // Set global comment as $comment, for externa/latter use?
        $GLOBALS['comment'] = $comment;

        // Comment array
        $item = array();

        // Checking comment type
        switch ( $comment->comment_type ) {
            // Se for pingback ou trackback
            case 'pingback':
            case 'trackback':
                // Define comment edit uri
                $comment_edit_uri = get_edit_comment_link(
                    $comment->comment_ID
                );

                // Define pingback
                $item[] = '<li class="post pingback">';
                $item[] = '<p>' . __( 'Pingback', 'text-domain' ) . ' '
                        . get_comment_author_link()
                        . ' <a href="' . $comment_edit_uri . '">'
                        . __( '(Editar)', 'text-domain' ) . '</a>'
                        . '</p>';
                break;
            default:
                // Define ID do comentário
                $commentID = 'comment-' . get_comment_ID();

                // Define item
                $item[] = '<li class="' . implode( ' ', get_comment_class() )
                    . '" id="li-' . $commentID . '">';
                $item[] = '<article id="' . $commentID . '" class="comment">';

                // Footer note de comentário
                $item[] = '<footer>';

                // Author vCard
                $item[] = '<div class="comment-author vcard">';
                $item[] = get_avatar( $comment, 40 );
                $item[] = sprintf(
                    __(
                        '%s <span class="says">Diz:</span>',
                        'text-domain'
                    ),
                    sprintf(
                        '<cite class="fn">%s</cite>',
                        get_comment_author_link()
                    )
                );
                $item[] = '</div>';

                // Se não aprovado
                if ( $comment->comment_approved == '0' ) {
                    $item[] = '<em>';
                    $item[] = __(
                        'Comentário aguardando moderação',
                        'text-domain'
                    );
                    $item[] = '</em>';
                    $item[] = '<br>';
                }

                // Author Metadata
                $item[] = '<div class="comment-meta commentmetadata">';

                // URL de comentário
                $comment_uri = esc_url( get_comment_link( $comment->comment_ID ) );

                // Link do comentário
                $item[] = '<a href="' . $comment_uri . '">';
                $item[] = '<time pubdate datetime"'
                    . get_comment_time( 'c' ) . '">';
                // Ref.para Tradutores: %1$s = data, %2$s = horário
                $item[] = sprintf(
                    __( '%1$s às %2$s', 'text-domain' ),
                    get_comment_date(),
                    get_comment_time()
                );
                $item[] = '</time>';
                $item[] = '</a>';

                // Define comment edit uri
                $comment_edit_uri = get_edit_comment_link( $comment->comment_ID );

                // Editar comentário
                $item[] = ' <a href="' . $comment_edit_uri . '">'
                        . __( '(Editar)', 'text-domain' ) . '</a>';
                $item[] = '</div>';
                $item[] = '</footer>';

                // Conteúdo de comentário
                $item[] = '<div class="comment-content">';
                $item[] = get_comment_text();
                $item[] = '</div>';

                // Reply link
                $item[] = '<div class="reply">';
                $item[] = get_comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth']
                        )
                    )
                );
                $item[] = '</div>';
                $item[] = '</article>';
                break;
        }

        // Exibe comentário
        echo implode( '', $item );
    }
}



// Verifica método de títulos em arquivo
if ( ! function_exists( 'yx_archives_title' ) ) {
    /**
     * Exibe o título do template/página de arquivo (`archives.php`), levando
     * em conta todas as possibilidades de títulos e tipos de título para a
     * mesma.
     */
    function yx_archives_title()
    {
        // Variável de título padrão
        $title = __( 'Arquivos', 'text-domain' );

        // Condicionais
        if ( is_category() ) {
            // Se for Categoria
            $title = sprintf(
                __( 'Arquivo da Categoria: %s', 'text-domain' ),
                '<span>' . single_cat_title( '', false ) . '</span>'
            );
        } elseif ( is_tag() ) {
            // Se for Tag
            $title = sprintf(
                __( 'Arquivo da Tag: %s', 'text-domain' ),
                '<span>' . single_tag_title( '', false ) . '</span>'
            );
        } elseif ( is_author() ) {
            // Se for Autor
            /**
             * Puxa o primeiro post, assim sabemos qual autor estamos vendo.
             */
            the_post();

            // Define link do autor
            $link = '<a class="url fn n" href="'
                  . get_author_posts_url( get_the_author_meta( 'ID' ) )
                  . '" title="' . esc_attr( get_the_author() ) . '" rel="me">'
                  . get_the_author() . '</a>';

            // Define título
            $title = sprintf(
                __( 'Arquivo do Autor: %s', 'text-domain' ),
                '<span class="vcard">' . $link . '</span>'
            );

            /**
             * Como chamamos "the_post()" acima, precisamos dar "rewind" no
             * loop, para que o loop seja executado completamente, ao listar
             * os posts.
             */
            rewind_posts();
        } elseif ( is_day() ) {
            // Se for Dia (Data)
            $title = sprintf(
                __( 'Arquivo Diário: %s', 'text-domain' ),
                '<span>' . get_the_date() . '</span>'
            );
        } elseif ( is_month() ) {
            // Se for Mês (Data)
            $title = sprintf(
                __( 'Arquivo Mensal: %s', 'text-domain' ),
                '<span>' . get_the_date( 'F Y' ) . '</span>'
            );
        } elseif ( is_year() ) {
            // Se for Ano (Data)
            $title = sprintf(
                __( 'Arquivo Anual: %s', 'text-domain' ),
                '<span>' . get_the_date( 'Y' ) . '</span>'
            );
        }

        // Exibindo o título
        echo $title;
    }



    /**
     * Em páginas de arquivo, exibe a descrição da taxonomia, sendo opcional.
     */
    function yx_taxonomy_description()
    {
        // Verifica se é categoria ou tag
        if ( is_category() ) {
            // Exibe a descrição opcional da categoria
            $cats_description = category_description();

            // Se não vazio, exibe descrição
            if ( ! empty( $cats_description ) ) {
                echo apply_filters(
                    'category_archive_meta',
                    '<div class="taxonomy-description">'
                        . $cats_description . '</div>'
                );
            }
        } elseif ( is_tag() ) {
            // Exibe a descrição opcional da tag
            $tags_description = tag_description();

            // Se não vazio, exibe descrição
            if ( ! empty( $tags_description ) ) {
                echo apply_filters(
                    'tag_archive_meta',
                    '<div class="taxonomy-description">'
                        . $tags_description . '</div>'
                );
            }
        }
    }
}
