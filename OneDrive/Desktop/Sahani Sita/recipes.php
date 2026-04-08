<?php
require_once "config.php";

$recipes = fetch_recipes($conn);

$page_title = $site_name . " | Recipes";
$current_page = "recipes";
$base_path = "";

include "includes/header.php";
?>

<section class="page-banner">
    <div class="container">
        <span class="eyebrow">Recipe gallery</span>
        <h1>All recipes in the Sahani Sita collection</h1>
        <p>Choose any recipe card to read the full ingredients list and cooking instructions.</p>
    </div>
</section>

<section class="container section-space">
    <?php if (!$db_available): ?>
        <div class="message message-error">
            <p>The database is not available yet. Import `database.sql` first.</p>
            <p><?php echo e($db_error); ?></p>
        </div>
    <?php elseif (empty($recipes)): ?>
        <div class="empty-state">
            <h3>No recipes available</h3>
            <p>Add your first recipe to the database, then refresh this page.</p>
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($recipes as $recipe): ?>
                <article class="recipe-card recipe-card-large">
                    <div class="recipe-image-wrap">
                        <img src="<?php echo e(media_url($recipe['image_path'])); ?>" alt="<?php echo e($recipe['title']); ?>">
                    </div>
                    <div class="recipe-card-body">
                        <?php if ((int) $recipe['featured'] === 1): ?>
                            <span class="tag">Featured</span>
                        <?php else: ?>
                            <span class="tag alt-tag">Everyday Pick</span>
                        <?php endif; ?>

                        <h2><?php echo e($recipe['title']); ?></h2>
                        <p><?php echo e(limit_text($recipe['description'], 150)); ?></p>
                        <div class="card-actions">
                            <a class="btn btn-small" href="recipe-detail.php?id=<?php echo (int) $recipe['id']; ?>">Open Recipe</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
