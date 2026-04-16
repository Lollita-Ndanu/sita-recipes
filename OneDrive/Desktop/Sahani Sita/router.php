<?php
$request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$request_path = $request_path === false ? '/' : $request_path;
$document_root = __DIR__;

if ($request_path !== '/') {
    $existing_path = realpath($document_root . $request_path);

    if ($existing_path && strncmp($existing_path, $document_root, strlen($document_root)) === 0 && is_file($existing_path)) {
        return false;
    }
}

$routes = array(
    '/' => '/index.php',
    '/home' => '/index.php',
    '/recipes' => '/recipes.php',
    '/about' => '/about.php',
    '/contact' => '/contact.php',
    '/admin' => '/admin/index.php',
    '/admin/dashboard' => '/admin/dashboard.php',
    '/admin/logout' => '/admin/logout.php',
    '/admin/upload-media' => '/admin/upload-media.php',
);

if (isset($routes[$request_path])) {
    require $document_root . $routes[$request_path];
    return true;
}

if (preg_match('#^/recipe/(\d+)/?$#', $request_path, $matches)) {
    $_GET['id'] = $matches[1];
    $_REQUEST['id'] = $matches[1];
    require $document_root . '/recipe-detail.php';
    return true;
}

http_response_code(404);
require $document_root . '/index.php';