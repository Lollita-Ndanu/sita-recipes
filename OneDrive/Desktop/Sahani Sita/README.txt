================================================================================
  SAHANI SITA RECIPES — FULL PROJECT DOCUMENTATION
================================================================================
  Project name : Sahani Sita Recipes
  Built with   : HTML, CSS, PHP, MySQL
  Local URL    : http://localhost:8080
  Admin URL    : http://localhost:8080/admin
  Date         : April 2026
================================================================================


--------------------------------------------------------------------------------
  WHAT IS THIS WEBSITE?
--------------------------------------------------------------------------------
Sahani Sita Recipes is a food recipe website that lets visitors browse and read
recipes from six Kenyan chefs. The name "Sahani Sita" means "Six Plates" — one
for each chef on the team.

The website has two sides:
  1. The PUBLIC side — what any visitor sees when they open the website.
  2. The ADMIN side — a private area where the website owner can manage recipes.

Think of it like a restaurant menu online. Customers (visitors) can browse the
menu, but only the restaurant manager (admin) can add, edit, or remove items.


--------------------------------------------------------------------------------
  HOW TO RUN THE WEBSITE LOCALLY
--------------------------------------------------------------------------------
Option A — XAMPP (Apache + MySQL):
  1. Open the XAMPP Control Panel.
  2. Start Apache and MySQL.
  3. Copy the project folder into C:\xampp\htdocs\.
  4. Open phpMyAdmin (http://localhost/phpmyadmin).
  5. Create a database called recipes_db.
  6. Import the file named database.sql.
  7. Open http://localhost/Sahani%20Sita/home in your browser.

Option B — PHP Built-in Server (no Apache needed):
  1. Open a terminal inside the project folder.
  2. Run: C:\xampp\php\php.exe -S localhost:8080 router.php
  3. Open http://localhost:8080 in your browser.

DEFAULT ADMIN LOGIN
  Username : admin
  Password : admin123


--------------------------------------------------------------------------------
  PROJECT FILE STRUCTURE
--------------------------------------------------------------------------------
  index.php              — Homepage
  about.php              — About Us page
  recipes.php            — Recipe gallery page
  recipe-detail.php      — Single recipe detail page
  contact.php            — Contact page
  config.php             — Database connection and shared functions
  router.php             — Routes clean URLs to the right PHP file
  style.css              — All the website's visual styling
  database.sql           — Database tables and sample data
  images/                — All photos and videos used on the site
  images/uploads/        — Files uploaded through the admin panel
  includes/header.php    — Top navigation bar (shared across all pages)
  includes/footer.php    — Bottom footer (shared across all pages)
  includes/auth.php      — Security check for the admin area
  admin/index.php        — Admin login page
  admin/dashboard.php    — Admin control panel (manage recipes)
  admin/logout.php       — Logs the admin out


================================================================================
  PUBLIC PAGES — WHAT EACH PAGE DOES
================================================================================


--------------------------------------------------------------------------------
  PAGE 1 — HOMEPAGE (index.php)
  URL: http://localhost:8080
--------------------------------------------------------------------------------
This is the first page a visitor sees. Its job is to make a strong first
impression and invite people to explore the site.

  SECTION 1 — HERO BANNER (top of the page)
  ------------------------------------------
  A large full-screen image of Mediterranean food fills the top of the page.
  On top of it you see the website name, a short tagline, and two buttons:
  "Explore Recipes" and "About Us". This is called a "hero" section — it's
  like the front cover of a magazine. It grabs attention immediately.

  SECTION 2 — MEET OUR CHEFS
  ---------------------------
  Six cards appear in a neat grid, one for each chef. Each card has:
    - A short looping video that plays automatically (muted, no sound).
    - The chef's name.
    - Their title (e.g. "The Stew Maker", "Dough Dealer").
    - A short description of what they specialise in.

  The videos are in this order:
    Kelly    — Stew video
    Victor   — Fry video
    Lollita  — Dough video
    Stephen  — Chicken video
    Jay      — Candy video
    Sam      — Smoothie video

  The idea is to give each chef a personality and show visitors what kind of
  food they can expect to find on the site.

  SECTION 3 — FEATURED RECIPES
  -----------------------------
  This section shows a selection of recipes that have been marked as "featured"
  in the admin panel. Each recipe appears as a card with an image, title, and
  a "View Recipe" button. These cards are loaded live from the database — so
  whenever the admin marks a recipe featured, it appears here automatically.

  SECTION 4 — TESTIMONIALS
  -------------------------
  Three quotes from happy users of the website. Each card shows:
    - The quote in their own words.
    - Their name.
    - Where they are from (city, country).

  This section builds trust — it shows that real people use and enjoy the site.

  SECTION 5 — FOOTER
  -------------------
  The footer appears at the very bottom of every page. It has four columns:
    - Brand column  : The website logo, tagline, and a brief description.
    - Quick Links   : Links to Home, Recipes, and Contact.
    - Explore       : Links to About Us and the Recipe Gallery.
    - Follow Us     : Icons linking to Instagram, TikTok, and Facebook.
  Below all columns is a copyright bar: "© 2026 Sahani Sita Recipes".


--------------------------------------------------------------------------------
  PAGE 2 — ABOUT US (about.php)
  URL: http://localhost:8080/about
--------------------------------------------------------------------------------
This page tells the story of the website and the people behind it.

  SECTION 1 — HERO BANNER
  ------------------------
  A full-width image of fresh vegetables sits at the top. On top of it:
    - "About Us" label
    - Title: "The story behind Sahani Sita"
    - Subtitle explaining the six chefs and their shared passion.

  SECTION 2 — OUR STORY (PROSE BLOCKS)
  -------------------------------------
  Four short paragraphs tell the story of Sahani Sita in simple, warm language:

    Our Mission     — Why the website was created. Celebrating authentic
                      Kenyan cooking and the diversity of Kenyan cuisine.

    Fresh Ingredients — The chefs' commitment to locally sourced, seasonal,
                        quality produce.

    Made from Scratch — Everything is handmade. No pre-packaged ingredients.
                        Slow cooking, careful seasoning, intentional plating.

    Our Values        — Warmth, creativity, and respect for the craft of
                        cooking. Every meal is an invitation to gather and eat.

  SECTION 3 — TESTIMONIALS
  -------------------------
  Same three testimonial cards as the homepage (Amina, Brian, Faith). Repeating
  them here reinforces trust on the About page as well.


--------------------------------------------------------------------------------
  PAGE 3 — RECIPES GALLERY (recipes.php)
  URL: http://localhost:8080/recipes
--------------------------------------------------------------------------------
This is where visitors go to browse all the recipes available on the site.

  SECTION 1 — HERO BANNER
  ------------------------
  A full-width pizza image at the top with:
    - "Recipe Gallery" label
    - Title: "All recipes in the Sahani Sita collection" (kept on one line)
    - Subtitle inviting visitors to click a card to see full details.

  SECTION 2 — RECIPE CARDS GRID
  ------------------------------
  All recipes stored in the database appear here as cards. Each card shows:
    - A photo of the dish.
    - The recipe title.
    - A short description.
    - A "View Recipe" button.

  If the database is not connected, the page shows a friendly error message
  instead of crashing. This is called "graceful degradation."

  Clicking any card takes the visitor to the Recipe Detail page for that dish.


--------------------------------------------------------------------------------
  PAGE 4 — RECIPE DETAIL (recipe-detail.php)
  URL: http://localhost:8080/recipe?id=1  (the number changes per recipe)
--------------------------------------------------------------------------------
This page shows everything about one specific recipe.

  WHAT IS ON THIS PAGE:
    - The recipe title as a page banner heading.
    - The main dish photo.
    - A short description of the dish.
    - A full ingredients list.
    - Step-by-step cooking instructions.
    - An image gallery (if the admin uploaded extra photos).
    - A video gallery (if the admin uploaded recipe videos).
    - A "Back to Recipes" button.

  If a visitor tries to open a recipe that does not exist (e.g. a broken link),
  the page shows a helpful "Recipe missing" message instead of an error.


--------------------------------------------------------------------------------
  PAGE 5 — CONTACT (contact.php)
  URL: http://localhost:8080/contact
--------------------------------------------------------------------------------
This page lets visitors send a message to the Sahani Sita team.

  SECTION 1 — HERO BANNER
  ------------------------
  A full-bleed smoothie image fills the top of the page with a welcome message
  for the contact section.

  SECTION 2 — CONTACT DETAILS CARD (left column)
  ------------------------------------------------
  Shows how to reach the team directly:
    - Phone    : +254 712 345 678
    - WhatsApp : +254 712 345 678
    - Email    : admin@sahanisitarecipes.me

  Each item has a matching icon (phone icon, WhatsApp logo, etc.).

  SECTION 3 — SEND A MESSAGE FORM (right column)
  -----------------------------------------------
  A form with three fields:
    - Full name
    - Email address
    - Message (text area)

  HOW THE FORM WORKS:
    1. The visitor fills in all three fields and clicks "Submit Message."
    2. The PHP code checks that all fields are filled in.
    3. It also checks that the email address looks valid (e.g. has an @ symbol).
    4. If anything is wrong, a red error message appears explaining the problem.
    5. If everything is correct, a green success message appears.

  NOTE: This form does not actually send an email. It is a demonstration form
  that validates the input and shows a confirmation message. In a real website
  you would connect it to an email service like Mailgun or SendGrid.


================================================================================
  ADMIN PANEL — WHAT IT DOES
================================================================================


--------------------------------------------------------------------------------
  ADMIN LOGIN (admin/index.php)
  URL: http://localhost:8080/admin
--------------------------------------------------------------------------------
The admin area is protected. Only someone with the correct username and password
can get in. The background shows a full spaghetti food image to keep the food
theme even on the login screen.

  Login credentials:
    Username : admin
    Password : admin123

  If the wrong password is entered, the page shows an error and does not let
  the person in. This is the website's security gate.


--------------------------------------------------------------------------------
  ADMIN DASHBOARD (admin/dashboard.php)
  URL: http://localhost:8080/admin/dashboard
--------------------------------------------------------------------------------
This is the control room of the website. Everything to do with recipes is
managed from here. Think of it as the "back office" of the restaurant.

  WHAT THE ADMIN CAN DO HERE:

  CREATE A RECIPE
    Fill in a form with:
      - Recipe title (e.g. "Beef Stew")
      - Description (a short summary of the dish)
      - Ingredients (listed out)
      - Instructions (the cooking steps)
      - Main image (the primary photo shown on the recipe card)
      - Gallery images (extra photos of the dish)
      - Gallery videos (cooking process videos)
      - Featured toggle (tick this to show the recipe on the homepage)

  READ / VIEW ALL RECIPES
    A table lists every recipe in the database. The admin can see at a glance
    which recipes exist and which ones are marked as featured.

  EDIT A RECIPE
    Click the "Edit" button next to any recipe. The form fills in with the
    existing data and the admin can change anything they want, then save.

  DELETE A RECIPE
    Click the "Delete" button to permanently remove a recipe from the database.
    A confirmation step prevents accidental deletion.

  MARK AS FEATURED
    Tick the "Featured" checkbox when creating or editing a recipe. This makes
    it appear in the "Featured Recipes" section on the homepage automatically.

  UPLOAD MEDIA
    The dashboard lets the admin upload image files (JPG, PNG, WebP) and
    video files (MP4) directly from their computer. Uploaded files are saved
    into the images/uploads/ folder on the server.

  LOGOUT
    A logout button in the navigation ends the admin session securely and
    redirects back to the login page.


================================================================================
  TECHNICAL OVERVIEW (for presentation explanations)
================================================================================

  WHAT IS PHP?
  PHP is the programming language that runs on the server. When a visitor opens
  a page, PHP reads from the database and builds the HTML page before sending
  it to the browser. The visitor never sees PHP code — only the finished page.

  WHAT IS MySQL?
  MySQL is the database. It stores all the recipe data — titles, descriptions,
  ingredients, instructions, image file names, and which recipes are featured.
  Think of it as a very organised spreadsheet that PHP can read and write to.

  WHAT IS THE ROUTER?
  router.php maps clean, readable URLs (like /recipes) to the actual PHP files
  (like recipes.php). Without it, the URL would look like /recipes.php which
  is less professional. The router makes the site look polished.

  WHAT IS THE DATABASE.SQL FILE?
  This file is a snapshot of the database structure. When you import it into
  phpMyAdmin, it creates all the tables and fills them with sample data so the
  site works straight away without manually entering recipes.

  WHAT IS SESSION PROTECTION?
  When the admin logs in, PHP creates a "session" — a temporary memory that
  remembers who is logged in while the browser is open. The auth.php file
  checks for this session on every admin page. If it is missing, the user is
  redirected to the login page immediately. This prevents unauthorised access.

  WHAT IS FORM VALIDATION?
  On the contact page, PHP checks the submitted data before accepting it:
    - Are all fields filled in? (server-side check)
    - Does the email look like a real email address?
  This protects the site from empty or nonsense submissions.

  WHAT IS CSS?
  style.css contains all the visual design rules — colours, fonts, spacing,
  grid layouts, and responsive behaviour. The entire site uses one single CSS
  file so that the design stays consistent across every page.

  WHAT IS RESPONSIVE DESIGN?
  The site is designed to look good on both desktop and mobile. The recipe
  grid and chef card grid switch from multiple columns to a single column
  on smaller screens automatically.


================================================================================
  DESIGN DECISIONS (for presentation talking points)
================================================================================

  COLOUR SCHEME
    Primary orange  — warmth, food, appetite
    Deep purple     — elegance and sophistication
    White           — clean, modern, easy to read
    Dark text       — maximum readability

  TYPOGRAPHY
    Clean sans-serif font throughout. Headings are bold and large.
    "Eyebrow" labels (small uppercase text above headings) are used to give
    context before the main title — e.g. "About Us" above "The story behind
    Sahani Sita". This is a common professional web design pattern.

  FULL-BLEED HERO IMAGES
    Every public page opens with a large food photograph that fills the full
    width of the screen. This immediately communicates "food website" to the
    visitor without needing to read anything.

  AUTOPLAY VIDEOS ON CHEF CARDS
    Instead of static photos, each chef card shows a short looping food video.
    The videos are muted (no sound) so they play automatically without being
    intrusive. This makes the chef section feel alive and dynamic.

  ADMIN LOGIN BACKGROUND
    The admin login page uses a spaghetti food photo as the background. Even
    the private pages maintain the food theme, making the whole site feel
    cohesive and intentional.

  FROSTED GLASS CONTACT CARDS
    On the contact page, the cards sit on top of the smoothie background image
    using a semi-transparent white background with a blur effect. This is a
    modern design technique called "glassmorphism."

  FOUR-COLUMN FOOTER
    The footer is split into four columns: brand identity, quick navigation,
    explore section, and social media links. This is standard professional
    website footer layout.


================================================================================
  CHEFS SUMMARY
================================================================================

  Kelly    — The Stew Maker       — Slow-cooked, hearty, rich stews
  Victor   — Fry Master           — Frying and sautéing techniques
  Lollita  — Dough Dealer         — Artisan bread, pasta, and baked goods
  Stephen  — Chicken Whisperer    — Seasoned, crispy, juicy poultry dishes
  Jay      — Sweet Tooth          — Cakes, cookies, and desserts
  Sam      — Smoothie Expert      — Fruit blends and healthy drinks


================================================================================
  CONTACT DETAILS ON THE WEBSITE
================================================================================

  Phone    : +254 712 345 678
  WhatsApp : +254 712 345 678
  Email    : admin@sahanisitarecipes.me


================================================================================
  END OF DOCUMENTATION
================================================================================

