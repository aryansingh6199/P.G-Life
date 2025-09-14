<?php
// AI-based PG Recommendation System
// This file provides intelligent PG recommendations based on user preferences

class PGRecommendationEngine {
    private $con;
    
    public function __construct($connection) {
        $this->con = $connection;
    }
    
    /**
     * Get personalized recommendations based on user's interested properties
     */
    public function getPersonalizedRecommendations($user_id, $limit = 5) {
        // Get user's interested properties to understand preferences
        $interested_query = "
            SELECT p.*, c.name as city_name 
            FROM properties p 
            JOIN cities c ON p.city_id = c.id 
            JOIN interested_users_properties iup ON p.id = iup.property_id 
            WHERE iup.user_id = ?
        ";
        
        $stmt = mysqli_prepare($this->con, $interested_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $interested_result = mysqli_stmt_get_result($stmt);
        
        $preferences = $this->analyzeUserPreferences($interested_result);
        
        // Get recommendations based on preferences
        return $this->getRecommendationsBasedOnPreferences($preferences, $user_id, $limit);
    }
    
    /**
     * Analyze user preferences from their interested properties
     */
    private function analyzeUserPreferences($interested_result) {
        $preferences = [
            'preferred_cities' => [],
            'avg_rent' => 0,
            'preferred_gender' => 'other',
            'min_rating' => 0,
            'preferred_amenities' => []
        ];
        
        $total_rent = 0;
        $total_rating = 0;
        $count = 0;
        $gender_counts = ['male' => 0, 'female' => 0, 'other' => 0];
        $city_counts = [];
        
        while ($row = mysqli_fetch_assoc($interested_result)) {
            $count++;
            $total_rent += $row['rent'];
            $total_rating += ($row['rating_clean'] + $row['rating_food'] + $row['rating_safety']) / 3;
            
            // Track gender preferences
            $gender_counts[$row['gender']]++;
            
            // Track city preferences
            $city = $row['city_name'];
            $city_counts[$city] = isset($city_counts[$city]) ? $city_counts[$city] + 1 : 1;
        }
        
        if ($count > 0) {
            $preferences['avg_rent'] = $total_rent / $count;
            $preferences['min_rating'] = $total_rating / $count;
            
            // Determine preferred gender
            $preferences['preferred_gender'] = array_keys($gender_counts, max($gender_counts))[0];
            
            // Get preferred cities (sorted by frequency)
            arsort($city_counts);
            $preferences['preferred_cities'] = array_keys($city_counts);
        }
        
        return $preferences;
    }
    
    /**
     * Get recommendations based on analyzed preferences
     */
    private function getRecommendationsBasedOnPreferences($preferences, $user_id, $limit) {
        // Build query based on preferences
        $query = "
            SELECT p.*, c.name as city_name,
                   (p.rating_clean + p.rating_food + p.rating_safety) / 3 as avg_rating,
                   ABS(p.rent - ?) as rent_diff
            FROM properties p 
            JOIN cities c ON p.city_id = c.id 
            WHERE p.id NOT IN (
                SELECT property_id FROM interested_users_properties WHERE user_id = ?
            )
        ";
        
        $params = [$preferences['avg_rent'], $user_id];
        $types = "di";
        
        // Add rating filter
        if ($preferences['min_rating'] > 0) {
            $query .= " AND (p.rating_clean + p.rating_food + p.rating_safety) / 3 >= ?";
            $params[] = $preferences['min_rating'] - 0.5; // Slightly lower threshold
            $types .= "d";
        }
        
        // Add gender preference
        if ($preferences['preferred_gender'] !== 'other') {
            $query .= " AND (p.gender = ? OR p.gender = 'other')";
            $params[] = $preferences['preferred_gender'];
            $types .= "s";
        }
        
        // Order by relevance score
        $query .= " ORDER BY 
                      CASE 
                        WHEN c.name IN ('" . implode("','", array_slice($preferences['preferred_cities'], 0, 2)) . "') THEN 1 
                        ELSE 2 
                      END,
                      avg_rating DESC,
                      rent_diff ASC
                    LIMIT ?";
        
        $params[] = $limit;
        $types .= "i";
        
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }
    
    /**
     * Get trending PGs based on interest count and ratings
     */
    public function getTrendingPGs($limit = 10) {
        $query = "
            SELECT p.*, c.name as city_name,
                   (p.rating_clean + p.rating_food + p.rating_safety) / 3 as avg_rating,
                   COUNT(iup.id) as interest_count
            FROM properties p 
            JOIN cities c ON p.city_id = c.id 
            LEFT JOIN interested_users_properties iup ON p.id = iup.property_id
            GROUP BY p.id
            ORDER BY (interest_count * 0.4 + avg_rating * 0.6) DESC
            LIMIT ?
        ";
        
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }
    
    /**
     * Get similar PGs based on a specific property
     */
    public function getSimilarPGs($property_id, $limit = 5) {
        // Get the reference property details
        $ref_query = "SELECT * FROM properties WHERE id = ?";
        $stmt = mysqli_prepare($this->con, $ref_query);
        mysqli_stmt_bind_param($stmt, "i", $property_id);
        mysqli_stmt_execute($stmt);
        $ref_result = mysqli_stmt_get_result($stmt);
        $ref_property = mysqli_fetch_assoc($ref_result);
        
        if (!$ref_property) return false;
        
        // Find similar properties
        $query = "
            SELECT p.*, c.name as city_name,
                   (p.rating_clean + p.rating_food + p.rating_safety) / 3 as avg_rating,
                   ABS(p.rent - ?) as rent_diff
            FROM properties p 
            JOIN cities c ON p.city_id = c.id 
            WHERE p.id != ?
              AND (p.city_id = ? OR ABS(p.rent - ?) <= ?)
              AND (p.gender = ? OR p.gender = 'other' OR ? = 'other')
            ORDER BY 
                CASE WHEN p.city_id = ? THEN 1 ELSE 2 END,
                rent_diff ASC,
                avg_rating DESC
            LIMIT ?
        ";
        
        $rent_tolerance = $ref_property['rent'] * 0.3; // 30% tolerance
        
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, "iiididsis", 
            $ref_property['rent'], 
            $property_id,
            $ref_property['city_id'],
            $ref_property['rent'],
            $rent_tolerance,
            $ref_property['gender'],
            $ref_property['gender'],
            $ref_property['city_id'],
            $limit
        );
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }
    
