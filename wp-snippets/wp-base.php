<?php
/**
 * Post
 * ----------------------------------------------------------------------
 * Snippets diversos, que modificam o comportamento do PHP, WordPress, do
 * conteúdo ou do template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// WORDPRESS
// ----------------------------------------------------------------------

// Atiba modo debug
define( 'WP_DEBUG', true );



// PHP INI
// ----------------------------------------------------------------------

// Tamanho máximo de upload (precisa ser menor que `post_max_size`)
ini_set( 'upload_max_size', '32M' );

// Tamanho máximo para post data
ini_set( 'post_max_size', '64M' );

// Tempo máximo de execução de scripts, em segundos
ini_set( 'max_execution_time', '300' );



// PREVENINDO ACESSO DIRETO AOS PLUGINS PELO ARQUIVO PHP
// ----------------------------------------------------------------------

// Bloqueia acesso direto ao plugin
if ( !defined( 'ABSPATH' ) ) die( 'Acesso Negado' );



// ADICIONANDO CAMPOS ADICIONAIS AO PERFIL DO AUTOR
// ----------------------------------------------------------------------

/**
 * Registra campos para Twitter/Facebook no perfil do usuário no admin,
 * pode ser usado para exibir barra de links sociais na página do autor.
 *
 * @param array $contact_methods
 *      Array com dados de contato do usuário
 * @return array
 *      Array modificado
 */
function register_social_networks( $contact_methods )
{
    // Twitter
    $contact_methods['twitter'] = 'Twitter Handle';

    // Facebook
    $contact_methods['facebook'] = 'Facebook URL';

    return $contact_methods;
}
// Registra Filter Hook
add_filter( 'user_contactmethods', 'register_social_networks', 16, 2 );

// Para exibir um destes campos no front-end/template
get_user_meta( $user_id, 'twitter', true );



/**
 * Define campos com diversas redes sociais para perfis de usuário no admin.
 *
 * @return array
 *      Array associativo com nome da classe/campo (um ícone FontAwesome, por
 *      questões de praticidade) e o nome da rede.
 */
function yx_social_fields()
{
    return [
        'fa-facebook'       => 'Facebook',
        'fa-twitter'        => 'Twitter',
        'fa-google-plus'    => 'Google Plus',
        'fa-instagram'      => 'Instagram',
        'fa-youtube'        => 'YouTube',
        'fa-pinterest'      => 'Pinterest',
        'fa-flickr'         => 'Flickr',
        'fa-github'         => 'GitHub',
        'fa-linkedin'       => 'LinkedIn',
        'fa-digg'           => 'Digg',
        'fa-vk'             => 'VK',
        'fa-tumblr'         => 'Tumblr'
    ];
}

/**
 * Registra as redes sociais de `yx_social_fields` no perfil de todos os
 * usuários do site.
 *
 * @param array $contactmethods
 *      Array associativo com os métodos de contato do usuário
 * @return array
 *      Array modifciado
 */
function yx_social_register( $contactmethods )
{
    // Puxa lista
    $fields = yx_social_fields();

    // Define campos
    foreach ( $fields as $name => $description ) {
        if ( ! isset( $contactmethods[ $name ] ) ) {
            $contactmethods[ $name ] = $description;
        }
    }
    return $contactmethods;
}
// Registra Filter Hook
add_filter( 'user_contactmethods', 'yx_social_register', 10, 1 );

/**
 * Responsável por salvar as redes sociais de `yx_social_fields`.
 *
 * @param int $user_id
 *      ID do usuário a vincular
 * @return bool
 *      Status do save
 */
function yx_social_save( $user_id )
{
    // Apenas Admin e o próprio usuário podem modificar
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Puxa campos
    $fields = yx_social_fields();

    // Salva campos
    foreach ( $fields as $name => $description ) {
        $link = esc_url_raw( trim( $_POST[ $name ] ) );
        if ( ! empty( $link ) ) {
            update_user_meta(
                $user_id,
                $name,
                $link
            );
        }
    }
    return true;
}
// Registra Action Hooks
add_action( 'personal_options_update', 'yx_social_save' );
add_action( 'edit_user_profile_update', 'yx_social_save' );



