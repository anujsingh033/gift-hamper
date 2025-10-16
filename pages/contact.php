<?php
$pageTitle = 'Contact Us';
include '../includes/config.php';
// Note: We include header in the HTML structure below

// Process contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // In a real application, you would send an email here
    // For now, we'll just show a success message
    $success = "Thank you for your message! We'll get back to you soon.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Cute Gift Hamper for inquiries about our gift hampers, custom orders, or customer service. We're here to help you find the perfect gift.">
    <meta name="keywords" content="contact, gift hampers, customer service, custom orders, gift inquiries">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/contact.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="contact-page">
            <div class="container">
                <h1>Contact Us</h1>
                
                <div class="contact-container">
                    <div class="contact-info">
                        <h2>Get in Touch</h2>
                        <p>We'd love to hear from you! Please fill out the form below or contact us using the information provided.</p>
                        
                        <div class="contact-details">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h3>Address</h3>
                                    <p>123 Gift Street<br>Hamper City, HC 12345</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h3>Phone</h3>
                                    <p>(123) 456-7890</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h3>Email</h3>
                                    <p>info@cute-gift-hamper.com</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <h3>Hours</h3>
                                    <p>Monday - Friday: 9am - 5pm<br>Saturday: 10am - 2pm<br>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-form">
                        <h2>Send Us a Message</h2>
                        
                        <?php if (isset($success)): ?>
                            <div class="success-message"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any contact page specific JavaScript here if needed
    </script>
</body>
</html>