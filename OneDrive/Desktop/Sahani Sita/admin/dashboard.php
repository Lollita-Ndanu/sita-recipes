<?php
require_once "../includes/auth.php";

require_admin_login();
start_admin_session();

$form_error = "";
$status_message = "";

function remove_media_items($items, $items_to_remove)
{
    if (empty($items_to_remove)) {
        return array_values($items);
    }

    return array_values(array_filter($items, function ($item) use ($items_to_remove) {
        return !in_array($item, $items_to_remove, true);
    }));
}

function cleanup_new_uploads($uploaded_media)
{
    delete_uploaded_media_files(array_merge($uploaded_media['images'] ?? array(), $uploaded_media['videos'] ?? array()));
}

function remove_staged_media_items($items, $items_to_remove)
{
    return remove_media_items($items, $items_to_remove);
}

$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editing_recipe = $edit_id > 0 ? fetch_recipe_by_id($conn, $edit_id) : null;
$display_existing_images = $editing_recipe ? parse_media_list($editing_recipe['gallery_images'] ?? '') : array();
$display_existing_videos = $editing_recipe ? parse_media_list($editing_recipe['gallery_videos'] ?? '') : array();

$form_data = array(
    'title' => $editing_recipe['title'] ?? '',
    'description' => $editing_recipe['description'] ?? '',
    'ingredients' => $editing_recipe['ingredients'] ?? '',
    'instructions' => $editing_recipe['instructions'] ?? '',
    'image_path' => $editing_recipe['image_path'] ?? '',
    'gallery_images' => $editing_recipe['gallery_images'] ?? '',
    'gallery_videos' => $editing_recipe['gallery_videos'] ?? '',
    'staged_images' => '',
    'staged_videos' => '',
    'featured' => isset($editing_recipe['featured']) ? (int) $editing_recipe['featured'] : 0,
);

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'created') {
        $status_message = 'Recipe created successfully.';
    } elseif ($_GET['status'] === 'updated') {
        $status_message = 'Recipe updated successfully.';
    } elseif ($_GET['status'] === 'deleted') {
        $status_message = 'Recipe deleted successfully.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (!$db_available) {
        $form_error = 'The database is not ready yet. Import database.sql first.';
    } elseif ($action === 'create' || $action === 'update') {
        // These values come from the dashboard form and are validated before saving.
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $keep_existing_media = isset($_POST['keep_existing_media']) ? 1 : 0;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $recipe_id = isset($_POST['recipe_id']) ? (int) $_POST['recipe_id'] : 0;
        $cover_choice = trim($_POST['cover_choice'] ?? '');
        $staged_images = parse_media_list($_POST['uploaded_images'] ?? '');
        $staged_videos = parse_media_list($_POST['uploaded_videos'] ?? '');
        $requested_remove_staged_images = parse_media_list($_POST['remove_uploaded_images'] ?? '');
        $requested_remove_staged_videos = parse_media_list($_POST['remove_uploaded_videos'] ?? '');

        $staged_images = remove_staged_media_items($staged_images, $requested_remove_staged_images);
        $staged_videos = remove_staged_media_items($staged_videos, $requested_remove_staged_videos);

        if (!empty($requested_remove_staged_images) || !empty($requested_remove_staged_videos)) {
            delete_uploaded_media_files(array_merge($requested_remove_staged_images, $requested_remove_staged_videos));
        }

        $base_recipe = $editing_recipe;

        if ($action === 'update' && (!$base_recipe || $recipe_id !== (int) $base_recipe['id'])) {
            $base_recipe = fetch_recipe_by_id($conn, $recipe_id);
        }

        $all_current_existing_images = parse_media_list($base_recipe['gallery_images'] ?? '');
        $all_current_existing_videos = parse_media_list($base_recipe['gallery_videos'] ?? '');

        $requested_remove_images = parse_media_list($_POST['remove_existing_images'] ?? '');
        $requested_remove_videos = parse_media_list($_POST['remove_existing_videos'] ?? '');

        if ($keep_existing_media === 1) {
            $existing_images = remove_media_items($all_current_existing_images, $requested_remove_images);
            $existing_videos = remove_media_items($all_current_existing_videos, $requested_remove_videos);
            $removed_existing_images = array_values(array_intersect($all_current_existing_images, $requested_remove_images));
            $removed_existing_videos = array_values(array_intersect($all_current_existing_videos, $requested_remove_videos));
        } else {
            $existing_images = array();
            $existing_videos = array();
            $removed_existing_images = $all_current_existing_images;
            $removed_existing_videos = $all_current_existing_videos;
        }

        $display_existing_images = $existing_images;
        $display_existing_videos = $existing_videos;

        $all_images = array_values(array_unique(array_merge($existing_images, $staged_images)));
        $all_videos = array_values(array_unique(array_merge($existing_videos, $staged_videos)));
        $image_path = '';

        if (strpos($cover_choice, 'existing:') === 0) {
            $requested_cover = substr($cover_choice, 9);

            if (in_array($requested_cover, $all_images, true)) {
                $image_path = $requested_cover;
            }
        } elseif (strpos($cover_choice, 'new:') === 0) {
            $requested_index = (int) substr($cover_choice, 4);

            if (isset($staged_images[$requested_index])) {
                $image_path = $staged_images[$requested_index];
            }
        }

        if ($image_path === '' && !empty($all_images)) {
            $image_path = $all_images[0];
        }

        $gallery_images = implode("\n", $all_images);
        $gallery_videos = implode("\n", $all_videos);

        $form_data = array(
            'title' => $title,
            'description' => $description,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
            'image_path' => $image_path,
            'gallery_images' => $gallery_images,
            'gallery_videos' => $gallery_videos,
            'staged_images' => implode("\n", $staged_images),
            'staged_videos' => implode("\n", $staged_videos),
            'featured' => $featured,
        );

        if ($title === '' || $description === '' || $ingredients === '' || $instructions === '') {
            $form_error = 'Please fill in every recipe field before saving.';
        } elseif ($image_path === '') {
            $form_error = 'Please upload at least one image for the recipe.';
        }

        if ($form_error === '' && $action === 'create') {
            $stmt = $conn->prepare("INSERT INTO recipes (title, description, ingredients, instructions, image_path, gallery_images, gallery_videos, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param("sssssssi", $title, $description, $ingredients, $instructions, $image_path, $gallery_images, $gallery_videos, $featured);
                $stmt->execute();
                $stmt->close();

                header("Location: dashboard.php?status=created");
                exit;
            }

            $form_error = 'Unable to create the recipe right now.';
        } elseif ($form_error === '' && $recipe_id > 0) {
            $stmt = $conn->prepare("UPDATE recipes SET title = ?, description = ?, ingredients = ?, instructions = ?, image_path = ?, gallery_images = ?, gallery_videos = ?, featured = ? WHERE id = ?");

            if ($stmt) {
                $stmt->bind_param("sssssssii", $title, $description, $ingredients, $instructions, $image_path, $gallery_images, $gallery_videos, $featured, $recipe_id);
                $stmt->execute();
                $stmt->close();

                delete_uploaded_media_files(array_merge($removed_existing_images, $removed_existing_videos));

                header("Location: dashboard.php?status=updated");
                exit;
            }

            $form_error = 'Unable to update the recipe right now.';
        } elseif ($form_error === '') {
            $form_error = 'Invalid recipe selected for editing.';
        }
    } elseif ($action === 'delete') {
        $recipe_id = isset($_POST['recipe_id']) ? (int) $_POST['recipe_id'] : 0;

        if ($recipe_id > 0) {
            $recipe_to_delete = fetch_recipe_by_id($conn, $recipe_id);
            $media_to_delete = array();

            if ($recipe_to_delete) {
                $media_to_delete = array_merge(
                    parse_media_list($recipe_to_delete['gallery_images'] ?? ''),
                    parse_media_list($recipe_to_delete['gallery_videos'] ?? '')
                );
            }

            $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");

            if ($stmt) {
                $stmt->bind_param("i", $recipe_id);
                $stmt->execute();
                $stmt->close();

                delete_uploaded_media_files($media_to_delete);

                header("Location: dashboard.php?status=deleted");
                exit;
            }
        }

        $form_error = 'Unable to delete that recipe.';
    }
}

