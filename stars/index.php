<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once 'header.php';
$celebrities = getCelebrities($pdo);
$celebrities2 = getCelebrities($pdo);

?>



<section class="py-5 celebrity-slider-section" style="margin-top: 40px;">
    <div class="container-fluid px-0">

        <div class="swiper celebritySwiper" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper">


                <?php
                shuffle($celebrities2);
                $celebrities2 = array_slice($celebrities2, 0, 3);
                foreach ($celebrities2 as $celebrity): ?>

                <div class="swiper-slide">
                    <!-- Promo Sticker -->
                    <div class="promo-sticker bg-scroll text-white fw-bold shadow-sm">
                        You can book <?= htmlspecialchars($celebrity['name']) ?> today! 👍
                    </div>
                    <div class="row align-items-center g-4 w-100">

                        <div class="col-lg-6 text-center" data-aos="fade-right">
                            <?php if ($celebrity['picture']): ?>
                            <img src="<?= htmlspecialchars($celebrity['picture']) ?>"
                                alt="<?= htmlspecialchars($celebrity['name']) ?>"
                                class="img-fluid rounded-lg shadow-lg celebrity-slide-img">
                            <?php else: ?>
                            <div
                                class="d-flex align-items-center justify-content-center bg-light text-muted rounded-lg shadow-lg celebrity-slide-img placeholder-img">
                                <i class="bi bi-person-circle" style="font-size: 6rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-6" data-aos="fade-left">
                            <div class="celebrity-text-card p-4 p-lg-5 shadow-sm bg-white rounded-4 h-100">
                                <h6 class="text-success-money mb-2 fs-3">Featured Star
                                </h6>
                                <h3 class="fw-bold display-5 text-dark-blue">
                                    <?= htmlspecialchars($celebrity['name']) ?></h3>
                                <?php
                                    $category = htmlspecialchars($celebrity['category']);
                                    $badgeClass = $categoryColors[$category] ?? 'bg-scroll'; // fallback if not found
                                    ?>
                                <span class="badge <?= $badgeClass ?> mb-3 p-2 text-uppercase fw-semibold">
                                    <?= $category ?>
                                </span>

                                <p class="text-muted lead">
                                    <?= htmlspecialchars(substr($celebrity['description'], 0, 100)) ?>
                                </p>
                                <!--<h4 class="text-success-money mb-4 fs-3">$<?= number_format($celebrity['fee']) ?>
                                    </h4>-->
                                <div class="d-flex flex-column flex-sm-row gap-3">
                                    <a href="celebrities.php?id=<?= $celebrity['id'] ?>"
                                        class="btn btn-outline-primary-custom btn-lg">
                                        View Profile
                                    </a>
                                    <a href="booking.php?id=<?= $celebrity['id'] ?>"
                                        class="btn btn-primary-custom btn-lg">
                                        <i class="bi bi-calendar-plus me-1"></i> Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>

            </div>

            <div class="swiper-button-prev celebrity-swiper-prev"></div>
            <div class="swiper-button-next celebrity-swiper-next"></div>
            <div class="swiper-pagination"></div>
        </div>
        <h4 class="text-dark-blue text-center mt-4 mb-0">
            Book your favorite celebrities
        </h4>

    </div>
</section>
<?php
// Fetch RSS feed from Google News

$rss = simplexml_load_file('https://news.google.com/rss/search?q="celebrity+meet+and+greet"&hl=en-US&gl=US&ceid=US:en');
$headlines = [];

if ($rss && isset($rss->channel->item)) {
    foreach ($rss->channel->item as $item) {
        $headlines[] = [
            'title' => (string) $item->title,
            'link' => (string) $item->link,
        ];
    }
}

