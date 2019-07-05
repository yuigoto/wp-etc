<?php if ( ! defined( 'ABSPATH' ) ) die ( 'Acesso direto ao arquivo negado.' );

/**
 * Helpers
 * ----------------------------------------------------------------------
 * Declare aqui funções de uso geral e utilitárias do tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/* CUSTOM LOGIN FORM
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_custom_login' ) ) {
    /**
     * Exibe uma versão customizada do formulário de login, utilize como
     * alternativa de `wp_login_form()`.
     *
     * @param array $args
     *      Array com argumentos de login
     * @return string
     *      String com formulário, quando não exibido diretamente
     */
    function yx_custom_login( $args = array() )
    {
        $defaults = array(
            'echo'              => true,
            'redirect'          => ( is_ssl() ? 'https://' : 'http://' )
                . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'form_id'           => 'loginform',
            'label_username'    => __( 'Usuário ou e-mail', THEME_DOMAIN ),
            'label_password'    => __( 'Senha', THEME_DOMAIN ),
            'label_remember'    => __( 'Lembre-me', THEME_DOMAIN ),
            'label_log_in'      => __( 'Acessar', THEME_DOMAIN ),
            'id_username'       => 'user_login',
            'id_password'       => 'user_pass',
            'id_remember'       => 'rememberme',
            'id_submit'         => 'wp-submit',
            'remember'          => true,
            'value_username'    => '',
            'value_remember'    => false
        );
        
        $args = wp_parse_args(
            $args,
            apply_filters( 'login_form_defaults', $defaults )
        );
    
        $login_form_top     = apply_filters( 'login_form_top', '', $args );
        $login_form_middle  = apply_filters( 'login_form_middle', '', $args );
        $login_form_bottom  = apply_filters( 'login_form_bottom', '', $args );
        
        // Montando formulário
        $form = array();
        
        // Abre formulário
        $form[] = '<form name="' . $args['form_id'] . '" id="'
            . $args['form_id'] . '" class="ab-topbar-form" action="'
            . esc_url( site_url( 'wp-login.php', 'login_post' ) )
            . '" method="post" target="_self">';
    
        // Login top filter
        $form[] = $login_form_top;
        
        // Username
        {
            $form[] = '<div class="ab-topbar-form__group mr-2">';
            $form[] = '<input type="text" name="log" id="'
                . esc_attr( $args['id_username'] )
                . '" class="ab-topbar-form__input mr-1" value="'
                . esc_attr( $args['value_username'] ) .'" size="20" />';
            $form[] = '<i class="fa fa-user"></i>';
            $form[] = '</div>';
        }
    
        // Password
        {
            $form[] = '<div class="ab-topbar-form__group mr-2">';
            $form[] = '<input type="password" name="pwd" id="'
                . esc_attr( $args['id_password'] )
                . '" class="ab-topbar-form__input mr-1" value="" size="20" />';
            $form[] = '<i class="fa fa-key"></i>';
            $form[] = '</div>';
        }
    
        // Login middle filter
        $form[] = $login_form_middle;
        
        // Me lembre
        {
            if ( $args['remember'] ) {
                $form[] = '<p class="login-remember">';
                $form[] = '<label>';
                $form[] = '<input name="rememberme" type="checkbox" id="'
                    . esc_attr( $args['id_remember'] )
                    . '" value="forever"'
                    . ( $args['value_remember'] ? ' checked="checked"' : '' )
                    . ' /> ';
                $form[] = esc_html( $args['label_remember'] );
                $form[] = '</label>';
                $form[] = '</p>';
            }
        }
        
        // Botão enviar
        {
            $form[] = '<button type="submit" name="wp-submit" id="'
                . esc_attr( $args['id_submit'] )
                . '" class="ab-topbar-form__button">';
            $form[] = '<i class="fas fa-play"></i>';
            $form[] = '</button>';
        }
        
        // Input oculto para remember-me
        {
            $form[] = '<input type="hidden" name="redirect_to" value="'
                . esc_url( $args['redirect'] ) . '"/>';
        }
        
        // Login bottom filter
        $form[] = $login_form_bottom;
        
        // Fecha formulário
        $form[] = '</form>';
        
        if ( $args['echo'] ) {
            echo implode( '', $form );
        } else {
            return implode( '', $form );
        }
    }
}

/* FILTROS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_get_featured_posts' ) ) {
    /**
     * Getter para conteúdo destacado/fixado.
     *
     * @return mixed
     */
    function yx_get_featured_posts()
    {
        return apply_filters( 'yx_get_featured_posts', array() );
    }
}

/* IMAGENS DO POST
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_post_image' ) ) {
    /**
     * Verifica se o post possui, ou não, imagem.
     *
     * Caso tenha, retorna a URI do arquivo, se não houver retorna `false`.
     *
     * @param int $post_id
     *      ID do post
     * @param string $size
     *      Tamanho da imagem, deve ser uma das strings de tamanho suportadas
     *      pelo WordPress
     * @return string|bool
     *      URL da imagem ou boolean `false`
     */
    function yx_post_image( $post_id = null, $size = 'full' )
    {
        // Não tem ID? Solicita o global
        if ( ! is_numeric( $post_id ) || null === $post_id || $post_id < 1 ) {
            global $post;
            
            // Define ID
            $post_id = $post->ID;
        }
        
        // Define tamanho
        if (
            trim( $size ) != 'thumbnail'
            && trim( $size ) != 'medium'
            && trim( $size ) != 'large'
            && trim( $size ) != 'full'
        ) {
            $size = 'full';
        }
        
        // Solicita ID de imagem
        $image_thumbnail_id = get_post_thumbnail_id( $post_id );
        
        // Imagem é válida?
        if (
            '' != trim( $image_thumbnail_id )
            && is_numeric( $image_thumbnail_id )
        ) {
            // Solicita URL
            $image_link = wp_get_attachment_image_src(
                $image_thumbnail_id,
                $size
            );
            
            // Retorna a URI
            return $image_link[0];
        }
        
        return false;
    }
}

