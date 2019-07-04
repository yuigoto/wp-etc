<?php
/**
 * __BASE : Sidebar
 * ----------------------------------------------------------------------
 * Sidebar padrão do template.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/**
 * Atributos usados neste template, em específico. É um costume meu, portanto
 * opcional.
 *
 * Se usá-lo em múltiplos template, lembre-se que é necessário renomear para
 * evitar problemas com sobreposição de variáveis.
 *
 * @var array
 */
$sidebar_attr = array();
?>
<div id="secondary" class="widget-area" role="complementary">
    <?php do_action( 'before_sidebar' ); ?>
    
    <?php if ( ! dynamic_sidebar( 'sidebar-main' ) ): ?>
        <?php /* FALLBACKS */ ?>
    
        <!-- BUSCA PADRÃO -->
        <aside id="search" class="widget widget-search">
            <?php get_search_form(); ?>
        </aside>
    
        <hr/>
    
        <!-- ARQUIVOS -->
        <aside id="archives-side" class="widget">
            <h4 class="widget-title">
                <?php _e( 'Arquivos', THEME_DOMAIN ); ?>
            </h4>
            <ul class="navbar-nav">
                <?php
                    wp_get_archives(
                        array(
                            'limit' => 6,
                            'type' => 'monthly',
                            'format' => 'custom',
                            'before' => '<li class="nav-item">',
                            'after' => '</li>'
                        )
                    );
                ?>
            </ul>
        </aside>
    
        <hr/>
    
        <!-- META -->
        <aside id="meta" class="widget">
            <h4 class="widget-title">
                <?php _e( 'Meta', THEME_DOMAIN ); ?>
            </h4>
            <ul class="navbar-nav">
                <?php
                    wp_register(
                        '<li class="nav-item">',
                        '</li>'
                    );
                ?>
                <li class="nav-item">
                    <?php wp_loginout(); ?>
                </li>
                <?php
                    wp_meta();
                ?>
            </ul>
        </aside>
    <?php endif; ?>
</div>

<?php if ( is_active_sidebar( 'sidebar-subs' ) ): ?>
    <div id="tertiary" class="widget-area" role="supplementary">
        <?php dynamic_sidebar( 'sidebar-subs' ); ?>
    </div>
<?php endif; ?>
