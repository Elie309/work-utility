<!DOCTYPE html>
<html>
<head>
    <title>My PHP Website</title>
</head>
<body>
    <header>
        <h1>Welcome to My PHP Website</h1>
    </header>

    <nav>
        <ul>
            <li><a href="qrcodegen.php">QR Code</a></li>
            <li><a href="imageresizer.php">Image</a></li>
        </ul>
    </nav>

    <main>
        <h2>Home Page</h2>
        <p>This is the home page of my PHP website.</p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My PHP Website. All rights reserved.</p>
    </footer>
</body>
</html>