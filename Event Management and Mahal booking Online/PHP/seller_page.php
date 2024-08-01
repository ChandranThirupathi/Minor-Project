<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if user email exists in the Seller table
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM SellerInfo WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User is already registered as a seller, redirect to product info page
        header("Location: seller.php");
        exit();
    } else {
        // User is not registered as a seller, redirect to personal info page
        header("Location: reg.html");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If session email is not set, redirect to login page
    header("Location: login.html");
    exit();
}
?>
