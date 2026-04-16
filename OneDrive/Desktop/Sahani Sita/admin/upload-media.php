<?php
require_once __DIR__ . "/../includes/auth.php";

require_admin_login();
start_admin_session();

header('Content-Type: application/json');

if (empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    http_response_code(413);
    echo json_encode(array(
        'success' => false,
        'message' => 'The selected files are too large for the current upload limit. Please choose smaller files or increase the PHP upload limit.',
    ));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
    exit;
}

if (!$db_available) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'message' => 'Database is not available.'));
    exit;
}

$uploaded_media = store_uploaded_recipe_media($_FILES['recipe_media'] ?? array());

if (!empty($uploaded_media['errors'])) {
    delete_uploaded_media_files(array_merge($uploaded_media['images'], $uploaded_media['videos']));
    http_response_code(422);
    echo json_encode(array(
        'success' => false,
        'message' => implode(' ', $uploaded_media['errors']),
    ));
    exit;
}

$items = array();

foreach ($uploaded_media['images'] as $path) {
    $items[] = array(
        'kind' => 'image',
        'path' => $path,
        'url' => '../' . media_url($path),
        'name' => basename($path),
    );
}

foreach ($uploaded_media['videos'] as $path) {
    $items[] = array(
        'kind' => 'video',
        'path' => $path,
        'url' => '../' . media_url($path),
        'name' => basename($path),
    );
}

echo json_encode(array(
    'success' => true,
    'items' => $items,
));
?>