if ( ! function_exists( 'yx_post_image_extended' ) ) {
    /**
     * Versão extendida de `yx_post_image`, que verifica imagens alternativas,
     * seja por extensão do tema ou plugin.
     *
     * @param int $post_id
     *      ID do post
     * @param string $size
     *      Tamanho da imagem, deve ser uma das strings de tamanho suportadas
     *      pelo WordPress
     * @param bool $main_image_only
     *      Se `true` retorna apenas `post_thumbnail`, se `false`, realiza a
     *      verificação de outras imagens antes
     * @return false|string
     *      URL da imagem ou boolean `false`
     */
    function yx_post_image_extended(
        $post_id,
        $size = 'full',
        $main_image_only = false
    ) {
        // Não tem ID? Solicita o global
        if ( ! is_numeric( $post_id ) || null === $post_id || $post_id < 1 ) {
            global $post;
        
            // Define ID
            $post_id = $post->ID;
        }
    
        // Define tamanho
        if (
            trim( $size ) != 'thumbnail'
            && trim( $size ) != 'medium'
            && trim( $size ) != 'large'
            && trim( $size ) != 'full'
        ) {
            $size = 'full';
        }
        
        // Se deve caçar imagens de extensão primeiro
        if ( false === $main_image_only ) {
            // MFI Reloaded
            if ( class_exists( 'MFI_Reloaded' ) ) {
                // Puxa URL de uma imagem setada no plugin
                if ( function_exists( 'mfi_reloaded_get_image_id' ) ) {
                    // Ícone
                    $imgs = mfi_reloaded_get_image_id( 'icon', $post_id );
                    $imgs = wp_get_attachment_image_url( $imgs, $size );
    
                    // É válido?
                    if ( $imgs !== false ) return $imgs;
                    
                    // Outra imagem
                    $imgs = mfi_reloaded_get_image_id( 'other', $post_id );
                    $imgs = wp_get_attachment_image_url( $imgs, $size );
    
                    // É válido?
                    if ( $imgs !== false ) return $imgs;
                }
            }
    
            // Insira outras extensões aqui
    
            // Plugin Guest Author (Exemplo)
            $meta_author = get_post_meta( $post_id, 'guest-author', true );
            if ( is_numeric( $meta_author ) && $meta_author > 0 ) {
                // Puxa imagem
                $imgs = post_images( $post_id, $size );
    
                // É válido?
                if ( $imgs !== false ) return $imgs;
            }
        }
        
        // Fallback como imagem principal
        return yx_post_image( $post_id, $size );
    }
}

if ( ! function_exists( 'yx_featured_image' ) ) {
    /**
     * Exibe a imagem destacada em posts e páginas.
     *
     * @param string $size
     *      Tamanho da imagem, deve ser uma das strings de tamanho suportadas
     *      pelo WordPress
     * @param string $class
     *      Classe a ser aplicada na imagem
     */
    function yx_featured_image(
        $size = 'full',
        $class = 'featured-image'
    ) {
        global $post;
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $attachment_id = get_post_thumbnail_id( $post->ID );
            
            // Aplica o título à imagem
            if (
                get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) === ''
            ) {
                // Não tem título
                $title = the_title_attribute(
                    array(
                        'before' => __( 'Imagem do Artigo', THEME_DOMAIN ),
                        'echo' => false
                    )
                );
            } else {
                $title = trim(
                    strip_tags(
                        get_post_meta(
                            $attachment_id,
                            '_wp_attachment_image_alt',
                            'true'
                        )
                    )
                );
            }
            
            // Adicione a classe
            if ( $class != 'featured-image' ) $class .= ' featured-image';
            
            // Verifica dimensões (define se é paisagem/retrato)
            $dimensions = wp_get_attachment_image_src( $attachment_id, $size );
            $image_w = $dimensions[1];
            $image_h = $dimensions[2];
            
            if ( $image_h > $image_w ) {
                // Retrato
                $class .= 'vertical-image';
            } elseif ( $image_w > $image_h ) {
                // Paisagem
                $class .= 'horizontal-image';
            } else {
                // Quadrado
                $class .= 'square-image';
            }
            
            // Define atributos
            $args = array(
                'class' => $class,
                'alt' => $title
            );
            
            the_post_thumbnail( $size, $args );
        }
    }
}

if ( ! function_exists( 'yx_featured_image_link' ) ) {
    /**
     * Retorna a URL da imagem destacada para posts e páginas.
     *
     * @param int $post_id
     *      ID do post
     * @param string $size
     *      Tamanho da imagem, deve ser uma das strings de tamanho suportadas
     *      pelo WordPress
     * @return string|null
     */
    function yx_featured_image_link( $post_id, $size = 'full' )
    {
        $imgs = get_post_thumbnail_id( $post_id );
        if ( '' != trim( $imgs ) && is_numeric( $imgs ) ) {
            $link = wp_get_attachment_image_src( $imgs, $size );
            
            return $link[0];
        }
    
        return null;
    }
}

/* FEATURES DO WORDPRESS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_has_logo' ) ) {
    /**
     * Exibe o logo e/ou título e descrição do website.
     *
     * @param bool $description
     *      Se deve, ou não, exibir a descrição do website
     * @param bool $always_show_title
     *      Se, mesmo com logo, o título deve ser exibido
     */
    function yx_has_logo(
        $description = false,
        $always_show_title = false
    ) {
        $logo = '';
        
        // Se houver logo personalizado
        if ( function_exists( 'get_custom_logo' ) ) $logo = get_custom_logo();
        
        // Fallback para o título do site
        if ( empty( $logo ) || $always_show_title === true ) {
            $logo .= '<h1 class="site-title"><a href="'
                . esc_url( home_url( '/' ) ) . '" rel="home">'
                . get_bloginfo( 'name' ) . '</a></h1>';
        }
        
        // Exibir descrição?
        if ( $description && $always_show_title === true ) {
            $logo .= '<p class="site-description">'
                . get_bloginfo( 'description' ) . '</p>';
        }
        
        echo $logo;
    }
}

