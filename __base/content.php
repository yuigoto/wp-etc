<?php
/**
 * __BASE : Content (Padrão)
 * ----------------------------------------------------------------------
 * Template básico para exibição de conteúdo de uma postagem.
 *
 * É utilizado no loop como conteúdo "padrão" e como fallback caso outros
 * templates de conteúdo não estejam presentes.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

// Indica se o post possui comentários ou não (`true`/`false`)
$has_comments = (
    ! post_password_required()
    && ( comments_open() || '0' != get_comments_number() )
);

// ID do post
$article_id = 'post-' . get_the_ID();

// Classe para o post
$article_class = ( is_singular() || is_single() )
    ? 'entry-excerpt' : 'entry-full';

/**
 * Atributos usados neste template, em específico. É um costume meu, portanto
 * opcional.
 *
 * Se usá-lo em múltiplos template, lembre-se que é necessário renomear para
 * evitar problemas com sobreposição de variáveis.
 *
 * @var array
 */
$article_attr = array(
    'title' => sprintf(
        __( 'Permalink para %s', THEME_DOMAIN ),
        the_title_attribute( 'echo=0' )
    )
);
?>
<article id="<?php echo $article_id; ?>" <?php post_class( $article_class ); ?>>
    <?php if ( is_single() || is_singular() ): ?>
        <?php yx_breadcrumbs(); ?>
    <?php endif; ?>
    
    <!-- HEADER -->
    <header class="entry-header">
        <!-- Título do post -->
        <h2 class="entry-title">
            <?php if ( is_single() || is_singular() ): ?>
                <?php the_title(); ?>
            <?php else: ?>
                <a href="<?php the_permalink(); ?>"
                   title="<?php echo esc_attr( $article_attr['title'] ); ?>"
                   rel="bookmark">
                    <?php the_title(); ?>
                </a>
            <?php endif; ?>
        </h2>
        
        <!-- Meta dados -->
        <?php if ( 'post' == get_post_type() ): ?>
            <?php yx_posted_on(); ?>
        <?php endif; ?>
    </header>
    
    <!-- CORPO -->
    <?php /* Verifica se é post single ou se estamos numa listagem */ ?>
    <?php if ( is_search() ): ?>
        <?php /* Se for busca, exibe o excerto */ ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
    <?php else: ?>
        <?php /* Caso contrário exibe conteúdo */ ?>
        <div class="entry-content">
            <?php
            // Imagem do post
            yx_featured_image( 'thumbnail', 'thumbnail' );
            
            // Conteúdo
            the_content(
                __(
                    'Continuar lendo <span class="meta-nav">&raquo;</span>',
                    THEME_DOMAIN
                )
            );
            
            // Deixa preparado para a tag "More" e "Next Page" (posts longos)
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">'
                        . __( '<span>Páginas:</span>', THEME_DOMAIN ),
                    'after' => '</div>'
                )
            );
            ?>
        </div>
    <?php endif; ?>
    
    <!-- RODAPÉ -->
    <footer class="entry-meta">
        <?php /* Comentários apenas se for um `post` */ ?>
        <?php if ( 'post' == get_post_type() ): ?>
            <?php
            /* TRADUTORES:
             * Entre os ítens de cada lista, há espaço após a vírgula.
             */
            
            // Listagem de categorias
            $cats_list = get_the_category_list(
                __( ', ', THEME_DOMAIN )
            );
            
            // Listagem de tags
            $tags_list = get_the_tag_list(
                '',
                __( ', ', THEME_DOMAIN )
            );
            ?>
        <?php endif; ?>
        
        <?php /* Exibe categorias */ ?>
        <?php if ( $cats_list && yx_categorized_blog() ): ?>
            <span class="cat-links">
                <?php
                printf(
                    __( 'Categorias: %1$s', THEME_DOMAIN ),
                    $cats_list
                );
                ?>
            </span>
        <?php endif; ?>
        
        <?php /* Exibe tags */ ?>
        <?php if ( $tags_list ): ?>
            <span class="sep"> | </span>
            <span class="tag-links">
                <?php
                printf(
                    __(
                        'Tags: %1$s',
                        THEME_DOMAIN
                    ),
                    $tags_list
                );
                ?>
            </span>
        <?php endif; ?>
        
        <!-- COMENTÁRIOS -->
        <?php if ( $has_comments ): ?>
            <span class="sep"> | </span>
            <span class="comments-link">
                <?php
                comments_popup_link(
                    __( 'Deixe um comentário', THEME_DOMAIN ),
                    __( '1 comentário', THEME_DOMAIN ),
                    __( '% comentários', THEME_DOMAIN )
                );
                ?>
            </span>
        <?php endif; ?>
        
        <!-- EDIÇÃO DE POST -->
        <?php
        // Se for página, não exibe separador
        if ( is_page() ) {
            edit_post_link(
                __( '[Editar Página]', THEME_DOMAIN ),
                '<span class="edit-link">',
                '</span>'
            );
        } else {
            edit_post_link(
                __( '[Editar Postagem]', THEME_DOMAIN ),
                '<span class="sep"> | </span><span class="edit-link">',
                '</span>'
            );
        }
        ?>
    </footer>
</article><!-- #<?php echo $article_id; ?> -->
<hr>
