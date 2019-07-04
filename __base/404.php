<?php
/**
 * __BASE : 404
 * ----------------------------------------------------------------------
 * Template para quando algum conteúdo não é encontrado.
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
$error_attr = array();
?>
<?php get_header(); ?>

<div class="container py-4">
    <!-- ÁREA PRINCIPAL -->
    <div class="col-12 py-2 py-lg-0">
        <article id="404-page" class="post error404 not-found">
            <!-- HEADER -->
            <header class="entry-header">
                <h2 class="entry-title text-center">
                    <?php
                    _e(
                        'Ops! A página ou postagem que procura não foi encontrada!',
                        THEME_DOMAIN
                    );
                    ?>
                </h2>
            </header>
            
            <!-- MAIN -->
            <div class="entry-content">
                <p class="lead text-muted text-center">
                    <?php
                    _e(
                        'Aparentemente, não foi possível encontrar o que procura. Tente outro link ou utilize a busca.',
                        THEME_DOMAIN
                    );
                    ?>
                </p>
    
                <hr>
                
                <!-- FORMULÁRIO DE BUSCA -->
                <?php get_search_form(); ?>
    
                <hr>
                
                <!-- WIDGET POSTS RECENTES -->
                <?php the_widget( 'WP_Widget_Recent_Posts' ); ?>
                
                <hr>
                
                <!-- LISTA DE CATEGORIAS -->
                <h3>Categorias</h3>
                <ul>
                    <?php
                    wp_list_categories(
                        array(
                            'orderby'       => 'count',
                            'order'         => 'DESC',
                            'show_count'    => 1,
                            'title_li'      => '',
                            'number'        => 10
                        )
                    );
                    ?>
                </ul>
                
                <hr/>
                
                <!-- WIDGET DE ARQUIVO -->
                <?php
                /**
                 * Tradutores:
                 * %1$s = smiley
                 */
                $archive_content = '<p>'
                    . sprintf(
                        __(
                            'Tente procurar nos arquivos mensais. %1$s',
                            THEME_DOMAIN
                        ),
                        convert_smilies( ':)' )
                    )
                    . '</p>';
                
                the_widget(
                    'WP_Widget_Archives',
                    'dropdown=1',
                    "after_title</h2>{$archive_content}"
                );
                ?>
    
                <!-- WIDGET TAG CLOUD -->
                <?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
            </div>
        </article>
    </div>
</div>

<?php get_footer(); ?>
