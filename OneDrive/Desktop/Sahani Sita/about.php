<?php
require_once "config.php";

$page_title = $site_name . " | About";
$current_page = "about";
$base_path = "";

include "includes/header.php";
?>

<section class="page-banner">
    <div class="container">
        <span class="eyebrow">About the project</span>
        <h1>Why we built Sahani Sita Recipes</h1>
        <p>This basic website was designed for a school project that focuses on web fundamentals and custom CMS development.</p>
    </div>
</section>

<section class="container section-space about-layout">
    <article class="content-card">
        <h2>Project goal</h2>
        <p>
            Sahani Sita Recipes demonstrates how a dynamic website can be built from scratch without using heavy frameworks.
            The public pages help users discover recipes in a simple and colorful way.
        </p>
    </article>

    <article class="content-card">
        <h2>Technologies used</h2>
        <p>
            The frontend uses plain HTML and CSS, the backend uses vanilla PHP, and the recipe data is stored in a MySQL database.
            A small amount of JavaScript is used only where necessary, such as the mobile navigation menu.
        </p>
    </article>

    <article class="content-card">
        <h2>What makes it dynamic</h2>
        <p>
            Recipes are not hard-coded into every page. Instead, they are stored in MySQL and loaded into the website using PHP.
            This means one recipe can be created once and displayed anywhere it is needed.
        </p>
    </article>

    <article class="content-card purple-tint">
        <h2>Team values</h2>
        <p>
            We wanted the website to feel welcoming, colorful and easy to use. The orange, yellow, green and purple palette helps the site feel lively without making the layout complicated.
        </p>
    </article>
</section>

<?php include "includes/footer.php"; ?>
