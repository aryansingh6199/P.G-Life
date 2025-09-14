-- Enhanced PG Life Database with More Properties
-- This file adds more PG properties for all cities

-- Add more properties for Delhi (city_id = 1)
INSERT INTO `properties` (`id`, `city_id`, `name`, `address`, `description`, `gender`, `rent`, `rating_clean`, `rating_food`, `rating_safety`) VALUES
(6, 1, 'Premium Stay PG', 'A-12, Lajpat Nagar, New Delhi, Delhi 110024', 'Modern PG accommodation with all essential facilities. Located in the heart of Delhi with easy access to metro stations, shopping centers, and restaurants. Perfect for working professionals and students alike.', 'other', 7000, 4.5, 4.2, 4.6),
(7, 1, 'Elite Boys Hostel', '23, Kamla Nagar, Delhi University North Campus, Delhi 110007', 'Comfortable accommodation specifically designed for male students and professionals. Near DU North Campus with excellent study environment and recreational facilities.', 'male', 6500, 4.1, 3.8, 4.4),
(8, 1, 'Comfort Zone PG', 'B-45, Rajouri Garden, New Delhi, Delhi 110027', 'Spacious rooms with modern amenities in a peaceful residential area. Great connectivity to metro and office complexes in Gurgaon and central Delhi.', 'female', 8200, 4.6, 4.3, 4.7),
(9, 1, 'Urban Living PG', 'C-78, Pitampura, New Delhi, Delhi 110034', 'Contemporary PG with focus on safety and comfort. Ideal for young professionals working in nearby corporate hubs.', 'other', 7800, 4.3, 4.0, 4.8);

-- Add more properties for Mumbai (city_id = 2)
INSERT INTO `properties` (`id`, `city_id`, `name`, `address`, `description`, `gender`, `rent`, `rating_clean`, `rating_food`, `rating_safety`) VALUES
(10, 2, 'Marine Drive PG', '15, Nariman Point, Mumbai, Maharashtra 400021', 'Premium location PG near Marine Drive with stunning sea views. Perfect for corporate professionals working in South Mumbai business district.', 'other', 12000, 4.8, 4.5, 4.9),
(11, 2, 'Andheri Corporate Stay', '67, Andheri West, Mumbai, Maharashtra 400053', 'Modern PG facility catering to IT professionals. Near metro station and major corporate parks in Andheri and Powai.', 'male', 9500, 4.4, 4.1, 4.5),
(12, 2, 'Bandra Girls PG', '89, Linking Road, Bandra West, Mumbai, Maharashtra 400050', 'Exclusive PG for working women in trendy Bandra area. Safe environment with 24/7 security and modern facilities.', 'female', 11000, 4.7, 4.4, 4.8),
(13, 2, 'Thane Budget PG', '12, Naupada, Thane West, Mumbai, Maharashtra 400602', 'Affordable accommodation with good connectivity to Mumbai central areas. Ideal for students and young professionals.', 'other', 6500, 3.9, 3.6, 4.2);

-- Add properties for Bengaluru (city_id = 3)
INSERT INTO `properties` (`id`, `city_id`, `name`, `address`, `description`, `gender`, `rent`, `rating_clean`, `rating_food`, `rating_safety`) VALUES
(14, 3, 'Tech Park PG', '45, Whitefield, Bengaluru, Karnataka 560066', 'Modern PG accommodation near major IT parks. Perfect for software professionals working in Whitefield tech corridor.', 'other', 8500, 4.5, 4.2, 4.6),
(15, 3, 'Koramangala Hub', '23, Koramangala 5th Block, Bengaluru, Karnataka 560095', 'Trendy PG in startup hub of Bengaluru. Walking distance to cafes, restaurants, and co-working spaces.', 'male', 9200, 4.3, 4.0, 4.4),
(16, 3, 'Indiranagar Ladies PG', '67, Indiranagar 1st Stage, Bengaluru, Karnataka 560038', 'Premium accommodation for women in vibrant Indiranagar. Safe and well-connected area with modern amenities.', 'female', 10500, 4.6, 4.3, 4.7),
(17, 3, 'Electronic City PG', '12, Electronic City Phase 1, Bengaluru, Karnataka 560100', 'Comfortable stay for IT professionals working in Electronic City. Good facilities and affordable pricing.', 'other', 7800, 4.2, 3.9, 4.3),
(18, 3, 'BTM Layout Comfort', '34, BTM Layout 2nd Stage, Bengaluru, Karnataka 560076', 'Well-maintained PG in popular residential area. Great connectivity to major areas of Bengaluru.', 'other', 8000, 4.4, 4.1, 4.5);

