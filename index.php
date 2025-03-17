<?php 
include('db.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="templates/styles.css">
</head>

<body>
    <?php include('templates/header.php'); ?>

    <div class="container">
        <h1>Would you like to</h1>

            <div class="add-article-button-container">
                <a href="newauction.php" class="large-button">Add a New Auction</a>
            </div>

            <div class="add-article-button-container">
                <a href="listitems.php" class="large-button">List All The Auctioned Items</a>
            </div>

    <?php include('templates/footer.php'); ?>
</body>
</html>