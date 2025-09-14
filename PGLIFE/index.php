<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Find Best PG Accommodations | PG Life</title>
    <link rel="icon" type="image/png" href="img/logo.png">

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/index.css" rel="stylesheet" />
</head>

<body>
  <!-- Header Section -->
  <?php require "./includes/header.php"; ?>

  <div id="loading">
  </div>

  <div class="container-fluid pg-search-container">
    <div class="row justify-content-center mb-3 text-white">
      <div class="col-auto">
        <h2>&nbsp Happiness per Square Foot</h2>
      </div>
    </div>
    <!-- Search Bar -->
    <form class="" action="property_list.php" method="GET">
      <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
          <div class="input-group md-form form-sm form-2 pl-0">
            <input name="city" class="form-control my-0 py-1 red-border" type="text" placeholder="Enter your city to search for PGs" aria-label="Search">
            <div class="input-group-append">
              <button type="submit" class="btn btn-dark">
                <!-- <span class="input-group-text red lighten-3" id="basic-text1"> -->
                  <i class="fas fa-search text-grey" aria-hidden="true"></i>
                <!-- </span> -->
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="city-container">
    <div class="city-caption">
      <h2>Major Cities</h2>
    </div>
    <div class="city-box">

      <div class="city-img">
        <a href="property_list.php?city=Delhi">
          <img src="./img/delhi.png" alt="Delhi">
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Mumbai">
          <img src="./img/mumbai.png" alt="Mumbai">
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Hyderabad">
          <img src="./img/hyderabad.png" alt="Hyderabad">
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Bengaluru">
          <img src="./img/bangalore.png" alt="Bengaluru">
        </a>
      </div>

    </div>
  </div>

  <!-- AI Recommendations Section -->
  <div class="recommendations-container">
    <div class="container">
      <div class="recommendations-header">
        <h2 class="text-center mb-4">🤖 AI-Powered Recommendations</h2>
        <p class="text-center text-muted mb-5">Discover perfect PGs tailored just for you using our intelligent recommendation system</p>
      </div>
      
      <div class="recommendation-tabs">
        <ul class="nav nav-pills justify-content-center mb-4" id="recommendation-tabs">
          <li class="nav-item">
            <a class="nav-link active" id="trending-tab" data-toggle="pill" href="#trending" role="tab">
              🔥 Trending Now
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="budget-tab" data-toggle="pill" href="#budget" role="tab">
              💰 Budget Friendly
            </a>
          </li>
          <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" id="personalized-tab" data-toggle="pill" href="#personalized" role="tab">
              ✨ For You
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
      
      <div class="tab-content" id="recommendation-content">
        <!-- Trending Recommendations -->
        <div class="tab-pane fade show active" id="trending" role="tabpanel">
          <div class="recommendation-grid" id="trending-grid">
            <div class="loading-spinner text-center">
              <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <p class="mt-2">Finding trending PGs...</p>
            </div>
          </div>
        </div>
        
        <!-- Budget Friendly Recommendations -->
        <div class="tab-pane fade" id="budget" role="tabpanel">
          <div class="budget-filter mb-4">
            <div class="row justify-content-center">
              <div class="col-md-4">
                <label for="budget-range">Maximum Budget:</label>
                <select class="form-control" id="budget-range">
                  <option value="5000">Under ₹5,000</option>
                  <option value="8000" selected>Under ₹8,000</option>
                  <option value="12000">Under ₹12,000</option>
                  <option value="15000">Under ₹15,000</option>
                  <option value="20000">Under ₹20,000</option>
                </select>
              </div>
            </div>
          </div>
          <div class="recommendation-grid" id="budget-grid">
            <div class="loading-spinner text-center">
              <div class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <p class="mt-2">Finding budget-friendly options...</p>
            </div>
          </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Personalized Recommendations -->
        <div class="tab-pane fade" id="personalized" role="tabpanel">
          <div class="recommendation-grid" id="personalized-grid">
            <div class="loading-spinner text-center">
              <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <p class="mt-2">Personalizing recommendations...</p>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Modal Pages -->
  <?php require "./includes/signup_modal.php"; ?>
  <?php require "./includes/login_modal.php" ?>


  <!-- Footer -->
  <?php require "./includes/footer.php" ?>

  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/common.js"></script>
  <script type="text/javascript" src="js/recommendations.js"></script>
</body>

</html>
