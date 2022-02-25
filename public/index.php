<?php

require "../bootstrap.php";

use Src\Controllers\CardController;
use Src\Constants\Messages;
use Src\Constants\Constants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, Constants::PREFIX_API_V1) === false) {
    header(Messages::STATUS_CODE_HEADER_NOT_FOUND);
    return;
}

$uri = explode(Constants::PREFIX_API_V1 . '/', $uri);
$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestWith = $_SERVER['HTTP_ACCEPT'] ?? null;
$authorizeString = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

if ($authorizeString === Constants::TOKEN) {
    switch ($uri[1]) {
        case 'validate-payment-information':
            $controller = new CardController($requestMethod, $requestWith);
            $controller->processRequest();
            break;
        case 'login':
            break;
        default:
            header(Messages::STATUS_CODE_HEADER_NOT_FOUND);
            break;
    }
} else {
    header(Messages::STATUS_CODE_WRONG_AUTHORIZATION);
}


