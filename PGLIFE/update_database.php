<?php
// Database connection test and property insertion script
require "includes/database_connect.php";

if ($con) {
    echo "Database connection successful!<br>";
    
    // Check current properties
    $result = mysqli_query($con, "SELECT p.id, p.name, c.name as city_name FROM properties p JOIN cities c ON p.city_id = c.id ORDER BY c.id, p.id");
    
    echo "<h3>Current Properties:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . " - " . $row['name'] . " in " . $row['city_name'] . "<br>";
    }
    
    // Let's add the new properties directly
    $newProperties = [
        // Delhi properties (city_id = 1)
        [6, 1, 'Premium Stay PG', 'A-12, Lajpat Nagar, New Delhi, Delhi 110024', 'Modern PG accommodation with all essential facilities. Located in the heart of Delhi with easy access to metro stations, shopping centers, and restaurants. Perfect for working professionals and students alike.', 'other', 7000, 4.5, 4.2, 4.6],
        [7, 1, 'Elite Boys Hostel', '23, Kamla Nagar, Delhi University North Campus, Delhi 110007', 'Comfortable accommodation specifically designed for male students and professionals. Near DU North Campus with excellent study environment and recreational facilities.', 'male', 6500, 4.1, 3.8, 4.4],
        [8, 1, 'Comfort Zone PG', 'B-45, Rajouri Garden, New Delhi, Delhi 110027', 'Spacious rooms with modern amenities in a peaceful residential area. Great connectivity to metro and office complexes in Gurgaon and central Delhi.', 'female', 8200, 4.6, 4.3, 4.7],
        [9, 1, 'Urban Living PG', 'C-78, Pitampura, New Delhi, Delhi 110034', 'Contemporary PG with focus on safety and comfort. Ideal for young professionals working in nearby corporate hubs.', 'other', 7800, 4.3, 4.0, 4.8],
        
        // Mumbai properties (city_id = 2)
        [10, 2, 'Marine Drive PG', '15, Nariman Point, Mumbai, Maharashtra 400021', 'Premium location PG near Marine Drive with stunning sea views. Perfect for corporate professionals working in South Mumbai business district.', 'other', 12000, 4.8, 4.5, 4.9],
        [11, 2, 'Andheri Corporate Stay', '67, Andheri West, Mumbai, Maharashtra 400053', 'Modern PG facility catering to IT professionals. Near metro station and major corporate parks in Andheri and Powai.', 'male', 9500, 4.4, 4.1, 4.5],
        [12, 2, 'Bandra Girls PG', '89, Linking Road, Bandra West, Mumbai, Maharashtra 400050', 'Exclusive PG for working women in trendy Bandra area. Safe environment with 24/7 security and modern facilities.', 'female', 11000, 4.7, 4.4, 4.8],
        [13, 2, 'Thane Budget PG', '12, Naupada, Thane West, Mumbai, Maharashtra 400602', 'Affordable accommodation with good connectivity to Mumbai central areas. Ideal for students and young professionals.', 'other', 6500, 3.9, 3.6, 4.2],
        
        // Bengaluru properties (city_id = 3)
        [14, 3, 'Tech Park PG', '45, Whitefield, Bengaluru, Karnataka 560066', 'Modern PG accommodation near major IT parks. Perfect for software professionals working in Whitefield tech corridor.', 'other', 8500, 4.5, 4.2, 4.6],
        [15, 3, 'Koramangala Hub', '23, Koramangala 5th Block, Bengaluru, Karnataka 560095', 'Trendy PG in startup hub of Bengaluru. Walking distance to cafes, restaurants, and co-working spaces.', 'male', 9200, 4.3, 4.0, 4.4],
        [16, 3, 'Indiranagar Ladies PG', '67, Indiranagar 1st Stage, Bengaluru, Karnataka 560038', 'Premium accommodation for women in vibrant Indiranagar. Safe and well-connected area with modern amenities.', 'female', 10500, 4.6, 4.3, 4.7],
        [17, 3, 'Electronic City PG', '12, Electronic City Phase 1, Bengaluru, Karnataka 560100', 'Comfortable stay for IT professionals working in Electronic City. Good facilities and affordable pricing.', 'other', 7800, 4.2, 3.9, 4.3],
        [18, 3, 'BTM Layout Comfort', '34, BTM Layout 2nd Stage, Bengaluru, Karnataka 560076', 'Well-maintained PG in popular residential area. Great connectivity to major areas of Bengaluru.', 'other', 8000, 4.4, 4.1, 4.5],
        
        // Hyderabad properties (city_id = 4)
        [19, 4, 'HITEC City PG', '78, HITEC City, Madhapur, Hyderabad, Telangana 500081', 'Premium PG near major IT companies in HITEC City. Modern facilities with excellent connectivity to tech parks.', 'male', 8800, 4.5, 4.2, 4.6],
        [20, 4, 'Gachibowli Tech Hub', '45, Gachibowli, Hyderabad, Telangana 500032', 'Contemporary accommodation for IT professionals. Located in the heart of Hyderabad\'s IT corridor.', 'other', 9000, 4.4, 4.1, 4.5],
        [21, 4, 'Banjara Hills Elite', '23, Banjara Hills Road No. 12, Hyderabad, Telangana 500034', 'Upscale PG in posh Banjara Hills area. Perfect for professionals seeking premium accommodation.', 'female', 11500, 4.7, 4.4, 4.8],
        [22, 4, 'Kukatpally Budget Stay', '56, Kukatpally, Hyderabad, Telangana 500072', 'Affordable PG with good facilities. Great value for money with decent connectivity to major areas.', 'other', 6800, 4.0, 3.7, 4.2],
        [23, 4, 'Kondapur Corporate PG', '89, Kondapur, Hyderabad, Telangana 500084', 'Modern PG facility near corporate offices and shopping malls. Ideal for working professionals.', 'other', 8200, 4.3, 4.0, 4.4]
    ];
    
    echo "<h3>Adding new properties...</h3>";
    
    foreach ($newProperties as $prop) {
        $sql = "INSERT IGNORE INTO properties (id, city_id, name, address, description, gender, rent, rating_clean, rating_food, rating_safety) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "iissssiddd", $prop[0], $prop[1], $prop[2], $prop[3], $prop[4], $prop[5], $prop[6], $prop[7], $prop[8], $prop[9]);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Added: " . $prop[2] . "<br>";
        } else {
            echo "Error adding " . $prop[2] . ": " . mysqli_error($con) . "<br>";
        }
        mysqli_stmt_close($stmt);
    }
    
    echo "<h3>Adding basic amenities for new properties...</h3>";
    
    // Add basic amenities for all new properties (id 6-23)
    for ($prop_id = 6; $prop_id <= 23; $prop_id++) {
        $basic_amenities = [1, 2, 4, 5, 7, 8, 10, 12]; // Wi-Fi, Power Backup, TV, Bed, Water Purifier, Dining, Washing Machine, CCTV
        
        foreach ($basic_amenities as $amenity_id) {
            $sql = "INSERT IGNORE INTO properties_amenities (property_id, amenity_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $prop_id, $amenity_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    
    echo "<h3>Adding testimonials...</h3>";
    
    $testimonials = [
        [6, 'Rahul Sharma', 'Excellent location and facilities. The staff is very helpful and the rooms are well-maintained.'],
        [7, 'Amit Kumar', 'Perfect for students near DU. Good study environment and friendly atmosphere.'],
        [8, 'Neha Agarwal', 'Safe and comfortable place for working women. Excellent connectivity to office areas.'],
        [10, 'Rohan Shah', 'Premium location with amazing sea views. Worth every penny for the experience.'],
        [14, 'Karthik Reddy', 'Perfect for techies working in Whitefield. Great connectivity and modern facilities.'],
        [19, 'Srinivas Rao', 'Perfect location near all major IT companies. Great facilities and professional environment.']
    ];
    
    foreach ($testimonials as $test) {
        $sql = "INSERT IGNORE INTO testimonials (property_id, user_name, content) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $test[0], $test[1], $test[2]);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Added testimonial for property " . $test[0] . "<br>";
        }
        mysqli_stmt_close($stmt);
    }
    
    echo "<h3>Database update completed!</h3>";
    
} else {
    echo "Database connection failed: " . mysqli_connect_error();
}
?>