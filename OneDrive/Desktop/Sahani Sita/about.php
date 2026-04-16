<?php
require_once "config.php";

$page_title = $site_name . " | About";
$current_page = "about";
$base_path = "";

include "includes/header.php";
?>

<section class="page-hero">
    <div class="page-hero-box page-hero-box--about">
        <div class="page-hero-overlay">
            <div class="page-hero-content page-hero-content--wide">
                <span class="eyebrow">About us</span>
                <h1>The story behind Sahani Sita</h1>
                <p>Six of Kenya's finest chefs, one shared passion — crafting food that brings people together.</p>
            </div>
        </div>
    </div>
</section>

<section class="container about-prose section-space">
    <div class="about-prose-inner">

        <div class="about-prose-block">
            <h3 class="about-subheading">Our mission</h3>
            <p>
                Sahani Sita was born from a love of authentic Kenyan cooking. We believe every meal should tell a story —
                through bold spices, fresh ingredients, and recipes passed down through generations. Our six chefs
                bring their individual talents together to offer a collection of dishes that is as diverse as Kenya itself.
            </p>
        </div>

        <div class="about-prose-block">
            <h3 class="about-subheading">Fresh ingredients</h3>
            <p>
                Every dish starts with quality produce sourced locally. From market-fresh vegetables to farm-raised poultry,
                our chefs insist on ingredients that are seasonal, vibrant, and full of natural flavour. When the
                ingredient is right, the meal speaks for itself.
            </p>
        </div>

        <div class="about-prose-block">
            <h3 class="about-subheading">Made from scratch</h3>
            <p>
                Nothing is pre-packaged here. Every stew, dough, noodle, and dessert is prepared by hand — cooked slowly,
                seasoned carefully, and plated with intention. That is the Sahani Sita promise.
            </p>
        </div>

        <div class="about-prose-block">
            <h3 class="about-subheading">Our values</h3>
            <p>
                We keep our kitchen welcoming, our recipes honest, and our portions generous.
                Sahani Sita is built on warmth, creativity, and a deep respect for the craft of cooking.
                Every meal we share is an invitation to gather, eat, and enjoy.
            </p>
        </div>

    </div>
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
                        <strong>Amina Odhiambo</strong>
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
                        <strong>Brian Kamau</strong>
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
                        <strong>Faith Wanjiku</strong>
                        <span>Kisumu, Kenya</span>
                    </div>
                </footer>
            </blockquote>

        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>
