<?php
return array(    
    /**
     * Directorio de scripts js
     * Lista de scripts de javascript. Incluya los scripts que desea llamar
     * usando Router::js("nombre_de_script"). Las rutas deben ser respecto a la
     * raiz de la aplicación. Si no se agrega a esta lista, maqinato buscará en:
     *      public/js/nombre_de_script.js
     */
    "js" => array(
        "jquery"    =>  "public/js/jquery/jquery-2.0.3.min.js"
    ),
    
    /**
     * Directorio de scripts css
     * Lista de scripts de hojas de estilo. Incluya los scripts que desea llamar
     * usando Router::css("nombre_de_script"). Las rutas deben ser respecto a la
     * raiz de la aplicación. Si no se agrega a esta lista, maqinato buscará en:
     *      public/css/nombre_de_script.css
     */
    "css" => array(
        "template"  =>  "public/css/template.css"
    ),
    
    /**
     * Directorio de rutas de la aplicación
     * Array de rutas de la aplicación, es usado por el Router para calcular las
     * rutas dentro de maqinato. Solo se debe modificar si se modifica el árbol
     * de directorios.
     */
    "app" => array(
        'root'          =>'',
        'api'           =>'api/',
        'core'          =>'core/',
        'engine'        =>'engine/',
            'controllers'   =>'engine/controllers/',
            'models'        =>'engine/models/',
            'tests'         =>'engine/tests/',
            'vendors'       =>'engine/vendors/',
            'views'         =>'engine/views/',
                'templates'     =>'engine/views/templates/',
        'public'        =>'public/',
            'css'           =>'public/css/',
            'js'            =>'public/js/'
    )
);