<?php
$pageTitle = 'About Us';
include '../includes/config.php';
// Note: We include header in the HTML structure below
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Cute Gift Hamper - our story, values, and team. We create beautifully curated gift hampers for every special occasion.">
    <meta name="keywords" content="gift hampers, about us, our story, gift company, curated gifts">
    <meta name="author" content="Cute Gift Hamper">
    <title><?php echo $pageTitle; ?> - Cute Gift Hamper</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>favicon.ico" type="image/x-icon">
    <link rel="canonical" href="<?php echo BASE_URL; ?>pages/about.php">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <section class="about-page">
            <div class="container">
                <h1>About Cute Gift Hamper</h1>
                
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Story</h2>
                        <p>Cute Gift Hamper was founded in 2020 with a simple mission: to make gift-giving easy, thoughtful, and special. We believe that the perfect gift can bring joy to both the giver and the receiver.</p>
                        
                        <p>Our team of gift curators carefully selects each item in our hampers to ensure quality, beauty, and that special touch that makes our gifts memorable. Whether you're celebrating a birthday, anniversary, new baby, or any special occasion, we have the perfect hamper for you.</p>
                        
                        <h2>Our Values</h2>
                        <ul>
                            <li><strong>Quality:</strong> We source only the finest products for our hampers.</li>
                            <li><strong>Creativity:</strong> Our gift combinations are unique and thoughtfully designed.</li>
                            <li><strong>Customer Satisfaction:</strong> Your happiness is our top priority.</li>
                            <li><strong>Sustainability:</strong> We use eco-friendly packaging whenever possible.</li>
                        </ul>
                    </div>
                    
                    <div class="about-image">
                        <img src="<?php echo BASE_URL; ?>assets/images/about-us.jpg" alt="About Cute Gift Hamper">
                    </div>
                </div>
                
                <div class="team-section">
                    <h2>Meet Our Team</h2>
                    <div class="team-grid">
                        <div class="team-member">
                            <img src="<?php echo BASE_URL; ?>assets/images/team1.jpg" alt="Team Member">
                            <h3>Sarah Johnson</h3>
                            <p>Founder & CEO</p>
                        </div>
                        <div class="team-member">
                            <img src="<?php echo BASE_URL; ?>assets/images/team2.jpg" alt="Team Member">
                            <h3>Michael Chen</h3>
                            <p>Head of Curation</p>
                        </div>
                        <div class="team-member">
                            <img src="<?php echo BASE_URL; ?>assets/images/team3.jpg" alt="Team Member">
                            <h3>Emma Rodriguez</h3>
                            <p>Customer Experience Manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script>
    // Add any about page specific JavaScript here if needed
    </script>
</body>
</html>