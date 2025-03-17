<?php

session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php include('templates/header.php'); ?>

<h3>You have succesfully logged out</h3>
<h5>Return to main page</h5>

<div class="add-article-button-container">
    <a href="index.php" class="large-button">Main Page</a>
</div>

<?php include('templates/footer.php'); ?>

 
</body>
</html>