<?php
require_once "../includes/auth.php";

start_admin_session();

if (is_admin_logged_in()) {
    header("Location: dashboard.php");
    exit;
}

$login_error = "";
$username_value = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_value = trim($_POST['username'] ?? '');
    $password_value = $_POST['password'] ?? '';

    if (!$db_available) {
        $login_error = "The database is not ready yet. Import database.sql first.";
    } elseif ($username_value === '' || $password_value === '') {
        $login_error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");

        if ($stmt) {
            $stmt->bind_param("s", $username_value);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result ? $result->fetch_assoc() : null;
            $stmt->close();

            if ($admin && hash('sha256', $password_value) === $admin['password_hash']) {
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = (int) $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: dashboard.php");
                exit;
            }
        }

        $login_error = "Invalid username or password.";
    }
}

$page_title = $site_name . " | Admin Login";
$current_page = "admin";
$base_path = "../";

include "../includes/header.php";
?>

<section class="page-banner compact-banner">
    <div class="container narrow-container">
        <span class="eyebrow">Admin access</span>
        <h1>Login to the recipe CMS</h1>
        <p>Only the admin can add, edit or delete recipes from the dashboard.</p>
    </div>
</section>

<section class="container section-space">
    <div class="auth-card narrow-container">
        <h2>Admin login</h2>
        <p class="auth-note">Default demo login: <strong>admin</strong> / <strong>admin123</strong></p>

        <?php if ($login_error !== ''): ?>
            <div class="message message-error">
                <p><?php echo e($login_error); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php" class="stack-form">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="<?php echo e($username_value); ?>" placeholder="Enter username">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Enter password">

            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
