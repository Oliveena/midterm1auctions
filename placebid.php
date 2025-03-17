<?php
require_once 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//var_dump($_SESSION['email']);

if (!isset($_GET['id'])) {
    echo "Error: Couldn't find item.";
    exit();
}

$auctionId = $_GET['id'];

// Fetch auction details
$sql = "SELECT * FROM auctions WHERE id = '$auctionId'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Error: Auction item not found.";
    exit();
}

$auction = mysqli_fetch_assoc($result);

// is logged in?
$is_logged_in = isset($_SESSION['username']);

// auto fill bidder information
$bidderName = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$bidderEmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';

var_dump($_SESSION['email']);

// set empty error list
$errorList = [];
$bidPrice = isset($_POST['bid_price']) ? $_POST['bid_price'] : '';

// session is active?
if (!isset($_SESSION['username'])) {
    // if not active, redirecting user to log in
    header("Location: login.php");
    exit(); // do not ececute the rest of the script
}


// submission
if (isset($_POST['submit'])) {
    // trim and validate
    $bidderName = trim($_POST['bidder_name']);
    $bidderEmail = trim($_POST['bidder_email']);
    $bidPrice = trim($_POST['bid_price']);
    
    if (empty($bidderName) || empty($bidderEmail) || empty($bidPrice)) {
        $errorList[] = "All fields are required.";
    }

    // validating bidder's username
    if (!preg_match("/^[a-zA-Z0-9\s\-\,\.]{2,100}$/", $bidderName)) {
        $errorList[] = "Bidder name is invalid.";
    }

    // validating email
    if (!filter_var($bidderEmail, FILTER_VALIDATE_EMAIL)) {
        $errorList[] = "Bidder email is invalid.";
    }

    // validating bid price 
    if ($bidPrice <= $auction['lastBidPrice']) {
        $errorList[] = "Bid must be higher than the current bid of " . number_format($auction['lastBidPrice'], 2);
    }

    // proceed if no errors
    if (empty($errorList)) {
        $bidderName = mysqli_real_escape_string($conn, $bidderName);
        $bidderEmail = mysqli_real_escape_string($conn, $bidderEmail);
        $bidPrice = mysqli_real_escape_string($conn, $bidPrice);
        
        // save user info as bidder info
        $_SESSION['name'] = $bidderName;
        $_SESSION['email'] = $bidderEmail;

        // update item
        $sql = "UPDATE auctions SET lastBidPrice = '$bidPrice', lastBidderName = '$bidderName', lastBidderEmail = '$bidderEmail' WHERE id = '$auctionId'";
        
        if (mysqli_query($conn, $sql)) {
            echo "<h3>Your bid has been placed successfully!</h3>";
            // display new values
            $auction['lastBidPrice'] = $bidPrice;
            $auction['lastBidderName'] = $bidderName;
            $auction['lastBidderEmail'] = $bidderEmail;
        } else {
            echo "Error placing bid: " . mysqli_error($conn);
        }
    }
}

$sql = "SELECT * FROM auctions WHERE id = '$auctionId'";
$result = mysqli_query($conn, $sql);
$auction = mysqli_fetch_assoc($result);

mysqli_free_result($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Item Details</title>
    <link rel="stylesheet" href="templates/styles.css">
    <!--incorporating AJAX-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php include('templates/header.php'); ?>

    <!--displaying item info-->
    <h1><?php echo htmlspecialchars($auction['itemDescription']); ?></h1>
    <img src="<?php echo htmlspecialchars($auction['itemImagePath']); ?>" width="150" alt="Auction Item Image">
    <p>Seller: <?php echo htmlspecialchars($auction['sellerName']); ?></p>
    <p>Last Bid: $<?php echo number_format($auction['lastBidPrice'], 2); ?></p>

    <h3>Place a Bid</h3>

    <?php if (!empty($errorList)): ?>
        <ul>
            <?php foreach ($errorList as $error): ?>
                <li style="color: red;"><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!--form for bidding-->
    <form method="POST">
    <label for="bidder_name">Your Name:</label>
    <input type="text" name="bidder_name" id="bidder_name" value="<?php echo htmlspecialchars($bidderName); ?>" required><br><br>

    <label for="bidder_email">Your Email:</label>
    <input type="email" name="bidder_email" id="bidder_email" value="<?php echo htmlspecialchars($bidderEmail); ?>" required><br><br>

    <label for="bid_price">New Bid Price:</label>
    <input type="number" name="bid_price" id="bid_price" value="<?php echo htmlspecialchars($bidPrice); ?>" step="0.01" min="<?php echo $auction['lastBidPrice'] + 0.01; ?>" required><br><br>

    <input type="submit" name="submit" value="Place Bid">
</form>


    <script>
        // AJAX for checking if bid acceptable
        $(document).ready(function() {
    $('#bid_price').on('input', function() {
        var bidPrice = parseFloat($(this).val());
        var auctionId = <?php echo $auction['id']; ?>;

        // send to isbidtoolow.php for validation
        $.ajax({
            url: 'isbidtoolow.php',
            type: 'GET',
            data: { id: auctionId, bid: bidPrice },
            success: function(response) {
                if (response === 'bid too low') {
                    $('#bid_price').after('<span style="color: red;">Bid too low. Minimum bid is $' + (<?php echo $auction['lastBidPrice']; ?> + 0.01).toFixed(2) + '</span>');
                } else {
                    $('#bid_price').next('span').remove();
                }
            }
        });
    });
});
    </script>


    <?php include('templates/footer.php'); ?>
</body>
</html>

<?php
mysqli_close($conn);
?>
