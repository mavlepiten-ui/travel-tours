-- ============================================
-- Avery Maise Transport - Complete Database
-- Run this in phpMyAdmin or MySQL CLI to set up
-- ============================================

-- Reset the database for the new structure
DROP DATABASE IF EXISTS travel_db;
CREATE DATABASE travel_db;
USE travel_db;

-- Create the trips table (MetaData only)
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the trip_media table (Multiple files per trip)
-- Media is stored as LONGBLOB in the database for cloud compatibility
CREATE TABLE trip_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL DEFAULT 'db_stored',
    file_type ENUM('image', 'video') NOT NULL,
    file_data LONGBLOB DEFAULT NULL,
    mime_type VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE
);

-- Create the reviews table (Customer Ratings)
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    route VARCHAR(150) NOT NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    message TEXT NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Optional: Seed with starter reviews (pre-approved)
-- ============================================
INSERT INTO reviews (name, route, rating, message, approved) VALUES
('Maria C.', 'Manila to Tagaytay Trip', 5, 'Kuya was very accommodating and patient. The vehicle was super clean and cold. We felt safe the entire trip to Tagaytay. Will definitely book again!', 1),
('James R.', 'Manila to Baguio (3-Day Trip)', 5, 'Best driver experience in the Philippines! He knew all the hidden spots in Baguio. Very professional and the vehicle was spotless. Highly recommended for families.', 1),
('Sarah K.', 'NAIA Airport Transfer', 5, 'Airport pickup was on time even though our flight was delayed. Very understanding and flexible. The ride to our hotel in Makati was smooth and comfortable.', 1);

-- ============================================
-- Useful Admin SQL Commands
-- Run these in phpMyAdmin > SQL tab
-- Change the ID numbers to match your reviews
-- ============================================

-- View all pending (unapproved) reviews:
SELECT * FROM reviews WHERE approved = 0;

-- Approve a review (change 4 to the actual review ID):
-- UPDATE reviews SET approved = 1 WHERE id = 4;

-- Delete a spam/troll review (change 4 to the actual review ID):
-- DELETE FROM reviews WHERE id = 4;

-- View all approved reviews:
SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC;

-- View all trips with their media count:
-- SELECT t.id, t.title, t.created_at, COUNT(m.id) as media_count 
-- FROM trips t LEFT JOIN trip_media m ON t.id = m.trip_id 
-- GROUP BY t.id ORDER BY t.created_at DESC;

-- ============================================
-- MIGRATION: If upgrading from old schema, run these:
-- ALTER TABLE trip_media ADD COLUMN file_data LONGBLOB DEFAULT NULL;
-- ALTER TABLE trip_media ADD COLUMN mime_type VARCHAR(100) DEFAULT NULL;
-- ============================================
