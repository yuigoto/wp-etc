<?php
/**
 * MFI Reloaded
 * ----------------------------------------------------------------------
 * Snippets para uso com o plugin `MFI Reloaded`, que permite o uso de imagens
 * de destaque adicionais e posts.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

if ( class_exists( 'MFI_Reloaded' ) ) {
    /**
     * Adiciona suporte para imagens de destaque adicionais em posts, pages
     * ou qualquer custom post, logo abaixo da imagem principal.
     *
     * Exclusivo para uso com o plugin MFI_Reloaded.
     */
    function mfi_custom_image_picker()
    {
        // Text Domain para traduções, modifique conforme necessitar
        $domain = 'text-domain';

        // Adiciona suporte para imagens extras em posts e páginas
        add_theme_support(
            // Nome do plugin
            'mfi-reloaded',
            // Campos extra de imagem (duplique e modifique para mais)
            array(
                // Slug/ID único do campo
                'icon-images' => array(
                    // Tipos de post vinculados
                    'post_types' => array( 'post', 'page' ),
                    // Labels de interface
                    'labels' => array(
                        // Nome
                        'name' => __( 'Ícone', $domain ),
                        // Definir Item
                        'set' => __( 'Definir Ícone', $domain ),
                        // Remover Item
                        'remove' => __( 'Remover Ícone', $domain ),
                        // Título do Popup
                        'popup_title' => __( 'Selecionar Ícone', $domain ),
                        // Botão do Popup
                        'popup_select' => __( 'Selecionar Imagem', $domain )
                    )
                )
            )
        );
    }
    // Registra Action Hook
    add_action( 'after_setup_theme', 'mfi_custom_image_picker' );
}
