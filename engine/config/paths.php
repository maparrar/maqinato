<?php
return array(
    /**
     * Nombre de los scripts que se deben cargar cuando se ejecuta 
     *      Router::js("basic")
     * Son los sripts que se cargan en todas las páginas
     * Estos scripts deben estar en el array de "js" (definido más abajo)
     */
    "basic" => array(
        "jquery",
        "jquery-ui",
        "maqinato",
        "ajax"
    ),
    /**
     * Directorio de scripts js
     * Lista de scripts de javascript. Incluya los scripts que desea llamar
     * usando Router::js("nombre_de_script"). Las rutas deben ser respecto a la
     * raiz de la aplicación. Si no se agrega a esta lista, maqinato buscará en:
     *      public/js/nombre_de_script.js
     */
    "js" => array(
        "jquery"    =>  "public/js/jquery/jquery-2.0.3.min.js",
        "jquery-ui" =>  "public/js/jquery/jquery-ui-1.10.3.custom.min.js",
        "maqinato"  =>  "core/js/Maqinato.js",
        "ajax"      =>  "public/js/ajax/Ajax.js",
        
        "accessing" =>  "public/js/views/Accessing.js"
    ),
    
    /**
     * Directorio de scripts css
     * Lista de scripts de hojas de estilo. Incluya los scripts que desea llamar
     * usando Router::css("nombre_de_script"). Las rutas deben ser respecto a la
     * raiz de la aplicación. Si no se agrega a esta lista, maqinato buscará en:
     *      public/css/nombre_de_script.css
     */
    "css" => array(
        "maqinato"  =>  "core/css/maqinato.css",
        "jquery-ui" =>  "public/css/jquery/jquery-ui-1.10.3.custom.min.css",
        "general"   =>  "public/css/general.css",
        "accessing" =>  "public/css/accessing.css"
    ),
    
    /**
     * Rutas de la aplicación
     * Array de rutas de la aplicación, es usado por el Router para calcular las
     * rutas dentro de maqinato. Solo se debe modificar si se modifica el árbol
     * de directorios.
     */
    "app" => array(
        'root'          =>'',
        'api'           =>'api/',
        'core'          =>'core/',
        'engine'        =>'engine/',
            'ajax'          =>'engine/ajax/',
            'controllers'   =>'engine/controllers/',
            'models'        =>'engine/models/',
            'tests'         =>'engine/tests/',
            'vendors'       =>'engine/vendors/',
            'views'         =>'engine/views/',
                'templates'     =>'engine/views/templates/',
        'public'        =>'public/',
            'css'           =>'public/css/',
            'js'            =>'public/js/'
    ),
    
    /**
     * Directorio de páginas permitidas en la aplicación. Como una medida de
     * seguridad, solo las páginas especificadas en este array serán redireccionadas,
     * las páginas no especificadas en este array, direccionarán a la página de
     * error. Así un usuario solo podrá acceder a lugares explícitamente permitidos.
     * La clave o índice (valor de la izquierda) es el nombre en la URL, el valor
     * de la derecha es la página a la que se redirige.
     */
    "directory" => array(
        ''              =>  'accessing',
        'accessing'     =>  'accessing',
        'main'          =>  'main',
        'home'          =>  'main',
        'error'         =>  'error'
    )
);