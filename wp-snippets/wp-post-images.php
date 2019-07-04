<?php
/**
 * Post Images
 * ----------------------------------------------------------------------
 * Snippets e funções para extrair imagens de posts facilmente, entre outras
 * funções com imagens, declare em `functions.php` e utilize no template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

// Adiciona suporte à imagens (imagem de destaque/cabeçalho)
add_theme_support( 'post-thumbnails' );

/**
 * Verifica se o post tem imagem de destaque.
 *
 * Se tiver, retorna a URI da imagem. Caso não tenha, retorna `false`.
 *
 * @param int $post_id
 *      Optional, ID do post desejado, padrão: ID do post atual
 * @param string $size
 *      Opcional, um dos tamanhos permitidos pelo WordPress (como
 *      `thumbnail` ou `full`), padrão: full
 * @return string|bool
 *      URI da imagem ou boolean `false`
 */
function post_images( $post_id = null, $size = 'full' )
{
    // Verifica ID
    if ( !is_numeric( $post_id ) || null === $post_id || $post_id < 1 ) {
        // Retira de global, se usar padrão
        global $post;
        $post_id = $post->ID;
    }

    // Define tamanho
    switch ( trim( $size ) ) {
        case 'thumbnail':
        case 'medium':
        case 'large':
        case 'full':
            break;
        default:
            $size = 'full';
            break;
    }

    // Extrai ID
    $img_id = get_post_thumbnail_id( $post_id );

    // Valida existÊncia
    if ( '' != trim( $img_id ) && is_numeric( $img_id ) ) {
        // Retorna a URI da imagem
        $img_link = wp_get_attachment_image_src( $img_id, $size );
        return $img_link[0];
    }
    return false;
}

/**
 * Versão extendida de `post_images`, também verifica imagens personalizadas,
 * como as utilizadas pelo plugin `MFI_Reloaded` (modifique a função para que
 * encontre o nome/slug da imagem).
 *
 * Na maioria dos casos, esta função é desnecessária.
 *
 * @param int $post_id
 *      Optional, ID do post desejado, padrão: ID do post atual
 * @param string $size
 *      Opcional, um dos tamanhos permitidos pelo WordPress (como
 *      `thumbnail` ou `full`), padrão: full
 * @param bool $main
 *      Opcional, se a função deve retornar apenas a imagem "principal",
 *      ignorando imagens de plugins ou outras, padrão: false
 * @return string|boolean
 *      URI da imagem ou boolean `false`
 */
function post_imgs_extend( $post_id = null, $size = 'full', $main = false )
{
    // Verifica ID
    if ( !is_numeric( $post_id ) || null === $post_id || $post_id < 1 ) {
        // Retira de global, se usar padrão
        global $post;
        $post_id = $post->ID;
    }

    // Define tamanho
    switch ( trim( $size ) ) {
        case 'thumbnail':
        case 'medium':
        case 'large':
        case 'full':
            break;
        default:
            $size = 'full';
            break;
    }

    // Verifica imagens alternativas ANTES da principal
    if ( false === $main ) {
        // Verifica MFI Reloaded
        if ( class_exists( 'MFI_Reloaded' ) ) {
            // Extrai imagem (modifique se necessário o slug)
            $imgs = mfi_reloaded_get_image_id( 'post-icon', $post_id );
            $imgs = wp_get_attachment_image_url( $imgs, $size );
            if ( false !== $imgs ) return $imgs;
        }

        // Exemplos abaixo usam plugins antigos
        // --------------------------------------------------------------

        // Extraindo imagem de custom type vinculado
        $meta_author = get_post_meta( $post_id, 'guest-author', true );
        if ( is_numeric( $meta_author ) && $meta_author > 0 ) {
            $imgs = post_imgs( $post_id, $size );
            if ( false !== $imgs ) return $imgs;
        }

        // Plugin personalizado para vídeos do Vimeo/YouTube/DailyMotion
        if ( function_exists( 'videosCodeTest' ) ) {
            $meta_videos = get_post_meta( $post_id, 'videos-data', true );
            if ( '' != trim( $meta_videos ) && false !== $meta_videos ) {
                // Verifica o tipo do vídeo
                $type = videosCodeTest( $meta_videos );
                if ( false !== $type ) {
                    switch ( $type ) {
                        case 'youtube':
                            $meta_videos = videosGetIDYoutube( $meta_videos );
                            return videosImageYoutube( $meta_videos, 'big' );
                            break;
                        case 'vimeo':
                            $meta_videos = videosGetIDVimeo( $meta_videos );
                            return videosImageVimeo( $meta_videos, 'big' );
                            break;
                        case 'dailymotion':
                            $meta_videos = videosGetIDDailymotion( $meta_videos );
                            return videosImageDailymotion( $meta_videos, 'big' );
                            break;
                        default:
                            return false;
                            break;
                    }
                }
            }
        }
    }
    // Retorna imagem padrão
    return post_imgs( $post_id, $size );
}



/**
 * Exibe a imagem em destaque para posts e páginas.
 *
 * @param string $size
 *      Um dos tamanhos de imagem permitidos pelo WordPress, ou um dos custom
 *      image sizes definidos pelo usuário/tema, padrão é 'full'
 * @param string $class
 *      Classe para ser aplicada à imagem
 */
