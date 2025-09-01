<?php
if (!extension_loaded('phalcon')) {
    die('Phalcon extension not loaded!');
}

// Autoloader ??? Composer
require_once '../vendor/autoload.php';

require_once '../app/controllers/IndexController.php';
require_once '../app/controllers/WebhookController.php';
require_once '../app/controllers/UsersController.php';
require_once '../app/controllers/TestController.php';


$uri = $_SERVER['REQUEST_URI'] ?? '/';

$uri = parse_url($uri, PHP_URL_PATH);

switch ($uri) {
    case '/':
        $controller = new IndexController();
        $controller->indexAction();
        break;
        
    case '/webhook':
        $controller = new WebhookController();
        $controller->indexAction();
        break;
        
    case '/users':
        $controller = new UsersController();
        $controller->indexAction();
        break;
        
    case '/test-mongo':
        $controller = new TestController();
        $controller->mongoAction();
        break;
        
    default:
        http_response_code(404);
        echo '404 Not Found';
}
