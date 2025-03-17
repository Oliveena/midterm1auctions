<?php
include('db.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// GET auctionId and the bid that was just placed
$auctionId = isset($_GET['id']) ? $_GET['id'] : null;
$newBid = isset($_GET['bid']) ? $_GET['bid'] : null;

if ($auctionId && $newBid) {
    // GET last bid
    $sql = "SELECT lastBidPrice FROM auctions WHERE id = '$auctionId'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $auction = mysqli_fetch_assoc($result);
        
        // comparing new bid vs last bid
        if ($newBid <= $auction['lastBidPrice']) {
            echo 'bid too low';
        } else {
            echo '';
        }
    } else {
        echo 'Auction item not found.';
    }
}

mysqli_close($conn);
?>