// Limit to the 10 latest headlines
$latestHeadlines = array_slice($headlines, 0, 10);
?>
<div class="ticker-wrapper bg-scroll text-white py-2 overflow-hidden position-relative">
    <div class="container">
        <div class="ticker d-flex align-items-center">
            <?php if (!empty($latestHeadlines)): ?>
            <?php
                // Function to render a single set of headlines
                function renderHeadlines($headlinesArray)
                {
                    foreach ($headlinesArray as $news): ?>
            <a href="<?= $news['link'] ?>" class="text-white text-decoration-none ticker-item" target="_blank">
                <i class="bi bi-megaphone-fill me-2"></i><?= htmlspecialchars($news['title']) ?>
            </a>
            <?php endforeach;
                }
                ?>
            <?php
                // Render the headlines multiple times to ensure continuous loop
                // The more content you have, the more times you might need to repeat
                // (or adjust the animation percentage). 3-4 times is usually safe.
                for ($i = 0; $i < 3; $i++) { // Repeat 3 times: Original + 2 duplicates
                    renderHeadlines($latestHeadlines);
                }
                ?>
            <?php else: ?>
            <span>No news available right now.</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.ticker-wrapper {
    white-space: nowrap;
    overflow: hidden;
    position: relative;
}

.ticker {
    display: inline-flex;
    /*
         * Key change: The animation percentage.
         * We need to calculate how much to move based on the actual content width.
         * Since we repeat the content N times (here, 3 times), we want to scroll
         * exactly one "set" of original content.
         * If repeated 3 times, moving by -33.33% (1/3 of total width) will loop.
         * If repeated 2 times (as before), -50% (1/2 of total width) will loop.
         *
         * Adjust speed: Lower value means faster animation.
         * The duration should be relative to the amount of content.
         * A longer duration is needed for more content to keep the speed consistent.
         */
    animation: ticker-scroll 10s linear infinite;
    /* Start with 20s, adjust as needed */
}

/* Pause animation on hover */
.ticker-wrapper:hover .ticker {
    animation-play-state: paused;
}

.ticker-item {
    flex-shrink: 0;
    /* Adjusted padding-right to account for Bootstrap's gap utility */
    padding-right: 3rem;
    white-space: nowrap;
    font-weight: 500;
}

/* Keyframe animation for continuous scroll */
@keyframes ticker-scroll {
    0% {
        transform: translateX(0%);
    }

    /*
         * If you repeat the content 'N' times, the target for 100% should be
         * `translateX(-100% / N)`.
         * Since we are repeating 3 times (Original + 2 Duplicates), N=3.
         * So, -100% / 3 = -33.333% approximately.
         */
    100% {
        transform: translateX(-33.333%);
        /* Move by 1/3 of the total width */
    }
}

/* Adjust `gap` on the `.ticker` if using Bootstrap's `gap-5` for proper calculation */
/* Add this if you suspect the gap is not being factored into the width correctly */
.ticker.d-flex {
    gap: 3rem;
    /* Ensure this matches your gap-5 or adjust padding-right if gap is handled differently */
}
</style>

<!--<section class="py-5 bg-white" data-aos="fade-in">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold display-4 section-heading">About us</h2>
            </div>
            <div class="row align-items-center">

                <div class="col-md-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold">Book Your Favorite Celebrities</h1>
                    <p class="lead">Connect with top celebrities for events, appearances, and personal engagements.
                    </p>
                    <div class="d-flex gap-3 mt-4 flex-column flex-sm-row">
                        <a href="#featured" class="btn btn-light-custom btn-lg px-4">
                            <i class="bi bi-people me-2"></i>Browse Celebrities
                        </a>
                        <a href="#booking" class="btn btn-outline-light-custom btn-lg px-4">
                            <i class="bi bi-calendar-check me-2"></i>Book Now
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-center mt-4 mt-md-0" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800"
                        alt="Celebrity Booking" class="img-fluid rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>-->

