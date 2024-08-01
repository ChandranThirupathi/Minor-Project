<?php
// include 'db_connection.php'; // Include your database connection file

// session_start(); // Start the session

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Retrieve SellerID
//     // Generate a random UUID (v4)
//     $sellerID = uniqid();

//     // Retrieve personal info data
//     $firstName = $_POST['firstName'];
//     $lastName = $_POST['lastName'];
//     $loginEmail = $_SESSION['email']; // Email stored in session from login
//     $email = $_POST['email']; // Email submitted in the form
//     $dateOfBirth = $_POST['dateOfBirth'];
//     $mobileNumber = $_POST['mobileNumber'];
//     $gender = $_POST['gender'];

//     // Check if the submitted email matches the email stored in the session
//     if ($loginEmail !== $email) {
//         echo "Error: The email used for registration does not match the login email.";
//     } else {
//         // Handle file upload for profile image (optional)
//         $profileImageURL = ''; // Initialize profile image URL variable
//         if (isset($_FILES['profileImageURL']) && $_FILES['profileImageURL']['error'] === UPLOAD_ERR_OK) {
//             $profileImageName = $_FILES['profileImageURL']['name'];
//             $profileImageTmpName = $_FILES['profileImageURL']['tmp_name'];
//             $profileImageURL = 'C:\xampp\htdocs\signup\images' . $profileImageName; // Modify the path as needed
//             if (!move_uploaded_file($profileImageTmpName, $profileImageURL)) {
//                 echo "Error uploading file.";
//             }
//         }

//         // Check if the email already exists in the database
//         $checkEmailQuery = "SELECT COUNT(*) FROM SellerInfo WHERE Email = ?";
//         $checkStmt = $conn->prepare($checkEmailQuery);
//         $checkStmt->bind_param("s", $email);
//         $checkStmt->execute();
//         $checkStmt->bind_result($emailCount);
//         $checkStmt->fetch();
//         $checkStmt->close();

//         if ($emailCount > 0) {
//             echo "Error: Email already exists.";
//         } else {
//             // Prepare and execute SQL statement to insert data into the database
//             $sql = "INSERT INTO SellerInfo (SellerID, FirstName, LastName, Email, DateOfBirth, MobileNumber, Gender, ProfileImageURL) 
//                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
//             $stmt = $conn->prepare($sql);
//             $stmt->bind_param("ssssssss", $sellerID, $firstName, $lastName, $email, $dateOfBirth, $mobileNumber, $gender, $profileImageURL);

//             if ($stmt->execute()) {
//                 $_SESSION['SellerID'] = $sellerID;
//                 header("Location: business.html");
//                 // Redirect to the next step or any other page as needed
//                 exit(); // Stop script execution after redirect
//             } else {
//                 echo "Error: " . $stmt->error;
//             }

//             $stmt->close();
//         }
//     }

//     $conn->close();
// } else {
//     echo "Invalid request method.";
// }




include 'db_connection.php'; // Include your database connection file

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve SellerID
    // Generate a random UUID (v4)
    $sellerID = uniqid();

    // Retrieve personal info data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $loginEmail = $_SESSION['email']; // Email stored in session from login
    $email = $_POST['email']; // Email submitted in the form
    $dateOfBirth = $_POST['dateOfBirth'];
    $mobileNumber = $_POST['mobileNumber'];
    $gender = $_POST['gender'];

    // Check if the submitted email matches the email stored in the session
    if ($loginEmail !== $email) {
        echo "Error: The email used for registration does not match the login email.";
    } else {
        // Handle file upload for profile image (optional)
        $profileImageURL = ''; // Initialize profile image URL variable
        if (isset($_FILES['profileImageURL']) && $_FILES['profileImageURL']['error'] === UPLOAD_ERR_OK) {
            $profileImageName = $_FILES['profileImageURL']['name'];
            $profileImageTmpName = $_FILES['profileImageURL']['tmp_name'];
            $profileImageURL = 'images/' . $profileImageName; // Modify the path as needed
            if (!move_uploaded_file($profileImageTmpName, $profileImageURL)) {
                echo "Error uploading file.";
            }
        }

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT COUNT(*) FROM SellerInfo WHERE Email = ?";
        $checkStmt = $conn->prepare($checkEmailQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->bind_result($emailCount);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($emailCount > 0) {
            echo "Error: Email already exists.";
        } else {
            // Prepare and execute SQL statement to insert data into the database
            $sql = "INSERT INTO SellerInfo (SellerID, FirstName, LastName, Email, DateOfBirth, MobileNumber, Gender, ProfileImageURL) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $sellerID, $firstName, $lastName, $email, $dateOfBirth, $mobileNumber, $gender, $profileImageURL);

            if ($stmt->execute()) {
                $_SESSION['SellerID'] = $sellerID;
                header("Location: business.html?SellerID=" . $sellerID);
                exit(); // Stop script execution after redirect
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}

?>
