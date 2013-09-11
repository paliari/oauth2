<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/UserProvider.php';

$userProvider = new UserProvider();

\Paliari\Oauth2\Oauth2Facade::frontController($userProvider);
