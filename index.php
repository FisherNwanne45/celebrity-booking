<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celebrity Experience | Loading</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Lora:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1a0005, #33000c, #4d0013);
            overflow: hidden;
            font-family: 'Lora', serif;
            color: #e8d0c1;
            perspective: 1000px;
            position: relative;
        }

        .starry-sky {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .star {
            position: absolute;
            background-color: #fff;
            border-radius: 50%;
            animation: twinkle var(--duration) infinite ease-in-out;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 10;
            max-width: 800px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(45, 0, 10, 0.5);
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(173, 83, 83, 0.2);
        }

        .logo {
            font-family: 'Cinzel', serif;
            font-size: 2.8rem;
            margin-bottom: 25px;
            color: #d4af37;
            letter-spacing: 3px;
            text-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
            position: relative;
        }

        .logo::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background: linear-gradient(to right, transparent, #d4af37, transparent);
        }

        .preloader {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto 40px;
        }

        .golden-star {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #d4af37, #f9e076, #d4af37);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            animation: pulse 3s infinite, rotate 15s linear infinite;
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.4);
            z-index: 2;
        }

        .golden-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 160px;
            height: 160px;
            border: 3px solid #d4af37;
            border-radius: 50%;
            animation: rotate 20s linear infinite reverse;
        }

        .golden-ring::before {
            content: "";
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 10px;
            background: #d4af37;
            border-radius: 50%;
            box-shadow: 0 0 15px #d4af37;
        }

        .loading-text {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 30px 0 20px;
            position: relative;
            font-family: 'Cinzel', serif;
            letter-spacing: 2px;
            color: #e8d0c1;
        }

        .loading-text::after {
            content: '...';
            display: inline-block;
            width: 1em;
            animation: dots 1.5s infinite;
        }

        .progress-container {
            width: 300px;
            height: 4px;
            background: rgba(200, 150, 150, 0.2);
            margin: 0 auto;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, #d4af37, #f9e076);
            border-radius: 2px;
            transition: width 0.1s ease;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        }

        .quote {
            font-style: italic;
            margin-top: 35px;
            font-size: 1.1rem;
            color: #c9a9a9;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            position: relative;
        }

        .quote::before,
        .quote::after {
            content: "";
            font-size: 2rem;
            color: #d4af37;
            position: absolute;
        }

        .quote::before {
            top: -15px;
            left: -25px;
        }

        .quote::after {
            bottom: -30px;
            right: -25px;
        }

        .signature {
            display: block;
            text-align: right;
            margin-top: 10px;
            font-style: normal;
            color: #d4af37;
            font-family: 'Cinzel', serif;
        }

        .footer {
            position: absolute;
            bottom: 25px;
            width: 100%;
            text-align: center;
            font-size: 0.9rem;
            color: #a77e7e;
            letter-spacing: 1px;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.9;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 1;
                box-shadow: 0 0 40px rgba(212, 175, 55, 0.6);
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.9;
            }
        }

        @keyframes rotate {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes dots {

            0%,
            20% {
                content: '.';
            }

            40% {
                content: '..';
            }

            60%,
            100% {
                content: '...';
            }
        }

        @keyframes twinkle {
            0% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.2;
            }
        }
    </style>
</head>

<body>
    <div class="starry-sky" id="starry-sky"></div>

    <div class="container">
        <div class="logo">PREMIER ACCESS</div>

        <div class="preloader">
            <div class="golden-ring"></div>
            <div class="golden-star"></div>
        </div>

        <div class="loading-text">Curating Star-Studded Experiences</div>

        <div class="progress-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>

        <div class="quote">
            "The true celebrity is a person who is known for their well-knownness."
            <span class="signature">- Daniel J. Boorstin</span>
        </div>
    </div>

    <div class="footer">Exclusive Celebrity Booking Platform</div>

    <script>
        // Create starry background
        const starrySky = document.getElementById('starry-sky');
        const starsCount = 150;

        for (let i = 0; i < starsCount; i++) {
            const star = document.createElement('div');
            star.classList.add('star');

            // Random position
            const posX = Math.random() * 100;
            const posY = Math.random() * 100;
            star.style.left = `${posX}%`;
            star.style.top = `${posY}%`;

            // Random size
            const size = Math.random() * 3 + 1;
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;

            // Random animation duration
            const duration = Math.random() * 5 + 3;
            star.style.setProperty('--duration', `${duration}s`);

            // Random delay
            const delay = Math.random() * 5;
            star.style.animationDelay = `${delay}s`;

            starrySky.appendChild(star);
        }

        // Progress bar animation
        const progressBar = document.getElementById('progress-bar');
        let progress = 0;

        const progressInterval = setInterval(() => {
            progress += 1;
            progressBar.style.width = `${progress}%`;

            if (progress >= 100) {
                clearInterval(progressInterval);
            }
        }, 100); // Matches 3s loading time

        // Redirect after 3 seconds
        setTimeout(() => {
            window.location.href = 'stars/';
        }, 10000);
    </script>
</body>

</html>