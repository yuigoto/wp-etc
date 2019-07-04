<?php
/**
 * Breadcrumb
 * ----------------------------------------------------------------------
 * Funções para exibição de breadcrumb em páginas e posts.
 *
 * Declare no `functions.php` e utilize em seu template aonde precisar.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

if ( !function_exists( 'show_breadcrumb' ) ) {
    /**
     * Exibe um breadcrumb simples para post ou página.
     *
     * @param int $post_id
     *      Opcional, ID do post para breadcrumb, padrão: ID do post em exibição
     * @param string $type
     *      Opcional, tipo de post para filtragem, padrão: page
     * @param string $class
     *      Opcional, classe para o wrapper do breadcrumb, padrão: breadcrumb
     * @param string $last
     *      Opcional, string com itens a serem adicionados ao final da
     *      breadcrumb, mas antes de fechar o wrapper, usado apenas caso
     *      queira adicionar mais algum link no breadcrumb que não seja
     *      possível computar normalmente, padrão: ''
     * @return void
     */
    function show_breadcrumb(
        $post_id = null,
        $type = null,
        $class = null,
        $last = ''
    ) {
        // Verifica ID
        if ( ! is_numeric( $post_id ) || null === $post_id || $post_id < 1 ) {
            // Pega o ID global
            global $post;
            $post_id = $post->ID;
        }

        // Verifica tipo
        $type = ( trim( $type ) != '' ) ? trim( $type ) : 'page';

        // Verifica classe
        $class = ( trim( $class ) != '' ) ? trim( $class ) : 'breadcrumb';

        // Se o post type é diferente de $type, não exibe nada
        if ( $type != get_post_type( $post_id ) ) return;

        // Puxa dados do post
        $post_data = get_post( $post_id );

        // Array de links
        $list = array();

        // Se o post tiver "pai" (parent), adiciona ao breadcrumb todos
        if ( $post_data->post_parent ) {
            // Adiciona o primeiro link
            $link = '<li><a href="' . get_the_permalink( $post_id ) . '">'
                . get_the_title( $post_id ) . '</a></li>';

            // Adiciona o item ao início do array para garantir ordem
            array_unshift( $list, $link );

            // Verificação recursiva de post "pai"
            $parent = $post_data->post_parent;
            while ( $parent ) {
                // Pega os dados do post pai
                $parent_data = get_post( $parent );

                // Monta o link
                $link = '<li><a href="'.get_the_permalink( $parent_data->ID )
                    . '">' . get_the_title( $parent_data->ID ) . '</a></li>';

                // Adiciona o item ao início do array
                array_unshift( $list, $link );
            }
        }

        // Se não houver itens, não exibe
        if ( count ( $list ) < 1 ) return;

        // Adiciona add-ons adicionais
        if ( '' != trim( $last ) ) $list[] = trim( $last );

        // Exibe o breadcrumb
        echo '<ul class="'.$class.'">' . implode( '', $list ) . '</ul>';
    }
}

/**
 * Exibe breadcrumbs um pouco mais avançadas que `show_breadcrumb`, com
 * possibilidade de expandir para custom posts e taxonomias.
 *
 * O parâmetro `$args` é um array associativo, que deve conter os seguintes
 * parâmetros para exibição (todos são opcionais):
 * - `id`: String, ID da breadcrumb, padrão: 'breadcrumb';
 * - `class`: String, classe CSS da breadcrumb, padrão: 'breadcrumb-list';
 * - `wrap_before`: String, HTML para ser impresso antes da breadcrumb,
 *      padrão: '';
 * - `wrap_after`: String, HTML para ser impresso após a breadcrumb,
 *      padrão: '';
 * - `show_home`: Boolean, se deve, ou não, exibir o link para a home,
 *      padrão: true;
 * - `show_on_home`: Boolean, se deve exibir, ou não, na home/front page,
 *      padrão: false;
 * - `show_current`: Boolean, se exibe, ou não, a página atual no breadcrumb,
 *      padrão: true;
 *
 * @param array $args
 *      Argumentos para exibição da breadcrumb
 */
