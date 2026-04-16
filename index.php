<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avery Maise Transport | Private Driver in the Philippines</title>
    <meta name="description" content="Avery Maise Transport - Your premium private driver and tour guide exploring the beauty of the Philippines.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* --- Reviews Section --- */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .review-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            transition: var(--transition);
        }
        .review-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        .review-card .stars {
            color: #f59e0b;
            font-size: 1.1rem;
            margin-bottom: 15px;
            letter-spacing: 2px;
        }
        .review-card .review-text {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 20px;
            font-style: italic;
        }
        .review-card .reviewer {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .reviewer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #a78bfa);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .reviewer-info strong {
            display: block;
            font-size: 0.95rem;
        }
        .reviewer-info span {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        .review-quote {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.15;
            font-family: serif;
            line-height: 1;
        }

        /* --- Stats Section --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 30px;
            text-align: center;
        }
        .stat-item h3 {
            font-size: 2.8rem;
            background: linear-gradient(135deg, var(--primary-color), #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .stat-item p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Floating Review Button */
        .fab-review {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 900;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(245,158,11,0.4);
            transition: all 0.3s ease;
            animation: fabPulse 2s infinite;
        }
        .fab-review:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(245,158,11,0.5);
        }
        @keyframes fabPulse {
            0%, 100% { box-shadow: 0 8px 25px rgba(245,158,11,0.4); }
            50% { box-shadow: 0 8px 35px rgba(245,158,11,0.6); }
        }
        .fab-tooltip {
            position: absolute;
            right: 70px;
            background: var(--surface-color);
            color: var(--text-color);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border: 1px solid var(--border-color);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .fab-review:hover .fab-tooltip { opacity: 1; }

        /* Review Modal Overlay */
        .review-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 2000;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .review-modal-overlay.active { display: flex; }
        .review-modal-box {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            max-width: 500px;
            width: 100%;
            padding: 35px;
            position: relative;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: reviewSlideUp 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        @keyframes reviewSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .review-modal-close {
            position: absolute;
            top: 15px;
            right: 18px;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.5rem;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }
        .review-modal-close:hover {
            background: var(--border-color);
            color: var(--text-color);
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="brand">Avery Maise <span>Transport</span></a>
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Toggle Menu">
                <i data-lucide="menu"></i>
            </button>
            <nav class="nav-links" id="nav-links">
                <a href="index.php" class="active">Home</a>
                <a href="about.html">About</a>
                <a href="services.html">Services</a>
                <a href="travels.php">My Travels</a>
                <a href="contact.html" class="btn btn-primary">Book Now</a>
                <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode">
                    <i data-lucide="moon"></i>
                </button>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-bg">
                <img src="taal_hero.png" alt="Taal Volcano Beautiful Landscape" />
                <div class="overlay"></div>
            </div>
            <div class="container hero-content">
                <span class="badge">Your Personal Driver</span>
                <h1>Discover the Philippines with <span>Avery Maise Transport</span></h1>
                <p>Relax and enjoy the ride. From beautiful beaches to majestic mountains, we'll take you there safely and comfortably.</p>
                <div class="hero-actions">
                    <a href="contact.html" class="btn btn-primary">Plan Your Trip</a>
                    <a href="travels.php" class="btn btn-secondary">My Travels</a>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="section-padding" style="background: var(--surface-color); border-bottom: 1px solid var(--border-color);">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <h3>500+</h3>
                        <p>Trips Completed</p>
                    </div>
                    <div class="stat-item">
                        <h3>8+</h3>
                        <p>Luzon Destinations</p>
                    </div>
                    <div class="stat-item">
                        <h3>5★</h3>
                        <p>Passenger Rating</p>
                    </div>
                    <div class="stat-item">
                        <h3>24/7</h3>
                        <p>Available for Booking</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Quick Highlights Section -->
        <section class="section-padding">
            <div class="container">
                <div class="section-header">
                    <h2>Why Choose Us?</h2>
                    <p>Experience the finest private transport service in the Philippines.</p>
                </div>
                <div class="cards-grid">
                    <div class="card" style="padding: 2rem; text-align: center;">
                        <i data-lucide="shield-check" style="width: 48px; height: 48px; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Safe & Reliable</h3>
                        <p>Fully maintained vehicles and professional driving at all times.</p>
                    </div>
                    <div class="card" style="padding: 2rem; text-align: center;">
                        <i data-lucide="map" style="width: 48px; height: 48px; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Flexible Itineraries</h3>
                        <p>Customize your own trip or let us guide you to the best spots.</p>
                    </div>
                    <div class="card" style="padding: 2rem; text-align: center;">
                        <i data-lucide="clock" style="width: 48px; height: 48px; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Punctual</h3>
                        <p>We value your time. Guaranteed on-time pickups for airport and tours.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Customer Reviews Section -->
        <section class="section-padding" style="background: var(--bg-color);">
            <div class="container">
                <div class="section-header">
                    <div class="badge">Testimonials</div>
                    <h2>What Our Passengers Say</h2>
                    <p>Real feedback from real travelers who rode with us.</p>
                </div>
                <div id="reviews-grid" class="reviews-grid">
                    <!-- Reviews load dynamically -->
                    <div style="text-align: center; padding: 40px; color: var(--text-secondary);">Loading reviews...</div>
                </div>


            </div>
        </section>

        <!-- FAQ Section -->
        <section class="section-padding">
            <div class="container">
                <div class="section-header">
                    <div class="badge">FAQ</div>
                    <h2>Frequently Asked Questions</h2>
                    <p>Everything you need to know before booking.</p>
                </div>
                <div style="max-width: 750px; margin: 0 auto;">
                    <details class="card" style="padding: 20px 25px; margin-bottom: 12px; cursor: pointer;">
                        <summary style="font-weight: 600; font-size: 1rem; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            What vehicle do you use?
                            <i data-lucide="chevron-down" style="width:20px;height:20px;color:var(--text-secondary);"></i>
                        </summary>
                        <p style="margin-top: 12px; color: var(--text-secondary); line-height: 1.7;">We use a well-maintained 2022 Toyota Avanza in Greenish Gun Metal Mica Metallic with air conditioning, comfortable seating, and space for up to 7 passengers plus luggage.</p>
                    </details>
                    <details class="card" style="padding: 20px 25px; margin-bottom: 12px; cursor: pointer;">
                        <summary style="font-weight: 600; font-size: 1rem; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            How do I pay?
                            <i data-lucide="chevron-down" style="width:20px;height:20px;color:var(--text-secondary);"></i>
                        </summary>
                        <p style="margin-top: 12px; color: var(--text-secondary); line-height: 1.7;">We accept cash (Philippine Peso), GCash, PayMaya, and PayPal. For long-distance or multi-day trips, a partial advance payment may be requested to secure your booking.</p>
                    </details>
                    <details class="card" style="padding: 20px 25px; margin-bottom: 12px; cursor: pointer;">
                        <summary style="font-weight: 600; font-size: 1rem; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            Can I cancel my booking?
                            <i data-lucide="chevron-down" style="width:20px;height:20px;color:var(--text-secondary);"></i>
                        </summary>
                        <p style="margin-top: 12px; color: var(--text-secondary); line-height: 1.7;">Yes! Free cancellation is available up to 24 hours before the trip. Cancellations within 24 hours may incur a 50% fee. No-shows are charged the full fare.</p>
                    </details>
                    <details class="card" style="padding: 20px 25px; margin-bottom: 12px; cursor: pointer;">
                        <summary style="font-weight: 600; font-size: 1rem; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            Do you offer multi-day trips?
                            <i data-lucide="chevron-down" style="width:20px;height:20px;color:var(--text-secondary);"></i>
                        </summary>
                        <p style="margin-top: 12px; color: var(--text-secondary); line-height: 1.7;">Absolutely! We offer multi-day trips to destinations like Ilocos, Baguio, and Bicol. Contact us to discuss your itinerary and we'll provide a customized rate.</p>
                    </details>
                </div>
                <div style="text-align:center;margin-top:30px;">
                    <p style="color:var(--text-secondary);font-size:0.95rem;">For more questions, just contact us at <a href="mailto:junlynlp@gmail.com" style="color:var(--primary-color);font-weight:600;">junlynlp@gmail.com</a></p>
                </div>
            </div>
        </section>
    </main>

    <footer style="padding: 80px 0 40px; border-top: 1px solid var(--border-color); background: var(--surface-color);">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 60px;">
                <div>
                    <a href="index.php" class="brand" style="margin-bottom: 20px; display: block;">Avery Maise <span>Transport</span></a>
                    <p style="color: var(--text-secondary); line-height: 1.6;">Professional transport and tour services across Luzon. Safe, reliable, and premium travel for you and your family.</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 20px;">Quick Links</h4>
                    <ul style="list-style: none; display: grid; gap: 10px;">
                        <li><a href="index.php" style="color: var(--text-secondary); text-decoration: none;">Home</a></li>
                        <li><a href="about.html" style="color: var(--text-secondary); text-decoration: none;">About Us</a></li>
                        <li><a href="services.html" style="color: var(--text-secondary); text-decoration: none;">Services</a></li>
                        <li><a href="travels.php" style="color: var(--text-secondary); text-decoration: none;">My Travels</a></li>
                        <li><a href="contact.html" style="color: var(--text-secondary); text-decoration: none;">Book Now</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: 20px;">Business Info</h4>
                    <p style="color: var(--text-secondary); margin-bottom: 10px;"><i data-lucide="map-pin" style="width: 16px; display: inline; vertical-align: middle; margin-right: 8px;"></i> Metro Manila, Philippines</p>
                    <p style="color: var(--text-secondary); margin-bottom: 10px;"><i data-lucide="shield-check" style="width: 16px; display: inline; vertical-align: middle; margin-right: 8px;"></i> Licensed Transport Operator</p>
                    <p style="color: var(--text-secondary);"><i data-lucide="mail" style="width: 16px; display: inline; vertical-align: middle; margin-right: 8px;"></i> junlynlp@gmail.com</p>
                </div>
            </div>
            <div style="text-align: center; padding-top: 40px; border-top: 1px solid var(--border-color); color: var(--text-secondary); font-size: 0.9rem;">
                <p>&copy; 2026 Avery Maise Transport. Official Partner of <a href="https://www.getyourguide.com" target="_blank" style="color: var(--primary-color); font-weight: 600;">GetYourGuide</a>.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Review Button -->
    <button class="fab-review" id="fab-review-btn" aria-label="Leave a Review">
        ★
        <span class="fab-tooltip">Rate Us!</span>
    </button>

    <!-- Review Modal -->
    <div class="review-modal-overlay" id="review-modal" onclick="if(event.target===this)closeReviewModal()">
        <div class="review-modal-box">
            <button class="review-modal-close" onclick="closeReviewModal()">✕</button>
            <h3 style="text-align:center;margin-bottom:5px;">⭐ Leave a Review</h3>
            <p style="text-align:center;color:var(--text-secondary);font-size:0.9rem;margin-bottom:25px;">Share your experience riding with us!</p>
            <form id="review-form" enctype="multipart/form-data">
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Your Name</label>
                    <input type="text" name="name" placeholder="e.g. Juan D." required style="width:100%;padding:12px 16px;border:1px solid var(--border-color);border-radius:12px;background:var(--bg-color);color:var(--text-color);font-family:inherit;font-size:0.95rem;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Route / Trip</label>
                    <input type="text" name="route" placeholder="e.g. Manila to Tagaytay" required style="width:100%;padding:12px 16px;border:1px solid var(--border-color);border-radius:12px;background:var(--bg-color);color:var(--text-color);font-family:inherit;font-size:0.95rem;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Rating</label>
                    <div id="star-rating" style="display:flex;gap:8px;font-size:1.8rem;cursor:pointer;color:#d1d5db;">
                        <span data-star="1">★</span>
                        <span data-star="2">★</span>
                        <span data-star="3">★</span>
                        <span data-star="4">★</span>
                        <span data-star="5" style="color:#f59e0b;">★</span>
                    </div>
                    <input type="hidden" id="review-rating" name="rating" value="5">
                </div>
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Your Review</label>
                    <textarea name="message" rows="3" placeholder="Tell us about your experience..." required style="width:100%;padding:12px 16px;border:1px solid var(--border-color);border-radius:12px;background:var(--bg-color);color:var(--text-color);font-family:inherit;font-size:0.95rem;resize:vertical;box-sizing:border-box;"></textarea>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Add a Photo <span style="color:var(--text-secondary);font-weight:400;">(optional)</span></label>
                    <input type="file" name="photo" accept="image/*" style="width:100%;padding:10px;border:1px dashed var(--border-color);border-radius:12px;background:var(--bg-color);color:var(--text-color);font-family:inherit;font-size:0.85rem;box-sizing:border-box;cursor:pointer;">
                </div>
                <button type="submit" id="review-submit-btn" class="btn btn-primary" style="width:100%;padding:14px;">Submit Review</button>
            </form>
            <div id="review-response" style="margin-top:15px;text-align:center;display:none;"></div>
        </div>
    </div>

    <!-- Floating Admin Button -->
    <a href="admin.html" class="fab-admin" aria-label="Admin Dashboard">
        <i data-lucide="lock"></i>
        <span class="tooltip">Admin Dashboard</span>
    </a>

    <script>lucide.createIcons();</script>
    <script src="main.js"></script>
    <script src="terms-modal.js"></script>
    <script>
        // --- Floating Review Modal ---
        const reviewModal = document.getElementById('review-modal');
        document.getElementById('fab-review-btn').addEventListener('click', () => {
            reviewModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        function closeReviewModal() {
            reviewModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // --- Dynamic Reviews ---
        const reviewsGrid = document.getElementById('reviews-grid');

        async function loadReviews() {
            try {
                const res = await fetch('reviews_api.php');
                const reviews = await res.json();
                if (reviews.length === 0) {
                    reviewsGrid.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-secondary);grid-column:1/-1;">No reviews yet. Be the first to leave one!</div>';
                    return;
                }
                reviewsGrid.innerHTML = reviews.map(r => {
                    const initials = r.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
                    const stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
                    return `
                        <div class="review-card">
                            <div class="review-quote">"</div>
                            <div class="stars">${stars}</div>
                            ${r.photo ? `<img src="${r.photo}" alt="Review photo" style="width:100%;border-radius:12px;margin-bottom:12px;max-height:200px;object-fit:cover;">` : ''}
                            <p class="review-text">${r.message}</p>
                            <div class="reviewer">
                                <div class="reviewer-avatar">${initials}</div>
                                <div class="reviewer-info">
                                    <strong>${r.name}</strong>
                                    <span>${r.route}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch(e) {
                reviewsGrid.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-secondary);grid-column:1/-1;">Could not load reviews.</div>';
            }
        }
        loadReviews();

        // --- Star Rating Picker ---
        const starContainer = document.getElementById('star-rating');
        const ratingInput = document.getElementById('review-rating');
        let currentRating = 5;

        starContainer.querySelectorAll('[data-star]').forEach(star => {
            star.addEventListener('click', () => {
                currentRating = parseInt(star.dataset.star);
                ratingInput.value = currentRating;
                starContainer.querySelectorAll('[data-star]').forEach(s => {
                    s.style.color = parseInt(s.dataset.star) <= currentRating ? '#f59e0b' : '#d1d5db';
                });
            });
            star.addEventListener('mouseenter', () => {
                const hoverVal = parseInt(star.dataset.star);
                starContainer.querySelectorAll('[data-star]').forEach(s => {
                    s.style.color = parseInt(s.dataset.star) <= hoverVal ? '#f59e0b' : '#d1d5db';
                });
            });
        });
        starContainer.addEventListener('mouseleave', () => {
            starContainer.querySelectorAll('[data-star]').forEach(s => {
                s.style.color = parseInt(s.dataset.star) <= currentRating ? '#f59e0b' : '#d1d5db';
            });
        });

        // --- Submit Review ---
        const reviewForm = document.getElementById('review-form');
        const reviewBtn = document.getElementById('review-submit-btn');
        const reviewResponse = document.getElementById('review-response');

        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            reviewBtn.disabled = true;
            reviewBtn.innerText = 'Submitting...';
            reviewResponse.style.display = 'none';

            const formData = new FormData(reviewForm);

            try {
                const res = await fetch('reviews_api.php', { method: 'POST', body: formData });
                const result = await res.json();
                reviewResponse.style.display = 'block';
                if (result.success) {
                    reviewResponse.innerHTML = `<span style="color:#22c55e;font-weight:600;">✅ ${result.success}</span>`;
                    reviewForm.reset();
                    currentRating = 5;
                    ratingInput.value = 5;
                    starContainer.querySelectorAll('[data-star]').forEach(s => {
                        s.style.color = parseInt(s.dataset.star) <= 5 ? '#f59e0b' : '#d1d5db';
                    });
                } else {
                    reviewResponse.innerHTML = `<span style="color:#ef4444;font-weight:600;">❌ ${result.error}</span>`;
                }
            } catch(err) {
                reviewResponse.style.display = 'block';
                reviewResponse.innerHTML = '<span style="color:#ef4444;font-weight:600;">❌ Something went wrong.</span>';
            } finally {
                reviewBtn.disabled = false;
                reviewBtn.innerText = 'Submit Review';
            }
        });


    </script>
</body>
</html>
