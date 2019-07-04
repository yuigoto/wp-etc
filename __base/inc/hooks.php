<?php if ( ! defined( 'ABSPATH' ) ) die ( 'Acesso direto ao arquivo negado.' );

/**
 * Hooks
 * ----------------------------------------------------------------------
 * Utilize este arquivo para inclusão de actions e filtros do tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/* CANONICAL URLS
 * ------------------------------------------------------------------- */
// Remove filtro para evitar redirecionamento canônico
remove_filter('template_redirect', 'redirect_canonical');

/* CORE SETUP
 * ------------------------------------------------------------------- */

if ( ! function_exists( '_yx_core_setup' ) ) {
    /**
     * Setup do tema, executa em `after_setup_theme`, antes do hook
     * `init`, para que funcione como deve/como esperado.
     *
     * Define suporte à alguns features, post types e outras definições
     * do WordPress e plugins também.
     */
    function _yx_core_setup()
    {
        /* ATIVANDO TRADUÇÕES
         * ----------------------------------------------------------- */
        load_theme_textdomain(
            THEME_DOMAIN,
            get_template_directory() . '/languages'
        );

        /* FEATURES DO TEMA
         * ----------------------------------------------------------- */

        // Links de feeds RSS no cabeçalho para posts e comments
        add_theme_support( 'automatic-feed-links' );

        // Suporte a title tag
        add_theme_support( 'title-tag' );

        // Custom logo
        if ( function_exists( 'get_custom_logo' ) ) {
            add_theme_support( 'custom-logo' );
        }

        // Marcação HTML5 para recursos
        add_theme_support(
            'html5',
            array(
                'caption',
                'comment-form',
                'comment-list',
                'gallery',
                'search-form'
            )
        );

        // Featured content
        add_theme_support(
            'featured-content',
            array(
                // Declare o filtro separadamente!
                'featured_content_filter' => 'yx_get_featured_posts',
                'max_posts' => 6
            )
        );

        // Custom background
        {
            // Argumentos
            $args = array(
                'default-color' => 'ffffff'
            );

            // Aplica filtro nos argumentos (declare separadamente)
            $args = apply_filters('yx_custom_background_args', $args);

            // Adiciona suporte
            add_theme_support('custom-background', $args);
        }

        // Imagens destacadas
        add_theme_support( 'post-thumbnails' );

        // Menus
        add_theme_support( 'menu' );

        /* FORMATOS DE POST
         * ----------------------------------------------------------- */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'audio',
                'gallery',
                'image',
                'link',
                'quote',
                'video'
            )
        );

        /* POST FEATURES
         * ----------------------------------------------------------- */

        // Excerpt em posts
        add_post_type_support( 'page', 'excerpt' );

        /* TAMANHOS DE IMAGEM PERSONALIZADOS
         * ----------------------------------------------------------- */
        {
            add_image_size( 'yx-full-hd', 1920, 1080, true );
            add_image_size( 'yx-standard-hd', 1280, 720, true );
        }

        /* ESTILOS DE GALERIA PERSONALIZADOS
         * ----------------------------------------------------------- */

        // Se o tema usa estilos próprios para galeria
        // add_filter( 'use_default_gallery_style', '__return_false' );

        /* WOOCOMMERCE
         * ----------------------------------------------------------- */
        // add_theme_support( 'woocommerce' );

        /* UNYSON
         * ----------------------------------------------------------- */

        // Portfolio
        {
            add_post_type_support( 'fw-portfolio', 'excerpt' );
            add_post_type_support( 'fw-portfolio', 'comments' );
            add_post_type_support( 'fw-portfolio', 'page-attributes' );
        }

        // Event
        {
            add_post_type_support( 'fw-event', 'excerpt' );
        }

        /* LIFTER LMS
         * ----------------------------------------------------------- */
        // add_theme_support( 'lifterlms-sidebars' );
    }
    add_action( 'after_setup_theme', '_yx_core_setup' );
}

/* CORE WORDPRESS MODS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_hide_admin_bar' ) ) {
    /**
     * Remove a barra do admin no front-end do website, caso o usuário
     * não possua privilégios administrativos.
     *
     * @return bool
     */
    function yx_hide_admin_bar()
    {
        if ( ! current_user_can( 'manage_options' ) && ! is_admin() ) {
            return false;
        }
        return true;
    }
    add_filter( 'show_admin_bar', 'yx_hide_admin_bar' );
}

