<?php
/**
 * __BASE : Footer
 * ----------------------------------------------------------------------
 * Rodapé principal do template.
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
$footer_attr = array();
?>
</main>

<!-- FOOTER -->
<footer id="footer" class="bg-dark text-light">
    <div class="container footer-body py-2">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 text-center text-lg-left">
                <p class="m-0 p-0 text-muted">
                    <em>
                        &reg; <?php echo date( 'Y' ) . ' ' . get_bloginfo( 'name' ); ?>
                    </em>
                </p>
            </div>
            <div class="col-12 col-lg-6 text-center text-lg-right">
                <?php if ( ! dynamic_sidebar( 'social-footer' ) ): ?>
                    <ul class="nav justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>
</div>

<?php /* WordPress footer */ ?>
<?php wp_footer(); ?>
</body>
</html>
