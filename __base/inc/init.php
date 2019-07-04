<?php if ( ! defined( 'ABSPATH' ) ) die( 'Acesso direto ao arquivo negado' );

/**
 * Theme_Includes
 * ----------------------------------------------------------------------
 * Responsával pelo import de todos os arquivos com scripts, menus, widgets
 * e outras customizações do tema.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Theme_Includes
{
    /* PROPRIEDADES
     * --------------------------------------------------------------- */
    
    /**
     * Caminho relativo da pasta desta classe.
     *
     * @var string|null
     */
    private static $rel_path = null;
    
    /**
     * Armazena uma função anônima/callable, para realizar includes isolados
     * de arquivos.
     *
     * Originalmente um `create_function`, foi modificado para usar closures,
     * visto que `create_function` foi descontinuada.
     *
     * @var callable
     */
    private static $include_isolated_callable;
    
    /**
     * Status de inicialização da classe.
     *
     * @var bool
     */
    private static $initialized = false;
    
    /* MÉTODOS PÚBLICOS
     * --------------------------------------------------------------- */
    
    /**
     * Construtor, inicializa os includes.
     */
    public static function init()
    {
        // Verifica se já foi inicializado ou retorna
        if ( self::$initialized ) {
            return;
        } else {
            self::$initialized = true;
        }
        
        /**
         * Inclui um arquivo isoladamente, sem que o código do arquivo tenha
         * acesso às variáveis do contexto atual.
         *
         * @param string $path
         *      Caminho do arquivo a ser incluído
         */
        self::$include_isolated_callable = function( string $path )
        {
            include $path;
        };
        
        /**
         * Executa tanto no frontend, quanto no backend.
         */
        {
            self::include_child_first( '/helpers.php' );
            self::include_child_first( '/hooks.php' );
            self::include_all_child_first( '/includes' );
            
            // Define actions
            add_action( 'init', array( __CLASS__, '_action_init' ) );
            add_action(
                'widgets_init',
                array( __CLASS__, '_action_widgets_init' )
            );
        }
        
        /**
         * Executa apenas no frontend.
         */
        if ( ! is_admin() ) {
            add_action(
                'wp_enqueue_scripts',
                array( __CLASS__, '_action_enqueue_scripts' ),
                20
            /**
             * Realiza um include "tardio" aqui, para que seja possível
             * executar `wp_dequeue_style|script()`, se necessário.
             */
            );
        }
    }
    
    /**
     * Retorna o caminho de `$rel_path` dentro da pasta do tema "pai".
     *
     * @param string $rel_path
     *      Caminho relativo para um arquivo/pasta na pasta do tema pai
     * @return string
     *      Caminho absoluto para o destino
     */
    public static function get_parent_path( $rel_path )
    {
        return get_template_directory() . self::get_rel_path( $rel_path );
    }
    
    /**
     * Retorna o caminho para `$rel_path` dentro da pasta do tema "filho".
     *
     * @param string $rel_path
     *      Caminho relativo para um arquivo/pasta na pasta do tema filho
     * @return string
     *      Caminho absoluto para o destino
     */
    public static function get_child_path( $rel_path )
    {
        // Não é tema filho? Pare!
        if ( ! is_child_theme() ) return null;
        
        return get_stylesheet_directory() . self::get_rel_path( $rel_path );
    }
    
    /**
     * Executa o callable (`$include_isolated_callable`), para inclusão de
     * arquivos isoladamente do contexto da classe.
     *
     * @param string $path
     *      Caminho para o arquivo a ser incluído
     */
    public static function include_isolated( $path )
    {
        call_user_func( self::$include_isolated_callable, $path );
    }
    
    /**
     * Inclui o conteúdo de `$rel_path` primeiro se existir no tema filho,
     * depois se existir no tema pai.
     *
     * @param string $rel_path
     *      Caminho relativo para busca nas pastas de tema
     */
    public static function include_child_first( $rel_path )
    {
        // É tema filho?
        if ( is_child_theme() ) {
            $path = self::get_child_path( $rel_path );
            
            // Existe? Inclua!
            if ( file_exists( $path ) ) self::include_isolated( $path );
        }
        
        // Inclui arquivo do tema pai
        {
            $path = self::get_parent_path( $rel_path );
            
            // Existe? Inclua!
            if ( file_exists( $path ) ) self::include_isolated( $path );
        }
    }
    
    /**
     * Inclui o conteúdo de `$rel_path` primeiro se existir no tema pai,
     * depois se existir no tema filho.
     *
     * @param string $rel_path
     *      Caminho relativo para busca nas pastas de tema
     */
    public static function include_parent_first( $rel_path )
    {
        // Inclui arquivo do tema pai
        {
            $path = self::get_parent_path( $rel_path );
            
            // Existe? Inclua!
            if ( file_exists( $path ) ) self::include_isolated( $path );
        }
        
        // É tema filho?
        if ( is_child_theme() ) {
            $path = self::get_child_path( $rel_path );
            
            // Existe? Inclua!
            if ( file_exists( $path ) ) self::include_isolated( $path );
        }
    }
    
    /**
     * Solicita os conteúdo de `static.php` nos temas.
     *
     * Prioriza os arquivos do tema pai na ordem de carregamento.
     *
     * @internal
     */
    public static function _action_enqueue_scripts()
    {
        self::include_parent_first( '/static.php' );
    }
    
    /**
     * Solicita o conteúdo de `menus.php` dos temas.
     *
     * Prioriza os arquivos do tema filho na ordem de carregamento.
     *
     * @internal
     */
    public static function _action_init()
    {
        self::include_child_first( '/menus.php' );
    }
    
    /**
     * Inicializa os scripts de widgets do tema.
     *
     * Prioriza arquivos do tema filho sobre os do pai, na ordem de
     * carregamento.
     *
     * @internal
     */
    public static function _action_widgets_init()
    {
        // Montando array com caminhos de widgets
        {
            $paths = array();
            
            // É tema filho?
            if ( is_child_theme() ) {
                $paths[] = self::get_child_path( '/widgets' );
            }
            
            $paths[] = self::get_parent_path( '/widgets' );
        }
        
        /**
         * Armazena uma lista com os widgets já inclusos, para evitar o
         * carregamento de duplicatas.
         *
         * @var array
         */
        $included_widgets = array();
        
        // Busca nos caminhos
        foreach ( $paths as $path ) {
            // Busca apenas nos diretórios
            $dirs = glob( $path . '/*', GLOB_ONLYDIR );
            
            if ( ! $dirs ) continue;
            
            foreach ( $dirs as $dir ) {
                $dirname = basename( $dir );
                
                if ( isset( $included_widgets[ $dirname ] ) ) {
                    /**
                     * Isso acontece quando um widget do tema filho deseja
                     * sobrescrever o widget do tema pai.
                     *
                     * No caso, o widget já encontra-se incluso e não há a
                     * necessidade de fazê-lo novamente.
                     */
                    continue;
                } else {
                    $included_widgets[ $dirname ] = true;
                }
                
                // Define caminho final do include
                self::include_isolated( "{$dir}/class-widget-{$dirname}.php" );
                
                // Registra o widget
                register_widget(
                    'Widget_' . self::dirname_to_classname( $dirname )
                );
            }
        }
    }
    
    /* MÉTODOS PRIVADOS
     * --------------------------------------------------------------- */
    
    /**
     * Retorna o caminho completo para uma pasta ou arquivo, tendo como base
     * o caminho da pasta que contém esta classe, que também é o valor padrão
     * retornado.
     *
     * O valor de `$append` é opcional, e serve para indicar o nome de uma
     * pasta ou arquivo a ser adicionado como segmento ao final do caminho.
     *
     * @param string $append
     *      Segmento a ser adicionado ao caminho relativo desta classe
     * @return string
     *      Caminho completo para a pasta ou arquivo, o valor padrão é o
     *      caminho da pasta contendo esta classe
     */
    private static function get_rel_path( $append = '' )
    {
        // Se o caminho for nulo, define a raíz do caminho da classe
        if ( self::$rel_path === null ) {
            self::$rel_path = '/' . basename( dirname( __FILE__ ) );
        }
    
        return self::$rel_path . $append;
    }
    
    /**
     * Converte o nome de um diretório/estrutura em um nome de classe.
     *
     * Ex.: `module-test-example` => `Module_Test_Example`
     *
     * @param string $dirname 'foo-bar'
     *      Caminho a ser convertido em classe
     * @return string 'Foo_Bar'
     */
    private static function dirname_to_classname( $dirname )
    {
        $class_name = explode( '-', $dirname );
        $class_name = array_map( 'ucfirst', $class_name );
        $class_name = implode( '_', $class_name );
    
        return $class_name;
    }
    
    /**
     * Inclui arquivos adicionais do tema usando o callable definido no
     * construtor/inicializador.
     *
     * Sempre que disponíveis, inclui os arquivos do tema filho, para
     * depois incluir os arquivos do tema principal.
     *
     * @param string $dir_rel_path
     *      Caminho relativo desejado para busca
     */
    private static function include_all_child_first( $dir_rel_path )
    {
        $paths = array();
        
        // Se for tema filho
        if ( is_child_theme() ) {
            $paths[] = self::get_child_path( $dir_rel_path );
        }
        
        $paths[] = self::get_parent_path( $dir_rel_path );
        
        // Inclui isoladamente
        foreach ( $paths as $path ) {
            if ( $files = glob( $path . '/*.php' ) ) {
                foreach ( $files as $file ) {
                    self::include_isolated( $file );
                }
            }
        }
    }
}

// Inicializa
Theme_Includes::init();
