<?php
// session started, or not started?
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['QUERY_STRING'] == 'noname') {
    unset($_SESSION['username']);
}

$name = $_SESSION['username'] ?? 'Guest';

// Check if the user is logged in (based on the session)
$is_logged_in = isset($_SESSION['username']);  // Checks if 'username' is in session
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="templates/styles.css">
</head>

<body>
    <!-- header nav -->
    <header class="header">
        <a href="index.php" class="brand-logo">Midterm: Auctions Website</a>
        <nav class="navbar">
            <ul class="nav-links">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">You are logged in as <?php echo htmlspecialchars($name); ?></li>
                    <li class="nav-item"><a href="logout.php" class="btn">Log Out</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="btn">Login</a></li>
                    <p>or</p>
                    <li class="nav-item"><a href="registration.php" class="btn">Register</a></li>
                    <p>to auction items and place bids</p>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
