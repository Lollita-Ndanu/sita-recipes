<?php
require_once "config.php";

$recipe_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$recipe = fetch_recipe_by_id($conn, $recipe_id);

$gallery_images = $recipe ? parse_media_list($recipe['gallery_images'] ?? '') : array();
$gallery_videos = $recipe ? parse_media_list($recipe['gallery_videos'] ?? '') : array();

$page_title = $recipe ? $site_name . " | " . $recipe['title'] : $site_name . " | Recipe Detail";
$current_page = "recipes";
$base_path = "";

include "includes/header.php";
?>

<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Single recipe page</span>
        <h1><?php echo $recipe ? e($recipe['title']) : 'Recipe not found'; ?></h1>
        <p>
            <?php if ($recipe): ?>
                Read the ingredients, follow the instructions and enjoy a simple homemade dish.
            <?php else: ?>
                The recipe you are looking for does not exist or the database is not ready yet.
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="container section-space">
    <?php if (!$db_available): ?>
        <div class="message message-error">
            <p>The database is not available yet. Import `database.sql` first.</p>
            <p><?php echo e($db_error); ?></p>
        </div>
    <?php elseif (!$recipe): ?>
        <div class="empty-state">
            <h3>Recipe missing</h3>
            <p>Please go back to the gallery and choose another recipe.</p>
            <a class="btn btn-primary" href="recipes.php">Back to Recipes</a>
        </div>
    <?php else: ?>
        <article class="detail-layout">
            <div class="detail-image">
                <img src="<?php echo e(media_url($recipe['image_path'])); ?>" alt="<?php echo e($recipe['title']); ?>">
            </div>

            <div class="detail-content">
                <span class="tag"><?php echo (int) $recipe['featured'] === 1 ? 'Featured Dish' : 'Recipe'; ?></span>
                <p class="detail-summary"><?php echo e($recipe['description']); ?></p>

                <?php if (!empty($gallery_images)): ?>
                    <section class="detail-box media-box">
                        <h2>Image Gallery</h2>
                        <div class="media-grid">
                            <?php foreach ($gallery_images as $media_image): ?>
                                <div class="media-card">
                                    <img src="<?php echo e(media_url($media_image)); ?>" alt="<?php echo e($recipe['title']); ?> gallery image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($gallery_videos)): ?>
                    <section class="detail-box media-box">
                        <h2>Recipe Videos</h2>
                        <div class="media-grid video-grid">
                            <?php foreach ($gallery_videos as $media_video): ?>
                                <div class="media-card media-video-card">
                                    <video controls autoplay muted loop playsinline preload="metadata">
                                        <source src="<?php echo e(media_url($media_video)); ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <div class="detail-columns">
                    <section class="detail-box">
                        <h2>Ingredients</h2>
                        <!-- nl2br keeps each ingredient on its own line when stored as plain text in MySQL. -->
                        <p><?php echo format_multiline($recipe['ingredients']); ?></p>
                    </section>

                    <section class="detail-box">
                        <h2>Instructions</h2>
                        <p><?php echo format_multiline($recipe['instructions']); ?></p>
                    </section>
                </div>

                <a class="btn btn-secondary" href="recipes.php">Back to Recipe Gallery</a>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
