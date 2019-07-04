/**
 * __BASE : Keyboard Image Navigation
 * ----------------------------------------------------------------------
 * Ativa navegação pelas setas, quando em página de anexo do tipo
 * imagem.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
(function($) {
  "use strict";

  $(document).keydown(function(e) {
    // Define URL inicial
    var url = false;

    // Verifica se esquerda ou direita, solicita href dos links
    if (e.which === 37) {
      url = $('.previous-image a').attr('href');
    } else if (e.which === 39) {
      url = $('.next-image a').attr('href');
    }

    // A URL foi definida? Não estamos com foco em inputs?
    if (url && !$('textarea, select, input').is(':focus')) {
      // Segue a URL
      window.location = url;
    }
  });
})(jQuery);
