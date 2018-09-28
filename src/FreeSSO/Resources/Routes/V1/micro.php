<?php
require_once __DIR__ . '/broker.php';
require_once __DIR__ . '/group.php';
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/sso.php';

$routes = array_merge(
    $sso_broker_routes,
    $sso_group_routes,
    $sso_user_routes,
    $sso_sso_routes
);