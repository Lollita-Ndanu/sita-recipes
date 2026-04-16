<?php
require_once "config.php";

$page_title = $site_name . " | Contact";
$current_page = "contact";
$base_path = "";

$contact_name = "";
$contact_email = "";
$contact_message = "";
$feedback = "";
$feedback_type = "success";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_name = trim($_POST['name'] ?? '');
    $contact_email = trim($_POST['email'] ?? '');
    $contact_message = trim($_POST['message'] ?? '');

    if ($contact_name === '' || $contact_email === '' || $contact_message === '') {
        $feedback = "Please fill in every field before submitting the contact form.";
        $feedback_type = "error";
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid email address.";
        $feedback_type = "error";
    } else {
        // This demo form only shows confirmation text. No email service is connected.
        $feedback = "Thank you, " . $contact_name . ". Your message has been recorded for the project demo.";
        $contact_name = "";
        $contact_email = "";
        $contact_message = "";
    }
}

include "includes/header.php";
?>

<section class="page-hero">
    <div class="page-hero-box page-hero-box--contact">
        <div class="page-hero-overlay">
            <div class="page-hero-content">
                <span class="eyebrow">Contact us</span>
                <h1>Get in touch with the Sahani Sita team</h1>
                <p>Have a question, a booking enquiry, or just want to say hello? We'd love to hear from you.</p>
            </div>
        </div>
    </div>
</section>

<div class="container contact-page-heading">
    <span class="eyebrow">Contact us</span>
    <h1>Get in touch with the Sahani Sita team</h1>
    <p>Have a question, a booking enquiry, or just want to say hello? We'd love to hear from you.</p>
</div>

<section class="container section-space contact-grid" style="padding-top: 0;">
    <article class="content-card">
        <h2>Contact details</h2>
        <div class="contact-item">
            <img src="images/phone-call.png" alt="Phone icon">
            <div>
                <h3>Phone</h3>
                <p>+254 712 345 678</p>
            </div>
        </div>
        <div class="contact-item">
            <img src="images/whatsapp.png" alt="Whatsapp icon">
            <div>
                <h3>WhatsApp</h3>
                <p>+254 712 345 678</p>
            </div>
        </div>
        <div class="contact-item no-icon">
            <div>
                <h3>Email</h3>
                <p>admin@sahanisitarecipes.me</p>
            </div>
        </div>
    </article>

    <article class="content-card form-card">
        <h2>Send a message</h2>

        <?php if ($feedback !== ''): ?>
            <div class="message <?php echo $feedback_type === 'error' ? 'message-error' : 'message-success'; ?>">
                <p><?php echo e($feedback); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="contact.php" class="stack-form">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="<?php echo e($contact_name); ?>" placeholder="Enter your name">

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="<?php echo e($contact_email); ?>" placeholder="Enter your email">

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Type your message here"><?php echo e($contact_message); ?></textarea>

            <button class="btn btn-primary" type="submit">Submit Message</button>
        </form>
    </article>
</section>

<?php include "includes/footer.php"; ?>
