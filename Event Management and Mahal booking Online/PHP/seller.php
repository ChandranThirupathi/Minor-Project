<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Assume user is logged in and email is stored in session
$email = $_SESSION['email'];

// Initialize seller information array
$sellerInfo = [];
$businessInfo = [];

if ($email) {
    // Fetch seller information based on the session email
    $sellerSql = "SELECT SellerID, FirstName, LastName, Email FROM SellerInfo WHERE Email = ?";
    $sellerStmt = $conn->prepare($sellerSql);
    $sellerStmt->bind_param("s", $email);
    $sellerStmt->execute();
    $sellerResult = $sellerStmt->get_result();
    $sellerInfo = $sellerResult->fetch_assoc();
   

    if ($sellerInfo) {
        // Fetch business information if the seller exists
        $sellerID = $sellerInfo['SellerID'];
        $_SESSION['SellerID'] = $sellerID;
        
        $businessSql = "SELECT BusinessName FROM Businessinfo WHERE SellerID = ?";
        $businessStmt = $conn->prepare($businessSql);
        $businessStmt->bind_param("s", $sellerID);
        $businessStmt->execute();
        $businessResult = $businessStmt->get_result();
        $businessInfo = $businessResult->fetch_assoc();

        $businessStmt->close();
        $_SESSION['SellerID'] = $sellerID;
    }

    $sellerStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retailer Section</title>
    <link rel="stylesheet" href="seller.css">
</head>
<body>
    <main>
        <section class="main-content">
            <div class="left-side">
                <h2>Retailer Information</h2>
                <!-- Display seller information if registered -->
                <?php if ($sellerInfo): ?>
                <div class="seller-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($sellerInfo['FirstName'] . ' ' . $sellerInfo['LastName']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($sellerInfo['Email']); ?></p>
                    <p><strong>Mahal Name:</strong> <?php echo htmlspecialchars($businessInfo['Mahalname']); ?></p>
                    <section class="call-to-action">
                        <a href="pinfo.html">Add</a>
                    </section>
                </div>
                <?php else: ?>
               
                <?php endif; ?>
            </div>

            <div class="right-side">
                <section class="welcome-banner">
                    <div class="overlay">
                        <h1>Welcome to Our Retailer Community!</h1>
                        <p>Start displaying your mahal.</p>
                    </div>
                </section>

                <section class="intro">
                    <p>Join our platform and enjoy the benefits of reaching a wide audience with our easy-to-use tools and support services.</p>
                </section>

                <section class="how-it-works">
                    <div class="step">
                        <h2>Step 1: Register</h2>
                        <p>Fill in your details to create a retailer account.</p>
                    </div>
                    <div class="step">
                        <h2>Step 2: List mahal</h2>
                        <p>Potray your mahal along with images.</p>
                    </div>
                    <div class="step">
                        <h2>Step 3: Receiving of orders </h2>
                        <p> We'll reach you and confirm the order if the date is available.</p>
                    </div>
                </section>

                <section class="why-sell">
                    <h2>Why Sell with Us?</h2>
                    <ul>
                        <li>Wide Customer Reach</li>
                        <li>Secure Transactions</li>
                        <li>Dedicated Support Team</li>
                        <li>Detailed Analytics and Reports</li>
                    </ul>
                </section>

                <section class="testimonials">
                    <h2>What Our Sellers Say</h2>
                    <div class="testimonial">
                        <p>"Displaying on this platform has boosted my business!" - Murugappan</p>
                    </div>
                    <div class="testimonial">
                        <p>"The enviroment and support are fantastic. Highly recommend!" - kumar</p>
                    </div>
                </section>

                <section class="faq">
                    <h2>Frequently Asked Questions</h2>
                    <div class="question">
                        <h3>How do I register as a retailer?</h3>
                        <p>Click the 'Register' button and fill out the form.</p>
                    </div>
                    <div class="question">
                        <h3>What fees do you charge?</h3>
                        <p>Our fee structure is transparent and competitive. Please visit our fees page for more details.</p>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Home</a>
                <!-- <a href="#">Shop</a> -->
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">FAQs</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="contact-info">
                <p>Email: <a href="mailto:support@duetspace.com">support@duetspace.com</a></p>
                <p>Phone: <a href="91+ 6374511901">91+ 6374511901</a></p>
                <p>Address: 123 ,Adyar, chennai-600024 </p>
            </div>
            <div class="social-media">
                <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
                <a href="#"><img src="instagram-icon.png" alt="Instagram"></a>
                <a href="#"><img src="linkedin-icon.png" alt="LinkedIn"></a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 Marriage Hall Booking. All rights reserved</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.faq .question h3').forEach(item => {
            item.addEventListener('click', () => {
                const answer = item.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>
