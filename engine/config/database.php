<?php
return array(
    "driver" => "mysql",
    "persistent" => false,
    "database" => "maqinato",
    "read"=>array(
        "login" => "maqinatoRead",
        "password" => "asdasd"
     ),
    "write"=>array(
        "login" => "maqinatoWrite",
        "password" => "asdasd"
     ),
    "delete"=>array(
        "login" => "maqinatoDelete",
        "password" => "asdasd"
     ),
    "all"=>array(
        "login" => "maqinato",
        "password" => "asdasd"
     ),
    "host"=>array(
        "development"=>"localhost",
        "testing"=>"localhost",
        "release"=>"release.database.server.com",
        "production"=>"production.database.server.com"
    )
);