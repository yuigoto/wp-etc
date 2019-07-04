<?php
if ( ! function_exists( 'yx_bootstrap4_pagination' ) ) {
    /**
     * Paginação estilo Bootstrap 4 para WordPress.
     *
     * Uma versão expandida do:
     * https://github.com/talentedaamer/Bootstrap-wordpress-pagination
     *
     * Implementei algumas coisas que o Pinegrow também gera, e balanceei
     * a geração de links numéricos removendo o range, para que sempre exiba
     * um número de links similar quando muitas páginas estão presentes.
     *
     * @param array $args
     *      Array com argumentos para modificar o output do menu
     * @return bool|void
     */
    function yx_bootstrap4_pagination( array $args = array() )
    {
        // Text domain, for translation
        $text_domain = ( defined( 'THEME_DOMAIN' ) )
            ? THEME_DOMAIN
            : 'text-domain';
        
        $defaults = array(
            'range'             => 4,
            'custom_query'      => false,
            'previous_string'   => __( '&lsaquo;', $text_domain ),
            'next_string'       => __( '&rsaquo;', $text_domain ),
            'first_string'      => __( '&laquo;', $text_domain ),
            'last_string'       => __( '&raquo;', $text_domain ),
            'before_output'     => '<ul class="pagination">',
            'after_output'      => '</ul>'
        );
        
        // Junta argumentos
        $args = wp_parse_args(
            $args,
            apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
        );
        
        //$args['range'] = (int) $args['range'] - 1;
        if ( ! $args['custom_query'] ) {
            /**
             * @type WP_Query
             */
            $args['custom_query'] = @$GLOBALS['wp_query'];
        }
        
        $count = (int) $args['custom_query']->max_num_pages;
        $page = intval( get_query_var( 'paged' ) );
        $ceil = (int) ceil( $args['range'] / 2 );
    
        // 1 página? Retorna
        if ( $count <= 1 ) return false;
        
        // Se não tiver página declarada, é a primeira
        if ( !$page ) $page = 1;
        
        // Define range min/max
        if ( $count > $args['range'] ) {
            if ( $page <= $ceil ) {
                $min = 1;
                $max = $args['range'] + 1;
            } elseif ( $page >= ( $count - $ceil ) ) {
                $min = $count - $args['range'];
                $max = $count;
            } else {
                $min = $page - $ceil;
                $max = $page + $ceil;
            }
        } else {
            $min = 1;
            $max = $count;
        }
        
        // Armazena links para exibição
        $echo = [];
        
        // Define links de Primeira e Página Anterior
        $previous = intval( $page - 1 );
        $previous = esc_attr( get_pagenum_link( $previous ) );
        $firstpage = esc_attr( get_pagenum_link( 1 ) );
    
        // Link de Primeira Página
        // if ( $firstpage && ( 1 != $page ) ) {
        if ( $firstpage ) {
            $echo[] = '<li class="page-item first'
                . ( $page == 1 ? ' disabled' : '' ) . '">';
            $echo[] = '<a class="page-link" href="' . $firstpage
                . '" aria-label="' . __( 'Primeira', $text_domain )
                . '" title="' . __( 'Primeira', $text_domain ) . '">';
            $echo[] = $args['first_string'];
            $echo[] = '</a>';
            $echo[] = '</li>';
        }
        
        // Link de Página Anterior
        // if ( $previous && ( 1 != $page ) ) {
        if ( $previous ) {
            $echo[] = '<li class="page-item previous'
                . ( $page == 1 ? ' disabled' : '' ) . '">';
            $echo[] = '<a class="page-link" href="' . $previous
                . '" aria-label="' . __( 'Anterior', $text_domain )
                . '" title="' . __( 'Anterior', $text_domain ) . '">';
            $echo[] = $args['previous_string'];
            $echo[] = '</a>';
            $echo[] = '</li>';
        }
        
        // Links numéricos
        if ( ! empty( $min ) && ! empty( $max ) ) {
            for ( $i = $min; $i <= $max; $i++) {
                if ( $page == $i ) {
                    $link = '<a class="page-link active" href="%s">%d</a>';
                    
                    $echo[] = '<li class="page-item active">';
                    $echo[] = sprintf(
                        $link,
                        esc_attr( get_pagenum_link( $i ) ),
                        $i
                    );
                    $echo[] = '</li>';
                } else {
                    $link = '<a class="page-link" href="%s">%d</a>';
                    
                    $echo[] = '<li class="page-item">';
                    $echo[] = sprintf(
                        $link,
                        esc_attr( get_pagenum_link( $i ) ),
                        $i
                    );
                    $echo[] = '</li>';
                }
            }
        }
    
        // Define links de Próxima Página e Última
        $next = intval( $page ) + 1;
        $next = esc_attr( get_pagenum_link( $next ) );
        $lastpage = esc_attr( get_pagenum_link( $count ) );
    
        // Link de Próxima Página
        // if ( $next && ( $count != $page ) ) {
        if ( $next ) {
            $echo[] = '<li class="page-item next'
                . ( $page == $count ? ' disabled' : '' ) . '">';
            $echo[] = '<a class="page-link" href="' . $next
                . '" aria-label="' . __( 'Pr&acute;xima', $text_domain )
                . '" title="' . __( 'Pr&acute;xima', $text_domain ) . '">';
            $echo[] = $args['next_string'];
            $echo[] = '</a>';
            $echo[] = '</li>';
        }
        
        // Link de Última Página
        if ( $lastpage ) {
            $echo[] = '<li class="page-item last'
                . ( $page == $count ? ' disabled' : '' ) . '">';
            $echo[] = '<a class="page-link" href="' . $lastpage
                . '" aria-label="' . __( '&Uacute;ltima', $text_domain )
                . '" title="' . __( '&Uacute;ltima', $text_domain ) . '">';
            $echo[] = $args['last_string'];
            $echo[] = '</a>';
            $echo[] = '</li>';
        }
        
        // Se houver links, exibe
        if ( count( $echo ) > 0 ) {
            echo $args['before_output'] . implode( "", $echo )
                . $args['after_output'];
        }
    }
}