/* NAVEGAÇÃO
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_breadcrumbs' ) ) {
    /**
     * Imprime breadcrumbs simples no website.
     *
     * Lista de argumentos aceitos:
     * - `id`, string, deve ser a ID do breadcrumb no HTML;
     * - `class`, string, classe CSS a ser utilizada no breadcrumb;
     * - `wrap_before`, string, HTML que deve anteceder o breadcrumb;
     * - `wrap_after`, string, HTML que deve vir após o breadcrumb;
     * - `show_home`, bool, se deve ser exibido o link para a home do site;
     * - `show_on_home`, bool, se deve ser exibido na home do site;
     * - `show_current`, bool, se exibe, ou não, a página atual;
     *
     * @param array $args
     *      Array contendo argumentos para modificar breadcrumbs
     */
    function yx_breadcrumbs( $args = null )
    {
        # PROPRIEDADES GERAIS ----------------------------------------- #
    
        // Solicita globais de post e query
        global $post, $wp_query;
    
        // Exemplo de custom taxonomy para custom post types
        $custom_taxonomy = 'tipos';
    
        // ID da página inicial
        $frontpage_id = get_option( 'page_on_front' );
    
        // ID da página "pai" (quando página filha)
        $parent_id = ( $post ) ? $post->post_parent : false;
        
        // Variável de paginação
        $paged = get_query_var( 'paged' );
        
        // Se estamos em navegação paginada
        $is_paged = ( $paged ) ? true : false;
        
        # ARGUMENTOS DO BREADCRUMB ------------------------------------ #
        
        // Argumentos padrão
        $default_args = array(
            'id'            => 'breadcrumb',
            'class'         => 'breadcrumb-list',
            'wrap_before'   => '',
            'wrap_after'    => '',
            'show_home'     => true,
            'show_on_home'  => false,
            'show_current'  => true
        );
        
        // Realiza merge com argumentos fornecidos
        $args = ( is_array( $args ) )
            ? wp_parse_args( $args, $default_args )
            : $default_args;
    
        # TEMPLATES DE ATRIBUTOS -------------------------------------- #
    
        /**
         * Retorna string contendo o atributo `itemprop` de um elemento,
         * se declarado.
         *
         * @param string $prop
         *      Valor do atributo `itemprop`
         * @return string
         */
        $attr_itemprop = function( $prop = "" )
        {
            if ( $prop !== '' ) {
                return ' itemprop="' . trim( $prop ) . '"';
            }
            return "";
        };
    
        /**
         * Retorna string contendo os atributos `itemscope` e `itemtype`
         * devidamente preenchidos.
         *
         * @param string $type
         *      Nome do schema a ser apresentado como `itemtype`
         * @return string
         */
        $attr_itemtype = function( $type = "" )
        {
            if ( $type !== '' ) {
                return ' itemscope itemtype="https://schema.org/'
                    . trim( $type ) . '"';
            }
            return "";
        };
        
        # TEMPLATES DE ELEMENTOS DE LISTA ----------------------------- #
    
        /**
         * Retorna um elemento da lista do breadcrumb com propriedades e link,
         * quando necessário.
         *
         * @param string $text
         *      Texto do elemento de lista
         * @param string $href
         *      URL para o link, opcional
         * @param string $title
         *      Título do elemento, opcional
         * @return string
         *      String contendo o elemento de lista e link (quando declarado)
         */
        $listitem = function(
            string $text,
            string $href = "",
            string $title = ""
        ) use ( $attr_itemprop, $attr_itemtype ) {
            $link = '<li' . $attr_itemprop( "itemListElement" )
                . $attr_itemtype( 'ListItem' ) . '>';
            // `$href` foi declarado? Abre anchor
            if ( trim( $href ) ) {
                $link .= '<a href="' . trim( $href ) . '"';
                if ( $title !== '' ) {
                    $link .= ' title="' . trim( $title ) . '"';
                }
                $link .= $attr_itemprop( 'Item' ) . '>';
            }
            $link .= '<span' . $attr_itemprop( 'name' ) . '>'
                . trim( $text ) . '</span>';
            // `$href` foi declarado? Fecha anchor
            if ( trim( $href ) ) {
                $link .= '</a>';
            }
            $link .= '</li>';
            
            return $link;
        };
    
        # INICIA BREADCRUMB ------------------------------------------- #
    
        /**
         * Armazena breadcrumb enquanto monta.
         */
        $breadcrumb = array();
        
        // Abrindo o wrapper do breadcrumb, se houver um
        {
            if ( $args['wrap_before'] != '' ) {
                $breadcrumb[] = $args['wrap_before'];
            }
        }
        
        // Abrindo lista do breadcrumb
        {
            $breadcrumb[] = '<ul id="' . $args['id'] . '" class="'
                . $args['class'] . '"'
                . sprintf( $type_attr, 'BreadcrumbList' ) . '>';
        }
        
        // Monta links
        if ( is_home() || is_front_page() ) {
            // Home ou Front Page
            if ( $paged ) {
                // Exibir na home?
                if ( true === $args['show_on_home'] ) {
                    // Adiciona link da home
                    $breadcrumb[] = $listitem(
                        'Home',
                        home_url( '/' ),
                        'Home'
                    );
                }
            } else {
                // Exibir na home?
                if ( true === $args['show_on_home'] ) {
                    // Exibir página atual?
                    if ( $args['show_current'] ) {
                        $breadcrumb[] = $listitem( 'Home' );
                    }
                }
            }
        } else {
            // Exibir link da Home?
            if ( $args['show_home'] ) {
                $breadcrumb[] = $listitem( 'Home', home_url( '/' ), 'Home' );
            }
            
            // Condicionais para Breadcrumbs
            if ( is_category() ) {
                // Categoria
                
                // Checa se possui "pai"
                $cats = get_category(
                    get_query_var( 'cat' ),
                    false
                );
                
                // Há categorias acima?
                if ( $cats->parent != 0 ) {
                    // Solicita parents
                    $parents = get_category_parents( $cats->parent, true, '§' );
                    $parents = preg_replace( "/^(.+)§$/", "$1", $parents );
                    $parents = explode( '§', $parents );
                    
                    // Adiciona categorias pai
                    foreach ( $parents as $item ) {
                        if ( trim( $item ) != '' ) {
                            // Extrai URL e Título
                            preg_match(
                                '/<a([^>]+)>([^<]+)<\/a>/',
                                $item,
                                $item_data
                            );
        
                            // Adiciona item ao breadcrumb
                            $breadcrumb[] = $listitem(
                                trim( $item_data[2] ),
                                preg_replace(
                                    '/href\=\"(.*)\"/',
                                    '$1',
                                    trim( $item_data[1] )
                                ),
                                trim( $item_data[2] )
                            );
                        }
                    }
                }
                
                // Há paginação?
                if ( $is_paged ) {
                    $breadcrumb[] = $listitem(
                        get_cat_name( $cats->cat_ID ),
                        get_category_link( $cats->cat_ID ),
                        'Arquivos para a categoria: ' . get_cat_name( $cats->cat_ID )
                    );
                } else {
                    $breadcrumb[] = $listitem(
                        get_cat_name( $cats->cat_ID )
                    );
                }
            } elseif ( is_tag() ) {
                // Se tag
    
                // ID da tag
                $tag_id = get_queried_object_id();
    
                // Solicita a tag
                $tag = get_tag( $tag_id );
    
                // Verifica se há paginação
                if ( $is_paged ) {
        
                    // Adiciona link para a tag
                    $breadcrumb[] = $listitem(
                        'Tag: <em>' . $tag->name . '</em>',
                        get_tag_link( $tag_id ),
                        'Posts marcados em: ' . $tag->name
                    );
                } else {
                    if ( $args['show_current'] ) {
                        $breadcrumb[] = $listitem(
                            'Tag: <em>' . $tag->name . '</em>'
                        );
                    }
                }
            } elseif ( is_author() ) {
                // Se página de autor
    
                // Global de autor
                global $author;
    
                // Solicita dados do autor
                $author_data = get_userdata( $author );
    
                // Tem paginação?
                if ( $is_paged ) {
                    // Link para a home do autor
                    $breadcrumb[] = $listitem(
                        'Autor: ' . $author_data->display_name,
                        get_author_posts_url( $author ),
                        'Postagens de ' . $author_data->display_name
                    );
                } else {
                    if ( $args['show_current'] ) {
                        $breadcrumb[] = $listitem(
                            'Autor: <em>' . $author_data->display_name
                            . '</em>'
                        );
                    }
                }
            } elseif ( is_day() || is_month() || is_year() ) {
                // Arquivo: Datas (Dia/Mês/Ano)
                
                // Ano
                $breadcrumb[] = $listitem(
                    get_the_time( 'Y' ),
                    get_year_link( get_the_time( 'Y' ) ),
                    ( is_year() )
                        ? 'Arquivos: ' . get_the_time( 'Y' )
                        : get_the_time( 'Y' )
                );
                
                // Mês
                if ( is_month() || is_day() ) {
                    $breadcrumb[] = $listitem(
                        ucfirst( get_the_time( 'F' ) ),
                        get_month_link(
                            get_the_time( 'Y' ),
                            get_the_time( 'm' )
                        ),
                        ( is_month() )
                            ? 'Arquivos: ' . ucfirst( get_the_time( 'F' ) )
                            : ucfirst( get_the_time( 'F' ) )
                    );
                }
                
                // Dia
                if ( is_day() ) {
                    $breadcrumb[] = $listitem(
                        'Dia: ' . get_the_time( 'd' ),
                        get_day_link(
                            get_the_time( 'Y' ),
                            get_the_time( 'm' ),
                            get_the_time( 'd' )
                        ),
                        ( is_day() )
                            ? 'Arquivos: ' . ucfirst( get_the_time( 'd/m/Y' ) )
                            : ucfirst( get_the_time( 'd/m/Y' ) )
                    );
                }
            } elseif ( is_tax( 'post_format' ) ) {
                // Se for taxonomia de formato de post
                
                // Solicita qual taxonomia
                $query_object = get_queried_object();
    
                // É paginado?
                if ( $is_paged ) {
                    $breadcrumb[] = $listitem(
                        'Formato de Post: <em>' . $query_object->name . '</em>',
                        get_term_link( $query_object->term_id, 'post_format' ),
                        'Formato de Post: ' . $query_object->name
                    );
                } else {
                    $breadcrumb[] = $listitem(
                        'Formato de Post: <em>' . $query_object->name . '</em>'
                    );
                }
            } elseif ( is_post_type_archive() ) {
                // Página de arquivo para post type personalizado
    
                // Solicita objeto de query
                $query_object = get_queried_object();
                
                
                // É paginado?
                if ( $is_paged ) {
                    $breadcrumb[] = $listitem(
                        post_type_archive_title( '', false ),
                        get_post_type_archive_link( $query_object->name ),
                        'Arquivos : ' . post_type_archive_title( '', false )
                    );
                } else {
                    $breadcrumb[] = $listitem(
                        post_type_archive_title( '', false )
                    );
                }
            } elseif ( is_tax() ) {
                // Se for uma taxonomia
    
                // Solicita termo da taxonomia
                $query_object = get_queried_object();
                
                // Solicita taxonomia
                $taxonomy = get_taxonomy( $query_object->taxonomy );
                
                // É paginado?
                if ( $is_paged ) {
                    $breadcrumb[] = $listitem(
                        $taxonomy->labels->singular_name . ': '
                            . $query_object->name,
                        get_term_link(
                            $query_object->term_id,
                            $taxonomy->name
                        )
                    );
                } else {
                    $breadcrumb[] = $listitem(
                        $taxonomy->labels->singular_name . ': '
                            . $query_object->name
                    );
                }
            } elseif ( is_search() ) {
                // Is Search
                if ( $is_paged ) {
                    $breadcrumb[] = $listitem(
                        'Resultados da busca para: <em>'
                            . get_search_query() . '</em>',
                        get_search_link(),
                        'Resultados da busca para: <em>'
                            . get_search_query() . '</em>'
                    );
                } else {
                    $breadcrumb[] = $listitem(
                        'Resultados da busca para: <em>'
                            . get_search_query() . '</em>'
                    );
                }
            } elseif ( is_single() && ! is_attachment() ) {
                // Single post, mas que não seja attachment
                if ( get_post_type() != 'post' ) {
                    // Se não for 'post'
                    
                    // Solicita objeto do post type
                    $post_type = get_post_type_object( get_post_type() );
                    
                    // Solicita slug
                    $slug = $post_type->rewrite;
                    
                    // Exibe link para arquivo do post type
                    $breadcrumb[] = $listitem(
                        $post_type->labels->singular_name,
                        home_url( '/' ) . $slug['slug'] . '/',
                        $post_type->labels->singular_name
                    );
                    
                    // Adiciona item atual
                    if ( $args['show_current'] ) {
                        $breadcrumb[] = $listitem(
                            get_the_title()
                        );
                    }
                } else {
                    // Se post
                    
                    // Solicita categoria
                    $cats = get_the_category();
                    $cats = get_category_parents( $cats[0], true, '§' );
                    /**
                     * IMPORTANTE:
                     * Aqui temos como intenção montar um sistema hierárquico
                     * com as categorias!
                     *
                     * Por isso solicitamos APENAS a primeira categoria!
                     *
                     * Remova e modifique caso não seja desejável.
                     */
                    $cats = preg_replace( "/^(.+)§$/", "$1", $cats );
                    $cats = explode( '§', $cats );
                    
                    foreach ( $cats as $item ) {
                        if ( trim( $item ) != '' ) {
                            // Extrai URL e Título
                            preg_match(
                                '/<a([^>]+)>([^<]+)<\/a>/',
                                $item,
                                $item_data
                            );
    
                            // Adiciona item ao breadcrumb
                            $breadcrumb[] = $listitem(
                                trim( $item_data[2] ),
                                preg_replace(
                                    '/href\=\"(.*)\"/',
                                    '$1',
                                    trim( $item_data[1] )
                                ),
                                trim( $item_data[2] )
                            );
                        }
                    }
                    
                    // Estamos paginando comentários?
                    if ( get_query_var( 'cpage' ) ) {
                        // Exibe link do post
                        $breadcrumb[] = $listitem(
                            get_the_title(),
                            get_permalink(),
                            get_the_title()
                        );
                        
                        // Exibe página de comentários atual
                        $breadcrumb[] = $listitem(
                            'Página de Comentários: <em>'
                                . get_query_var( 'cpage' ) . '</em>'
                        );
                    } else {
                        // Adiciona o link atual
                        if ( $args['show_current'] ) {
                            $breadcrumb[] = $listitem(
                                get_the_title()
                            );
                        }
                    }
                }
            } elseif (
                !is_single()
                && !is_page()
                && get_post_type() != 'post'
                && !is_404()
            ) {
                // Se for qualquer coisa
                
                // Solicita post type e o objeto de post type
                $post_type = get_post_type();
                $post_object = get_post_type_object( $post_type );
                
                // Se válido
                if ( null !== $post_object && false !== $post_object ) {
                    // Há paginação?
                    if ( $is_paged ) {
                        $breadcrumb[] = $listitem(
                            $post_object->label,
                            get_post_type_archive_link( $post_type->name ),
                            $post_object->label
                        );
                    } else {
                        // Exibir link atual?
                        if ( $args['show_current'] ) {
                            $breadcrumb[] = $listitem(
                                $post_object->label
                            );
                        }
                    }
                } else {
                    // Se post object for inválido, pode ser taxonomia
                    $tax = get_queried_object();
                    
                    // Adiciona link atual
                    if ( $args['show_current'] ) {
                        $breadcrumb[] = $listitem( $tax->name );
                    }
                }
            } elseif ( is_attachment() ) {
                // Se anexo (attachment)
                
                // Solicita o ID da página pai
                $attachment_parent = get_post( $parent_id );
                
                // Solicita categoria
                $cats = get_the_category( $attachment_parent->ID );
                $cats = $cats[0];
                
                // Se houver categoria
                if ( $cats ) {
                    // Solicita categoria(s)
                    $cats = get_category_parents( $cats, true, '§' );
                    $cats = preg_replace( "/^(.+)§$/", "$1", $cats );
                    $cats = explode( '§', $cats );
                    
                    // Havendo categorias
                    foreach ( $cats as $item ) {
                        // Extrai URL e Título
                        preg_match(
                            '/<a([^>]+)>([^<]+)<\/a>/',
                            $item,
                            $item_data
                        );
                        
                        // Adiciona item ao breadcrumb
                        $breadcrumb[] = $listitem(
                            trim( $item_data[2] ),
                            preg_replace(
                                '/href\=\"(.*)\"/',
                                '$1',
                                trim( $item_data[1] )
                            ),
                            trim( $item_data[2] )
                        );
                    }
                }
                
                // Adiciona link de página pai
                $breadcrumb[] = $listitem(
                    $attachment_parent->post_title,
                    get_permalink( $attachment_parent ),
                    $attachment_parent->post_title
                );
                
                // Exibe link atual
                if ( $args['show_current'] ) {
                    $breadcrumb[] = $listitem( get_the_title() );
                }
            } elseif ( is_page() && !$parent_id ) {
                // Se página "single" sem "Pai"
                if ( $args['show_current'] ) {
                    $breadcrumb[] = $listitem(
                        get_the_title()
                    );
                }
            } elseif ( is_page() && $parent_id ) {
                // Se página "single" com "Pai"
                
                // ID do pai é front page?
                if ( $parent_id != $frontpage_id ) {
                    // Array temporário para breadcrumb de parents
                    $crumbs = array();
                    
                    // Enquanto `$parent_id` for válido
                    while ( $parent_id ) {
                        // Solicita objeto de página
                        $page = get_post( $parent_id );
                        
                        // Se parent ID não for o de front page, adiciona
                        if ( $parent_id != $frontpage_id ) {
                            $crumbs[] = $listitem(
                                get_the_title( $page->ID ),
                                get_permalink( $page->ID ),
                                get_the_title( $page->ID )
                            );
                        }
                        
                        // Altera parent ID
                        $parent_id = $page->post_parent;
                    }
                    
                    // Inverte ordem para exibição
                    $crumbs = array_reverse( $crumbs );
                    
                    // Realiza o merge no array principal
                    $breadcrumb = array_merge( $breadcrumb, $crumbs );
                }
                
                // Se exibe página atual
                if ( $args['show_current'] ) {
                    $breadcrumb[] = $listitem(
                        get_the_title()
                    );
                }
            } elseif ( is_404() ) {
                // Se 404
                if ( true === $args['show_current'] ) {
                    $breadcrumb[] = $listitem( '404' );
                }
            } elseif ( has_post_format() && !is_singular() ) {
                // Se arquivos para um formato de post específico
                $breadcrumb[] = $listitem(
                    get_post_format_string( get_post_format() )
                );
            } elseif ( is_archive() ) {
                $breadcrumb[] = $listitem(
                    'Arquivos'
                );
            }
        }
        
        // Se navegação paginada, sempre adiciona identificação da página
        if ( $is_paged ) {
            $breadcrumb[] = $listitem(
                'Página ' . $paged
            );
        }
        
        // Fecha breadcrumb
        {
            $breadcrumb[] = '</ul>';
        }
    
        // Fecha o wrapper do breadcrumb (se houver um)
        {
            if ( $args['wrap_after'] != '' ) {
                $breadcrumb[] = $args['wrap_after'];
            }
        }
        
        # FECHA BREADCRUMB -------------------------------------------- #
        
        echo implode( "", $breadcrumb );
    }
}

