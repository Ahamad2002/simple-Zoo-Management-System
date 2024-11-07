<header>
    <div class="header-content">
        <div class="logo">
            <img src="images/logo.jpg" alt="ZooParc Logo">
        </div>
        <div class="site-title">
            <h1>ZooParc Zoological Park</h1>
        </div>
    </div>
    <nav class="nav-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="events.php">Programs/Events</a></li>
            <li><a href="food-outlets.php">food</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="volunteer-registration.php">Volunteer Registration</a></li>
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                echo '<li><a href="bookedhistory.php">Booked History</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
    <div class="nav-toggle">&#9776;</div>
</header>