function show_breadcrumb_adv( $args = null )
{
    // CONFIGURAÇÕES PADRÃO
    // ------------------------------------------------------------------
    $default_args = array(
        // ID do breadcrumb
        'id'            => 'breadcrumb',
        // Classe do breadcrumb
        'class'         => 'breadcrumb-list',
        // HTML para inserir antes do breadcrumb
        'wrap_before'   => '',
        // HTML para inserir após o breadcrumb
        'wrap_after'    => '',
        // Exibir link para home
        'show_home'     => true,
        // Exibir na home/front-page
        'show_on_home'  => false,
        // Exibir permalink da página atual
        'show_current'  => true
    );

    // Processando argumentos e definindo padrões
    $args = ( is_array( $args ) )
        ? wp_parse_args( $args, $default_args )
        : $default_args;

    // ATRIBUTOS DE SCHEMA (C/ WILDCARDS)
    // ------------------------------------------------------------------

    // Propriedade do Item
    $prop_attr = ' itemprop="%1s"';

    // Escopo e Tipo do Item
    $type_attr = ' itemscope itemtype="http://schema.org/%1s"';

    // ELEMENTOS BASE
    // ------------------------------------------------------------------

    // Item Simples, com Anchor
    $link_base = '<li'
        . sprintf( $prop_attr, 'itemListElement' )
        . sprintf( $type_attr, 'ListItem' ) .'>'
        . '<a href="%1s"' . sprintf( $prop_attr, 'item' ) . ' title="%2s">'
        . '<span' . sprintf( $prop_attr, 'name' ) . '>'
        . '%3s'
        . '</span></a></li>';

    // Item Simples, sem Anchor
    $link_void = '<li'
        . sprintf( $prop_attr, 'itemListElement' )
        . sprintf( $type_attr, 'ListItem' ) .'>%1s</li>';

    // PARÂMETROS GLOBAIS E OUTROS (CUSTOM POST-TYPES, ETC)
    // ------------------------------------------------------------------
    global $post, $wp_query;

    // Exemplo com Custom Taxonomy para Custom Post Types
    $custom_taxo = 'tipos';

    // ID da Front Page
    $frontpage_id = get_option( 'page_on_front' );

    // Parent ID (caso a página esteja seja derivada/filha)
    $parent_id = ( $post ) ? $post->post_parent : false;

    // Array com dados do breadcrumb
    $bc_data = array();

    // WRAPPER DE ABERTURA
    // ------------------------------------------------------------------
    if ( $args['wrap_before'] !== '' ) $bc_data[] = $args['wrap_before'];

    // ABRE LISTA
    // ------------------------------------------------------------------
    $bc_data[] = '<ul id="' . $args['id'] . '" class="' . $args['class'] . '"'
        . sprintf( $type_attr, 'BreadcrumbList' ) . '>';

    // DEFINE LINKS
    // ------------------------------------------------------------------

    // Front Page/Home
    if ( is_home() || is_front_page() ) {
        // Se permitir exibição na home
        if ( $args['show_on_home'] ) {
            // Verifica se é exibição paginada
            if ( get_query_var( 'paged' ) ) {
                // Link para Home
                $bc_data[] = sprintf(
                    $link_base,
                    home_url( '/' ),
                    'Home',
                    'Home'
                );

                // Adiciona Ref. Página Atual
                $bc_data[] = sprintf(
                    $link_void,
                    'Página ' . get_query_var( 'paged' )
                );
            } else {
                // Exibe o item atual
                if ( $args['show_current'] ) {
                    $bc_data[] = sprint_f(
                        $link_void,
                        'Home'
                    );
                }
            }
        } else {
            // Quebra execução se não exibir na home
            return;
        }
    } else {
        // Adiciona link para Home
        if ( $args['show_home'] ) {
            $bc_data[] = sprintf( $link_base, home_url( '/' ), 'Home', 'Home' );
        }

        // Se categoria
        if ( is_category() ) {
            // Categoria "Pai"
            $cats = get_category( get_query_var( 'cat' ), false );

            // Havendo categoria Pai
            if ( $cats->parent != 0 ) {
                // Puxa categoria
                $pars = get_category_parents( $cats->parent, true, '§' );
                $pars = preg_replace( '/^(.+)§$/', "$1", $pars );
                $pars = explode( '§', $pars );

                // Adicionando categorias
                foreach ( $subs as $item ) {
                    // Extrai dados do link no array $item_data
                    preg_match(
                        '/<a([^>]+)>([^<]+)<\/a>/',
                        $item,
                        $item_data
                    );

                    // Adiciona item ao breadcrumb
                    $bc_data[] = sprintf(
                        $link_base,
                        preg_replace(
                            '/href\=\"(.*)\"/',
                            '$1',
                            trim( $item_data[1] )
                        ),
                        trim( $item_data[2] ),
                        trim( $item_data[2] )
                    );
                }
            }

            // Se houver paginação o link da categoria é alterado
            if ( get_query_var( 'paged' ) ) {
                // Pega ID da categoria
                $cats = $cats->cat_ID;

                // Adiciona link da categoria
                $bc_data[] = sprintf(
                    $link_base,
                    get_category_link( $cats ),
                    get_cat_name( $cats ),
                    get_cat_name( $cats )
                );

                // Adiciona Item Rel. Página Atual
                $bc_data[] = sprintf(
                    $link_void,
                    'Página ' . get_query_var( 'paged' )
                );
            } else {
                if ( $args['show_current'] ) {
                    $bc_data[] = sprintf(
                        $link_void,
                        single_cat_title( '', false )
                    );
                }
            }
        }

        // Se Busca
        elseif ( is_search() ) {
            $bc_data[] = sprintf(
                $link_void,
                'Resultados da busca para: ' . get_search_query()
            );
        }

        // Se Dia/Mês/Ano
        elseif ( is_day() || is_month() || is_year() ) {
            // Ano
            $bc_data[] = sprintf(
                $link_base,
                get_year_link( get_the_time( 'Y' ) ),
                get_the_time( 'Y' ),
                ( is_year() )
                    ? 'Arquivos: ' . get_the_time( 'Y' )
                    : get_the_time( 'Y' )
            );

            // Mês
            if ( is_month() || is_day() ) {
                $bc_data[] = sprintf(
                    $link_base,
                    get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                    get_the_time( 'F/Y' ),
                    ( is_month() )
                        ? 'Arquivos: ' . get_the_time( 'F' )
                        : get_the_time( 'F' )
                );
            }

            // Dia
            if ( is_day() ) {
                $bc_data[] = sprintf(
                    $link_base,
                    get_day_link(
                        get_the_time( 'Y' ),
                        get_the_time( 'm' ),
                        get_the_time( 'd' )
                    ),
                    get_the_time( 'd/F/Y' ),
                    get_the_time( 'd' )
                );
            }
        }

        // Se Single, mas não anexo (como Imagens)
        elseif ( is_single() && !is_attachment() ) {
            // Se não for post
            if ( get_post_type() != 'post' ) {
                // Puxa type
                $post_type = get_post_type_object( get_post_type() );

                // Post Slug
                $slug = $post_type->rewrite;

                // Exibe link para o post type
                $bc_data[] = sprintf(
                    $link_base,
                    home_url( '/' ) . $slug['slug'] . '/',
                    $post_type->labels->singular_name,
                    $post_type->labels->singular_name
                );

                // Exibe link atual
                if ( $args['show_current'] ) {
                    // Se puder exibir o título atual
                    $bc_data[] = sprintf(
                        $link_void,
                        get_the_title()
                    );
                }
            } else {
                // Puxando Categoria
                $cats = get_the_category();
                $cats = get_category_parents( $cats[0], TRUE, '§' );
                $cats = preg_replace( "/^(.+)§$/", "$1", $cats );
                $cats = explode( '§', $cats );

                // Se hover mais de uma categoria
                foreach ( $cats as $item ) {
                    // Se não for vazio
                    if ( trim( $item ) != '' ) {
                        // Extrai dados do link no array $item_data
                        preg_match(
                            '/<a([^>]+)>([^<]+)<\/a>/',
                            $item,
                            $item_data
                        );

                        // Adiciona item ao breadcrumb
                        $bc_data[] = sprintf(
                            $link_base,
                            preg_replace(
                                '/href\=\"(.*)\"/',
                                '$1',
                                trim( $item_data[1] )
                            ),
                            trim( $item_data[2] ),
                            trim( $item_data[2] )
                        );
                    }
                }

                // Há, ou não, paginação de comentários?
                if ( get_query_var( 'cpage' ) ) {
                    // Exibe link do post
                    $bc_data[] = sprintf(
                        $link_base,
                        get_the_permalink(),
                        get_the_title(),
                        get_the_title()
                    );

                    // Exibe página atual
                    $bc_data[] = sprintf(
                        $link_void,
                        'Página de Comentários: <em>'
                            . get_query_var( 'cpage' ) .'</em>'
                    );
                } else {
                    // Exibe link atual, se ativo
                    if ( $args['show_current'] ) {
                        $bc_data[] = sprintf(
                            $link_void,
                            get_the_title()
                        );
                    }
                }
            }
        }

        // Se não for Single, Página, Post nem 404 (especial para Custom Post)
        elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            // Puxa Post Type e o objeto
            $post_type = get_post_type();
            $post_object = get_post_type_object( $post_type );

            // Se válido
            if ( $post_object !== null && $post_object !== false ) {
                if ( get_query_var( 'paged' ) ) {
                    // Exibe o link do post type
                    $bc_data[] = sprintf(
                        $link_base,
                        get_post_type_archive_link( $post_object->name ),
                        $post_object->label,
                        $post_object->label
                    );

                    // Exibe a página atual
                    $bc_data[] = sprintf(
                        $link_void,
                        'Página: <em>' . get_query_var( 'paged' ) . '</em>'
                    );
                } else {
                    // Adiciona current
                    if ( $args['show_current'] ) {
                        $bc_data[] = sprintf(
                            $link_void,
                            $post_object->label
                        );
                    }
                }
            } else {
                // Se inválido, pode ser taxonomia
                $taxo = get_queried_object();

                // Adiciona current
                if ( $taxo !== null && $taxo !== false && $args['show_current'] ) {
                    $bc_data[] = sprintf(
                        $link_void,
                        $taxo->name
                    );
                }
            }
        }

        // Se Anexo (ex.: Imagem)
        elseif ( is_attachment() ) {
            // Parent post ID
            $attach_parent = get_post( $parent_id );

            // Puxando categoria
            $cats = get_the_category( $attach_parent->ID );
            $cats = $cats[0];

            // Se houver categoria "pai"
            if ( $cats ) {
                // Puxando categoria
                $cats = get_category_parents( $cats, true, '§' );
                $cats = preg_replace( '/^(.+)§$/', "$1", $cats );
                $cats = explode( '§', $cats );

                // Se houver mais de uma
                foreach ( $cats as $item ) {
                    // Extrai dados do link no array $item_data
                    preg_match(
                        '/<a([^>]+)>([^<]+)<\/a>/',
                        $item,
                        $item_data
                    );

                    // Adiciona item ao breadcrumb
                    $bc_data[] = sprintf(
                        $link_base,
                        preg_replace(
                            '/href\=\"(.*)\"/',
                            '$1',
                            trim( $item_data[1] )
                        ),
                        trim( $item_data[2] ),
                        trim( $item_data[2] )
                    );
                }
            }

            // Adiciona post parent
            $bc_data[] = sprintf(
                $link_base,
                get_permalink( $attach_parent ),
                $attach_parent->post_title,
                $attach_parent->post_title
            );

            // Adicionando current
            if ( $args['show_current'] ) {
                $bc_data[] = sprintf(
                    $link_void,
                    get_the_title()
                );
            }
        }

        // Se Página, sem Pai
        elseif ( is_page() && !$parent_id ) {
            // Se puder exibir o link atual
            if ( $args['show_current'] ) {
                $bc_data[] = sprintf(
                    $link_void,
                    get_the_title()
                );
            }
        }

        // Se Página, com Pai
        if ( is_page() && $parent_id ) {
            // Se não for front page
            if ( $parent_id != $frontpage_id ) {
                // Array temporário para breadcrumbs
                $crumbs = array();

                // Enquanto `parent_id` for válido
                while ( $parent_id ) {
                    // Adiciona link
                    $page = get_page( $parent_id );

                    // Se não for front page
                    if ( $parent_id != $frontpage_id ) {
                        $crumbs[] = sprintf(
                            $link_base,
                            get_the_permalink( $page->ID ),
                            get_the_title( $page->ID ),
                            get_the_title( $page->ID )
                        );
                    }

                    // Atualiza Parent ID
                    $parent_id = $page->post_parent;
                }

                // Inverte ordem dos links
                $crumbs = array_reverse( $crumbs );

                // Dá merge no array principal
                $bc_data = array_merge( $bc_data, $crumbs );
            }

            // Página single
            if ( $args['show_current'] ) {
                $bc_data[] = sprintf(
                    $link_void,
                    get_the_title()
                );
            }
        }

        // Se Página de Tags
        elseif ( is_tag() ) {
            // Se estiver paginado
            if ( get_query_var( 'paged' ) ) {
                // ID da tag
                $tag_id = get_queried_object_id();

                // Puxando tag
                $tag = get_tag( $tag_id );

                // Adiciona link do autor
                $bc_data[] = sprintf(
                    $link_base,
                    get_tag_link( $tag_id ),
                    'Posts marcados em: ' . $tag->name,
                    'Posts marcados em: <em>' . $tag->name . '</em>'
                );

                // Adiciona Item Rel. Página Atual
                $bc_data[] = sprintf(
                    $link_void,
                    'Página ' . get_query_var( 'paged' )
                );
            } else {
                if ( $args['show_current'] ) {
                    $bc_data[] = sprintf(
                        $link_void,
                        'Posts marcados em: <em>'
                            . single_tag_title( '', false ) . '</em>'
                    );
                }
            }
        }

        // Se Página de Autores
        elseif ( is_author() ) {
            // Global de Autor
            global $author;

            // Puxando dados
            $author = get_userdata( $author );

            // Verifica paginação
            if ( get_query_var( 'paged' ) ) {
                // Adiciona link do autor
                $bc_data[] = sprintf(
                    $link_base,
                    get_author_posts_url( $author->ID ),
                    'Itens postados por ' . $author->display_name,
                    'Itens postados por <em>'
                        . $author->display_name . '</em>'
                );
            } else {
                if ( $args['show_current'] ) {
                    $bc_data[] = sprintf(
                        $link_void,
                        'Itens postados por <em>'
                            . $author->display_name . '</em>'
                    );
                }
            }
        }

        // Se houver paginação
        elseif ( get_query_var( 'paged' ) ) {
            // Adiciona Item Rel. Página Atual
            $bc_data[] = sprintf(
                $link_void,
                'Página ' . get_query_var( 'paged' )
            );
        }

        // Se Página 404
        elseif ( is_404() ) {
            if ( $args['show_current'] ) {
                $bc_data[] = sprintf( $link_void, '404' );
            }
        }

        // Se houver PostFormat e não for single/singular
        elseif ( has_post_format() && !is_singular() ) {
            // Arquivos para o PostFormat
            $bc_data[] = sprintf(
                $link_void,
                get_post_format_string( get_post_format() )
            );
        }
    }

    // FECHA LISTA
    // ------------------------------------------------------------------
    $bc_data[] = '</ul>';

    // WRAPPER DE FECHAMENTO
    // ------------------------------------------------------------------
    if ( $args['wrap_after'] !== '' ) $bc_data[] = $args['wrap_after'];

    // EXIBE BREADCRUMB
    // ------------------------------------------------------------------
    echo implode( "", $bc_data );
}