function yx_featured_image( $size = 'full', $class = 'featured-image' )
{
    // Puxa a global de post
    global $post;

    if ( has_post_thumbnail( $post->ID ) ) {
        // Puxa ID do anexo
        $attachment_id = get_post_thumbnail_id( $post->ID );

        // Puxa o título do post ou página e aplica à imagem
        if (
            get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) === ''
        ) {
            // Se não houver título, então exibe "Imagem do Artigo: [Nome]"
            $title = the_title_attribute(
                array(
                    'before'    => __( 'Imagem do Artigo: ', 'text-domain' ),
                    'echo'      => false
                )
            );
        } else {
            // Titulo
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

        // Adiciona featured image à classe
        if ( $class != 'featured-image' ) {
            $class.= ' featured-image';
        }

        // Verifica dimensões
        $imgs_data = wp_get_attachment_image_src( $attachment_id, $size );

        // Comparando
        $imgs_w = $imgs_data[1];
        $imgs_h = $imgs_data[2];

        if ( $imgs_h > $imgs_w ) {
            $class.= ' vertical-image';
        } elseif ( $imgs_w > $imgs_h ) {
            $class.= ' horizontal-image';
        }

        // Define atributos
        $args = array(
            'class' => $class,
            'alt'   => $title
        );

        // Echo the featured image
        the_post_thumbnail( $size, $args );
    }
}

/**
 * Returna a URL imagem do post.
 *
 * @param int $post_id
 *      ID do post
 * @param string $size
 *      Tamanho da imagem, de acordo com os definidos pelo WordPress
 * @return string|bool
 *      URL da imagem ou false, se não houver nada
 */
function yx_featured_image_link( $post_id, $size = 'full' )
{
    // Puxa ID da imagem
    $imgs = get_post_thumbnail_id( $post_id );

    // É válido?
    if ( '' != trim( $imgs ) && is_numeric( $imgs ) ) {
        // Verifica URI
        $link = wp_get_attachment_image_src( $imgs, $size );
        return ( $link[0] ) ? $link[0] : false;
    }
    return false;
}



/**
 * Modificador para template padrão de galeria.
 *
 * IMPORTANTE:
 * PRECISA DE MELHORIAS E REVISÃO!
 *
 * @param array $output
 *      Dados de output do shortcode de galeria
 * @param array $attr
 *      Atributos da galeria/post
 * @return string|null
 *      Galeria ou null
 */
function yx_images_list( $output, $attr )
{
    // Global de post
    global $post;

    // Verifica ordem
    if ( isset( $attr['orderby'] ) ) {
        // Sanitiza query
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );

        // Desabilita orderby, se inválido
        if ( ! $attr['orderby'] ) unset( $attr['orderby'] );
    }

    // Define atributos básicos
    $args = array(
        'order'         => 'ASC',
        'orderby'       => 'ID',
        'id'            => $post->ID,
        'itemtag'       => 'dl',
        'icontag'       => 'dt',
        'captiontag'    => 'dd',
        'columns'       => 3,
        'size'          => 'thumbnail',
        'include'       => '',
        'exclude'       => ''
    );

    // Define dados e unifica atributos
    $attrib = shortcode_atts( $args, $attr );

    // Define ID (necessário?)
    $attrib['id'] = intval( $attrib['id'] );

    // Define ordem
    if ( 'RAND' == $attrib['order'] ) $attrib['orderby'] = 'none';

    // Array com IDs de anexos
    $attach_id = array();

    // Verifica include
    if ( ! empty( $attrib['include'] ) ) {
        // Define include
        $attrib['include'] = preg_replace( '/[^0-9,]+/', '', $attrib['include'] );

        // Puxa ID anexo
        $pull = get_posts(
            array(
                'include'               => $attrib['include'],
                'post_status'           => 'inherit',
                'post_type'             => 'attachment',
                'post_mime_type'        => 'image',
                'order'                 => $attrib['order'],
                'orderby'               => $attrib['orderby']
            )
        );

        foreach ( $pull as $id => $val ) {
            $attach_id[ $val->ID ] = $pull[ $id ];
        }
    }

    // Para, se não houver imagens
    if ( empty( $attach_id ) ) return null;

    // Array de retorno
    $output = array();

    // Abre lista
    $output[] = '<ul class="post-images">';

    // Monta lista
    foreach ( $attach_id as $id => $attachment ) {
        // Miniatura
        $mini = wp_get_attachment_image_src( $id, 'thumbnail' );

        // URL da imagem
        $full = wp_get_attachment_image_src( $id, 'full' );

        // Output
        $output[] = '<li class="yx-post-images__item">';
        $output[] = '<a href="' . $full[0] . '" target="_blank" rel="post-images" class="yx-post-images__link">';
        $output[] = '<img src="' . $mini[0] . '" alt="">';
        $output[] = '</a>';
        $output[] = '</li>';
    }

    // Fecha lista
    $output[] = '</ul>';

    // Retorna
    return implode( '', $output );
}
// Registra Filter Hook
add_filter( 'post_gallery', 'yx_images_list', 10, 2 );
