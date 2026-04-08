<?php
require_once "config.php";

$featured_recipes = fetch_recipes($conn, true, 3);

if (empty($featured_recipes)) {
    $featured_recipes = fetch_recipes($conn, false, 3);
}

$page_title = $site_name . " | Home";
$current_page = "home";
$base_path = "";

include "includes/header.php";
?>

<section class="hero-section">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Welcome to Sahani Sita Recipes</span>
            <h1>Simple recipes, bright flavors, and a basic PHP recipe website.</h1>
            <p>
                This school project shows how plain HTML, CSS, PHP and MySQL can work together
                to build a colorful and dynamic recipe platform.
            </p>
            <div class="cta-buttons">
                <a class="btn btn-primary" href="recipes.php">Browse Recipes</a>
                <a class="btn btn-secondary" href="about.php">Learn About Us</a>
            </div>
        </div>

        <div class="hero-card-stack">
            <article class="feature-note orange-card">
                <h2>Fresh ideas</h2>
                <p>Explore smoothies, pizza, salads, cupcakes and more in one easy website.</p>
            </article>
            <article class="feature-note green-card">
                <h2>Built from scratch</h2>
                <p>The pages are connected directly to MySQL using vanilla PHP.</p>
            </article>
        </div>
    </div>
</section>

<section class="container section-space">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Featured recipes</span>
            <h2>Popular dishes from the recipe collection</h2>
        </div>
        <a class="text-link" href="recipes.php">See all recipes</a>
    </div>

    <?php if (!$db_available): ?>
        <div class="message message-error">
            <p>Database connection failed. Import `database.sql` into MySQL, then refresh the page.</p>
            <p><?php echo e($db_error); ?></p>
        </div>
    <?php elseif (empty($featured_recipes)): ?>
        <div class="empty-state">
            <h3>No recipes yet</h3>
            <p>Add recipes to the database to make this section come alive.</p>
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($featured_recipes as $recipe): ?>
                <article class="recipe-card">
                    <div class="recipe-image-wrap">
                        <img src="<?php echo e(media_url($recipe['image_path'])); ?>" alt="<?php echo e($recipe['title']); ?>">
                    </div>
                    <div class="recipe-card-body">
                        <span class="tag">Featured</span>
                        <h3><?php echo e($recipe['title']); ?></h3>
                        <p><?php echo e(limit_text($recipe['description'], 125)); ?></p>
                        <a class="btn btn-small" href="recipe-detail.php?id=<?php echo (int) $recipe['id']; ?>">View Recipe</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="container section-space info-band">
    <article>
        <h3>Public interface</h3>
        <p>Visitors can browse the homepage, read about the team, explore recipe cards and open each recipe detail page.</p>
    </article>
    <article>
        <h3>Basic and clear</h3>
        <p>The structure stays simple so it is easy to explain in class and easy to maintain later.</p>
    </article>
    <article>
        <h3>Dynamic content</h3>
        <p>Recipes are stored in the database and loaded into the pages when needed.</p>
    </article>
</section>

<?php include "includes/footer.php"; ?>
