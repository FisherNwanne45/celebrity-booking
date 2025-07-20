<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" sizes="32x32" href="stars/assets/favicon.png">
        <meta name="description"
            content="Book your favorite celebrities for exclusive meet and greet events, bookings, and special appearances. Discover profiles, fees, and availability today!">
        <meta property="og:title" content="Book Your Favorite Celebrities">
        <meta property="og:description"
            content="Discover profiles, fees, and availability of top celebrities for your events. Book now!">
        <meta property="og:image" content="stars/assets/favicon.png">
        <meta property="og:type" content="website">
        <title>Celebrity Booking and Reservation</title>
        <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #007bff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: #555;
        }
        </style>
        <script>
        setTimeout(() => {
            window.location.href = 'stars/';
        }, 3000); // 3 seconds
        </script>
    </head>

    <body>
        <div>
            <div class="loader"></div>
            <div class="loading-text">Loading celebrities...</div>
        </div>
    </body>

</html>