<?php
if (!isset($page_title)) {
    $page_title = $site_name;
}

if (!isset($current_page)) {
    $current_page = "";
}

if (!isset($base_path)) {
    $base_path = "";
}

if (!isset($is_admin)) {
    $is_admin = false;
}

$body_classes = array($is_admin ? 'admin-body' : 'public-body');

if ($current_page !== '') {
    $body_classes[] = 'page-' . preg_replace('/[^a-z0-9_-]/i', '-', strtolower($current_page));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo e($base_path); ?>style.css">
</head>
<body class="<?php echo e(implode(' ', $body_classes)); ?>">
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="/home">
                <img src="<?php echo e($base_path); ?>images/logo.png" alt="Sahani Sita Recipes logo">
                <span>
                    <strong><?php echo e($site_name); ?></strong>
                   
                </span>
            </a>

            <button class="menu-toggle" type="button" data-nav-toggle aria-expanded="false" aria-label="Open navigation">
                Menu
            </button>

            <nav class="site-nav" data-nav-panel>
                <a class="<?php echo $current_page === 'home' ? 'active' : ''; ?>" href="/home">Home</a>
                <a class="<?php echo $current_page === 'recipes' ? 'active' : ''; ?>" href="/recipes">Recipes</a>
                <a class="<?php echo $current_page === 'about' ? 'active' : ''; ?>" href="/about">About us</a>
                <a class="<?php echo $current_page === 'contact' ? 'active' : ''; ?>" href="/contact">Connect</a>

                <?php if ($is_admin): ?>
                    <a class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>" href="/admin/dashboard">Dashboard</a>
                    <a href="/admin/logout">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="page-shell">