if ( ! function_exists( 'yx_content_nav' ) ) {
    /**
     * Exibe uma navegação simples para próximo post/post anterior, quando
     * aplicável.
     *
     * @param string $nav_id
     *      ID da barra de navegação, deve ser único, para identificação no
     *      template final
     */
    function yx_content_nav( $nav_id )
    {
        // Solicita globais de query e post
        global $wp_query, $post;
        
        // Não exibe markup vazia, em páginas 'single', se não houve navegação
        if ( is_single() ) {
            // Verifica anterior/próximo
            $prev = ( is_attachment() )
                ? get_post( $post->post_parent )
                : get_adjacent_post( false, '', true );
            $next = get_adjacent_post( false, '', true );
            // Se ambos forem falsos, para
            if ( ! $next && ! $prev ) return;
        }
        
        // Não exibe markup em arquivos, se houver apenas 1 página
        if (
            $wp_query->max_num_pages < 2
            && ( is_home() || is_archive() || is_search() )
        ) {
            return;
        }
        
        // Define classe da navegação
        $nav_class = 'site-navigation';
        $nav_class .= ( is_single() )
            ? ' post-navigation'
            : ' paging-navigation';
        
        // Array com navegação final
        $navigation = array();
        
        // Abre navegação
        $navigation[] = '<nav id="' . $nav_id . '" class="'
            . $nav_class . '">';
        
        // Título para screen-readers
        $navigation[] = '<h3 class="assistive-text">'
            . __( 'Navegação', THEME_DOMAIN )
            . '</h3>';
        
        // Links de posts
        if ( is_single() ) {
            // Anterior
            if ( get_previous_post_link() ) {
                $navigation[] = get_previous_post_link(
                    '<div class="nav-previous">%link</div>',
                    '<span class="meta-nav">'
                    . _x( '&larr;', 'Post Anterior', THEME_DOMAIN )
                    . '</span> %title'
                );
            }
            
            // Próximo
            if ( get_next_post_link() ) {
                $navigation[] = get_next_post_link(
                    '<div class="nav-next">%link</div>',
                    '%title <span class="meta-nav">'
                    . _x('&rarr;', 'Próximo Post', THEME_DOMAIN)
                    . '</span>'
                );
            }
        } elseif (
            $wp_query->max_num_pages > 1
            && ( is_home() || is_archive() || is_search() )
        ) {
            // Página Anterior
            if ( get_previous_posts_link() ) {
                $navigation[] = '<div class="nav-previous">';
                $navigation[] = get_previous_posts_link(
                    '<span class="meta-nav">'
                    . _x( '&larr;', 'Postagens Recentes', THEME_DOMAIN )
                    . '</span>'
                    . __( 'Postagens Recentes', THEME_DOMAIN )
                );
                $navigation[] = '</div>';
            }
            
            // Próxima Página
            if ( get_next_posts_link() ) {
                $navigation[] = '<div class="nav-next">';
                $navigation[] = get_next_posts_link(
                    __( 'Postagens Antigas', THEME_DOMAIN )
                    . '<span class="meta-nav">'
                    . _x( '&rarr;', 'Postagens Antigas', THEME_DOMAIN )
                    . '</span>'
                );
                $navigation[] = '</div>';
            }
        }
        
        // Fecha navegação
        $navigation[] = '</nav><!-- #' . $nav_id . ' -->';
        
        echo implode( "", $navigation );
    }
}

