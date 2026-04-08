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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo e($base_path); ?>style.css">
</head>
<body class="<?php echo $is_admin ? 'admin-body' : 'public-body'; ?>">
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="<?php echo e($base_path); ?>index.php">
                <img src="<?php echo e($base_path); ?>images/logo.png" alt="Sahani Sita Recipes logo">
                <span>
                    <strong><?php echo e($site_name); ?></strong>
                    <small><?php echo $is_admin ? 'Simple recipe CMS' : 'Colorful homemade inspiration'; ?></small>
                </span>
            </a>

            <button class="menu-toggle" type="button" data-nav-toggle aria-expanded="false" aria-label="Open navigation">
                Menu
            </button>

            <nav class="site-nav" data-nav-panel>
                <a class="<?php echo $current_page === 'home' ? 'active' : ''; ?>" href="<?php echo e($base_path); ?>index.php">Home</a>
                <a class="<?php echo $current_page === 'recipes' ? 'active' : ''; ?>" href="<?php echo e($base_path); ?>recipes.php">Recipes</a>
                <a class="<?php echo $current_page === 'about' ? 'active' : ''; ?>" href="<?php echo e($base_path); ?>about.php">About</a>
                <a class="<?php echo $current_page === 'contact' ? 'active' : ''; ?>" href="<?php echo e($base_path); ?>contact.php">Contact</a>

                <?php if ($is_admin): ?>
                    <a class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>" href="<?php echo e($base_path); ?>admin/dashboard.php">Dashboard</a>
                    <a href="<?php echo e($base_path); ?>admin/logout.php">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="page-shell">
