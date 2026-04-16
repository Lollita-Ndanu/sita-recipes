<?php
require_once __DIR__ . "/../includes/auth.php";

start_admin_session();

if (is_admin_logged_in()) {
    header("Location: /admin/dashboard");
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

                header("Location: /admin/dashboard");
                exit;
            }
        }

        $login_error = "Invalid username or password.";
    }
}

$page_title = $site_name . " | Admin Login";
$current_page = "admin";
$base_path = "../";
$is_admin = true;

include __DIR__ . "/../includes/header.php";
?>

<section class="admin-login-shell">
    <div class="admin-login-overlay">
        <div class="admin-login-box">
            <div class="admin-login-brand">
                <img src="../images/logo.png" alt="Sahani Sita logo">
                <span>Sahani Sita</span>
            </div>

            <h1 class="admin-login-title">Welcome back</h1>
            <p class="admin-login-sub">Sign in to manage recipes on the dashboard.</p>
            <p class="admin-login-sub"> Username: admin Password: admin123</p>

            <?php if ($login_error !== ''): ?>
                <div class="message message-error">
                    <p><?php echo e($login_error); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="/admin" class="stack-form">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="<?php echo e($username_value); ?>" placeholder="Enter username" autocomplete="username">

                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter password" autocomplete="current-password">

                <button class="btn btn-primary" type="submit">Login</button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
