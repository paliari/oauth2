<?php

require_once __DIR__."/boot.php";

$u = $userProvider->getUserDetails();
echo "<pre>";
var_export($u);
echo "</pre>";
$oauth->frontController();
$u = $userProvider->getUserDetails();
