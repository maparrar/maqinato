<?php
/**
 * Variable que contiene los posibles ambientes de configuración de la aplicación
 * De acuerdo a las urls de cada environment el sistema detecta en qué ambiente
 * se encuentra. Por ejemplo si el sistema detecta 10.0.0.102 como host actual, 
 * se sabe que está en entorno de desarrollo 'development'.
 */
return array(
    "development"   =>  array(
        "urls"  =>  array(
            "localhost",
            "127.0.0.1",
            "10.0.2.2",
            "10.0.0.102"
        )
    ),
    "release"       =>  array(
        "urls"  =>  array(
            "www.release.candidate.server",
            "www.other.release.candidate.server",
            "01.01.01.01"
        )
    ),
    "production"    =>  array(
        "urls"  =>  array(
            "www.production.server.com",
            "www.other.production.server.com",
            "production.server.com",
            "other.production.server.com",
            "00.00.00.00"
        )
    )
);