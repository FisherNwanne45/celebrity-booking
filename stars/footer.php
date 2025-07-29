<footer class="bg-dark-blue text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-right">
                <h5 class="mb-3 text-white fw-bold">
                    <a class="navbar-brand d-flex align-items-center text-dark text-decoration-none" href="index.php">
                        <!-- Logo Wrapper: This div controls the overall size and positioning of the animated logo -->
                        <div class="logo-wrapper position-relative me-1 flex-shrink-0">
                            <div class="golden-ring-logo"></div>
                            <div class="golden-star-logo"></div>
                        </div>
                        <!-- Site Name -->
                        <span class="h4 fw-bold mb-0"><?= $siteName ?></span>
                    </a>
                </h5>
                <p class="text-light-grey">Connecting you with the world's top celebrities for unforgettable
                    events and
                    experiences.</p>
                <div class="d-flex gap-3 mb-3">
                    <?php if ($facebook): ?>
                    <a href="<?= $facebook ?>" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if ($twitter): ?>
                    <a href="<?= $twitter ?>" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
                    <?php endif; ?>
                    <?php if ($instagram): ?>
                    <a href="<?= $instagram ?>" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if ($linkedin): ?>
                    <a href="<?= $linkedin ?>" class="text-white fs-4"><i class="bi bi-linkedin"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-2 mb-4" data-aos="fade-up" data-aos-delay="100">
                <h5 class="mb-3 text-white fw-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-light-grey text-decoration-none">Home</a>
                    </li>
                    <li class="mb-2"><a href="index.php#featured"
                            class="text-light-grey text-decoration-none">Celebrities</a>
                    </li>
                    <li class="mb-2"><a href="index.php#how-it-works" class="text-light-grey text-decoration-none">How
                            It
                            Works</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                <h5 class="mb-3 text-white fw-bold">Contact Us</h5>
                <ul class="list-unstyled text-light-grey">
                    <?php if ($email): ?>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i><?= $email ?></li><?php endif; ?>
                    <?php if ($phone): ?><li class="mb-2"><i class="bi bi-telephone me-2"></i><?= $phone ?></li>
                    <?php endif; ?>
                    <?php if ($address): ?><li class="mb-2"><i class="bi bi-geo-alt me-2"></i><?= $address ?></li>
                    <?php endif; ?>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i><a href="contact.php"
                            class="text-light-grey text-decoration-none">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="mb-3 text-white fw-bold">Newsletter</h5>
                <p class="text-light-grey">Subscribe for updates on new celebrity additions and exclusive
                    offers.</p>
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Your email">
                    <button class="btn btn-primary-custom" type="button">Join</button>
                </div>
            </div>
        </div>
        <hr class="my-4 border-light-grey">
        <div class="text-center text-light-grey">
            <p>&copy; <?= date('Y') ?> <?= $siteName ?>. All rights reserved.</p>
        </div>
    </div>
    <button id="backToTopBtn" class="btn btn-primary back-to-top shadow">
        <i class="bi bi-arrow-up"></i>
    </button>

</footer>
<script>
// Show/hide button on scroll
window.addEventListener('scroll', () => {
    const btn = document.getElementById('backToTopBtn');
    btn.style.display = (window.scrollY > 300) ? 'block' : 'none';
});

// Smooth scroll to top
document.getElementById('backToTopBtn').addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 1000, // Animation duration
    once: true, // Whether animation should happen only once - while scrolling down
});

const swiper = new Swiper('.celebritySwiper', {
    slidesPerView: 1,
    spaceBetween: 30,
    navigation: {
        nextEl: '.celebrity-swiper-next',
        prevEl: '.celebrity-swiper-prev',
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    // Responsive breakpoints
    breakpoints: {
        768: {
            slidesPerView: 1,
        },
    }
});
</script>
</body>

</html>