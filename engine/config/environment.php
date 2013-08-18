<?php
/**
 * Base de datos por defecto. Se usarán estos datos si no se especifica una para
 * cada ambiente
 */
$database=array(
    "name" => "maqinato",
    "driver" => "mysql",
    "persistent" => true,
    "host"=>"localhost",
    "connections" => array(
        array(
            "name"=>"read",
            "login" => "maqinatoRead",
            "password" => "asdasd"
        ),
        array(
            "name"=>"write",
            "login" => "maqinatoWrite",
            "password" => "asdasd"
        ),
        array(
            "name"=>"delete",
            "login" => "maqinatoDelete",
            "password" => "asdasd"
        ),
        array(
            "name"=>"all",
            "login" => "maqinato",
            "password" => "asdasd"
        )
    )
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
        "database"  => $database
    ),
    array(
        "name"  => "release",
        "urls"  =>  array(
            "www.release.candidate.server",
            "www.other.release.candidate.server",
            "01.01.01.01"
        ),
        "database"  => $database
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
            "persistent" => true,
            "host"=>"production.database.server.com",
            "connections" => array(
                array(
                    "name"=>"read",
                    "login" => "maqinatoRead",
                    "password" => "production"
                ),
                array(
                    "name"=>"write",
                    "login" => "maqinatoWrite",
                    "password" => "production"
                ),
                array(
                    "name"=>"delete",
                    "login" => "maqinatoDelete",
                    "password" => "production"
                ),
                array(
                    "name"=>"all",
                    "login" => "maqinato",
                    "password" => "production"
                )
            )
        )
    )
);