if ( ! function_exists( 'yx_hide_admin_bar_styles' ) ) {
    /**
     * Remove o CSS da barra de admin no front-end do website, se o
     * usuário não possuir privilégios administrativos.
     */
    function yx_hide_admin_bar_styles()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            remove_action( 'wp_head', '_admin_bar_bump_cb' );
        }
    }
    add_action( 'get_header', 'yx_hide_admin_bar_styles' );
}

/* THEME CUSTOMIZER
 * ------------------------------------------------------------------- */

if ( ! function_exists( '_yx_customizer_register' ) ) {
    /**
     * Registra as seções, campos e controles do customizer para o template.
     *
     * Tudo o que for editável para o tema, dentro de "Aparência > Personalizar',
     * deve ser cadastrado nesta parte.
     *
     * @param WP_Customize_Manager $wp_customize
     *      Instância do gerenciador de customizações
     */
    function _yx_customizer_register( $wp_customize )
    {
        // REDES SOCIAIS
        // --------------------------------------------------------------
        $wp_customize->add_section(
            'template_social',
            array(
                'title' => __( 'Social Links', THEME_DOMAIN ),
                'description' => __(
                    'Links das redes sociais do template.',
                    THEME_DOMAIN
                ),
                'priority' => '0'
            )
        );

        // Toggler
        $wp_customize->add_setting(
            'template_social_display',
            array( 'type' => 'theme_mod' )
        );
        $wp_customize->add_control(
            'template_social_display',
            array(
                'label' => __(
                    'Exibir links de redes sociais?',
                    THEME_DOMAIN
                ),
                'description' => __(
                    'Exibe/oculta os links de redes sociais do tema.',
                    THEME_DOMAIN
                ),
                'type' => 'checkbox',
                'section' => 'template_social'
            )
        );

        // Social : Facebook
        $wp_customize->add_setting(
            'template_social_facebook',
            array( 'type' => 'theme_mod' )
        );
        $wp_customize->add_control(
            'template_social_facebook',
            array(
                'label' => __( "URL do Facebook", THEME_DOMAIN ),
                'description' => __(
                    'URL para a página ou perfil, com protocolo (http/https).',
                    THEME_DOMAIN
                ),
                'type' => 'url',
                'section' => 'template_social'
            )
        );

        // Social : Twitter
        $wp_customize->add_setting(
            'template_social_twitter',
            array( 'type' => 'theme_mod' )
        );
        $wp_customize->add_control(
            'template_social_twitter',
            array(
                'label' => __( "URL do Twitter", THEME_DOMAIN ),
                'description' => __(
                    'URL para a página ou perfil, com protocolo (http/https).',
                    THEME_DOMAIN
                ),
                'type' => 'url',
                'section' => 'template_social'
            )
        );

        // Social : Instagram
        $wp_customize->add_setting(
            'template_social_instagram',
            array( 'type' => 'theme_mod' )
        );
        $wp_customize->add_control(
            'template_social_instagram',
            array(
                'label' => __( "URL do Instagram", THEME_DOMAIN ),
                'description' => __(
                    'URL para a página ou perfil, com protocolo (http/https).',
                    THEME_DOMAIN
                ),
                'type' => 'url',
                'section' => 'template_social'
            )
        );

        // Social : LinkedIn
        $wp_customize->add_setting(
            'template_social_instagram',
            array( 'type' => 'theme_mod' )
        );
        $wp_customize->add_control(
            'template_social_instagram',
            array(
                'label' => __( "URL do LinkedIn", THEME_DOMAIN ),
                'description' => __(
                    'URL para a página ou perfil, com protocolo (http/https).',
                    THEME_DOMAIN
                ),
                'type' => 'url',
                'section' => 'template_social'
            )
        );
    }
    add_action( 'customize_register', '_yx_customizer_register' );
}

/* CUSTOM POST TYPES + TAXONOMIES
 * ------------------------------------------------------------------- */
