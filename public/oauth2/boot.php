<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/UserProvider.php';

$connectionParams = array(
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'dbname' => 'test',
    'user' => 'root',
    'password' => '',
    'service' => true,
    'charset' => 'UTF8',
    'driverOptions' => array(
        'charset' => 'UTF8',
    ),
);

$connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

$storage = new \Paliari\Oauth2ServerFacade\Storage($connection, array('user_table' => 'usuarios'));

$oauth = new \Paliari\Oauth2ServerFacade\Oauth2Facade($storage);

$userProvider = new UserProvider($storage);
$oauth->setUserProvider($userProvider);
