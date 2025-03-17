<?php

// connect to database
$conn = mysqli_connect('localhost', 'midterm1auctions', 'eOApNomOJr7L1b', 'midterm1auctions');

// check the connection
if (!$conn) {
    echo 'Connection error' . mysqli_connect_error();
}

?>