/* COMENTÁRIOS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_comment_title' ) ) {
    /**
     * Imprime o texto de título da seção "Comentários", precedendo a lista
     * contendo os mesmos.
     */
    function yx_comment_title()
    {
        $comments_number = get_comments_number();
        
        printf(
            _n(
                'Um comentário em &ldquo;%2$s&rdquo;',
                '%1$s comentários em &ldquo;%2$s&rdquo;',
                $comments_number,
                THEME_DOMAIN
            ),
            number_format_i18n( $comments_number ),
            '<span>' . get_the_title() . '</span>'
        );
    }
}

if ( ! function_exists( 'yx_comment_template' ) ) {
    /**
     * Callback para modificar o template de comentários, pingbacks e trackbacks.
     *
     * Utilize como argumento para a função `wp_list_comments()`.
     *
     * O método abre uma tag (definida no argumento `style`), mas não há a
     * necessidade de fechá-la, visto que é realizado automaticamente
     * pelo WordPress.
     *
     * Tags aninhadas, porém, PRECISAM ser devidamente fechadas.
     *
     * @param WP_Comment $comment
     *      Objeto de comentário
     * @param array $args
     *      Argumentos de exibição
     * @param int $depth
     *      Profundidade máxima para aninhamento de comentários
     */
    function yx_comment_template( $comment, $args, $depth )
    {
        if ( 'div' === $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        
        // Define a global de comentários, para uso externo/posterior (?)
        $GLOBALS['comment'] = $comment;
        
        // Array de comentários
        $comment_body = array();
        $comment_class = null;
    
        // Define URL para edição
        $comment_edit_uri = get_edit_comment_link(
            $comment->comment_ID
        );
        
        // Verifica o tipo de comentário
        switch ( $comment->comment_type ) {
            // Pingbacks
            case 'pingback':
                // Define ID
                $comment_id = 'pingback-' . get_comment_ID();
    
                // Define classe
                $comment_class = comment_class(
                    'yx-pingback',
                    $comment->comment_ID,
                    $comment->comment_post_ID,
                    false
                );
    
                // Define
                $comment_body[] = '<' . $tag . ' id="' . $comment_id
                    .'" ' . $comment_class . '>';
                $comment_body[] = '<p>' . __( 'Pingback', THEME_DOMAIN )
                    . ' ' . get_comment_author_link()
                    . ' <a href="' . $comment_edit_uri . '">'
                    . __( '[Editar]', THEME_DOMAIN ).'</a>'
                    . '</p>';
                break;
            // Trackback
            case 'trackback':
                // Define ID
                $comment_id = 'trackback-' . get_comment_ID();
                
                // Define classe
                $comment_class = comment_class(
                    'yx-trackback',
                    $comment->comment_ID,
                    $comment->comment_post_ID,
                    false
                );
                
                // Define
                $comment_body[] = '<' . $tag . ' id="' . $comment_id
                    .'" ' . $comment_class . '>';
                $comment_body[] = '<p>' . __( 'Trackback', THEME_DOMAIN )
                    . ' ' . get_comment_author_link()
                    . ' <a href="' . $comment_edit_uri . '">'
                    . __( '[Editar]', THEME_DOMAIN ).'</a>'
                    . '</p>';
                break;
            // Comentário
            default:
                // Define ID
                $comment_id = 'comment-' . get_comment_ID();
                
                // Começa a definir item
                $comment_body[] = '<' . $tag . ' id="' . $comment_id
                    . '" ' . $comment_class . '>';
    
                {
                    // Avatar do Comentário
                    $comment_body[] = '<div class="comment-avatar">';
                    $comment_body[] = '<figure>';
                    $comment_body[] = get_avatar( $comment, 40 );
                    $comment_body[] = '</figure>';
                    $comment_body[] = '</div>';
                }
                
                // Conteúdo do comentário
                $comment_body[] = '<div class="comment-body">';
            
                {
                    
                    // Rodapé do comentário
                    $comment_body[] = '<footer>';
                    
                    // VCard
                    $comment_body[] = '<div class="comment-author vcard">';
                    $comment_body[] = sprintf(
                        __(
                            '<h6>%s <span class="says">Diz:</span></h6>',
                            THEME_DOMAIN
                        ),
                        sprintf(
                            '<cite class="fn">%s</cite>',
                            get_comment_author_link()
                        )
                    );
                    $comment_body[] = '</div>';
                    
                    // Status
                    if ( $comment->comment_approved == '0' ) {
                        $comment_body[] = '<p class="comment-not-approved">';
                        $comment_body[] = '<em>';
                        $comment_body[] = __(
                            'Comentário aguardando moderação',
                            THEME_DOMAIN
                        );
                        $comment_body[] = '</em>';
                        $comment_body[] = '</p>';
                    }
                    
                    $comment_body[] = '<div class="comment-meta commentmetadata">';
                    
                    {
                        // Solicitando URI e timestamps de comentário
                        $comment_uri = esc_url(
                            get_comment_link( $comment->comment_ID )
                        );
                        $comment_timestamp = get_comment_time( 'c' );
                        $comment_time = get_comment_time();
                        $comment_date = get_comment_date();
                        
                        
                        $comment_body[] = '<a href="' . $comment_uri . '">';
                        $comment_body[] = '<time datetime="'
                            . $comment_timestamp . '">';
                        // Para tradutores: %1 = data, %2 = horário
                        $comment_body[] = sprintf(
                            __( '%1$s às %2$s', THEME_DOMAIN ),
                            $comment_date,
                            $comment_time
                        );
                        $comment_body[] = '</time>';
                        $comment_body[] = '</a>';
                        
                        // Link de edição
                        $comment_body[] = '<a href="' . $comment_edit_uri . '">';
                        $comment_body[] = __( '[Editar]', THEME_DOMAIN );
                        $comment_body[] = '</a>';
                    }
                    $comment_body[] = '</div>';
                    $comment_body[] = '</footer>';
                    
                    // Corpo de texto do comentário
                    $comment_body[] = '<div class="comment-content">';
                    $comment_body[] = get_comment_text();
                    $comment_body[] = '</div>';
                    
                    // Link de réplica
                    $comment_body[]= '<div class="comment-reply reply">';
                    $comment_body[] = get_comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth']
                            )
                        )
                    );
                    $comment_body[] = '</div>';
                }
                
                // Fecha conteúdo do comentário
                $comment_body[] = '</div>';
                break;
        }
        
        echo implode( "", $comment_body );
    }
}

