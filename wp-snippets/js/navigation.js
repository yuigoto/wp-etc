 /**
 * WP SNIPPETS : Navigation
 * ----------------------------------------------------------------------
 * Exemplo de complemento para templates.
 * 
 * Gerencia toggles para menu em telas pequenas e permite uso de TAB para 
 * dropdown menus.
 * 
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @license     GNU General Public License v2
 * @version     0.0.1
 */
( function() {
  var container, button, menu, links, subMenus, i, len;

  container = document.getElementById( 'site-navigation' );
  if ( ! container ) {
      return;
  }

  button = container.getElementsByTagName( 'button' )[0];
  if ( 'undefined' === typeof button ) {
      return;
  }

  menu = container.getElementsByTagName( 'ul' )[0];

  // Se o menu estiver vazio, para
  if ( 'undefined' === typeof menu ) {
      button.style.display = 'none';
      return;
  }

  menu.setAttribute( 'aria-expanded', 'false' );
  if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
      menu.className += ' nav-menu';
  }

  button.onclick = function() {
      if ( -1 !== container.className.indexOf( 'toggled' ) ) {
          container.className = container.className.replace( ' toggled', '' );
          button.setAttribute( 'aria-expanded', 'false' );
          menu.setAttribute( 'aria-expanded', 'false' );
      } else {
          container.className += ' toggled';
          button.setAttribute( 'aria-expanded', 'true' );
          menu.setAttribute( 'aria-expanded', 'true' );
      }
  };

  // Pega todos os links no menu
  links    = menu.getElementsByTagName( 'a' );
  subMenus = menu.getElementsByTagName( 'ul' );

  // Define items com subitens para aria-haspopup="true".
  for ( i = 0, len = subMenus.length; i < len; i++ ) {
      subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
  }

  // Sempre que um item estiver em foco/sair, executa
  for ( i = 0, len = links.length; i < len; i++ ) {
      links[i].addEventListener( 'focus', toggleFocus, true );
      links[i].addEventListener( 'blur', toggleFocus, true );
  }

  /**
   * Define/remove classe `focus` em um elemento.
   */
  function toggleFocus() {
      var self = this;

      // Sobe até os ancestrais do link atual até atingir .nav-menu
      while ( -1 === self.className.indexOf( 'nav-menu' ) ) {
          // Adiciona classe `focus` em elementos `li`
          if ( 'li' === self.tagName.toLowerCase() ) {
              if ( -1 !== self.className.indexOf( 'focus' ) ) {
                  self.className = self.className.replace( ' focus', '' );
              } else {
                  self.className += ' focus';
              }
          }

          self = self.parentElement;
      }
  }

  /**
   * Define classe `focus` para permitir acesso à submenus em tablets.
   */
  ( function( container ) {
      var touchStartFn, i,
          parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

      if ( 'ontouchstart' in window ) {
          touchStartFn = function( e ) {
              var menuItem = this.parentNode, i;

              if ( ! menuItem.classList.contains( 'focus' ) ) {
                  e.preventDefault();
                  for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
                      if ( menuItem === menuItem.parentNode.children[i] ) {
                          continue;
                      }
                      menuItem.parentNode.children[i].classList.remove( 'focus' );
                  }
                  menuItem.classList.add( 'focus' );
              } else {
                  menuItem.classList.remove( 'focus' );
              }
          };

          for ( i = 0; i < parentLink.length; ++i ) {
              parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
          }
      }
  }( container ) );
} )();
