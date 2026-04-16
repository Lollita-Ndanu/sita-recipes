<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$site_name = "Sahani Sita Recipes";

// Live database credentials
$db_host = "sql310.byetcluster.com";
$db_user = "if0_41609961";
$db_pass = "Group2Sita";
$db_name = "if0_41609961_sahanisitarecipes";

mysqli_report(MYSQLI_REPORT_OFF);

$conn = false;
$db_error = "";

$mysqli = mysqli_init();

if ($mysqli) {
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);

    if (@$mysqli->real_connect($db_host, $db_user, $db_pass, $db_name)) {
        $conn = $mysqli;
    } else {
        $db_error = mysqli_connect_error() ?: $mysqli->connect_error;
    }
} else {
    $db_error = "Could not initialize the database connection.";
}

$db_available = $conn instanceof mysqli && !$conn->connect_error;

if ($db_available) {
    $conn->set_charset("utf8mb4");
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function format_multiline($value)
{
    return nl2br(e($value));
}

function limit_text($text, $limit = 150)
{
    $text = trim((string) $text);

    if ($text === "") {
        return "";
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit)) . "...";
}

function parse_media_list($value)
{
    $items = preg_split("/(\r\n|\n|\r)/", trim((string) $value));
    $clean_items = array();

    foreach ($items as $item) {
        $item = trim((string) $item);

        if ($item !== "") {
            $clean_items[] = $item;
        }
    }

    return array_values(array_unique($clean_items));
}

function normalize_media_text($value)
{
    return implode("\n", parse_media_list($value));
}

function media_upload_directory()
{
    return __DIR__ . "/images/uploads";
}

function ensure_media_upload_directory()
{
    $directory = media_upload_directory();

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    return $directory;
}

function store_uploaded_recipe_media($files)
{
    $result = array(
        'images' => array(),
        'videos' => array(),
        'errors' => array(),
    );

    if (!isset($files['name']) || !is_array($files['name'])) {
        return $result;
    }

    $allowed_extensions = array(
        'jpg' => 'images',
        'jpeg' => 'images',
        'png' => 'images',
        'gif' => 'images',
        'webp' => 'images',
        'mp4' => 'videos',
        'webm' => 'videos',
        'mov' => 'videos',
        'ogg' => 'videos',
    );

    $upload_directory = ensure_media_upload_directory();

    foreach ($files['name'] as $index => $original_name) {
        $error_code = $files['error'][$index] ?? UPLOAD_ERR_NO_FILE;

        if ($error_code === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($error_code !== UPLOAD_ERR_OK) {
            $result['errors'][] = 'One of the selected files could not be uploaded.';
            continue;
        }

        $temporary_name = $files['tmp_name'][$index] ?? '';
        $extension = strtolower(pathinfo((string) $original_name, PATHINFO_EXTENSION));

        if (!isset($allowed_extensions[$extension])) {
            $result['errors'][] = 'Only image and video files are allowed.';
            continue;
        }

        $base_name = pathinfo((string) $original_name, PATHINFO_FILENAME);
        $safe_base_name = preg_replace('/[^A-Za-z0-9_-]/', '-', $base_name);
        $safe_base_name = trim((string) $safe_base_name, '-');

        if ($safe_base_name === '') {
            $safe_base_name = 'media-file';
        }

        $new_file_name = $safe_base_name . '-' . uniqid() . '.' . $extension;
        $relative_path = 'uploads/' . $new_file_name;
        $destination_path = $upload_directory . '/' . $new_file_name;

        if (!move_uploaded_file($temporary_name, $destination_path)) {
            $result['errors'][] = 'A selected file could not be saved.';
            continue;
        }

        $bucket = $allowed_extensions[$extension];
        $result[$bucket][] = $relative_path;
    }

    $result['images'] = array_values($result['images']);
    $result['videos'] = array_values($result['videos']);
    $result['errors'] = array_values(array_unique($result['errors']));

    return $result;
}

function delete_uploaded_media_files($paths)
{
    foreach ($paths as $path) {
        $path = trim((string) $path);

        if ($path === '' || strpos($path, 'uploads/') !== 0) {
            continue;
        }

        $full_path = __DIR__ . '/images/' . str_replace('..', '', $path);

        if (is_file($full_path)) {
            @unlink($full_path);
        }
    }
}

function media_url($path, $base = "images/")
{
    $path = ltrim(trim((string) $path), '/\\');

    if ($path === '') {
        return $base;
    }

    if (strpos($path, 'uploads/') === 0) {
        return $base . $path;
    }

    return $base . basename($path);
}

function fetch_recipes($conn, $featured_only = false, $limit = 0)
{
    if (!$conn || $conn->connect_error) {
        return array();
    }

    $sql = "SELECT id, title, description, ingredients, instructions, image_path, gallery_images, gallery_videos, featured, created_at FROM recipes";

    if ($featured_only) {
        $sql .= " WHERE featured = 1";
    }

    $sql .= " ORDER BY created_at DESC, id DESC";

    if ($limit > 0) {
        $sql .= " LIMIT " . (int) $limit;
    }

    $result = $conn->query($sql);
    $recipes = array();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    }

    return $recipes;
}

function fetch_recipe_by_id($conn, $recipe_id)
{
    if (!$conn || $conn->connect_error || $recipe_id <= 0) {
        return null;
    }

    $stmt = $conn->prepare("SELECT id, title, description, ingredients, instructions, image_path, gallery_images, gallery_videos, featured, created_at FROM recipes WHERE id = ? LIMIT 1");

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $recipe;
}
?>
