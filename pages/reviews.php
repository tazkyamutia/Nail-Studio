<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="../css/reviews.css">
</head>
<body>
    <?php include '../views/headers.php'; ?>

    <section class="hero">
        <div class="container">
            <h1>Customer Reviews</h1>
            <p>See what our satisfied customers are saying about our premium cosmetic products</p>
            <div class="rating-summary">
                <div class="rating-score">4.8</div>
                <div class="stars">★★★★★</div>
                <div class="rating-text">Based on 2,847 reviews</div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="main-content">
            <div class="content-wrapper">
                <div class="filter-section">
                    <h2>Filter Reviews</h2>
                    <div class="filter-buttons">
                        <button class="filter-btn active" onclick="filterReviews('all')">All Reviews</button>
                        <button class="filter-btn" onclick="filterReviews('5-star')">5 Stars</button>
                        <button class="filter-btn" onclick="filterReviews('4-star')">4 Stars</button>
                        <button class="filter-btn" onclick="filterReviews('skincare')">Skincare</button>
                        <button class="filter-btn" onclick="filterReviews('makeup')">Makeup</button>
                        <button class="filter-btn" onclick="filterReviews('verified')">Verified Only</button>
                    </div>
                </div>

                <div class="reviews-grid" id="reviewsGrid">
                    <div class="review-card" data-rating="5" data-category="skincare" data-verified="true">
                        <div class="review-header">
                            <div class="reviewer-avatar">S</div>
                            <div class="reviewer-info">
                                <h4>Sarah Johnson</h4>
                                <div class="review-date">March 15, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">Absolutely love the Vitamin C serum! My skin has never looked better. The texture is perfect and it absorbs quickly without leaving any sticky residue. I've been using it for 3 months now and the results are incredible.</p>
                        <div class="product-info">
                            <strong>Product:</strong> Premium Vitamin C Serum
                        </div>
                        <span class="verified-badge">✓ Verified Purchase</span>
                    </div>

                    <div class="review-card" data-rating="5" data-category="makeup" data-verified="true">
                        <div class="review-header">
                            <div class="reviewer-avatar">M</div>
                            <div class="reviewer-info">
                                <h4>Michelle Chen</h4>
                                <div class="review-date">March 12, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">This foundation is a game changer! Perfect coverage, long-lasting, and matches my skin tone perfectly. I've tried so many foundations and this is by far the best. Worth every penny!</p>
                        <div class="product-info">
                            <strong>Product:</strong> Flawless Coverage Foundation
                        </div>
                        <span class="verified-badge">✓ Verified Purchase</span>
                    </div>

                    <div class="review-card" data-rating="4" data-category="skincare" data-verified="false">
                        <div class="review-header">
                            <div class="reviewer-avatar">A</div>
                            <div class="reviewer-info">
                                <h4>Amanda Wilson</h4>
                                <div class="review-date">March 10, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★☆</div>
                        <p class="review-text">Great moisturizer with a lovely texture. It keeps my skin hydrated all day long. The only reason I'm not giving 5 stars is that the packaging could be better - it's hard to get the last bit of product out.</p>
                        <div class="product-info">
                            <strong>Product:</strong> Hydrating Night Moisturizer
                        </div>
                    </div>

                    <div class="review-card" data-rating="5" data-category="makeup" data-verified="true">
                        <div class="review-header">
                            <div class="reviewer-avatar">L</div>
                            <div class="reviewer-info">
                                <h4>Lisa Rodriguez</h4>
                                <div class="review-date">March 8, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">The lipstick collection is amazing! Rich colors, smooth application, and incredible staying power. I bought 5 different shades and love them all. Customer service was also exceptional.</p>
                        <div class="product-info">
                            <strong>Product:</strong> Luxury Lipstick Collection
                        </div>
                        <span class="verified-badge">✓ Verified Purchase</span>
                    </div>

                    <div class="review-card" data-rating="5" data-category="skincare" data-verified="true">
                        <div class="review-header">
                            <div class="reviewer-avatar">J</div>
                            <div class="reviewer-info">
                                <h4>Jennifer Kim</h4>
                                <div class="review-date">March 5, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">The anti-aging cream exceeded my expectations. Fine lines are visibly reduced and my skin feels so much firmer. Fast shipping and great packaging too. Will definitely repurchase!</p>
                        <div class="product-info">
                            <strong>Product:</strong> Advanced Anti-Aging Cream
                        </div>
                        <span class="verified-badge">✓ Verified Purchase</span>
                    </div>

                    <div class="review-card" data-rating="4" data-category="makeup" data-verified="true">
                        <div class="review-header">
                            <div class="reviewer-avatar">R</div>
                            <div class="reviewer-info">
                                <h4>Rachel Thompson</h4>
                                <div class="review-date">March 3, 2025</div>
                            </div>
                        </div>
                        <div class="review-stars">★★★★☆</div>
                        <p class="review-text">Love the eyeshadow palette! Beautiful colors and good pigmentation. Some shades are more vibrant than others, but overall it's a great product. The brush that comes with it could be better quality.</p>
                        <div class="product-info">
                            <strong>Product:</strong> Sunset Eyeshadow Palette
                        </div>
                        <span class="verified-badge">✓ Verified Purchase</span>
                    </div>
                </div>

                <div class="pagination">
                    <button class="page-btn">←</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <button class="page-btn">→</button>
                </div>
            </div>
        </div>

        <div class="stats-section">
            <h2>Our Customer Satisfaction</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Customer Satisfaction</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2,847</div>
                    <div class="stat-label">Total Reviews</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../views/footers.php'; ?>

    <script src="../js/reviews.js"></script>
</body>
</html>