<?php
/**
 * Tweaks
 * ----------------------------------------------------------------------
 * Contém funções personalizadas, que agem independentemente dos templates
 * do tema, para adicionar funcionalidades.
 *
 * Eventualmente, algumas das funcionalidades podem ser substituidas por
 * recursos 'padrão' do WordPress ou melhores soluções.
 *
 * Na sua maioria, realizam funções por debaixo dos panos, de forma bem *legal*.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// Permitir imports externos
add_filter( 'http_request_host_is_external', '__return_true' );

/**
 * Faz com que `wp_page_menu()`, um fallback para `wp_nav_menu()`, exiba um link
 * para a home do website.
 *
 * @param array $args
 *      Array com argumentos para o menu
 * @return array
 *      Argumentos alterados
 */
function yx_page_menu_args( $args )
{
    // Define exibição na home
    $args[ 'show_home' ] = true;
    // Retorna
    return $args;
}
// Registra Filter Hook
add_filter( 'wp_page_menu_args', 'yx_page_menu_args' );



/**
 * Adiciona classes personalizadas ao array de classes para o `body` do HTML do
 * website.
 *
 * @param array $classes
 *      Array com classes utilizadas no corpo
 * @return array
 *      Array alterado e atualizado
 */
function yx_body_classes( $classes )
{
    // Adiciona a classe 'group-blog' para quando houver mais de um autor
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // Retornando as classes
    return $classes;
}
// Registra Filter Hook
add_filter( 'body_class', 'yx_body_classes' );



/**
 * Filtra o link de próximo/anterior (next/previous) em links de páginas de
 * anexos, quando o anexo for uma imagem.
 *
 * Adiciona o ID '#main' aos links, para que o usário não precise realizar
 * scroll ao navegar entre anexos apenas, sendo direcionado diretamente ao
 * corpo do website.
 *
 * @param string $url
 *      URL a ser alterada
 * @param int $id
 *      ID do anexo para verificação
 * @return string
 *      URL modificada
 */
function yx_enhanced_image_navigation( $url, $id = '' )
{
    // Se o anexo não for imagem, retorna a URL limpa
    if ( !is_attachment() && !wp_attachment_is_image( $id ) ) return $url;

    // Puxa o post
    $imgs = get_post( $id );

    // Se a imagem não estiver vazia, e se o "pai" for diferente de $id
    if ( !empty( $imgs->post_parent ) && $imgs->post_parent != $id ) {
        // Adiciona a ID
        $url.= '#main';
    }

    // Retornando
    return $url;
}
// Registra Filter Hook
add_filter( 'attachment_link', 'yx_enhanced_image_navigation' );



/**
 * Automaticamente envolve embeds do YouTube em um `div`.
 *
 * @param string $return
 *      HTML oEmbed a ser retornado
 * @param object $data
 *      Objeto com dados do provedor do oEmbed
 * @param string $url
 *      URL do conteúdo
 * @return string
 *      HTML modificado
 */
function yx_oembed_youtube_wrapper( $return, $data, $url )
{
    if ( $data->provider_name == 'YouTube' ) {
        return '<div class="yx-post__video">' . $return . '</div>';
    } else {
        return $return;
    }
}
// Registra Filter Hook
add_filter( 'oembed_dataparse', 'yx_oembed_youtube_wrapper', 10, 3 );



/**
 * Muda o título do logo na tela de login.
 *
 * @return string
 *      Novo título
 */
function yx_login_name()
{
    return get_bloginfo( 'name' );
}
// Registra Filter Hook
add_filter( 'login_headertitle', 'yx_login_name' );



/**
 * Muda a URL do logo na tela de login.
 *
 * @return string
 *      Nova URL
 */
function yx_login_link()
{
    return get_bloginfo( 'url' );
}
// Registra Filter Hook
add_filter( 'login_headerurl', 'yx_login_link' );