if ( ! function_exists( '_yx_custom_post_types' ) ) {
    /**
     * Registra custom post types e taxonomias usados no tema.
     */
    function _yx_custom_post_types()
    {
        /* ASSOCIADOS
         * ----------------------------------------------------------- */
        register_post_type(
            'associado',
            array(
                'labels' => array(
                    'name' => __( 'Associados', THEME_DOMAIN ),
                    'singular_name' => __( 'Associado', THEME_DOMAIN )
                ),
                'description' => __(
                    'Cadastro de associados.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'revisions'
                ),
                'has_archive' => true,
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-nametag',
                'taxonomies' => array( 'estado' )
            )
        );

        register_taxonomy(
            'estado',
            'associado',
            array(
                'labels' => array(
                    'name' => __( 'Estados', THEME_DOMAIN ),
                    'singular_name' => __( 'Estado', THEME_DOMAIN )
                )
            )
        );

        /* CURSOS
         * ----------------------------------------------------------- */
        /*
        register_post_type(
            'curso',
            array(
                'labels' => array(
                    'name' => __( 'Cursos', THEME_DOMAIN ),
                    'singular_name' => __( 'Curso', THEME_DOMAIN )
                ),
                'description' => __(
                    'Registro de cursos.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'revisions'
                ),
                'has_archive' => true,
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-welcome-learn-more'
            )
        );
        */

        register_post_type(
            'cursos',
            array(
                'labels' => array(
                    'name' => __( 'Cursos', THEME_DOMAIN ),
                    'singular_name' => __( 'Curso', THEME_DOMAIN )
                ),
                'description' => __(
                    'Registro de cursos.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'revisions'
                ),
                'has_archive' => true,
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-welcome-learn-more'
            )
        );

        /* MULTIMÍDIA
         * ----------------------------------------------------------- */
        register_post_type(
            'multimidia',
            array(
                'labels' => array(
                    'name' => __( 'Multimídia', THEME_DOMAIN ),
                    'singular_name' => __( 'Multimídia', THEME_DOMAIN )
                ),
                'description' => __(
                    'Vídeos do YouTube para a home e seção multimídia.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'thumbnail',
                    'editor',
                    'excerpt'
                ),
                'has_archive' => true,
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-portfolio'
            )
        );

        /* PARCEIROS
         * ----------------------------------------------------------- */
        register_post_type(
            'parceiros',
            array(
                'labels' => array(
                    'name' => __( 'Parceiros', THEME_DOMAIN ),
                    'singular_name' => __( 'Parceiro', THEME_DOMAIN )
                ),
                'description' => array(
                    'Parceiros.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'thumbnail',
                    'excerpt'
                ),
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-universal-access'
            )
        );

        /* PUBLICAÇÕES
         * ----------------------------------------------------------- */
        register_post_type(
            'publicacoes',
            array(
                'labels' => array(
                    'name' => __( 'Publicações (Associados)', THEME_DOMAIN ),
                    'singular_name' => __( 'Publicação (Associados)', THEME_DOMAIN )
                ),
                'description' => array(
                    'Publicações para a área restrita do website.',
                    THEME_DOMAIN
                ),
                'public' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'author',
                    'thumbnail',
                    'excerpt',
                    'custom-fields',
                    'revisions'
                ),
                'has_archive' => true,
                'show_in_rest' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                'menu_icon' => 'dashicons-universal-access',
                'taxonomies' => array( 'area' )
            )
        );

        register_taxonomy(
            'area',
            'publicacoes',
            array(
                'labels' => array(
                    'name' => __( 'Áreas', THEME_DOMAIN ),
                    'singular_name' => __( 'Área', THEME_DOMAIN )
                )
            )
        );
    }
    add_action( 'init', '_yx_custom_post_types' );
}

/* WIDGETS + SIDEBARS
 * --------------------------------------------------posts_where----------------- */