// MODIFICADORES DO ADMIN
// ----------------------------------------------------------------------

// Oculta a topbar do admin no front-end (quando logado no admin)
add_filter( 'show_admin_bar', '__return_false' );

/**
 * Oculta links na sidebar do admin do WordPress.
 *
 * Modifique os valores na declaração da função, aqui mesmo.
 *
 * @param bool $post
 *      Opcional, oculta o link de posts, padrão: true
 * @param bool $comments
 *      Opcional, oculta o link de comentários, padrão: true
 * @param bool $upload
 *      Opcional, oculta o link de upload/mídia, padrão: true
 */
function hide_side_link( $post = true, $comments = true, $upload = true )
{
    // Posts
    if ( true === $post ) remove_menu_page( 'edit.php' );

    // Comments
    if ( true === $comments ) remove_menu_page( 'edit-comments.php' );

    // Mídia/Uploads
    if ( true === $upload )remove_menu_page( 'upload.php' );
}
add_action( 'admin_menu', 'hide_side_link' );

/**
 * Define às metaboxes ocultas, por padrão, para páginas de criação/edição
 * de posts/pages no administrador.
 *
 * Apenas para novos usuários.
 *
 * @param array $hidden
 *      Array contendo a lista de todas as metaboxes disponíveis
 * @param WP_Screen $screen
 *      Handle para objeto WP_Screen, representando a tela sendo visualizada
 * @return array
 *      Array modificado da metabox
 */
function hide_post_meta_box( $hidden, $screen )
{
    // Se `post` ou `page`
    if ( 'post' == $screen->base || 'page' == $screen->base ) {
        // Define campos
        $hidden = array(
            // Slug
            'slugdiv',
            // Campos personalizados
            'postcustom',
            // Trackbacks
            'trackbacksdiv',
            // Status de comentários
            'commentstatusdiv',
            // Excerpt/Resumo
            'postexcerpt',
            // Comentários
            'commentsdiv',
            // Autor
            'authordiv',
            // Revisões/Versões
            'revisionsdiv'
        );
    }
    return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'hide_post_meta_box', 10, 2 );



// HELPERS DE TEMPLATE
// ----------------------------------------------------------------------

/**
 * Exibe a URL para um arquivo/pasta dentro do caminho do template
 * atual.
 *
 * O único parâmetro necessário é o caminho do item relativo à raíz do
 * template.
 *
 * Exemplo:
 * - Declarando: `img/test.png`;
 * - Retorna: `http://site.com/wp-content/tema/img/test.png`;
 *
 * @param string $path
 *      Caminho do arquivo/pasta, relativo à raíz do template
 */
function template_file( $path = null )
{
    $path = trim( $path );
    if ( null === $path || "" === $path || false === $path ) return;

    $root = dirname( __FILE__ ).'/';
    if ( file_exists( $root . $path ) || is_dir( $root . $path ) ) {
        echo get_bloginfo( 'template_url' ) . '/' . $path;
    }
}

/**
 * Verifica se o tema possui um logo e exibe o mesmo.
 *
 * Caso não possua suporte, ou não tenha logo, é exibido um heading H1.
 */
function yx_has_logo()
{
    // Valor inicial do logo
    $logo = '';

    // Verifica se usa logo personalizado
    if ( function_exists( 'get_custom_logo' ) ) $logo = get_custom_logo();

    // Logo está vazio? Exibe heading
    if ( empty( $logo ) ) {
        $logo = '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) )
              . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h1>';
    }

    // Exibe o logo
    echo $logo;
}

/**
 * Filtra e troca uma classe na imagem do logo (adiciona a classe 'custom-logo'
 * ou outra que desejar, basta modificar).
 *
 * @param string $html
 *      String com o logo
 * @return string
 *      String modificada
 */
function yx_custom_logo_class( $html )
{
    // Troca a classe da imagem
    $html = str_replace(
        'class="custom-logo"',
        'class="custom-logo logo-image"',
        $html
    );

    // Troca a classe do link
    $html = str_replace(
        'class="custom-logo-link"',
        'class="custom-logo-link logo"',
        $html
    );
    return $html;
}
// Registra Filter Hook
add_filter( 'get_custom_logo', 'yx_custom_logo_class' );
