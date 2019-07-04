/**
 * WP SNIPPETS : Keyboard Image Navigation
 * ----------------------------------------------------------------------
 * Exemplo de complemento para templates, permite navegar por páginas de anexos 
 * (imagens) usando as setas do teclado.
 * 
 * Depende de jQuery.
 * 
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @license     GNU General Public License v2
 * @version     0.0.1
 */
jQuery( document ).ready( function( $ ) {
  $( document ).keydown( function( e ) {
      var url = false;
      if ( e.which === 37 ) {  // Esquerda
          url = $( '.nav-previous a' ).attr( 'href' );
      }
      else if ( e.which === 39 ) {  // Direita
          url = $( '.entry-attachment a' ).attr( 'href' );
      }
      if ( url && ( ! $( 'textarea, input' ).is( ':focus' ) ) ) {
          window.location = url;
      }
  } );
} );
