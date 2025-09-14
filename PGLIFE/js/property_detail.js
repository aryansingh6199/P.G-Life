window.addEventListener("load", function () {
  const search = window.location.search;
  const params = new URLSearchParams(search);
  const property_id = params.get('property_id');

  var is_interested_image = document.getElementsByClassName("is-interested-image")[0];
  is_interested_image.addEventListener("click", function (event) {
    var XHR = new XMLHttpRequest();

    // On success
    XHR.addEventListener("load", toggle_interested_success);

    // On error
    XHR.addEventListener("error", on_error);

    // Set up request
    XHR.open("GET", "api/toggle_interested.php?property_id=" + property_id);

    // Initiate the request
    XHR.send();

    document.getElementById("loading").style.display = 'block';
    event.preventDefault();
  });

  // Payment simulation functionality
  var confirmPaymentBtn = document.getElementById("confirm-payment");
  if (confirmPaymentBtn) {
    confirmPaymentBtn.addEventListener("click", function(event) {
      processPayment();
      event.preventDefault();
    });
  }
  
  // Load similar properties
  if (property_id) {
    loadSimilarProperties(property_id);
  }
});

var toggle_interested_success = function (event) {
  document.getElementById("loading").style.display = 'none';

  var response = JSON.parse(event.target.responseText);
  if (response.success) {
    var is_interested_image = document.getElementsByClassName("is-interested-image")[0];
    var interested_user_count = document.getElementsByClassName("interested-user-count")[0];

    if (response.is_interested) {
        is_interested_image.classList.add("fas");
        is_interested_image.classList.remove("far");
        interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) + 1;
    }
    else {
        is_interested_image.classList.add("far");
        is_interested_image.classList.remove("fas");
        interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) - 1;
    }
  } else if (!response.success && !response.is_logged_in) {
    //if user tries to mark a property interested without logging in, this code runs
    window.$("#login-modal").modal("show");
  }
};

var on_error = function (event) {
    document.getElementById("loading").style.display = 'none';
    // alert('Oops! Something went wrong! (on_error)');
    alert('Connection to server could not be established!');
};

// Payment simulation functions
var processPayment = function() {
    // Validate form
    var form = document.getElementById('payment-details-form');
    var name = document.getElementById('customer-name').value.trim();
    var phone = document.getElementById('customer-phone').value.trim();
    var email = document.getElementById('customer-email').value.trim();
    var paymentMethod = document.getElementById('payment-method').value;
    
    if (!name || !phone || !email || !paymentMethod) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    
    // Phone validation (basic)
    var phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    if (!phoneRegex.test(phone)) {
        alert('Please enter a valid phone number.');
        return;
    }
    
    // Show processing state
    document.getElementById('payment-form').style.display = 'none';
    document.getElementById('payment-processing').style.display = 'block';
    document.getElementById('payment-buttons').style.display = 'none';
    
    // Simulate payment processing (2-3 seconds)
    setTimeout(function() {
        // Generate booking reference
        var bookingRef = 'PG' + Date.now().toString().slice(-8) + Math.floor(Math.random() * 100);
        document.getElementById('booking-reference').textContent = bookingRef;
        
        // Show success state
        document.getElementById('payment-processing').style.display = 'none';
        document.getElementById('payment-success').style.display = 'block';
        document.getElementById('success-buttons').style.display = 'block';
        
        // Store booking info in localStorage for demo purposes
        var bookingInfo = {
            reference: bookingRef,
            customerName: name,
            customerPhone: phone,
            customerEmail: email,
            paymentMethod: paymentMethod,
            bookingDate: new Date().toISOString(),
            propertyId: new URLSearchParams(window.location.search).get('property_id')
        };
        
        localStorage.setItem('lastBooking', JSON.stringify(bookingInfo));
        
        // Optional: Show success animation
        confetti && confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
        
    }, 2500);
};

// Reset modal when closed
window.$ && $('#payment-modal').on('hidden.bs.modal', function () {
    // Reset modal to initial state
    document.getElementById('payment-form').style.display = 'block';
    document.getElementById('payment-processing').style.display = 'none';
    document.getElementById('payment-success').style.display = 'none';
    document.getElementById('payment-buttons').style.display = 'block';
    document.getElementById('success-buttons').style.display = 'none';
    
    // Clear form
    document.getElementById('payment-details-form').reset();
});

// Load similar properties function
function loadSimilarProperties(propertyId) {
    const grid = document.getElementById('similar-properties-grid');
    if (!grid) return;
    
    fetch(`api/get_recommendations.php?type=similar&property_id=${propertyId}&limit=4`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.recommendations.length > 0) {
                displaySimilarProperties(data.recommendations);
            } else {
                grid.innerHTML = `
                    <div class="no-similar text-center">
                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No similar properties found at the moment.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading similar properties:', error);
            grid.innerHTML = `
                <div class="error-similar text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">Unable to load similar properties.</p>
                </div>
            `;
        });
}

function displaySimilarProperties(properties) {
    const grid = document.getElementById('similar-properties-grid');
    
    const cardsHtml = properties.map(pg => {
        const overallRating = ((pg.rating_clean + pg.rating_food + pg.rating_safety) / 3).toFixed(1);
        const stars = generateStarsForSimilar(overallRating);
        const genderIcon = getSimilarGenderIcon(pg.gender);
        
        return `
            <div class="similar-card">
                <div class="similar-card-body">
                    <h5 class="similar-title">${pg.name}</h5>
                    <p class="similar-location">📍 ${pg.city_name}</p>
                    <div class="similar-rating mb-2">
                        <span class="stars">${stars}</span>
                        <span class="rating-text">${overallRating}</span>
                    </div>
                    <div class="similar-price mb-2">
                        <strong class="text-success">₹${parseInt(pg.rent).toLocaleString()}/month</strong>
                    </div>
                    <div class="similar-features mb-3">
                        <span class="feature-badge">${genderIcon} ${capitalizeFirstSimilar(pg.gender)}</span>
                        <span class="feature-badge">🏠 ${pg.amenities_count || 0} amenities</span>
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100" onclick="viewProperty(${pg.id})">
                        View Details
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    grid.innerHTML = `<div class="similar-properties-row">${cardsHtml}</div>`;
    
    // Add animation
    setTimeout(() => {
        grid.querySelectorAll('.similar-card').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 150);
        });
    }, 100);
}

function generateStarsForSimilar(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star text-warning"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star text-warning"></i>';
    }
    
    return stars;
}

function getSimilarGenderIcon(gender) {
    switch (gender) {
        case 'male': return '👨';
        case 'female': return '👩';
        default: return '👥';
    }
}

function capitalizeFirstSimilar(str) {
    if (str === 'other') return 'Unisex';
    return str.charAt(0).toUpperCase() + str.slice(1);
}
