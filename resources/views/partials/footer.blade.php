<footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- About Column -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-shop fs-4 me-2"></i>
                    Simple Marketplace
                </h5>
                <p class="text-light-emphasis">
                    Your trusted online shopping destination.We provide quality products with excellent customer
                    service.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('shop.index') }}"
                            class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Shop
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#about" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#contact" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Customer Service</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Help Center
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Shipping & Returns
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Terms & Conditions
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light-emphasis text-decoration-none hover-primary">
                            <i class="bi bi-chevron-right me-1"></i> Privacy Policy
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Contact Us</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                        <span class="text-light-emphasis">123 Street Name, City, Country 12345</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-telephone-fill text-primary me-2 mt-1"></i>
                        <a href="tel:+1234567890" class="text-light-emphasis text-decoration-none hover-primary">
                            +1 (234) 567-890
                        </a>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-envelope-fill text-primary me-2 mt-1"></i>
                        <a href="mailto:info@simplemarketplace.com"
                            class="text-light-emphasis text-decoration-none hover-primary">
                            info@simplemarketplace.com
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-light-emphasis">
                    &copy; {{ date('Y') }} Simple Marketplace.All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <i class="bi bi-credit-card-2-front fs-3 text-primary me-2" title="Visa"></i>
                <i class="bi bi-credit-card fs-3 text-danger me-2" title="Mastercard"></i>
                <i class="bi bi-paypal fs-3 text-info" title="PayPal"></i>
            </div>

        </div>
    </div>
</footer>