if ( ! function_exists( 'yx_widgets_register' ) ) {
    /**
     * Responsável por registrar todos os espaços para widgets no template.
     */
    function yx_widgets_register()
    {
        /* HOME SLIDER
         * ----------------------------------------------------------- */
        register_sidebar(
            array(
                'id' => 'home_slider',
                'name' => __( 'Home : Slider', THEME_DOMAIN ),
                'description' => __(
                    'Bloco para inserção do widget de slider na home.',
                    THEME_DOMAIN
                ),
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            )
        );

        /* SIDEBAR GLOBAL (POSTS)
         * ----------------------------------------------------------- */
        register_sidebar(
            array(
                'id' => 'widget-sidebar',
                'name' => __( 'Sidebar Global (Posts)', THEME_DOMAIN ),
                'description' => __(
                    'Sidebar global, normalmente aparece do lado de posts.',
                    THEME_DOMAIN
                ),
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            )
        );

        register_sidebar(
            array(
                'id' => 'sidebar-subs',
                'name' => __( 'Sidebar Secundária', THEME_DOMAIN ),
                'description' => __(
                    'Sidebar global secundária, normalmente aparece do lado de posts.',
                    THEME_DOMAIN
                ),
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            )
        );

        /* WIDGETS DO RODAPÉ
         * ----------------------------------------------------------- */
        register_sidebar(
            array(
                'id' => 'footer-social',
                'name' => __( 'Rodapé : Links Sociais', THEME_DOMAIN ),
                'description' => __(
                    'Links de redes sociais do rodapé.',
                    THEME_DOMAIN
                ),
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            )
        );

        register_sidebars(
            // Quantidade
            4,
            // Argumentos
            array(
                'id' => 'footer-widget',
                'name' => __( 'Rodapé : Widget %d', THEME_DOMAIN ),
                'description' => __(
                    'Bloco de widgets do rodapé do website.',
                    THEME_DOMAIN
                ),
                'class' => 'footer-widget',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>'
            )
        );
    }
    add_action( 'widgets_init', 'yx_widgets_register' );
}

/* TEMPLATES
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_category_template_page' ) ) {
    /**
     * Adiciona a possibilidade do uso de páginas do tipo `single` para o
     * caso de postagens em categorias específicas.
     *
     * @param string $template
     *      Nome do template atual, serve como fallback
     * @return string
     *      Template a ser exibido
     */
    function yx_category_template_page( $template )
    {
        // Verifica cada categoria
        foreach ( ( array ) get_the_category() as $category ) {
            // Define nome do template
            $file = TEMPLATEPATH . "/single-{$category->slug}.php";

            // Arquivo existe? Retorna este então
            if ( file_exists( $file ) ) return $file;
        }

        // Fallback
        return $template;
    }
    add_filter( 'single_template', 'yx_category_template_page' );
}

/* FEATURES DO SITE
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_categorized_transient_flush' ) ) {
    /**
     * Deleta o transiente criado pelo helper `yx_categorized_blog()`.
     *
     * Mais especificamente o transiente `all_the_cool_cats`.
     *
     * Para mais detalhes sobre o que o transiente trata, veja a função
     * `yx_categorized_blog` em `helpers.php`.
     */
    function yx_categorized_transient_flush()
    {
        delete_transient( 'all_the_cool_cats' );
    }
    add_action( 'edit_category', 'yx_categorized_transient_flush' );
    add_action( 'save_post', 'yx_categorized_transient_flush' );
}

if ( ! function_exists( 'yx_filter_site_title' ) ) {
    /**
     * Filtra o retorno da função `wp_title()`.
     *
     * @param string $title
     *      String com o título básico do site
     * @return string
     *      Título alterado/filtrado
     */
    function yx_filter_site_title( $title )
    {
        // Página + paginação
        global $page, $paged;

        // Adiciona site name
        $title .= get_bloginfo( 'name' );

        // Se home ou front page, adiciona descrição
        $desc = get_bloginfo( 'description', 'display' );
        if ( $desc && ( is_home() || is_front_page() ) ) {
            $title .= " | {$desc}";
        }

        // Adiciona página
        if ( $paged >= 2 || $page >= 2 ) {
            $title .= " | "
                .sprintf(
                    __( 'Página %s', THEME_DOMAIN ),
                    max( $paged, $page )
                );
        }

        return $title;
    }
    add_filter( 'wp_title', 'yx_filter_site_title' );
}

if ( ! function_exists( 'yx_custom_logo_class' ) ) {
    /**
     * Modifica as classes do logo personalizado, quando há suporte.
     *
     * @param string $html
     *      String contendo o logo para replacement
     * @return string
     *      String com classes aplicadas
     */
    function yx_custom_logo_class( $html )
    {
        // Troca classes da imagem
        $html = str_replace(
            'class="custom-logo"',
            'class="custom-logo logo-image"',
            $html
        );

        // Troca classe do link
        $html = str_replace(
            'class="custom-logo-link"',
            'class="custom-logo-link logo"',
            $html
        );

        return $html;
    }
    add_filter( 'get_custom_logo', 'yx_custom_logo_class' );
}

if ( ! function_exists( 'yx_body_classes' ) ) {
    /**
     * Permite adicionar ou filtrar classes do `body` (filtro de classe do
     * body do WordPress).
     *
     * @param array $classes
     *      Array com classes CSS para aplicar
     * @return array
     *      Array modificado
     */
    function yx_body_classes( $classes )
    {
        if ( is_multi_author() ) $classes[] = 'group-blog';

        return $classes;
    }
    add_filter( 'body_class', 'yx_body_classes' );
}

