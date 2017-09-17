<?php

include_once './config/config.php';
include_once './libs/sql.php';
include_once './libs/mysql.php';
include_once './libs/postgresql.php';
include_once './libs/arecord.php';
include_once './libs/arecordPg.php';
include_once './libs/userMySql.php';
include_once './libs/userPostgresql.php';



$user = new UserPostgresql();

/*$user->name = "test.com";
$res = $user->save();*/

$user->id = 4;
$res = $user->delete();

/*$user = new UserMySql();

$user->key = "ppps";
$user->data = "test@test.com";
$res = $user->save();

$user->id = 77;
$res = $user->delete();*/

include_once 'templates/index.php';

