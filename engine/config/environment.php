<?php
/**
 * En este archivo se configuran los posibles ambientes de la aplicación, un 
 * ambiente se refiere a una instalación de la aplicación que puede tener diferentes
 * condiciones, por ejemplo, se puede tener un ambiente para desarrollo en un
 * computador local, un ambuente para pruebas en un servidor intermedio y la 
 * versión de producción en el servidor final.
 * 
 * Cada ambiente puede tener una configuración diferente de base de datos y una 
 * para el servidor de archivos (data) donde se almacenan imágenes, videos,
 * documentos, etc.
 * 
 * En la primera parte se definen variables por defecto para la base de datos
 * $database y para el servidor de archivos $data, pero al final se pueden
 * especificar condiciones diferentes para cada uno de los ambientes.
 */

/**
 * Base de datos por defecto. Se usarán estos datos si no se especifica una para
 * cada ambiente
 */
$database=array(
    "name" => "maqinato",
    "driver" => "mysql",
    "persistent" => false,
    "host"=>"localhost",
    "connections" => array(
        array(
            "name"=>"read",
            "login" => "userRead",
            "password" => "passwordRead"
        ),
        array(
            "name"=>"write",
            "login" => "userWrite",
            "password" => "passwordWrite"
        ),
        array(
            "name"=>"delete",
            "login" => "userDelete",
            "password" => "passwordDelete"
        ),
        array(
            "name"=>"all",
            "login" => "userAll",
            "password" => "passwordAll"
        )
    )
);

/**
 * Servidor de archivos por defecto
 * Configuración del acceso a archivos de la aplicación, puede ser local o 
 * externo. Normalmente se usa para un folder donde se almacenan imágenes, videos,
 * audios, documentos, etc, con los que se alimenta la aplicación.
 * Algunos ejemplos de configuración:
 *  - Acceso a los archivos dentro de un folder local
 *      "source"    =>  "local",
 *      "isSSL"     =>  false,
 *      "domain"    =>  "",         //Ignorado
 *      "bucket"    =>  "",         //Ignorado
 *      "folder"    =>  "data/",
 *      "accessKey" =>  "",         //Ignorado
 *      "secretKey" =>  ""          //Ignorado
 *  - Acceso a los archivos en un servidor externo sin SSL y sin claves de acceso
 *      "source"    =>  "external",
 *      "isSSL"     =>  false,
 *      "domain"    =>  "www.externalfileserver.com",
 *      "bucket"    =>  "",
 *      "folder"    =>  "folder_foo/data/",
 *      "accessKey" =>  "",         //Ignorado
 *      "secretKey" =>  ""          //Ignorado
 *  - Acceso a los archivos en un servidor AWS sin SSL y sin claves de acceso
 *      "source"    =>  "external",
 *      "isSSL"     =>  false,
 *      "domain"    =>  "s3.amazonaws.com",
 *      "bucket"    =>  "bucket_name",
 *      "folder"    =>  "data/",
 *      "accessKey" =>  "",         //Ignorado
 *      "secretKey" =>  ""          //Ignorado
 *  - Acceso a los archivos en un servidor AWS con SSL requiere claves de acceso
 *      "source"    =>  "external",
 *      "isSSL"     =>  true,
 *      "domain"    =>  "s3.amazonaws.com",
 *      "bucket"    =>  "bucket_name",
 *      "folder"    =>  "data/",
 *      "accessKey" =>  "KJHAJ7JKEDSJA2J9YMPD",
 *      "secretKey" =>  "HedBxjeasde3dhYnfKdwHohu03u7nzvCWDPScErC"
 */