if ( ! function_exists( 'yx_page_menu_args' ) ) {
    /**
     * Filtra argumentos de `wp_page_menu()`, um fallback para `wp_nav_menu()`,
     * faz com que exiba o link para a home do website.
     *
     * @param array $args
     *      Array com argumentos para `wp_page_menu`
     * @return array
     *      Array modificado
     */
    function yx_page_menu_args( $args )
    {
        $args['show_home'] = true;

        return $args;
    }
    add_filter( 'wp_page_menu_args', 'yx_page_menu_args' );
}

if ( ! function_exists( 'yx_more_link' ) ) {
    /**
     * Modifica o link de "Leia Mais" quando se usa `the_content()` em uma
     * listagem de posts.
     *
     * @param string $more_link_text
     *      String contendo o link de "Leia Mais" completo, apenas extraímos
     *      o texto do link para inserir no botão customizado
     * @return string
     *      Botão de leia mais, pronto para impressão
     */
    function yx_more_link( $more_link_text )
    {
        if ( "" == $more_link_text ) {
            $more_link_text = 'Leia Mais';
        } else {
            // Extrai o conteúdo, pois $more_link_text vem como link
            preg_match( '/<a([^>]+)>(.*)<\/a>/', $more_link_text, $matches );
            $more_link_text = $matches[2];
        }

        return '<a class="btn btn-outline-info" href="' . get_permalink()
            . '">' . $more_link_text . '</a>';
    }
    add_filter( 'the_content_more_link', 'yx_more_link' );
}

if ( ! function_exists( 'yx_excerpt_more_link' ) ) {
    function yx_excerpt_more_link( $more )
    {
        // Puxa global de posts do WordPress
        global $post;


        return ' <a class="moretag" href="'
            . get_permalink( $post->ID ) . '">Continuar Lendo...</a>';
    }
    //add_filter( 'excerpt_more', 'yx_excerpt_more_link' );
}

/* FILTROS DE NAVEGAÇÃO
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_enhanced_image_nav' ) ) {
    /**
     * Filtra o link de navegação para páginas de anexo de imagem, adiciona o
     * hash `#main` ao final do link, para que o usuário abra a imagem e a tela
     * resolva o scroll diretamente pelo ID do elemento.
     *
     * @param string $url
     *      String contendo a URL do post
     * @param int|null $id
     *      ID de uma postagem
     * @return string
     *      URL final
     */
    function yx_enhanced_image_nav( $url, $id = null )
    {
        // Se não for anexo de imagem
        if ( ! is_attachment() && ! wp_attachment_is_image( $id ) ) return $url;

        $image_post = get_post( $id );
        if (
            ! empty( $image_post->post_parent )
            && $image_post->post_parent != $id
        ) {
            // Se for um anexo com página "pai", adiciona #main
            $url .= '#main';
        }

        return $url;
    }
    add_filter( 'attachment_link', 'yx_enhanced_image_nav' );
}

if ( ! function_exists( 'yx_menu_category_class' ) ) {
    /**
     * Modifica navmenus para que, em links de taxonomias/categorias, seja
     * adicionado o slug da categoria como uma classe.
     *
     * @param array $classes
     *      Array com nomes de classes do link
     * @param object $item
     *      Objeto WP_Post
     * @return array
     *      Array com classes modificadas
     */
    function yx_menu_category_class( array $classes, object $item )
    {
        // Objeto é cayegoria?
        if ( 'category' == $item->object ) {
            // Extrai dados
            $categories = get_category( $item->object_id );

            // Define array de classes
            $classes= array( $categories->slug );
        }

        return $classes;
    }
    // add_filter( 'nav_menu_css_class', 'yx_menu_category_class', 10, 2 );
}

/* POSTAGENS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_filter_posts_where' ) ) {
    /**
     * Filtra posts em uma query, este filtro procura por posts que se
     * iniciam com um valor específico.
     *
     * @param string $where
     *      Argumento WHERE para a WP_Query
     * @param WP_Query $query
     *      Instância WP_Query
     * @return string
     *      Argumento da query
     */
    function yx_filter_posts_where( $where, $query )
    {
        /**
         * Handler global de banco de dados.
         *
         * @type wpdb
         */
        global $wpdb;

        /**
         * Conteúdo da propriedade `starts-with` da query.
         *
         * @type mixed
         */
        $starts_with = $query->get( 'starts_with' );

        if ( $starts_with ) {
            $where .= " AND {$wpdb->posts}.post_title LIKE '{$starts_with}%'";
        }

        return $where;
    }
    add_filter( 'posts_where', 'yx_filter_posts_where', 10, 2 );
}

