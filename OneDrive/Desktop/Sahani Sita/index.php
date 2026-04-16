<?php
require_once "config.php";

$all_recipes = fetch_recipes($conn, false, 0);
$featured_recipes = fetch_recipes($conn, true, 3);

if (empty($featured_recipes)) {
    $featured_recipes = array_slice($all_recipes, 0, 3);
}

$recipe_count = count($all_recipes);

$page_title = $site_name . " | Home";
$current_page = "home";
$base_path = "";

include "includes/header.php";
?>

<section class="home-simple-hero">
    <div class="home-simple-hero-box">
        <div class="home-simple-hero-overlay">
            <div class="hero-col hero-left">
                <h1>Tantalizing meals made by six of Kenya's best chefs</h1>
            </div>

            <div class="hero-col hero-right">
                <div class="hero-meta">
                    <p class="home-simple-intro">
                        Explore simple dishes, colorful plates, sweet treats, and everyday cooking ideas
                        designed to make homemade food feel exciting and easy to enjoy.
                    </p>
                </div>

                <div class="home-simple-actions">
                    <a class="btn btn-primary" href="/recipes">Go to Recipes</a>
                    <a class="btn btn-secondary" href="/contact">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container chef-section section-space">
    <div class="section-heading section-heading-stack">
        <div>
            <span class="eyebrow">MEET OUR CHEFS</span>
            <h2>Get to know the culinary experts behind Sahani Sita</h2>
        </div>
    </div>

    <div class="chef-grid">

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/STEW.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Kelly</h3>
                <span class="chef-title">The Stew Maker</span>
                <p class="chef-desc">Kelly's slow-cooked stews are rich, hearty, and full of depth — the kind of comfort food that warms you from the inside out.</p>
            </div>
        </article>

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/fry.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Victor</h3>
                <span class="chef-title">Fry Master</span>
                <p class="chef-desc">Victor's mastery of frying techniques turns ordinary ingredients into crispy, golden delights that are simply irresistible.</p>
            </div>
        </article>

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/dough.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Lollita</h3>
                <span class="chef-title">Dough Dealer</span>
                <p class="chef-desc">Lollita is the dough queen — from artisan breads to handmade pasta, she shapes and bakes everything with passion and precision.</p>
            </div>
        </article>

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/chicken.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Stephen</h3>
                <span class="chef-title">Chicken Whisperer</span>
                <p class="chef-desc">Stephen has an uncanny gift for seasoning poultry to perfection, turning every chicken dish into a crispy, juicy masterpiece.</p>
            </div>
        </article>

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/candy.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Jay</h3>
                <span class="chef-title">Sweet Tooth</span>
                <p class="chef-desc">Jay's sweet creations range from velvety cakes to crispy cookies — desserts so good they rarely make it off the tray.</p>
            </div>
        </article>

        <article class="chef-card">
            <div class="chef-card-img">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo $base_path; ?>images/smoothie.mp4" type="video/mp4">
                </video>
            </div>
            <div class="chef-card-body">
                <h3 class="chef-name">Sam</h3>
                <span class="chef-title">Smoothie Expert</span>
                <p class="chef-desc">Sam blends fresh fruits, vegetables, and superfoods into vibrant smoothies that are both delicious and nutritious.</p>
            </div>
        </article>

    </div>
</section>

<section class="container home-simple-featured section-space">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Featured recipes</span>
            <h2>Popular dishes from the Sahani Sita recipe collection.</h2>
        </div>
        <a class="text-link" href="/recipes">See all recipes</a>
    </div>

    <?php if (!$db_available): ?>
        <div class="message message-error">
            <p>The recipe list is not available right now.</p>
            <p><?php echo e($db_error); ?></p>
        </div>
    <?php elseif (empty($featured_recipes)): ?>
        <div class="empty-state home-simple-empty-state">
            <h3>No recipes available yet.</h3>
            <p>Add recipes to the database so they can appear on the homepage.</p>
        </div>
    <?php else: ?>
        <div class="card-grid home-simple-card-grid">
            <?php foreach ($featured_recipes as $recipe): ?>
                <article class="recipe-card home-simple-recipe-card">
                    <div class="recipe-image-wrap home-simple-recipe-image">
                        <img src="<?php echo e(media_url($recipe['image_path'])); ?>" alt="<?php echo e($recipe['title']); ?>">
                    </div>
                    <div class="recipe-card-body">
                        <span class="tag">Featured</span>
                        <h3><?php echo e($recipe['title']); ?></h3>
                        <p><?php echo e(limit_text($recipe['description'], 125)); ?></p>
                        <a class="btn btn-small" href="/recipe/<?php echo (int) $recipe['id']; ?>">View this Recipe</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="about-testimonials">
    <div class="container">
        <div class="section-heading section-heading-stack">
            <div>
                <span class="eyebrow">Kind words</span>
                <h2>What people are saying...</h2>
            </div>
        </div>

        <div class="testimonial-grid">

            <blockquote class="testimonial-card">
                <p class="testimonial-text">
                    &ldquo;Sahani Sita is my go-to every single holiday season. Whether it&rsquo;s Christmas,
                    Easter, or a family celebration, I get every recipe from here. The instructions are so clear
                    that even my kids can follow along!&rdquo;
                </p>
                <footer class="testimonial-author">
                    <div>
                        <strong>Tracy Waiyaki</strong>
                        <span>Nairobi, Kenya</span>
                    </div>
                </footer>
            </blockquote>

            <blockquote class="testimonial-card">
                <p class="testimonial-text">
                    &ldquo;I discovered Sahani Sita when I was looking for a good stew recipe and I haven&rsquo;t
                    left since. Kelly&rsquo;s stew recipe alone is worth bookmarking this site. My whole family
                    now requests it every Sunday.&rdquo;
                </p>
                <footer class="testimonial-author">
                    <div>
                        <strong>Fanuel Tsuma</strong>
                        <span>Mombasa, Kenya</span>
                    </div>
                </footer>
            </blockquote>

            <blockquote class="testimonial-card">
                <p class="testimonial-text">
                    &ldquo;The smoothie recipes by Victor changed my mornings completely. I used to skip breakfast
                    but now I wake up excited to try a new blend. Sahani Sita makes healthy eating feel fun
                    and totally effortless.&rdquo;
                </p>
                <footer class="testimonial-author">
                    <div>
                        <strong>Angelo Marie Iraganje</strong>
                        <span>Gitega, Burundi</span>
                    </div>
                </footer>
            </blockquote>

        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>