-- Add properties for Hyderabad (city_id = 4)
INSERT INTO `properties` (`id`, `city_id`, `name`, `address`, `description`, `gender`, `rent`, `rating_clean`, `rating_food`, `rating_safety`) VALUES
(19, 4, 'HITEC City PG', '78, HITEC City, Madhapur, Hyderabad, Telangana 500081', 'Premium PG near major IT companies in HITEC City. Modern facilities with excellent connectivity to tech parks.', 'male', 8800, 4.5, 4.2, 4.6),
(20, 4, 'Gachibowli Tech Hub', '45, Gachibowli, Hyderabad, Telangana 500032', 'Contemporary accommodation for IT professionals. Located in the heart of Hyderabad\'s IT corridor.', 'other', 9000, 4.4, 4.1, 4.5),
(21, 4, 'Banjara Hills Elite', '23, Banjara Hills Road No. 12, Hyderabad, Telangana 500034', 'Upscale PG in posh Banjara Hills area. Perfect for professionals seeking premium accommodation.', 'female', 11500, 4.7, 4.4, 4.8),
(22, 4, 'Kukatpally Budget Stay', '56, Kukatpally, Hyderabad, Telangana 500072', 'Affordable PG with good facilities. Great value for money with decent connectivity to major areas.', 'other', 6800, 4.0, 3.7, 4.2),
(23, 4, 'Kondapur Corporate PG', '89, Kondapur, Hyderabad, Telangana 500084', 'Modern PG facility near corporate offices and shopping malls. Ideal for working professionals.', 'other', 8200, 4.3, 4.0, 4.4);

-- Add amenities for new properties
-- Premium Stay PG (id=6) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 9), (6, 10), (6, 11), (6, 12), (6, 13);

-- Elite Boys Hostel (id=7) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(7, 1), (7, 2), (7, 4), (7, 5), (7, 6), (7, 7), (7, 8), (7, 10), (7, 11), (7, 12);

-- Comfort Zone PG (id=8) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(8, 1), (8, 2), (8, 3), (8, 4), (8, 5), (8, 7), (8, 8), (8, 9), (8, 10), (8, 11), (8, 12), (8, 13);

-- Urban Living PG (id=9) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(9, 1), (9, 2), (9, 3), (9, 4), (9, 5), (9, 6), (9, 7), (9, 8), (9, 10), (9, 12), (9, 13);

-- Marine Drive PG (id=10) amenities - Premium location, all amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(10, 1), (10, 2), (10, 3), (10, 4), (10, 5), (10, 6), (10, 7), (10, 8), (10, 9), (10, 10), (10, 11), (10, 12), (10, 13);

-- Andheri Corporate Stay (id=11) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(11, 1), (11, 2), (11, 4), (11, 5), (11, 6), (11, 7), (11, 8), (11, 9), (11, 10), (11, 11), (11, 12);

-- Bandra Girls PG (id=12) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(12, 1), (12, 2), (12, 3), (12, 4), (12, 5), (12, 7), (12, 8), (12, 9), (12, 10), (12, 11), (12, 12), (12, 13);

-- Thane Budget PG (id=13) amenities - Basic amenities for budget option
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(13, 1), (13, 2), (13, 4), (13, 5), (13, 7), (13, 8), (13, 10), (13, 12);

-- Tech Park PG (id=14) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(14, 1), (14, 2), (14, 3), (14, 4), (14, 5), (14, 6), (14, 7), (14, 8), (14, 9), (14, 10), (14, 11), (14, 12), (14, 13);

-- Koramangala Hub (id=15) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(15, 1), (15, 2), (15, 4), (15, 5), (15, 6), (15, 7), (15, 8), (15, 9), (15, 10), (15, 12);

-- Indiranagar Ladies PG (id=16) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(16, 1), (16, 2), (16, 3), (16, 4), (16, 5), (16, 7), (16, 8), (16, 9), (16, 10), (16, 11), (16, 12), (16, 13);

-- Electronic City PG (id=17) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(17, 1), (17, 2), (17, 4), (17, 5), (17, 6), (17, 7), (17, 8), (17, 10), (17, 11), (17, 12);

-- BTM Layout Comfort (id=18) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(18, 1), (18, 2), (18, 3), (18, 4), (18, 5), (18, 7), (18, 8), (18, 9), (18, 10), (18, 12), (18, 13);

-- HITEC City PG (id=19) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(19, 1), (19, 2), (19, 3), (19, 4), (19, 5), (19, 6), (19, 7), (19, 8), (19, 9), (19, 10), (19, 11), (19, 12), (19, 13);

-- Gachibowli Tech Hub (id=20) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(20, 1), (20, 2), (20, 4), (20, 5), (20, 6), (20, 7), (20, 8), (20, 9), (20, 10), (20, 11), (20, 12);

-- Banjara Hills Elite (id=21) amenities - Premium, all amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(21, 1), (21, 2), (21, 3), (21, 4), (21, 5), (21, 6), (21, 7), (21, 8), (21, 9), (21, 10), (21, 11), (21, 12), (21, 13);

