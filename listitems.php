<?php 
include('db.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// no limit on items displayed
// TODO: add pagination to results
// TODO: add 'sort by' option to results
$sql = 'SELECT id, itemDescription, itemImagePath, sellerName, lastBidPrice FROM auctions ORDER BY id DESC';
$result = mysqli_query($conn, $sql);
$auctions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free result and close connection
mysqli_free_result($result);
mysqli_close($conn);

// check if user is logged in
$is_logged_in = isset($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Auctioned Items</title>
    <link rel="stylesheet" href="templates/styles.css"> 
</head>
<body>

<?php include('templates/header.php'); ?>

<h1>List of Auctioned Items</h1>

<table border="1" cellpadding="5" cellspacing="2">
    <thead>
        <tr>
            <th>Item Description</th>
            <th>Item Image</th>
            <th>Seller's Name</th>
            <th>Last Bid Price</th>
            <th>Make a Bid</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($auctions as $auction): ?>
            <tr>
                <td><?php echo htmlspecialchars($auction['itemDescription']); ?></td>
                <td>
                    <img src="<?php echo htmlspecialchars($auction['itemImagePath']); ?>" width="150" alt="Auction Item Image">
                </td>
                <td><?php echo htmlspecialchars($auction['sellerName']); ?></td>
                <td><?php echo htmlspecialchars($auction['lastBidPrice']); ?></td>
                <td>
                    <a href="placebid.php?id=<?php echo $auction['id']; ?>">Make a Bid</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('templates/footer.php'); ?>

</body>
</html>