if ( ! function_exists( 'yx_filter_search' ) ) {
    /**
     * Modifica a `WP_Query` global, para que a busca funcione apenas para
     * determinados post types.
     *
     * @param WP_Query $query
     *      Instância de objeto WP_Query
     * @return WP_Query
     *      Objeto WP_Query modificado
     */
    function yx_filter_search( $query )
    {
        // Do not run if admin
        if ( ! is_admin() ) {
            // Limita as buscas apenas para postagens
            if ( $query->is_main_query() && $query->is_search() ) {
                $query->set( 'post_type', array( 'post' ) );
            }
        }

        return $query;
    }
    add_filter( 'pre_get_posts', 'yx_filter_search' );
}

/* MEDIAELEMENT.JS
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_mejs_add_container_class' ) ) {
    /**
     * Adiciona uma classe ao container do MediaElement.js, para facilitar
     * modificações no comportamento usando CSS.
     *
     * Estende o objeto `_wpmejsSettings`, do core, para adicionar um novo
     * recurso via a API do plugin.
     */
    function yx_mejs_add_container_class()
    {
        // Não tem mediaelement? Para!
        if ( ! wp_script_is( 'mediaelement', 'done' ) ) return;

        // Define script do rodapé
        $script = '
          <script>
            (function() {
              // Define configurações
              var settings = window._wpmejsSettings || {};

              // Solicita recursos
              settings.features = settings.features || mejs.MepDefaults.features;

              // Adiciona a classe
              settings.features.push(\'yxclass\');

              // Executa ação e adiciona classes
              MediaElementPlayer.prototype.buildyxclass = function( player ) {
                player.container.addClass(\'yx-mejs-wrap\');
              };
            })();
          </script>
        ';

        echo $script;
    }
    add_action(
        'wp_print_footer_scripts',
        'yx_mejs_add_container_class'
    );
}

/* SMART SLIDER
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_smartslider3_skip_license_modal' ) ) {
    /**
     * Esconde o popup do SmartSlider3 na criação de slides.
     *
     * Em páginas aonde a licença é obrigatória, o popup ainda aparecerá.
     *
     * @param mixed $option
     *      Array com opções, usada para casos com condicionais
     * @return bool
     *      Se `true` oculta o modal
     */
    function yx_smartslider3_skip_license_modal( $option )
    {
        return true;
    }
    add_filter(
        'smartslider3_skip_license_modal',
        'yx_smartslider3_skip_license_modal'
    );
}

/* CONTACT FORM 7
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'yx_wpcf7_atts_filter' ) ) {
    /**
     * Permite que capturemos variáveis/atributos do shortcode para definir
     * valores padrão ao renderizar o formulário.
     *
     * @param array $out
     *      Array de saída
     * @param array $pairs
     *      Array com pares de dados
     * @param array $atts
     *      Atributos disponíveis no formulário
     * @return array
     *      Array filtrado
     */
    function yx_wpcf7_atts_filter(
        $out,
        $pairs,
        $atts
    ) {
        // Nome do atributo no shortcode
        $my_attr = 'cursos-message';

        if ( isset( $atts[ $my_attr ] ) ) {
            $out[ $my_attr ] = $atts[ $my_attr ];
        }

        return $out;
    }
    add_filter( 'shortcode_atts_wpcf7', 'yx_wpcf7_atts_filter', 10, 3 );
}

if ( ! function_exists( 'yx_wpcf7_form_element_filter' ) ) {
    /**
     * Filtra spans "desnecessários" do markup HTML retornado pelo ContactForm7.
     *
     * @param string $content
     *      String contendo o formulário a ser filtrado
     * @return string
     *      Formulário com filtros aplicados
     */
    function yx_wpcf7_form_element_filter( $content )
    {
        $content = preg_replace(
            '/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i',
            '\2',
            $content
        );
        return $content;
    }
    add_filter( 'wpcf7_form_elements', 'yx_wpcf7_form_element_filter' );
}