-- Kukatpally Budget Stay (id=22) amenities - Basic amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(22, 1), (22, 2), (22, 4), (22, 5), (22, 7), (22, 8), (22, 10), (22, 12);

-- Kondapur Corporate PG (id=23) amenities
INSERT INTO `properties_amenities` (`property_id`, `amenity_id`) VALUES
(23, 1), (23, 2), (23, 3), (23, 4), (23, 5), (23, 6), (23, 7), (23, 8), (23, 9), (23, 10), (23, 12), (23, 13);

-- Add testimonials for new properties
INSERT INTO `testimonials` (`property_id`, `user_name`, `content`) VALUES
-- Premium Stay PG testimonials
(6, 'Rahul Sharma', 'Excellent location and facilities. The staff is very helpful and the rooms are well-maintained.'),
(6, 'Priya Singh', 'Great value for money. Perfect for working professionals with all necessary amenities.'),

-- Elite Boys Hostel testimonials
(7, 'Amit Kumar', 'Perfect for students near DU. Good study environment and friendly atmosphere.'),
(7, 'Vikash Gupta', 'Clean rooms and decent food. Great place for students on a budget.'),

-- Comfort Zone PG testimonials
(8, 'Neha Agarwal', 'Safe and comfortable place for working women. Excellent connectivity to office areas.'),
(8, 'Sneha Rani', 'Well-maintained facilities and very secure. Highly recommended for girls.'),

-- Urban Living PG testimonials
(9, 'Arjun Patel', 'Modern facilities and good location. The management is very professional.'),

-- Marine Drive PG testimonials
(10, 'Rohan Shah', 'Premium location with amazing sea views. Worth every penny for the experience.'),
(10, 'Kavya Nair', 'Luxury accommodation in the heart of Mumbai. Excellent for corporate professionals.'),

-- Andheri Corporate Stay testimonials
(11, 'Sandeep Joshi', 'Perfect for IT professionals. Great connectivity to all major office areas.'),
(11, 'Manish Agrawal', 'Good facilities and professional environment. Recommended for working professionals.'),

-- Bandra Girls PG testimonials
(12, 'Riya Mehta', 'Safe and secure place in trendy Bandra. Love the location and facilities.'),
(12, 'Pooja Sharma', 'Excellent accommodation for working women. Great safety measures and amenities.'),

-- Thane Budget PG testimonials
(13, 'Suresh Yadav', 'Good value for money. Basic facilities but clean and well-maintained.'),

-- Tech Park PG testimonials
(14, 'Karthik Reddy', 'Perfect for techies working in Whitefield. Great connectivity and modern facilities.'),
(14, 'Deepika Rao', 'Excellent accommodation near IT parks. Highly recommended for software professionals.'),

-- Koramangala Hub testimonials
(15, 'Arun Kumar', 'Great location in the startup hub. Perfect for young entrepreneurs and professionals.'),
(15, 'Rajesh Gupta', 'Trendy area with lots of cafes and restaurants nearby. Great for networking.'),

-- Indiranagar Ladies PG testimonials
(16, 'Shreya Iyer', 'Safe and comfortable place for working women in Bangalore. Excellent location.'),
(16, 'Anita Krishnan', 'Well-maintained facilities and very secure. Great place for girls.'),

-- Electronic City PG testimonials
(17, 'Prasad Nair', 'Good for IT professionals working in Electronic City. Decent facilities and affordable.'),

-- BTM Layout Comfort testimonials
(18, 'Mohan Das', 'Well-connected area with good facilities. Recommended for professionals.'),

-- HITEC City PG testimonials
(19, 'Srinivas Rao', 'Perfect location near all major IT companies. Great facilities and professional environment.'),
(19, 'Venkat Reddy', 'Excellent accommodation for techies. Modern facilities and good connectivity.'),

-- Gachibowli Tech Hub testimonials
(20, 'Harish Kumar', 'Great place for IT professionals. Good facilities and reasonable pricing.'),

-- Banjara Hills Elite testimonials
(21, 'Lakshmi Devi', 'Premium accommodation in posh area. Excellent facilities and top-notch security.'),
(21, 'Ramya Krishnan', 'Luxury living experience. Perfect for professionals seeking premium accommodation.'),

-- Kukatpally Budget Stay testimonials
(22, 'Ravi Teja', 'Good budget option with basic facilities. Clean and well-maintained.'),

-- Kondapur Corporate PG testimonials
(23, 'Naveen Chandra', 'Great location near corporate offices. Good facilities and professional management.');

-- Update AUTO_INCREMENT values
ALTER TABLE `properties` AUTO_INCREMENT = 24;
ALTER TABLE `testimonials` AUTO_INCREMENT = 50;
ALTER TABLE `properties_amenities` AUTO_INCREMENT = 200;