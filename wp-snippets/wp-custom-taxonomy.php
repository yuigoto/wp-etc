<?php
/**
 * Custom Taxonomy
 * ----------------------------------------------------------------------
 * Contém um exemplo completo de como registrar uma custom taxonomy no
 * WordPress, assim como usar labels.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Registers a custom taxonomy for a post type.
 */
function custom_register_taxonomy()
{
    // Nome da Taxonomia
    $taxonomy_name = 'nome';

    // Tipo de Post para Vincular
    $post_type = 'post';

    // Text domain (para traduções)
    $domain = 'names-domain';

    // Labels da interface
    $labels = array(
        // Nome padrão (normalmente no plural)
        'name' => _x( 'Nomes', 'Taxonomy General Name', $domain ),
        // Nome no Singular
        'singular_name' => _x( 'Nome', 'Taxonomy Singular Name', $domain ),
        // Nome no Menu
        'menu_name' => __( 'Nomes', $domain ),
        // Todos os Itens
        'all_items' => __( 'Todos os Nomes', $domain ),
        // Editar Item
        'edit_item' => __( 'Editar Nome', $domain ),
        // Visualizar Item
        'view_item' => __( 'Visualizar Nome', $domain ),
        // Atualizar Item
        'update_item' => __( 'Atualizar Nome', $domain ),
        // Adicionar Novo Item
        'add_new_item' => __( 'Adicionar Novo Nome', $domain ),
        // Nome do Novo Item
        'new_item_name' => __( 'Nome do Novo Nome', $domain ),
        // Item Pai (se usar hierarquia)
        'parent_item' => __( 'Nome Pai', $domain ),
        // Nome do Pai com dois pontos,
        'parent_item_colon' => __( 'Nome Pai:', $domain ),
        // Buscar Itens
        'search_items' => __( 'Buscar Nomes', $domain ),
        // Itens Populares
        'popular_items' => __( 'Nomes Populares', $domain ),
        // Separar Itens por Vírgulas (usado na Metabox)
        'separate_items_with_commas' => __( 'Separe nomes por vírgulas', $domain ),
        // Adicionar ou Remover Itens
        'add_or_remove_items' => __( 'Adicionar ou Remover Nomes', $domain ),
        // Selecione entre os mais usados
        'choose_from_most_used' => __( 'Selecione Nomes Mais Utilizados', $domain ),
        // Nenhum Item encontrado
        'not_found' => __( 'Nenhum Nome Encontrado', $domain )
    );

    // Argumentos
    $taxonomy_args = array(
        // Nome descritivo, no plural
        'label' => __( 'Nomes', $domain ),
        // Labels de Interface
        'labels' => $labels,
        // Publicamente buscável?
        'public' => true,
        // Exibir na UI para gerenciamento?
        'show_ui' => true,
        // Exibir no Menu Admin (`show_ui` precisa ser `true`)
        'show_in_menu' => true,
        // Exibir em Menu de Navegação?
        'show_in_nav_menus' => true,
        // Permitir uso em TagClouds?
        'show_tagcloud' => true,
        // Permitir Edição Rápida/em Massa?
        'show_in_quick_edit' => true,
        // Callback de Metabox
        'meta_box_cb' => null,
        // Exibir Coluna da Taxonomia? (ao listar posts)
        'show_admin_column' => false,
        // Descrição
        'description' => 'DescricaoDaTaxonomia',
        // Permitir Hierarquia?
        'hierarchical' => false,
        // Callback para quando o `post_type` for atualizado
        'update_count_fallback' => '',
        // Permitir uso como Query Var?
        'query_var' => true,
        // Permitir uso com Mod Rewrite?
        'rewrite' => true,
        // Array com opções para gerenciamento
        'capabilities' => null,
        // Lembrar a ordem em que são adicionadas à objetos?
        'sort' => null
    );

    // Registrando
    register_taxonomy( $taxonomy_name, $post_type, $taxonomy_args );
}
// Adiciona Hook de Action
add_action( 'init', 'custom_register_taxonomy' );
