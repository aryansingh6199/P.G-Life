<?php
session_start();
require "../includes/database_connect.php";
require "../includes/recommendation_engine.php";

header('Content-Type: application/json');

if (!$con) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit();
}

$type = $_GET['type'] ?? 'trending';
$params = [];

// Get parameters based on type
switch ($type) {
    case 'personalized':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'User not logged in'
            ]);
            exit();
        }
        $params['user_id'] = $_SESSION['user_id'];
        $params['limit'] = $_GET['limit'] ?? 5;
        break;
        
    case 'similar':
        if (!isset($_GET['property_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Property ID required'
            ]);
            exit();
        }
        $params['property_id'] = intval($_GET['property_id']);
        $params['limit'] = $_GET['limit'] ?? 5;
        break;
        
    case 'budget':
        if (!isset($_GET['max_budget'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Max budget required'
            ]);
            exit();
        }
        $params['max_budget'] = intval($_GET['max_budget']);
        $params['city_id'] = isset($_GET['city_id']) ? intval($_GET['city_id']) : null;
        $params['limit'] = $_GET['limit'] ?? 8;
        break;
        
    case 'trending':
    default:
        $params['limit'] = $_GET['limit'] ?? 10;
        break;
}

// Get recommendations
$recommendations = getRecommendationsAPI($con, $type, $params);

// Add additional data for each recommendation
foreach ($recommendations as &$rec) {
    // Get interest count
    $interest_query = "SELECT COUNT(*) as count FROM interested_users_properties WHERE property_id = ?";
    $stmt = mysqli_prepare($con, $interest_query);
    mysqli_stmt_bind_param($stmt, "i", $rec['id']);
    mysqli_stmt_execute($stmt);
    $interest_result = mysqli_stmt_get_result($stmt);
    $interest_row = mysqli_fetch_assoc($interest_result);
    $rec['interest_count'] = $interest_row['count'];
    
    // Calculate overall rating
    $rec['overall_rating'] = round(($rec['rating_clean'] + $rec['rating_food'] + $rec['rating_safety']) / 3, 1);
    
    // Add amenities count
    $amenities_query = "SELECT COUNT(*) as count FROM properties_amenities WHERE property_id = ?";
    $stmt = mysqli_prepare($con, $amenities_query);
    mysqli_stmt_bind_param($stmt, "i", $rec['id']);
    mysqli_stmt_execute($stmt);
    $amenities_result = mysqli_stmt_get_result($stmt);
    $amenities_row = mysqli_fetch_assoc($amenities_result);
    $rec['amenities_count'] = $amenities_row['count'];
}

echo json_encode([
    'success' => true,
    'recommendations' => $recommendations,
    'type' => $type,
    'count' => count($recommendations)
]);

mysqli_close($con);
?>