<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Travels | Avery Maise Transport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Extra styles for media scroller */
        .media-scroller {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding-bottom: 10px;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }
        .media-scroller::-webkit-scrollbar {
            height: 6px;
        }
        .media-scroller::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        .media-item {
            flex: 0 0 100%;
            scroll-snap-align: start;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            cursor: pointer;
            transition: var(--transition);
        }
        .media-item:hover {
            opacity: 0.9;
            transform: scale(0.98);
        }
        .media-item img, .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* Lightbox Modal */
        .lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2200;
            padding: 20px;
            backdrop-filter: blur(10px);
        }
        .lightbox.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        .lightbox-content {
            max-width: 90%;
            max-height: 90vh;
            border-radius: 12px;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
        }
        /* Expanded Trip Modal */
        .trip-modal {
            position: fixed;
            inset: 0;
            background: rgba(var(--bg-color-rgb), 0.95);
            display: none;
            z-index: 2100;
            overflow-y: auto;
            backdrop-filter: blur(20px);
            padding: 40px 0;
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .trip-modal.active {
            display: block;
        }
        .trip-modal-content {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--surface-color);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
        }
        .trip-modal-header {
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }
        .close-trip-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--bg-color);
            border: none;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .close-trip-modal:hover {
            transform: rotate(90deg);
            background: var(--primary-color);
            color: #fff;
        }
        .trip-modal-body {
            padding: 40px;
        }
        .expanded-media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .expanded-media-item {
            border-radius: 16px;
            overflow: hidden;
            background: #000;
            aspect-ratio: 16/9;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: var(--transition);
        }
        .expanded-media-item:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }
        .expanded-media-item img, .expanded-media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .trip-story {
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Card Hover Effects */
        .trip-card {
            cursor: pointer;
            position: relative;
        }
        .trip-card::after {
            content: 'View Full Adventure';
            position: absolute;
            inset: 0;
            background: rgba(14, 165, 233, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
            opacity: 0;
            transition: var(--transition);
            z-index: 10;
            pointer-events: none;
            backdrop-filter: blur(2px);
        }
        .trip-card:hover::after {
            opacity: 1;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
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
                <a href="index.php">Home</a>
                <a href="about.html">About</a>
                <a href="services.html">Services</a>
                <a href="travels.php" class="active">My Travels</a>
                <a href="contact.html" class="btn btn-primary">Book Now</a>
                <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode">
                    <i data-lucide="moon"></i>
                </button>
            </nav>
        </div>
    </header>

    <main>
        <section class="travels-header">
            <div class="container">
                <h1>My <span>Travel Adventures</span></h1>
                <p>A collection of my recent trips and favorite destinations across the Philippines.</p>


            </div>
        </section>

        <section class="section-padding">
            <div class="container">


                <!-- Gallery Grid -->
                <div id="travels-grid" class="cards-grid">
                    <div class="loading">Loading travels...</div>
                </div>
            </div>
        </section>
    </main>

    <!-- Trip Detail Modal -->
    <div id="trip-modal" class="trip-modal">
        <div class="trip-modal-content">
            <button class="close-trip-modal" onclick="closeTripDetails()">
                <i data-lucide="x"></i>
            </button>
            <div class="trip-modal-header">
                <div class="badge" id="modal-date">Date</div>
                <h1 id="modal-title">Trip Title</h1>
            </div>
            <div class="trip-modal-body">
                <div class="trip-story" id="modal-description">
                    Trip story goes here...
                </div>
                <div class="expanded-media-grid" id="modal-media-grid">
                    <!-- Media items will be loaded here -->
                </div>
                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--border-color);">
                    <h3>Explore Adventures Nearby</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 20px;">Want to experience this yourself? Browse popular tours and skip-the-line tickets for this destination.</p>
                    <a href="https://www.getyourguide.com/s/?q=Luzon" class="btn btn-outline" target="_blank" id="modal-gyg-link">View Nearby Tours on GetYourGuide</a>
                </div>
            </div>
        </div>
    </div>

    <div id="lightbox" class="lightbox">
        <button class="lightbox-close" id="lightbox-close"><i data-lucide="x"></i></button>
        <div id="lightbox-container"></div>
    </div>

    <!-- Floating Admin Button -->
    <a href="admin.html" class="fab-admin" aria-label="Admin Dashboard">
        <i data-lucide="lock"></i>
        <span class="tooltip">Admin Dashboard</span>
    </a>

    <footer style="padding: 80px 0 40px; border-top: 1px solid var(--border-color); background: var(--surface-color); margin-top: 80px;">
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

    <script src="main.js"></script>
    <script>
        lucide.createIcons();

        // --- UI Selectors ---
        const travelsGrid = document.getElementById('travels-grid');


        
        // --- Lightbox Logic ---
        const lightbox = document.getElementById('lightbox');
        const lightboxContainer = document.getElementById('lightbox-container');
        const lightboxClose = document.getElementById('lightbox-close');

        function openLightbox(src, type) {
            lightboxContainer.innerHTML = type === 'video' 
                ? `<video class="lightbox-content" src="${src}" controls autoplay playsinline></video>` 
                : `<img class="lightbox-content" src="${src}" alt="Full view">`;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            lightboxContainer.innerHTML = '';
            if (!tripModal.classList.contains('active')) {
                document.body.style.overflow = '';
            }
        }

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // --- Trip Expansion Logic ---
        let allTrips = [];
        const tripModal = document.getElementById('trip-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalDescription = document.getElementById('modal-description');
        const modalDate = document.getElementById('modal-date');
        const modalMediaGrid = document.getElementById('modal-media-grid');

        function openTripDetails(index) {
            const trip = allTrips[index];
            if (!trip) return;

            modalTitle.innerText = trip.title;
            modalDescription.innerText = trip.description;
            modalDate.innerText = 'Posted on ' + new Date(trip.created_at).toLocaleDateString();

            modalMediaGrid.innerHTML = trip.media.map(m => `
                <div class="expanded-media-item" onclick="openLightbox('${m.file_path}', '${m.file_type}')">
                    ${m.file_type === 'video' 
                        ? `<video src="${m.file_path}" autoplay muted loop playsinline></video>` 
                        : `<img src="${m.file_path}" alt="${trip.title}" loading="lazy">`
                    }
                </div>
            `).join('');

            tripModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            lucide.createIcons();
        }

        function closeTripDetails() {
            tripModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // --- Fetch Trips ---
        async function fetchTrips() {
            try {
                const response = await fetch('api.php');
                const data = await response.json();
                allTrips = data;
                
                if (allTrips.length === 0) {
                    travelsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">No travels uploaded yet.</p>';
                    return;
                }

                travelsGrid.innerHTML = allTrips.map((trip, index) => {
                    const mediaHtml = trip.media.map(m => `
                        <div class="media-item">
                            ${m.file_type === 'video' 
                                ? `<video src="${m.file_path}" autoplay muted loop playsinline></video>` 
                                : `<img src="${m.file_path}" alt="${trip.title}" loading="lazy">`
                            }
                        </div>
                    `).join('');

                    return `
                        <div class="card trip-card" onclick="openTripDetails(${index})">
                            <div class="card-img-wrapper" style="height: auto; position: relative;">
                                ${trip.media.length > 1 ? `<div class="media-count">${trip.media.length} files</div>` : ''}
                                <div class="media-scroller">
                                    ${mediaHtml}
                                </div>
                            </div>
                            <div class="card-content">
                                <h3>${trip.title}</h3>
                                <p>${trip.description.substring(0, 100)}${trip.description.length > 100 ? '...' : ''}</p>
                                <span class="trip-date">Posted on ${new Date(trip.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                    `;
                }).join('');
                
                lucide.createIcons();
            } catch (error) {
                if (travelsGrid) {
                    travelsGrid.innerHTML = '<p>Error loading travels. Check your database connection.</p>';
                }
            }
        }




        // Initialize
        fetchTrips();
    </script>
    <script src="terms-modal.js"></script>
</body>
</html>

