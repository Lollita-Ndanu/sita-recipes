CREATE DATABASE IF NOT EXISTS recipes_db;
USE recipes_db;

DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS recipes;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    gallery_images TEXT NOT NULL,
    gallery_videos TEXT NOT NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash)
VALUES ('admin', SHA2('admin123', 256));

INSERT INTO recipes (title, description, ingredients, instructions, image_path, gallery_images, gallery_videos, featured) VALUES
(
    'Tropical Sunrise Smoothie',
    'A bright fruit smoothie that is simple to make for breakfast or a quick afternoon refreshment.',
    '2 bananas\n1 cup mango chunks\n1 cup pineapple chunks\n1 cup milk\n1 tablespoon honey\nIce cubes',
    'Add the bananas, mango, pineapple and milk into a blender.\nBlend until smooth.\nAdd honey and ice cubes.\nBlend again and serve immediately.',
    'phamkhanhquynhtrang-smoothie-6760874.jpg',
    'phamkhanhquynhtrang-smoothie-6760874.jpg',
    '211185.mp4',
    1
),
(
    'Garden Veggie Pizza',
    'A colorful pizza topped with vegetables and cheese for a fun homemade lunch or dinner.',
    '1 pizza base\n1 cup pizza sauce\n1 cup grated cheese\n1 green pepper\n1 tomato\n1 small onion\n1 teaspoon mixed herbs',
    'Spread pizza sauce over the base.\nAdd cheese and sliced vegetables.\nSprinkle mixed herbs on top.\nBake until the crust is golden and the cheese has melted.',
    'hoaluu-pizza-2589575.jpg',
    'hoaluu-pizza-2589575.jpg\nwow_pho-pizza-712667.jpg',
    '143420-782373959.mp4',
    1
),
(
    'Color Burst Cupcakes',
    'Soft cupcakes with a cheerful look that fit perfectly into a playful recipe website.',
    '2 cups flour\n1 cup sugar\n2 eggs\n1 cup milk\n1 teaspoon vanilla\n2 teaspoons baking powder',
    'Mix the dry ingredients in one bowl.\nWhisk the eggs, milk and vanilla in another bowl.\nCombine both mixtures until smooth.\nPour into cupcake liners and bake until done.',
    'cegoh-cupcakes-1133146.jpg',
    'cegoh-cupcakes-1133146.jpg',
    '',
    0
),
(
    'Fresh Harvest Salad',
    'A simple salad packed with fresh vegetables for a light and healthy meal.',
    'Lettuce leaves\n1 cucumber\n2 tomatoes\n1 carrot\nOlive oil\nSalt\nLemon juice',
    'Wash and slice all vegetables.\nPlace them in a large bowl.\nMix olive oil, lemon juice and salt for the dressing.\nPour the dressing over the salad and toss gently.',
    'jerzygorecki-vegetables-1584999.jpg',
    'jerzygorecki-vegetables-1584999.jpg',
    '',
    1
),
(
    'Mediterranean Rice Bowl',
    'A balanced bowl with rice and vegetables that is easy for beginners to prepare.',
    '2 cups cooked rice\n1 cup mixed vegetables\n1 tablespoon olive oil\n1 teaspoon garlic\nSalt\nBlack pepper',
    'Heat olive oil in a pan.\nAdd garlic and mixed vegetables.\nCook for a few minutes until tender.\nServe the vegetables over warm rice and season with salt and pepper.',
    'lukasbieri-mediterranean-cuisine-2378758.jpg',
    'lukasbieri-mediterranean-cuisine-2378758.jpg',
    '',
    0
);
