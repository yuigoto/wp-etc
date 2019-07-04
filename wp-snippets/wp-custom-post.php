<?php
/**
 * Custom Post
 * ----------------------------------------------------------------------
 * Contém um exemplo completo de como registrar um custom post type no
 * WordPress, assim como labels e taxonomias para o mesmo.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Registra post types.
 */
function custom_register_posttype()
{
    // Nome/slug do post type (minúsculas)
    $type_name = 'nome';

    // Text domain (para traduções)
    $domain = 'names-domain';

    // Labels da interface
    $labels = array(
        // Nome padrão do post type
        'name' => __( 'Nomes', $domain ),
        // Nome no singular
        'singular_name' => __( 'Nome', $domain ),
        // Nome para o link `Adicionar Novo...`
        'add_new' => __( 'Adicionar Novo', $domain ),
        // Nome para o botão `Adicionar Novo`
        'add_new_item' => __( 'Adicionar Nome', $domain ),
        // Editar Item
        'edit_item' => __( 'Editar Nome', $domain ),
        // Novo Item
        'new_item' => __( 'Novo Nome', $domain ),
        // Visualizar Item
        'view_item' => __( 'Visualizar Nome', $domain ),
        // Buscar Item
        'search_items' => __( 'Buscar Nomes', $domain ),
        // Nenhum Item Encontrado
        'not_found' => __( 'Nenhum Nome Encontrado', $domain ),
        // Nenhum Item Encontrado na Lixeira
        'not_found_in_trash' => __( 'Nenhum Nome na Lixeira', $domain ),
        // Item Pai (se usando hierarquia)
        'parent_item_colon' => __( 'Nome Pai:', $domain ),
        // Todos os Itens
        'all_items' => __( 'Todos os Nomes', $domain ),
        // Arquivos
        'archives' => __( 'Arquivo de Nomes', $domain ),
        // String para o Frame de Mídia (Selecionar/Inserir Arquivo)
        'insert_into_item' => __( 'Inserir no Nome', $domain ),
        // Filtro para Frame de Mídia
        'uploaded_to_this_item' => __( 'Enviado para este Nome', $domain ),
        // Nome da Imagem Destacada
        'featured_image' => __( 'Imagem do Nome', $domain ),
        // Definir Imagem Destacada...
        'set_featured_image' => __( 'Definir Imagem do Nome', $domain ),
        // Remover Imagem Destacada...
        'remove_featured_image' => __( 'Remover Imagem do Nome', $domain ),
        // Usar como Imagem Destacada...
        'use_featured_image' => __( 'Usar como Imagem do Nome', $domain ),
        // Nome de Menu
        'menu_name' => __( 'Nomes', $domain ),
        // Nome para Cabeçalho Oculto (usado para leitores de tela)
        'filter_items_list' => __( 'Filter Nomes para Exibição', $domain ),
        // Nome para Cabeçalho de Navegação Oculto
        'items_list_navigation' => __( 'Navegar pelos Nomes', $domain ),
        // Cabeçalho de Post Oculto
        'items_list' => __( 'Listando Nomes', $domain ),
        // Texto para `Adicionar Novo...` na Admin Bar
        'name_admin_bar' => __( 'Nome', $domain )
    );

    // Metaboxes suportadas para este post type
    $supports = array(
        'title',
        'editor',
        //'author',
        'thumbnail',
        'excerpt',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
        'post-formats'
    );

    // Argumentos
    $post_args = array(
        // Título/Label
        'label' => __( 'Nomes', $domain ),
        // Labels de Interface
        'labels' => $labels,
        // Descrição
        'description' => __( 'Test Application', $domain ),
        // Público para Autores/Leitores
        'public' => true,
        // Excluír dos Resultados de Busca?
        'exclude_from_search' => true,
        // Publicamente disponível pelo front-end?
        'publicly_queryable' => true,
        // Exibir na UI do Admin?
        'show_ui' => true,
        // Exibir em Menus de Navegação?
        'show_in_nav_menus' => true,
        // Exibir em Menus de Admin?
        'show_in_menu' => true,
        // Exibir na Admin Bar?
        'show_in_admin_bar' => true,
        // Posição no Menu (prioridade)
        'menu_position' => 5,
        // Ícone no Menu (pode ser `dashicon-*` ou uma URL de imagem)
        'menu_icon' => 'dashicons-admin-page',
        // Tipo de Post
        'capability_type' => array( 'nome', 'nomes' ),
        // Permitir Posts Hierárquicos?
        'hierarchical' => false,
        // Metaboxes
        'supports' => $supports,
        // Taxonomias
        'taxonomies' => array(),
        // Possui Arquivo?
        'has_archive' => false,
        // Usa Mod Rewrite?
        'rewrite' => true,
        // Ativar Variáveis de Query?
        'query_var' => true,
        // Pode Exportar?
        'can_export' => true
    );

    // Registrando Tipo
    register_post_type( $type_name, $post_args );
}
// Adiciona Hook de Action
add_action( 'init', 'custom_register_posttype' );
