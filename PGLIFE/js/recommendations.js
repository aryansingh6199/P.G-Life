// AI Recommendations JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize recommendations
    loadRecommendations('trending');
    
    // Tab switching
    document.querySelectorAll('#recommendation-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabType = this.getAttribute('href').substring(1);
            loadRecommendations(tabType);
        });
    });
    
    // Budget filter change
    document.getElementById('budget-range')?.addEventListener('change', function() {
        if (document.querySelector('#budget-tab').classList.contains('active')) {
            loadRecommendations('budget');
        }
    });
});

function loadRecommendations(type) {
    const gridId = type + '-grid';
    const grid = document.getElementById(gridId);
    
    if (!grid) return;
    
    // Show loading spinner
    grid.innerHTML = `
        <div class="loading-spinner text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading ${type} recommendations...</p>
        </div>
    `;
    
    // Build API URL
    let apiUrl = `api/get_recommendations.php?type=${type}`;
    
    if (type === 'budget') {
        const budget = document.getElementById('budget-range')?.value || 8000;
        apiUrl += `&max_budget=${budget}`;
    }
    
    // Fetch recommendations
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRecommendations(data.recommendations, gridId, type);
            } else {
                showError(gridId, data.message || 'Failed to load recommendations');
            }
        })
        .catch(error => {
            console.error('Error loading recommendations:', error);
            showError(gridId, 'Network error. Please try again.');
        });
}

function displayRecommendations(recommendations, gridId, type) {
    const grid = document.getElementById(gridId);
    
    if (recommendations.length === 0) {
        grid.innerHTML = `
            <div class="no-recommendations text-center">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5>No recommendations found</h5>
                <p class="text-muted">Try adjusting your preferences or check back later.</p>
            </div>
        `;
        return;
    }
    
    const cardsHtml = recommendations.map(pg => createRecommendationCard(pg, type)).join('');
    grid.innerHTML = cardsHtml;
    
    // Add animation
    setTimeout(() => {
        grid.querySelectorAll('.recommendation-card').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
    }, 100);
}

function createRecommendationCard(pg, type) {
    const overallRating = pg.overall_rating || ((pg.rating_clean + pg.rating_food + pg.rating_safety) / 3).toFixed(1);
    const stars = generateStars(overallRating);
    
    // Get recommendation reason based on type
    let reasonBadge = '';
    switch (type) {
        case 'trending':
            reasonBadge = `<span class="feature-tag">🔥 ${pg.interest_count || 0} interested</span>`;
            break;
        case 'budget':
            reasonBadge = `<span class="feature-tag">💰 Great Value</span>`;
            break;
        case 'personalized':
            reasonBadge = `<span class="feature-tag">✨ Recommended for you</span>`;
            break;
    }
    
    const genderIcon = getGenderIcon(pg.gender);
    
    return `
        <div class="recommendation-card">
            <h5>${pg.name}</h5>
            <p class="location">📍 ${pg.address}</p>
            <p class="price">₹${parseInt(pg.rent).toLocaleString()}/<small>month</small></p>
            
            <div class="rating">
                <div class="stars">${stars}</div>
                <span class="score">${overallRating}</span>
                <small class="text-muted ml-2">(${pg.city_name})</small>
            </div>
            
            <div class="features">
                ${reasonBadge}
                <span class="feature-tag">${genderIcon} ${capitalizeFirst(pg.gender)}</span>
                <span class="feature-tag">🏠 ${pg.amenities_count || 0} amenities</span>
            </div>
            
            <button class="btn view-btn" onclick="viewProperty(${pg.id})">
                View Details
            </button>
        </div>
    `;
}

function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

function getGenderIcon(gender) {
    switch (gender) {
        case 'male': return '👨';
        case 'female': return '👩';
        default: return '👥';
    }
}

function capitalizeFirst(str) {
    if (str === 'other') return 'Unisex';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function viewProperty(propertyId) {
    window.location.href = `property_detail.php?property_id=${propertyId}`;
}

function showError(gridId, message) {
    const grid = document.getElementById(gridId);
    grid.innerHTML = `
        <div class="error-message text-center">
            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h5>Oops! Something went wrong</h5>
            <p class="text-muted">${message}</p>
            <button class="btn btn-primary" onclick="location.reload()">
                <i class="fas fa-refresh"></i> Try Again
            </button>
        </div>
    `;
}

// Initialize recommendations when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Already handled above
    });
} else {
    // DOM is already loaded
    setTimeout(() => {
        if (document.getElementById('trending-grid')) {
            loadRecommendations('trending');
        }
    }, 500);
}