$fileServer=array(
    /**
     * Define si se debe acceder a los archivos de la aplicación en un folder o
     * una URL extarna
     * @var mixed:
     *      false: No carga archivos para la aplicación
     *      "local": Lee los datos de un folder dentro de la aplicación
     *      "external": Lee los datos de una fuente externa por medio de una URL
     */
    "source"    =>  "local",
    
    /**
     * Define si se accede por SSL a los archivos externos
     * @var bool true para acceso seguro al servidor de archivos (debe estar 
     *           configurado en el servidor), false en otro caso
     */
    "isSSL"     =>  false,
    
    /**
     * Dominio del servidor de archivos para acceder a los datos, no incluye el 
     * protocolo, pues se define en la variable isSSL. No se usa en caso de 
     * source="local"
     * @var string Dominio en caso de source="external", por ejemplo:
     *      - "www.maqinato.com"
     *      - "s3.amazonaws.com"
     */
    "domain"    =>  "",
    
    /**
     * Bucket o contenedor, usado principalmente en servidores de datos externos
     * como AWS. No se usa en caso de source="local"
     * @var string Contenedor de archivos en caso de datos externos como AWS
     */
    "bucket"    =>  "",
        
    /**
     * Folder raíz de almacenamiento
     * @var string:
     *      - En caso de que source="local" debe ser una ruta relativa dentro
     *        del folder de la aplicación. P.e.
     *          - si la aplicación está en la ruta: "/var/www/maqinato" y el folder 
     *            de datos en "/var/www/maqinato/data/" se debe pasar a esta 
     *            variable el valor "data/"
     *          - si la aplicación está en la ruta: "/var/www/maqinato" y el folder 
     *            de datos en "/var/www/maqinato/foo/data" se debe pasar a esta 
     *            variable el valor "foo/data/"
     *      - En caso de source="external", debe ser el folder que contiene los
     *        datos. P.e.
     *          - si los datos están almacenados en "http://dataserver.com/foo/data"
     *            el valor de esta variable debe ser: "foo/data/"
     *          - si se trata de un proveedor de datos externos como AWS que requiere
     *            un bucket o contenedor, se especifica en otra variable, excluyendo
     *            en esta variable el nombre del bucket. Para un servidor
     *            "http://s3.amazonaws.com/bucket_name/data" el valor de esta
     *            variable debe ser: "data/".
     */
    "folder"    =>  "data/",
    
    /**
     * Clave de acceso al servidor de archivos, por ahora solo se usa con servidores
     * AWS.
     * @var string Clave de acceso al servidor de archivos
     */
    "accessKey" =>  "",
    
    /**
     * Clave secreta para acceso al servidor de archivos. Solo usada para AWS.
     * @var string Clave secreta para aceeder al servidor de archivos
     */
    "secretKey" =>  ""
);

/**
 * Variable que contiene los posibles ambientes de configuración de la aplicación
 * De acuerdo a las urls de cada environment el sistema detecta en qué ambiente
 * se encuentra. Por ejemplo si el sistema detecta 10.0.0.102 como host actual, 
 * se sabe que está en entorno de desarrollo 'development'.
 */
return array(
    array(
        "name"  => "development",
        "urls"  =>  array(
            "localhost",
            "127.0.0.1",
            "10.0.2.2",
            "10.0.0.102"
        ),
        "database"  => $database,
        "fileServer"  => $fileServer
    ),
    array(
        "name"  => "release",
        "urls"  =>  array(
            "www.release.candidate.server",
            "www.other.release.candidate.server",
            "01.01.01.01"
        ),
        "database"  => $database,
        "fileServer"  => $fileServer
    ),
    array(
        "name"  => "production",
        "urls"  =>  array(
            "www.production.server.com",
            "www.other.production.server.com",
            "production.server.com",
            "other.production.server.com",
            "00.00.00.00"
        ),
        "database"  =>  array(
            "name" => "maqinato",
            "driver" => "mysql",
            "persistent" => false,
            "host"=>"localhost",
            "connections" => array(
                array(
                    "name"=>"read",
                    "login" => "userRead",
                    "password" => "passwordRead"
                ),
                array(
                    "name"=>"write",
                    "login" => "userWrite",
                    "password" => "passwordWrite"
                ),
                array(
                    "name"=>"delete",
                    "login" => "userDelete",
                    "password" => "passwordDelete"
                ),
                array(
                    "name"=>"all",
                    "login" => "userAll",
                    "password" => "passwordAll"
                )
            )
        ),
        "fileServer"  => $fileServer
    )
);