/* PÁGINAS, POSTS, ARQUIVOS E TAGS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_categorized_blog' ) ) {
    /**
     * Verifica se o blog/site possui mais de uma categoria.
     *
     * Retorna `true` apenas se houver mais de uma categoria.
     *
     * O transiente precisa ser limpo após o seu uso, por favor verifique
     * a função `yx_categorized_transient_flush` em `hooks.php`.
     *
     * @return bool
     */
    function yx_categorized_blog()
    {
        if (
            false === (
                $all_the_cool_cats = get_transient( 'all_the_cool_cats' )
            )
        ) {
            // Cria um array com todas as categorias vinculadas à posts
            $all_the_cool_cats = get_categories(
                array(
                    'hide_empty' => 1
                )
            );
            
            // Conta e define transiente
            $all_the_cool_cats = count( $all_the_cool_cats );
            set_transient( 'all_the_cool_cats', $all_the_cool_cats );
        }
        
        // Verifica transiente
        if ( '1' != $all_the_cool_cats && 1 != $all_the_cool_cats ) return true;
        
        // Havendo apenas 1 categoria, retorna false
        return false;
    }
}

if ( ! function_exists( 'yx_archives_title' ) ) {
    /**
     * Imprime uma versão especial do título para páginas de arquivos.
     *
     * Vale para os seguintes tipos de páginas:
     * - Categoria;
     * - Tag;
     * - Autor;
     * - Buscas;
     * - Arquivo (Dia);
     * - Arquivo (Mês);
     * - Arquivo (Ano);
     */
    function yx_archives_title()
    {
        // Título padrão do site
        $title = __( 'Arquivos', THEME_DOMAIN );
        
        // Variáveis de data
        $dia = null;
        $mes = null;
        $ano = null;
        
        // Pré-declarando variáveis de data, caso sejam usadas
        if ( is_day() || is_month() || is_year() ) {
            $dia = get_the_date( 'd' );
            $mes = get_the_date( 'm' );
            $ano = get_the_date( 'Y' );
        }
        
        // Condicionais
        if ( is_category() ) {
            /* ARQUIVO DE CATEGORIA
             * ------------------------------------------------------- */
            
            /**
             * Isso é algo que fiz para um mini portal de notícias, uma vez que
             * a parte de colunistas não deveria conter o breadcrumb de categorias.
             *
             * Caso seja `Colunistas` ele simplesmente para, caso seja uma categoria
             * "pai" e o pai for `Colunistas`, ele monta um breadcrumb.
             *
             * Normalmente você pode remover esse condicional, deixando
             * simplesmente, um:
             * $title = sprintf(
             *     __( '%s', TEXT_DOMAIN ),
             *     '<span>' . single_cat_title( '', false ) . '</span>'
             * );
             */
            if ( single_cat_title( '', false ) === 'Colunistas' ) {
                $title = '<span>' . single_cat_title( '', false ) . '</span>';
            } else {
                // Solicita o ID da categoria e as categorias "pai"
                $category_id = get_cat_ID( single_cat_title( '', false ) );
                $parent_categories = explode(
                    '/',
                    trim( get_category_parents( $category_id ), '/' )
                );
                
                // Status de categoria pai
                $is_colunista = false;
                
                // Verifica se tem parente, e se é "Colunista"
                foreach ( $parent_categories as $parent ) {
                    if ( $parent === "Colunistas" ) {
                        $is_colunista = true;
                    }
                }
                
                // Se for colunista, monta título diferenciado
                if ( $is_colunista ) {
                    // É um colunista
                    $title = sprintf(
                        __(
                            '<em>Colunistas</em>/%s',
                            THEME_DOMAIN
                        ),
                        '<span>' . single_cat_title( '', false ) . '</span>'
                    );
                } else {
                    // É uma categoria comum
                    $title = sprintf(
                        __( '%s', THEME_DOMAIN ),
                        '<span>' . single_cat_title( '', false ) . '</span>'
                    );
                }
            }
        } elseif ( is_tag() ) {
            /* ARQUIVO DE TAG
             * ------------------------------------------------------- */
            $title = sprintf(
                __( 'Tag : %s', THEME_DOMAIN ),
                '<span>' . single_tag_title( '', false ) . '</span>'
            );
        } elseif ( is_author() ) {
            /* ARQUIVO DE AUTOR
             * ------------------------------------------------------- */
            
            // Puxa o primeiro post, para extrair autor
            the_post();
            
            // Solicita dados e define link
            $author_id      = get_the_author_meta( 'ID' );
            $author_url     = esc_url( get_author_posts_url( $author_id ) );
            $author_name    = esc_attr( get_the_author() );
            $author_link    = '<a class="url f n author-link" href="'
                . $author_url . '" title="' . $author_name
                . '" rel="author">' . $author_name . '</a>';
            
            $title = sprintf(
                __( 'Author : %s', THEME_DOMAIN ),
                '<span class="vcard author-vcard">' . $author_link . '</span>'
            );
            
            /**
             * Como usamos `the_post()` para solicitar dados, precisamos dar
             * rewind para que o loop do WordPress funcione devidamente.
             */
            rewind_posts();
        } elseif ( is_search() ) {
            /* BUSCA
             * ------------------------------------------------------- */
            $search = get_search_query();
            
            $title = sprintf(
                __( 'Resultados da busca para: %1s', THEME_DOMAIN ),
                "<strong>{$search}</strong>"
            );
        } elseif ( is_day() ) {
            /* ARQUIVOS : DIA
             * ------------------------------------------------------- */
            $title = sprintf(
                __( 'Arquivos : Dia : %1s/%2s/%3s', THEME_DOMAIN ),
                $dia,
                $mes,
                $ano
            );
        } elseif ( is_month() ) {
            /* ARQUIVOS : MÊS
             * ------------------------------------------------------- */
            $title = sprintf(
                __( 'Arquivos : Mês : %1s/%2s', THEME_DOMAIN ),
                $mes,
                $ano
            );
        } elseif ( is_year() ) {
            /* ARQUIVOS : ANO
             * ------------------------------------------------------- */
            $title = sprintf(
                __( 'Arquivos : Ano : %s', THEME_DOMAIN ),
                $ano
            );
        }
        
        echo $title;
    }
}