$recipes = fetch_recipes($conn);
$recipe_count = count($recipes);
$featured_count = 0;

foreach ($recipes as $recipe_item) {
    if ((int) $recipe_item['featured'] === 1) {
        $featured_count++;
    }
}

$page_title = $site_name . " | Dashboard";
$current_page = "dashboard";
$base_path = "../";
$is_admin = true;

include "../includes/header.php";
?>

<section class="page-banner compact-banner">
    <div class="container">
        <span class="eyebrow">Admin CMS</span>
        <h1>Recipe dashboard</h1>
        <p>Welcome back, <?php echo e($_SESSION['admin_username']); ?>. Use this page to create, edit and delete recipes.</p>
    </div>
</section>

<section class="container section-space">
    <div class="stats-grid">
        <article class="stat-card orange-card">
            <h2><?php echo (int) $recipe_count; ?></h2>
            <p>Total recipes</p>
        </article>
        <article class="stat-card yellow-card">
            <h2><?php echo (int) $featured_count; ?></h2>
            <p>Featured recipes</p>
        </article>
        <article class="stat-card green-card">
            <h2><?php echo e($_SESSION['admin_username']); ?></h2>
            <p>Logged-in admin</p>
        </article>
    </div>

    <?php if ($status_message !== ''): ?>
        <div class="message message-success">
            <p><?php echo e($status_message); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($form_error !== ''): ?>
        <div class="message message-error">
            <p><?php echo e($form_error); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!$db_available): ?>
        <div class="message message-error">
            <p>The database is not available yet. Import `database.sql` first.</p>
            <p><?php echo e($db_error); ?></p>
        </div>
    <?php endif; ?>

    <div class="admin-grid">
        <article class="content-card form-card">
            <div class="section-heading section-heading-stack">
                <div>
                    <span class="eyebrow"><?php echo $editing_recipe ? 'Update recipe' : 'Create recipe'; ?></span>
                    <h2><?php echo $editing_recipe ? 'Edit selected recipe' : 'Add a new recipe'; ?></h2>
                </div>

                <?php if ($editing_recipe): ?>
                    <a class="text-link" href="dashboard.php">Clear edit mode</a>
                <?php endif; ?>
            </div>

            <form id="recipe-form" method="post" action="dashboard.php<?php echo $editing_recipe ? '?edit=' . (int) $editing_recipe['id'] : ''; ?>" class="stack-form" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $editing_recipe ? 'update' : 'create'; ?>">
                <?php if ($editing_recipe): ?>
                    <input type="hidden" name="recipe_id" value="<?php echo (int) $editing_recipe['id']; ?>">
                <?php endif; ?>
                <input type="hidden" id="remove_existing_images" name="remove_existing_images" value="">
                <input type="hidden" id="remove_existing_videos" name="remove_existing_videos" value="">
                <input type="hidden" id="uploaded_images" name="uploaded_images" value="<?php echo e($form_data['staged_images']); ?>">
                <input type="hidden" id="uploaded_videos" name="uploaded_videos" value="<?php echo e($form_data['staged_videos']); ?>">
                <input type="hidden" id="remove_uploaded_images" name="remove_uploaded_images" value="">
                <input type="hidden" id="remove_uploaded_videos" name="remove_uploaded_videos" value="">
                <input type="hidden" id="cover_choice" name="cover_choice" value="<?php echo $editing_recipe && $form_data['image_path'] !== '' ? e('existing:' . $form_data['image_path']) : ''; ?>">

                <label for="title">Recipe title</label>
                <input id="title" name="title" type="text" value="<?php echo e($form_data['title']); ?>" placeholder="Example: Garden Veggie Pizza">

                <label for="description">Short description</label>
                <textarea id="description" name="description" rows="3" placeholder="Write a short summary of the recipe"><?php echo e($form_data['description']); ?></textarea>

                <label for="ingredients">Ingredients</label>
                <textarea id="ingredients" name="ingredients" rows="6" placeholder="List each ingredient on a new line"><?php echo e($form_data['ingredients']); ?></textarea>

                <label for="instructions">Instructions</label>
                <textarea id="instructions" name="instructions" rows="6" placeholder="Write each step on a new line"><?php echo e($form_data['instructions']); ?></textarea>

                <div class="upload-panel upload-drop-zone" id="upload-drop-zone">
                    <div>
                        <label for="recipe_media">Upload recipe images and videos</label>
                        <input id="recipe_media" name="recipe_media[]" type="file" multiple accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime">
                    </div>
                    <p class="form-helper">Click the button above or drag and drop files here. You can pick multiple images and videos at once.</p>
                    <div class="upload-progress-shell" id="upload-progress-shell" aria-hidden="true">
                        <div class="upload-progress-bar" id="upload-progress-bar"></div>
                    </div>
                    <p class="form-helper upload-progress-text" id="upload-progress-text">Waiting for files...</p>
                    <label class="checkbox-line" for="keep_existing_media">
                        <input id="keep_existing_media" name="keep_existing_media" type="checkbox" <?php echo $editing_recipe ? 'checked' : ''; ?>>
                        Keep existing uploaded media when adding new files
                    </label>
                </div>

                <div class="selected-media-box">
                    <div class="selected-media-header">
                        <h3>Selected files preview</h3>
                        <p class="form-helper saving-note" id="saving-note">Saving recipe, please wait...</p>
                    </div>
                    <div class="selected-media-grid" id="selected-media-preview">
                        <p class="form-helper preview-empty-text">No new files selected yet.</p>
                    </div>
                </div>

                <?php if ($editing_recipe): ?>
                    <?php $existing_images = $display_existing_images; ?>
                    <?php $existing_videos = $display_existing_videos; ?>

                    <?php if (!empty($existing_images) || !empty($existing_videos)): ?>
                        <div class="existing-media-box">
                            <div class="selected-media-header">
                                <h3>Current uploaded media</h3>
                                <p class="form-helper">Choose a cover image and delete any saved file you no longer want.</p>
                            </div>

                            <?php if (!empty($existing_images)): ?>
                                <div class="existing-media-group">
                                    <strong>Images</strong>
                                    <div class="existing-media-grid" id="existing-images-grid">
                                        <?php foreach ($existing_images as $existing_image): ?>
                                            <div class="existing-media-card existing-image-card<?php echo $form_data['image_path'] === $existing_image ? ' is-cover' : ''; ?>" data-media-kind="image" data-media-path="<?php echo e($existing_image); ?>">
                                                <img src="../<?php echo e(media_url($existing_image)); ?>" alt="Existing recipe image">
                                                <span><?php echo e($existing_image); ?></span>
                                                <div class="existing-media-actions">
                                                    <button type="button" class="cover-select-button <?php echo $form_data['image_path'] === $existing_image ? 'active' : ''; ?>" data-cover-value="existing:<?php echo e($existing_image); ?>">Set as cover</button>
                                                    <button type="button" class="remove-media-button remove-existing-button" data-remove-kind="image" data-remove-value="<?php echo e($existing_image); ?>">Delete</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($existing_videos)): ?>
                                <div class="existing-media-group">
                                    <strong>Videos</strong>
                                    <div class="existing-media-grid" id="existing-videos-grid">
                                        <?php foreach ($existing_videos as $existing_video): ?>
                                            <div class="existing-media-card existing-video-card" data-media-kind="video" data-media-path="<?php echo e($existing_video); ?>">
                                                <video muted playsinline preload="metadata">
                                                    <source src="../<?php echo e(media_url($existing_video)); ?>">
                                                </video>
                                                <span><?php echo e($existing_video); ?></span>
                                                <div class="existing-media-actions">
                                                    <button type="button" class="remove-media-button remove-existing-button" data-remove-kind="video" data-remove-value="<?php echo e($existing_video); ?>">Delete</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <label class="checkbox-line" for="featured">
                    <input id="featured" name="featured" type="checkbox" <?php echo (int) $form_data['featured'] === 1 ? 'checked' : ''; ?>>
                    Mark this recipe as featured
                </label>

                <button class="btn btn-primary submit-button" id="recipe-submit-button" type="submit"><?php echo $editing_recipe ? 'Update Recipe' : 'Create Recipe'; ?></button>
            </form>
        </article>

        <article class="content-card data-card">
            <div class="section-heading section-heading-stack">
                <div>
                    <span class="eyebrow">Published recipes</span>
                    <h2>Current recipe list</h2>
                </div>
                <a class="text-link" href="../recipes.php">View public gallery</a>
            </div>

            <?php if (empty($recipes)): ?>
                <div class="empty-state compact-empty">
                    <h3>No recipes yet</h3>
                    <p>Create your first recipe using the form on this page.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="recipe-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Featured</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recipes as $recipe): ?>
                                <tr>
                                    <td><?php echo (int) $recipe['id']; ?></td>
                                    <td><?php echo e($recipe['title']); ?></td>
                                    <td><?php echo (int) $recipe['featured'] === 1 ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo e($recipe['image_path']); ?></td>
                                    <td class="table-actions">
                                        <a class="text-link" href="dashboard.php?edit=<?php echo (int) $recipe['id']; ?>">Edit</a>
                                        <form method="post" action="dashboard.php" onsubmit="return confirm('Delete this recipe?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="recipe_id" value="<?php echo (int) $recipe['id']; ?>">
                                            <button class="text-button delete-button" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </article>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