<section id="featured" class="py-5 bg-light-gradient">
    <div class="container py-4">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold display-4 section-heading">Featured Celebrities</h2>
            <p class="text-muted fs-5">
                Is the celebrity you are looking to MEET not on this list? Inform our management to make
                him/her available within the shortest time frame.
            </p>
        </div>

        <div class="text-center mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="btn-group flex-wrap justify-content-center filter-buttons" role="group"
                aria-label="Filter categories">
                <button class="btn btn-filter active m-1" data-filter="all">All</button>
                <button class="btn btn-filter m-1" data-filter="Entertainment">Entertainment</button>
                <button class="btn btn-filter m-1" data-filter="Music">Music</button>
                <button class="btn btn-filter m-1" data-filter="Sports">Sports</button>
                <button class="btn btn-filter m-1" data-filter="Business">Business</button>
                <button class="btn btn-filter m-1" data-filter="Influencer">Influencer</button>
            </div>
        </div>

        <div class="row" id="celebrityCards">
            <?php foreach ($celebrities as $celebrity): ?>
            <div class="col-lg-4 col-md-6 mb-4 celebrity-card"
                data-category="<?= htmlspecialchars($celebrity['category']) ?>" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 shadow-sm border-0 animated-card">
                    <?php if ($celebrity['picture']): ?>
                    <img src="<?= htmlspecialchars($celebrity['picture']) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($celebrity['name']) ?>" style="height: 300px; object-fit: cover;">
                    <?php else: ?>
                    <div class="bg-secondary d-flex align-items-center justify-content-center placeholder-img"
                        style="height: 300px;">
                        <i class="bi bi-person-circle text-white" style="font-size: 5rem;"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark-blue">
                            <?= htmlspecialchars($celebrity['name']) ?></h5>
                        <span
                            class="badge bg-secondary-gradient mb-2 align-self-start text-uppercase fw-semibold"><?= htmlspecialchars($celebrity['category']) ?></span>
                        <!--<h6 class="text-success-money fs-5">$<?= number_format($celebrity['fee']) ?></h6>-->
                        <p class="card-text text-muted mt-2 flex-grow-1">
                            <?= htmlspecialchars(substr($celebrity['description'], 0, 100)) ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                        <a href="celebrities.php?id=<?= $celebrity['id'] ?>"
                            class="btn btn-sm btn-outline-primary-custom">
                            View Profile
                        </a>
                        <a href="booking.php?id=<?= $celebrity['id'] ?>" class="btn btn-sm btn-primary-custom">
                            <i class="bi bi-calendar-plus me-1"></i>Book Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll("[data-filter]");
    const cards = document.querySelectorAll(".celebrity-card");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            buttons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            const filter = button.getAttribute("data-filter");

            cards.forEach(card => {
                const category = card.getAttribute("data-category");
                if (filter === "all" || category === filter) {
                    card.style.display = "block";
                    // Re-initialize AOS for filtered cards
                    AOS.refreshHard();
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
});
</script>

<section id="how-it-works" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold display-4 section-heading">How It Works</h2>
            <p class="text-muted fs-5">Simple steps to book your celebrity for any event</p>
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div
                    class="p-4 border rounded bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center animated-card">
                    <div class="mb-3">
                        <span
                            class="bg-primary-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center icon-circle">
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mt-2 text-dark-blue">Browse Celebrities</h5>
                    <p class="text-muted">Explore our diverse roster of available celebrities based on your
                        needs.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div
                    class="p-4 border rounded bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center animated-card">
                    <div class="mb-3">
                        <span
                            class="bg-primary-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center icon-circle">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mt-2 text-dark-blue">Book Your Date</h5>
                    <p class="text-muted">Easily select your desired event date and provide the necessary
                        details.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div
                    class="p-4 border rounded bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center animated-card">
                    <div class="mb-3">
                        <span
                            class="bg-primary-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center icon-circle">
                            <i class="bi bi-stars"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mt-2 text-dark-blue">Enjoy Your Event</h5>
                    <p class="text-muted">Experience an unforgettable special day with your chosen celebrity!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section py-5 text-center text-white" data-aos="zoom-in">
    <div class="container">
        <h2 class="fw-bold display-4 mb-3">Ready to Book Your Star?</h2>
        <p class="lead mb-4">Don't miss the chance to make your event extraordinary.</p>
        <a href="#featured" class="btn btn-light-custom btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Start Booking Today!
        </a>
    </div>
</section>
<?php include 'footer.php'; ?>