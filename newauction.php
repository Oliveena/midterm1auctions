<?php 
include('db.php');
session_start();

$itemDescription = isset($_POST['itemDescription']) ? $_POST['itemDescription'] : '';
$itemImagePath = isset($_FILES['itemImagePath']) ? $_FILES['itemImagePath'] : '';
$sellerName = $_POST['sellerName'] ?? '';
$sellerEmail = $_POST['sellerEmail'] ?? '';
$initialBidPrice = $_POST['initialBidPrice'] ?? 0;
$errorList = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // validating item description
    if (empty($itemDescription)) {
        $errorList[] = "Item description is required.";
    } elseif (strlen($itemDescription) < 2 || strlen($itemDescription) > 1000) {
        $errorList[] = "Item description must be between 2 and 1000 characters.";
    } else {
        // sanitizing item descr
        $itemDescription = strip_tags($itemDescription, '<p><ul><ol><li><br><hr><em><i><strong><b><span>');
    }

    // validating image path
    if (isset($_FILES['itemImagePath']) && $_FILES['itemImagePath']['error'] == 0) {
        $itemImagePathTmpName = $_FILES['itemImagePath']['tmp_name'];
        $itemImagePathName = $_FILES['itemImagePath']['name'];
        $itemImagePathExtension = strtolower(pathinfo($itemImagePathName, PATHINFO_EXTENSION));

        // validating file type (JPEG, PNG, GIF, BMP)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        if (!in_array($itemImagePathExtension, $allowedExtensions)) {
            $errorList[] = "Only JPEG, PNG, GIF, or BMP images are allowed.";
        } else {
            // sanitizing image name 
            $imageNameSanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($itemImagePathName, PATHINFO_FILENAME));
            $imageNameSanitized .= '_' . uniqid() . '.' . $itemImagePathExtension; // Append random suffix for uniqueness

            // path
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $itemImagePath = $uploadDir . $imageNameSanitized;

            // uploading the img
            if (!move_uploaded_file($itemImagePathTmpName, $itemImagePath)) {
                $errorList[] = "Failed to upload the image.";
            }
        }
    }

    // validating seller name
    if (!preg_match('/^[a-zA-Z0-9\s\-\.,]+$/', $sellerName) || strlen($sellerName) < 2 || strlen($sellerName) > 100) {
        $errorList[] = "Seller's name is invalid.";
    }

    // validating seller email
    if (!filter_var($sellerEmail, FILTER_VALIDATE_EMAIL)) {
        $errorList[] = "Invalid email address.";
    }

    // validating initial bid price
    if (!is_numeric($initialBidPrice) || $initialBidPrice < 0) {
        $errorList[] = "Initial bid price must be a non-negative number.";
    }

    // inserting into DB if all validations passed
    if (empty($errorList)) {
        $sql = "INSERT INTO auctions (itemDescription, itemImagePath, sellerName, sellerEmail, lastBidPrice)
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssd", $itemDescription, $itemImagePath, $sellerName, $sellerEmail, $initialBidPrice);
            if (mysqli_stmt_execute($stmt)) {
                $successMessage = "Auction successfully created!";
            } else {
                $errorList[] = "Failed to create auction.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errorList[] = "Database error: Failed to prepare query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add A New Auction</title>
    <link rel="stylesheet" href="templates/styles.css">
<!--TinyMCE setup-->
    <script src="https://cdn.tiny.cloud/1/4etmjp4jl7f9ene3ci99a6ymid64id6jlx3rr5gesz2srygx/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                'anchor', 'autolink', 'charmap', 'emoticons', 'image', 'link', 'lists', 'table', 'visualblocks', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | link image media table | numlist bullist | emoticons charmap | removeformat',
        });
    </script>
</head>
<body>

<?php include('templates/header.php'); ?>

<h1>Add A New Auction</h1>

<?php
if (!empty($errorList)) {
    foreach ($errorList as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
} elseif (isset($successMessage)) {
    echo "<p style='color:green;'>$successMessage</p>";
}
?>

<form action="newauction.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="itemDescription">Item Description (2-1000 characters):</label><br>
        <textarea name="itemDescription" id="itemDescription" rows="10" cols="50"></textarea>
    </div>

    <div>
        <label for="itemImagePath">Upload an Image (JPEG, PNG, GIF, BMP):</label><br>
        <input type="file" name="itemImagePath" id="itemImagePath" required>
    </div>

    <div>
        <label for="sellerName">Seller's Name (2-100 characters):</label><br>
        <input type="text" name="sellerName" id="sellerName" required>
    </div>

    <div>
        <label for="sellerEmail">Seller's Email:</label><br>
        <input type="email" name="sellerEmail" id="sellerEmail" required>
    </div>

    <div>
        <label for="initialBidPrice">Initial Bid Price in $CAD(0 or higher):</label><br>
        <input type="number" name="initialBidPrice" id="initialBidPrice" min="0" required>
    </div>

    <div>
        <button type="submit">Create New Auction</button>
    </div>
</form>

<?php include('templates/footer.php'); ?>

</body>
</html>
