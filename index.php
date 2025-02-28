<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Pasil Sports League Organizer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<!-- Navbar -->
<nav>
    <div class="logo-container">
        <div class="logo">
            <a href="index.php">
            <img src="images/logo.png" alt="Logo">
            </a>
        </div>
    </div>
    <div class="logo-text-container">
        <span class="logo-text">SPORTS LEAGUE</br>ORGANIZER</span>
    </div>
    <ul>
        <li><a href="#landing">HOME</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#info">INFO</a></li>
        <li><a href="./user/view_announcement.php">NEWS</a></li>
        <li><a href="./user/view_league.php">LEAGUE</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn">GAME FEED</a>
            <div class="dropdown-content">
                <a href="./user/view_teams.php">TEAMS</a>
                <a href="./user/match_schedules.php">MATCH</a>
                <a href="./user/view_game_results.php">RESULT</a>
                <a href="./user/view_cancelled_games.php">CANCELLED GAMES</a>
            </div>
        </li>


        <!-- <li><a href="./user/match_schedules.php">MATCH</a></li>
        <li><a href="./user/view_teams.php">TEAMS</a></li> -->
        <!-- <li><a href="./user/view_applicants_approved.php">Approved <br> Applicants</a></li> -->
        <!-- <li><a href="./user/view_bookings_approved.php">Approved <br> Bookings</a></li> -->


        <li><a href="./user/book.php">BOOK</a></li>
        <li><a href="#contact">CONTACT</a></li>
    </ul>
    <div class="buttons">
        <a href="./admin/login.php" class="login">LOGIN</a>
        <a href="./user/register.php" class="register">REGISTER</a>
    </div>
</nav>


<!-- Landing Section -->
<section id="landing" class="section">
    <div class="image-container left">
        <img src="images/vb1.png" alt="Volleyball Player">
    </div>
    <div class="content">
        <h1>GET IN THE GAME</h1>
        <p>Dive into the heart of the action, where every match <br>
            is a chance to shine and every play counts!<br>
        </p>
        <a href="#about" class="read-more">READ MORE</a>
    </div>
    <div class="image-container right">
        <img src="images/bb1.png" alt="Basketball Player">
    </div>
</section>

<!-- About Section -->
<section id="about" class="section">
    <h2>About Us</h2>
    <p>We’re committed to transforming sports management in Barangay Pasil. Our innovative digital platform simplifies the process of organizing sports events, registering participants, and booking sports facilities, ensuring a smoother and more accessible experience for the community.</p>
    <div class="about-cards">
        <div class="about-card">
            <h3>Our Mission</h3>
            <p>To streamline the management of sports leagues and community facilities through a user-friendly digital platform, enhancing community engagement and promoting a healthier lifestyle in Barangay Pasil.</p>
        </div>
        <div class="about-card">
            <h3>Our Vision</h3>
            <p>To create a connected and vibrant community in Barangay Pasil where sports activities are seamlessly organized, accessible to all, and contribute to a more active and engaged populace.</p>
        </div>
        <div class="about-card">
            <h3>Our Values</h3>
            <p class="center-text">CARE</p>
            <p class="align-right">
            C - Commitment to Community<br>
            A - Attentiveness to Needs<br>
            R - Relationship Building<br>
            E - Engagement in Activities</p>
        </div>
    </div>
     <p class="join-text">Join us and be part of the action!</p>
</section>

<!-- Info Basketball Section1 -->
<section id="info" class="section">
    <div class="info-content">
        <h2 class="title">MAKE A</br>SCORE</h2>
        <p class="subtitle">PLAY BASKETBALL</p>
        <p class="description">Every summer, Barangay Pasil's annual league brings thrilling basketball action across five divisions: Mosquito, Midget, Aspirant, Juniors, and Equalizer, offering thrilling competition across all ages. Teams compete throughout the season, aiming for the championship. Stay tuned for all the highlights!</p>
    </div>
</section>

<!-- Info Volleyball Section2 -->
<section id="info2" class="section">
    <div class="info-content2">
        <h2 class="title">SERVE</br>STRONG</h2>
        <p class="subtitle">PLAY VOLLEYBALL</p>
        <p class="description">In Barangay Pasil, the summer volleyball league brings players of all ages together for thrilling competition. With separate divisions for men and women, each game is a high-energy clash of skill and spirit. Feel the excitement as teams chase victory!</p>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section contact-section">
    <h2>GET IN TOUCH!</h2>
    <p>Want to get in touch? We'd love to hear from you. Here’s how you can reach us...</p>
    <div class="contact-cards">
        <div class="contact-card">
            <a href="https://www.flaticon.com/free-icon/location-pin_684908" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" alt="Location Icon" class="contact-icon">
            </a>
            <h3>ADDRESS</h3>
            <p>L. Flores St., Pasil, Cebu City<br>6000 Philippines</p>
        </div>
        <div class="contact-card">
            <a href="https://www.flaticon.com/free-icon/phone_597177" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/597/597177.png" alt="Phone Icon" class="contact-icon">
            </a>
            <h3>PHONE</h3>
            <p>(02) 1234 5678<br>(+63) 912 345 6789</p>
        </div>
        <div class="contact-card">
            <a href="https://www.flaticon.com/free-icon/email_542689" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/542/542689.png" alt="Mail Icon" class="contact-icon">
            </a>
            <h3>MAIL</h3>
            <p>bpslo2024@bpslo.cebu.ph</p>
        </div>
    </div>
</section>
</body>
</html>