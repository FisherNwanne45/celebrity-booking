<?php
$siteName = getSiteSetting($pdo, 'site_name');
$phone = getSiteSetting($pdo, 'phone_number');
$address = getSiteSetting($pdo, 'address');
$email = getSiteSetting($pdo, 'email');
$facebook = getSiteSetting($pdo, 'facebook');
$twitter = getSiteSetting($pdo, 'twitter');
$instagram = getSiteSetting($pdo, 'instagram');
$linkedin = getSiteSetting($pdo, 'linkedin');
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $siteName ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon.png">
        <meta name="description"
            content="Book your favorite celebrities for exclusive meet and greet events, bookings, and special appearances. Discover profiles, fees, and availability today!">
        <meta property="og:title" content="Book Your Favorite Celebrities">
        <meta property="og:description"
            content="Discover profiles, fees, and availability of top celebrities for your events. Book now!">
        <meta property="og:image" content="assets/favicon.png">
        <meta property="og:type" content="website">


        <link rel="stylesheet" href="assets/css/custom.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-stars">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center text-dark text-decoration-none" href="index.php">
                    <!-- Logo Wrapper: This div controls the overall size and positioning of the animated logo -->
                    <div class="logo-wrapper position-relative me-1 flex-shrink-0">
                        <div class="golden-ring-logo"></div>
                        <div class="golden-star-logo"></div>
                    </div>
                    <!-- Site Name -->
                    <span class="h4 fw-bold mb-0"><?= $siteName ?></span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#featured">Celebrities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#how-it-works">How It Works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php#faq">FAQ</a>
                        </li>

                    </ul>
                    <div class="d-flex">
                        <a href="contact.php" class="btn btn-outline-light">
                            <i class="bi bi-lock me-1"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </nav>