    /**
     * Get budget-friendly recommendations
     */
    public function getBudgetFriendlyPGs($max_budget, $city_id = null, $limit = 8) {
        $query = "
            SELECT p.*, c.name as city_name,
                   (p.rating_clean + p.rating_food + p.rating_safety) / 3 as avg_rating
            FROM properties p 
            JOIN cities c ON p.city_id = c.id 
            WHERE p.rent <= ?
        ";
        
        $params = [$max_budget];
        $types = "i";
        
        if ($city_id) {
            $query .= " AND p.city_id = ?";
            $params[] = $city_id;
            $types .= "i";
        }
        
        $query .= " ORDER BY (avg_rating * 0.7 + (? - p.rent) / ? * 0.3) DESC LIMIT ?";
        $params[] = $max_budget;
        $params[] = $max_budget;
        $params[] = $limit;
        $types .= "iii";
        
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }
}

// Usage functions for the API
function getRecommendationsAPI($con, $type, $params = []) {
    $engine = new PGRecommendationEngine($con);
    $recommendations = [];
    
    switch ($type) {
        case 'personalized':
            if (isset($params['user_id'])) {
                $result = $engine->getPersonalizedRecommendations($params['user_id'], $params['limit'] ?? 5);
                $recommendations = resultToArray($result);
            }
            break;
            
        case 'trending':
            $result = $engine->getTrendingPGs($params['limit'] ?? 10);
            $recommendations = resultToArray($result);
            break;
            
        case 'similar':
            if (isset($params['property_id'])) {
                $result = $engine->getSimilarPGs($params['property_id'], $params['limit'] ?? 5);
                $recommendations = resultToArray($result);
            }
            break;
            
        case 'budget':
            if (isset($params['max_budget'])) {
                $result = $engine->getBudgetFriendlyPGs(
                    $params['max_budget'], 
                    $params['city_id'] ?? null, 
                    $params['limit'] ?? 8
                );
                $recommendations = resultToArray($result);
            }
            break;
    }
    
    return $recommendations;
}

function resultToArray($result) {
    $array = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }
    }
    return $array;
}
?>