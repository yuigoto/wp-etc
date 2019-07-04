<?php
/**
 * MediaElement
 * ----------------------------------------------------------------------
 * Modificadores para o MediaElement.js do WordPress.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @version     0.0.1
 */

/**
 * Adiciona uma classe HTML ao container do MediaElement.js, para facilitar
 * personalização com CSS.
 *
 * Estende o objeto _wpmejsSettings, do core, para adicionar um novo recurso
 * via a API do plugin.
 */
function yx_mejs_add_container_class()
{
    // Se não for mediaelement, retorna
    if ( !wp_script_is( 'mediaelement', 'done' ) ) return;

    // Define o script
    $script = '
    <script>
    (function() {
        // Define configurações
        var settings = window._wpmejsSettings || {};
        // Puxa recursos
        settings.features = settings.features || mejs.MepDefaults.features;
        // Adiciona a classe
        settings.features.push( \'sixsidedclass\' );

        // Executa a ação e adiciona classes
        MediaElementPlayer.prototype.buildsixsidedclass = function( player ) {
            player.container.addClass( \'sixsided-mejs-wrap\' );
        };
        console.log( "SIXSIDED" );
    })();
    </script>
    ';

    // Exibe
    echo $script;
}
// Adiciona a action
add_action( 'wp_print_footer_scripts', 'yx_mejs_add_container_class' );
