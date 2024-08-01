<?php
include 'db_connection.php'; // Include your database connection file

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve SellerID from session
    $sellerID = $_SESSION['SellerID'];

    // Retrieve product info data
    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $numberOfProducts = $_POST['numberOfProducts'];
    $price = $_POST['price'];
    $productDescription = $_POST['productDescription'];
    $venueType = $_POST['venueType'];
    $capacity = $_POST['capacity'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $cateringOptions = $_POST['cateringOptions']; // Assuming this is an array
    $photographyOptions = $_POST['photographyOptions']; // Assuming this is an array
    $availableDates = $_POST['availableDates']; // Assuming this is an array

    // Insert data into Productinfo table
    $sql = "INSERT INTO ProductInfo (SellerID, ProductName, ProductCategory, NumberOfProducts, Price, ProductDescription, VenueType, Capacity, Address, Area, CateringOptions, PhotographyOptions, AvailableDates) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssisssss", $sellerID, $productName, $productCategory, $numberOfProducts, $price, $productDescription, $venueType, $capacity, $address, $area, $cateringOptions, $photographyOptions, $availableDates);

    if ($stmt->execute()) {
        // Get the last inserted product ID
        $lastInsertedProductId = $stmt->insert_id;

        // Handle image uploads
        if (isset($_FILES['image']) && is_array($_FILES['image']['name']) && !empty($_FILES['image']['name'][0])) {
            for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                if ($_FILES['image']['error'][$i] === UPLOAD_ERR_OK) {
                    $name = $_FILES['image']['name'][$i];
                    $type = $_FILES['image']['type'][$i];
                    $data = file_get_contents($_FILES['image']['tmp_name'][$i]);

                    // Insert the image data into the database
                    $imageSql = "INSERT INTO ImageUploads (name, type, data, ProductID) VALUES (?, ?, ?, ?)";
                    $imageStmt = $conn->prepare($imageSql);
                    $imageStmt->bind_param("sssi", $name, $type, $data, $lastInsertedProductId);
                    if ($imageStmt->execute()) {
                        echo "File {$name} uploaded successfully.<br>";
                    } else {
                        echo "Failed to upload file {$name}: " . $imageStmt->error . "<br>";
                    }
                    $imageStmt->close();
                } else {
                    echo "Error uploading file {$name}.<br>";
                }
            }
        } else {
            echo "No files uploaded.";
        }

        header("Location: seller.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