if ( ! function_exists( 'yx_posted_on' ) ) {
    /**
     * Exibe o "byline" do post, indicando data, hora, links para arquivos de
     * data e página do autor.
     */
    function yx_posted_on()
    {
        // Define valores
        $link_href          = esc_url( get_permalink() );
        $link_time          = esc_attr( get_the_time() );
        $link_timestamp     = esc_attr( get_the_time( 'c' ) );
        $link_date          = esc_html( get_the_date() );
        $link_author_name   = esc_html( get_the_author() );
        $link_author_meta   = get_the_author_meta( 'ID' );
        $link_author_url    = esc_url(
            get_author_posts_url( $link_author_meta )
        );
        $link_text          = esc_attr(
            sprintf(
                __(
                    'Visualizar todos os posts de %s',
                    THEME_DOMAIN
                ),
                $link_author_name
            )
        );
        
        // Imprime
        printf(
            __(
                'Postado em <a href="%1$s" title="%2$s" rel="bookmark">'
                . '<time class="entry-date" datetime="%3$s">%4$s</time>'
                . '</a><span class="byline"> por <span class="author vcard">'
                . '<a class="url fn n" href="%5$s" title="%6$s" rel="author">'
                . '%7$s</a></span></span>',
                THEME_DOMAIN
            ),
            $link_href,
            $link_time,
            $link_timestamp,
            $link_date,
            $link_author_url,
            $link_text,
            $link_author_name
        );
    }
}

