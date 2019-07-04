<?php
/**
 * __BASE : Index
 * ----------------------------------------------------------------------
 * Template principal e fallback para a página de blog.
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
$index_attr = array();
?>
<?php get_header(); ?>

<div class="container py-4">
    <div class="row">
        <!-- ÁREA PRINCIPAL -->
        <div class="col-12 col-lg-8 py-2 py-lg-0">
            <?php if ( have_posts() ): ?>
                <?php while ( have_posts() ): the_post(); ?>
                    <?php
                    /**
                     * Carrega um template específico para o formato de post
                     * desejado e/ou o modelo padrão (`content.php`).
                     *
                     * Para sobrepor o arquivo de template desejado em um
                     * child-theme, basta criar um arquivo com o mesmo nome
                     * do template.
                     */
                    ?>
                    <?php get_template_part( 'content', get_post_format() ); ?>
                <?php endwhile; ?>
            
                <!-- NAVEGAÇÃO (APENAS SE POSTS) -->
                <?php if ( ! is_page() ) yx_content_nav( 'nav-below' ); ?>
            
                <?php
                // TODO: Descrição
                if ( comments_open() || '0' != get_comments_number() ) {
                    // TODO: Descrição
                    comments_template( '', true );
                }
                ?>
            <?php else: ?>
                <?php get_template_part( 'no-results', 'index' ); ?>
            <?php endif; ?>
        </div>
        
        <!-- SIDEBAR -->
        <div class="col-12 col-lg-4 py-2 py-lg-0">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
