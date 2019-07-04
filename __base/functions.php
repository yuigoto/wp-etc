<?php if ( ! defined( 'ABSPATH' ) ) die( 'Acesso direto ao arquivo negado' );

/**
 * __BASE : Functions
 * ----------------------------------------------------------------------
 * Arquivo mestre de funções e includes do tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

/* CONSTANTES DO TEMPLATE
 * ------------------------------------------------------------------- */

if ( ! defined( 'THEME_DOMAIN' ) ) {
    /**
     * Text domain usado pelo tema.
     *
     * @var string
     */
    define( 'THEME_DOMAIN', '__yx' );
}

/* CONFIGURAÇÕES DO PHP
 * ------------------------------------------------------------------- */

ini_set( 'upload_max_filesize', '64M' );
ini_set( 'post_max_size', '64M' );
ini_set( 'max_execution_time', '300' );

/* THEME INCLUDES
 * ------------------------------------------------------------------- */

// Inclui autloader
include 'inc/init.php';
