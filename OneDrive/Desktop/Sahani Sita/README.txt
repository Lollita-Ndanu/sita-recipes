SAHANI SITA RECIPES
===================

Project type:
Basic dynamic recipe website with a custom CMS built using HTML, CSS, PHP and MySQL.

MAIN FILES
----------
index.php                - Homepage
recipes.php              - Recipe gallery
recipe-detail.php        - Single recipe page
about.php                - About page
contact.php              - Contact page
style.css                - Main stylesheet
database.sql             - MySQL database structure and sample data
includes/header.php      - Shared header and navigation
includes/footer.php      - Shared footer and JavaScript
includes/auth.php        - Admin session protection
admin/login.php          - Admin login page
admin/dashboard.php      - CMS dashboard for CRUD
admin/logout.php         - Logout page

DEFAULT ADMIN LOGIN
-------------------
Username: admin
Password: admin123

HOW TO RUN THE PROJECT IN XAMPP
-------------------------------
1. Start Apache and MySQL from the XAMPP Control Panel.
2. Copy the project folder into your XAMPP htdocs folder if needed.
3. Open phpMyAdmin in your browser.
4. Import the file named database.sql.
5. Open the website in your browser.

Example local URLs:
- http://localhost/Sahani%20Sita/index.php
- http://localhost/Sahani%20Sita/admin/login.php

DATABASE DETAILS
----------------
Database name: recipes_db

The SQL file creates:
- admins table
- recipes table
- sample recipes
- one default admin account

WEBSITE FEATURES
----------------
Public side:
- Homepage with featured recipes
- Recipes gallery page
- Dynamic single recipe page
- About page
- Contact page with demo form validation

Admin CMS:
- Admin login with session protection
- Create recipes
- Read existing recipes
- Update recipes
- Delete recipes
- Mark recipes as featured

NOTES
-----
- Recipe images are loaded from the images folder.
- The contact form is a demo form and does not send real emails.
- The CMS stores image file names, not uploaded files, to keep the project basic.
- The code includes comments in important places for explanation.

TESTING DONE
------------
- MySQL database imported successfully.
- PHP syntax check passed for all PHP files.
- Public pages loaded successfully.
- Admin login tested successfully.
- Recipe create and delete flow tested successfully.
