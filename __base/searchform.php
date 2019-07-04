<?php
/**
 * __BASE : SearchForm
 * ----------------------------------------------------------------------
 * Formulário de busca do tema.
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
$search_attr = array(
    'action' => esc_url( home_url( '/' ) ),
    'search' => esc_attr( get_search_query() ),
    'placeholder' => esc_attr( __( 'Buscar &hellip;', THEME_DOMAIN ) ),
    'button' => esc_attr( __( 'Buscar', THEME_DOMAIN ) )
);
?>
<form method="get" action="<?php echo $search_attr['action']; ?>">
    <div class="input-group">
        <input type="text" class="form-control" name="s"
               value="<?php echo $search_attr['search']; ?>"
               placeholder="<?php echo $search_attr['placeholder'] ?>">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary"
                    type="submit"
                    title="<?php echo $search_attr['button']; ?>">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</form>