/* REDES SOCIAIS E WEB SERVICES
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'youtube_image_url' ) ) {
    /**
     * Retorna a URL de uma thumbnail de vídeo do YouTube.
     *
     * @param string $video_id
     *      ID de vídeo do YouTube
     * @param string $size
     *      Tamanho da imagem (small, medium ou big)
     * @return string
     *      String com a URL da imagem
     */
    function youtube_thumb_url( $video_id, $size = 'small' )
    {
        // URL do CDN de screenshots
        $url = "https://img.youtube.com/vi/";
        
        if ( $size !== 'small' && $size !== 'medium' && $size !== 'big' ) {
            $size = 'medium';
        }
        
        // Define o nome da imagem
        switch ( $size ) {
            case 'big':
                $image = 'maxresdefault.jpg';
                break;
            case 'medium':
                $image = '0.jpg';
                break;
            default:
                $image = '1.jpg';
                break;
        }
        
        // Retorna link para imagem
        return $url . trim( $video_id ) . '/' . $image;
    }
}

if ( ! function_exists( 'extract_youtube_videoid' ) ) {
    /**
     * Extrai o ID de vídeo de um dos seguintes meios de compartilhamento do
     * YouTube:
     * - URL;
     * - Short URL;
     * - Iframe embed;
     * - Object embed;
     *
     * Código para PHP e outras linguagens em:
     * https://gist.github.com/yuigoto/875d23c3f9f7b1f9624b2b57a2983d97
     *
     * @param string $url_or_embed
     * @return bool|mixed
     */
    function extract_youtube_videoid( $url_or_embed )
    {
        $regex_frag_a = "(youtu\.be|youtube\.com)\/(watch\?(.*&)?v=|(embed|v)\/)";
        $regex_frag_b = "?([^\?&\"'>\r\n]+)";
        $regex_complete = "/{$regex_frag_a}{$regex_frag_b}/";
        $matches = array();
        preg_match( $regex_complete, $url_or_embed, $matches );
        if ( isset( $matches[5] ) ) return $matches[5];
        return false;
    }
}
