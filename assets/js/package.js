document.addEventListener('DOMContentLoaded', function() {
    initializePackages();
    const levelButtons = document.querySelectorAll('.level-btn');
    const packagePrices = {
        'pre-primary': {
            regular: 280,
            maintenance: 280,
            intensive: 420,
            superIntensive: 560,
            deposit: 280
        },
        'secondary': {
            regular: 350,
            maintenance: 350,
            intensive: 540,
            superIntensive: 720,
            deposit: 350
        },
        'upper-secondary': {
            regular: 580,
            maintenance: 580,
            intensive: 570,
            superIntensive: 760,
            deposit: 580
        },
        'post-secondary': {
            regular: 480,
            maintenance: 480,
            intensive: 720,
            superIntensive: 960,
            deposit: 480
        }
    };

    levelButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            levelButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Update prices based on selected level
            const level = this.dataset.level;
            updatePackagePrices(packagePrices[level]);
        });
    });
});

function initializePackages() {
    const packageButtons = document.querySelectorAll('.package-btn');
    
    packageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const packageType = this.closest('.package-card').querySelector('.package-badge').textContent;
            handlePackageSelection(packageType);
        });
    });
}

function handlePackageSelection(packageType) {
    // Show confirmation modal
    const confirmed = confirm(`Would you like to proceed with the ${packageType} package?`);
    
    if (confirmed) {
        // You can implement the actual package selection logic here
        // For example, making an API call or redirecting to a payment page
        console.log(`Selected package: ${packageType}`);
        
        // Temporary alert for demonstration
        alert('Thank you for your interest! Our team will contact you shortly to complete the registration process.');
    }
}

// Add smooth scroll for guarantee section
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Add animation on scroll
function animatePackageCards() {
    const cards = document.querySelectorAll('.package-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = entry.target.classList.contains('featured') 
                    ? 'scale(1.05)' 
                    : 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    animatePackageCards();
});

function updatePackagePrices(prices) {
    // Update regular program price
    document.querySelector('.package-card:nth-child(1) .amount').textContent = prices.regular;
    
    // Update maintenance program price
    document.querySelector('.package-card:nth-child(2) .amount').textContent = prices.maintenance;
    
    // Update intensive program price
    document.querySelector('.package-card:nth-child(3) .amount').textContent = prices.intensive;
    
    // Update super intensive program price
    document.querySelector('.package-card:nth-child(4) .amount').textContent = prices.superIntensive;
    
    // Update deposit amount
    document.querySelector('.fee-item:last-child .fee-amount').textContent = `RM${prices.deposit}`;
} 