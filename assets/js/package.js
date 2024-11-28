// Move packagePrices to global scope
const packagePrices = {
    'pre-primary': {
        regular: 280,
        maintenance: 690,
        intensive: 420,
        superIntensive: 560,
        deposit: 280
    },
    'secondary': {
        regular: 330,
        maintenance: 815,
        intensive: 495,
        superIntensive: 660,
        deposit: 330
    },
    'upper-secondary': {
        regular: 380,
        maintenance: 935,
        intensive: 570,
        superIntensive: 760,
        deposit: 380
    },
    'post-secondary': {
        regular: 480,
        maintenance: 1180,
        intensive: 720,
        superIntensive: 960,
        deposit: 480
    }
};

document.addEventListener('DOMContentLoaded', function() {
    initializePackages();
    const levelButtons = document.querySelectorAll('.level-btn');
    
    levelButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            levelButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
    
            // Update prices based on selected level
            const level = this.dataset.level;
            updatePackagePrices(packagePrices[level]);
            
            // Update package indicator for the new level
            const studentSelect = document.getElementById('student-select');
            if (studentSelect && studentSelect.options.length > 0) {
                const currentPackage = studentSelect.options[studentSelect.selectedIndex].getAttribute('data-package');
                const packageId = studentSelect.options[studentSelect.selectedIndex].getAttribute('data-package-id');
                updateCurrentPackageTag(currentPackage, packageId, true);
            }
        });
    });

    const studentSelect = document.getElementById('student-select');
    const currentPackageDisplay = document.getElementById('current-package-name');
    
    // Initialize with first student's package
    if (studentSelect && studentSelect.options.length > 0) {
        const currentPackage = studentSelect.options[0].getAttribute('data-package');
        const packageId = studentSelect.options[0].getAttribute('data-package-id');
        currentPackageDisplay.textContent = currentPackage || 'No Package';
        updateCurrentPackageTag(currentPackage, packageId, false);
    }
    
    // Update package display when student selection changes
    studentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const currentPackage = selectedOption.getAttribute('data-package');
        const packageId = selectedOption.getAttribute('data-package-id');
        currentPackageDisplay.textContent = currentPackage || 'No Package';
        updateCurrentPackageTag(currentPackage, packageId, false);
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
    const studentSelect = document.getElementById('student-select');
    const selectedLevel = document.querySelector('.level-btn.active').dataset.level;
    
    if (!studentSelect.value) {
        alert('Please select a student first');
        return;
    }

    // Define hours per package type
    const packageHours = {
        'Regular Program': {
            monthly: 8,
            quarterly: 24,
            halfYearly: 48
        },
        'Maintenance Program': {
            quarterly: 18
        },
        'Intensive Program': {
            monthly: 12,
            quarterly: 36
        },
        'Super Intensive Program': {
            monthly: 16,
            quarterly: 48
        }
    };

    const hours = packageHours[packageType];

    // Create and show the modal
    const modal = document.createElement('div');
    modal.className = 'package-modal';
    modal.innerHTML = `
        <div class="package-modal-content">
            <span class="close-modal">&times;</span>
            <h2>Select Payment Option for ${packageType}</h2>
            <div class="payment-options">
                ${packageType !== 'Maintenance Program' ? `
                    <div class="payment-option" data-type="monthly">
                        <h3>Monthly Payment</h3>
                        <p class="price"></p>
                        <p>${hours.monthly} hours of tutoring per month</p>
                    </div>
                ` : ''}
                <div class="payment-option" data-type="quarterly">
                    <h3>Quarterly Payment</h3>
                    <p class="price"></p>
                    <p>${hours.quarterly} hours of tutoring (3 months)</p>
                </div>
                ${packageType === 'Regular Program' ? `
                    <div class="payment-option" data-type="halfYearly">
                        <h3>Half-Yearly Payment</h3>
                        <p class="price"></p>
                        <p>${hours.halfYearly} hours of tutoring (6 months)</p>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Add event listeners
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.onclick = () => {
        modal.classList.add('closing');
        setTimeout(() => {
            modal.remove();
        }, 300);
    };

    const paymentOptions = modal.querySelectorAll('.payment-option');
    paymentOptions.forEach(option => {
        option.onclick = () => {
            modal.classList.add('closing');
            setTimeout(() => {
                submitPackageSelection(packageType, selectedLevel, option.dataset.type, studentSelect.value);
                modal.remove();
            }, 300);
        };
    });

    // Update prices based on selected level and package type
    updateModalPrices(modal, selectedLevel, packageType);
}

function updateModalPrices(modal, level, packageType) {
    const programTypes = {
        'Regular Program': 'regular',
        'Maintenance Program': 'maintenance',
        'Intensive Program': 'intensive',
        'Super Intensive Program': 'superIntensive'
    };

    const programKey = programTypes[packageType];
    const prices = packagePrices[level];

    if (packageType === 'Maintenance Program') {
        // Use maintenance price directly for quarterly payment
        modal.querySelector('[data-type="quarterly"] .price').textContent = `RM ${prices.maintenance}`;
    } else {
        const monthlyPrice = prices[programKey];
        const quarterlyPrice = getQuarterlyPriceForLevel(monthlyPrice, level, programKey);
        const halfYearlyPrice = getHalfYearlyPriceForLevel(monthlyPrice, level);

        if (modal.querySelector('[data-type="monthly"]')) {
            modal.querySelector('[data-type="monthly"] .price').textContent = `RM ${monthlyPrice}`;
        }
        modal.querySelector('[data-type="quarterly"] .price').textContent = `RM ${quarterlyPrice}`;
        
        if (packageType === 'Regular Program') {
            modal.querySelector('[data-type="halfYearly"] .price').textContent = `RM ${halfYearlyPrice}`;
        }
    }
}

function getQuarterlyPrice(monthlyPrice) {
    return Math.round(monthlyPrice * 2.85); // Approximate quarterly discount
}

function getHalfYearlyPrice(monthlyPrice) {
    return Math.round(monthlyPrice * 5.57); // Approximate half-yearly discount
}

function submitPackageSelection(packageType, level, paymentType, studentId) {
    const levelMap = {
        'pre-primary': 'Pre-Primary and Primary Level',
        'secondary': 'Secondary Level',
        'upper-secondary': 'Upper Secondary Level',
        'post-secondary': 'Post-Secondary Level'
    };

    let packageName;
    if (paymentType === 'monthly') {
        packageName = `${levelMap[level]} ${packageType} (Monthly)`;
    } else if (paymentType === 'quarterly') {
        packageName = `${levelMap[level]} ${packageType} (Quarterly)`;
    } else if (paymentType === 'halfYearly') {
        packageName = `${levelMap[level]} ${packageType} (Half-Yearly)`;
    }

    console.log('Submitting package:', packageName);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/Mathology-Attendance-System/parent/includes/update-student-package.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Package updated successfully! Please proceed to make payment.');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    console.error('Server response:', response);
                }
            } catch (e) {
                console.error('Error:', e);
                console.error('Server response:', xhr.responseText);
                alert('Error processing response');
            }
        }
    };

    xhr.send(`student_id=${studentId}&package_name=${encodeURIComponent(packageName)}`);
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
    const level = getCurrentLevel();
    
    // Regular Program Card
    const regularCard = document.querySelector('#regular-program-card');
    regularCard.querySelector('.amount').textContent = prices.regular;
    regularCard.querySelector('.package-features').innerHTML = `
        <li><i class="material-icons">schedule</i> 2 visits per week (1 hour each)</li>
        <li><i class="material-icons">schedule</i> OR 1 visit per week (2 hours each)</li>
        <li><i class="material-icons">check_circle</i> Quarterly Package (24 hours): RM ${getQuarterlyPriceForLevel(prices.regular, level)}</li>
        <li><i class="material-icons">check_circle</i> Half-Yearly Package (48 hours): RM ${getHalfYearlyPriceForLevel(prices.regular, level)}</li>
    `;

    // Maintenance Program Card
    const maintenanceCard = document.querySelector('#maintenance-program-card');
    maintenanceCard.querySelector('.amount').textContent = prices.maintenance;
    maintenanceCard.querySelector('.package-features').innerHTML = `
        <li><i class="material-icons">schedule</i> 1 visit per week (1.5 hours each)</li>
        <li><i class="material-icons">check_circle</i> Quarterly Payment Only</li>
        <li><i class="material-icons">check_circle</i> Flexible Schedule</li>
        <li><i class="material-icons">check_circle</i> Personalized Learning Path</li>
        <li><i class="material-icons">check_circle</i> Progress Monitoring</li>
    `;

    // Intensive Program Card
    const intensiveCard = document.querySelector('#intensive-program-card');
    intensiveCard.querySelector('.amount').textContent = prices.intensive;
    intensiveCard.querySelector('.package-features').innerHTML = `
        <li><i class="material-icons">schedule</i> 2 visits per week (1.5 hours each)</li>
        <li><i class="material-icons">check_circle</i> Quarterly Package (36 hours): RM ${getQuarterlyPriceForLevel(prices.intensive, level, 'intensive')}</li>
        <li><i class="material-icons">check_circle</i> Enhanced Learning Support</li>
        <li><i class="material-icons">check_circle</i> Detailed Progress Tracking</li>
        <li><i class="material-icons">check_circle</i> Priority Scheduling</li>
    `;

    // Super Intensive Program Card
    const superIntensiveCard = document.querySelector('#super-intensive-program-card');
    superIntensiveCard.querySelector('.amount').textContent = prices.superIntensive;
    superIntensiveCard.querySelector('.package-features').innerHTML = `
        <li><i class="material-icons">schedule</i> 2 visits per week (2 hours each)</li>
        <li><i class="material-icons">check_circle</i> Quarterly Package (48 hours): RM ${getQuarterlyPriceForLevel(prices.superIntensive, level, 'superIntensive')}</li>
        <li><i class="material-icons">check_circle</i> Program suitability depends on diagnostic assessment</li>
        <li><i class="material-icons">check_circle</i> Premium Learning Support</li>
        <li><i class="material-icons">check_circle</i> Advanced Progress Analytics</li>
    `;

    // Update deposit amount
    document.querySelector('.fee-item:last-child .fee-amount').textContent = `RM ${prices.deposit}`;
}

function getCurrentLevel() {
    return document.querySelector('.level-btn.active').dataset.level;
}

function getQuarterlyPriceForLevel(monthlyPrice, level, program = 'regular') {
    const quarterlyPrices = {
        'pre-primary': {
            regular: 800,
            intensive: 1200,
            superIntensive: 1600
        },
        'secondary': {
            regular: 945,
            intensive: 1415,
            superIntensive: 1885
        },
        'upper-secondary': {
            regular: 1085,
            intensive: 1625,
            superIntensive: 2170
        },
        'post-secondary': {
            regular: 1370,
            intensive: 2055,
            superIntensive: 2740
        }
    };
    return quarterlyPrices[level][program];
}

function getHalfYearlyPriceForLevel(monthlyPrice, level) {
    const halfYearlyPrices = {
        'pre-primary': 1560,
        'secondary': 1850,
        'upper-secondary': 2130,
        'post-secondary': 2700
    };
    return halfYearlyPrices[level];
}

function updateCurrentPackageTag(packageName, packageId, isAutoSwitch = false) {
    console.log('Updating package tag:', { packageName, packageId, isAutoSwitch });
    
    // Hide all current package tags first
    document.querySelectorAll('.current-package-tag').forEach(tag => {
        tag.style.display = 'none';
    });
    
    // Remove featured class from all cards
    document.querySelectorAll('.package-card').forEach(card => {
        card.classList.remove('featured');
    });

    if (!packageId) {
        console.log('No package ID provided');
        return;
    }

    // Convert packageId to number
    packageId = parseInt(packageId);

    // Get the current selected level
    const currentLevel = document.querySelector('.level-btn.active').dataset.level;
    console.log('Current level:', currentLevel);

    // Get package details from database
    const packageDetails = {
        // Pre-Primary and Primary Level
        1: { level: 'pre-primary', type: 'regular' },
        2: { level: 'pre-primary', type: 'regular' },
        3: { level: 'pre-primary', type: 'regular' },
        4: { level: 'pre-primary', type: 'maintenance' },
        5: { level: 'pre-primary', type: 'intensive' },
        6: { level: 'pre-primary', type: 'intensive' },
        7: { level: 'pre-primary', type: 'superIntensive' },
        8: { level: 'pre-primary', type: 'superIntensive' },
        // Secondary Level
        9: { level: 'secondary', type: 'regular' },
        10: { level: 'secondary', type: 'regular' },
        11: { level: 'secondary', type: 'regular' },
        12: { level: 'secondary', type: 'maintenance' },
        13: { level: 'secondary', type: 'intensive' },
        14: { level: 'secondary', type: 'intensive' },
        15: { level: 'secondary', type: 'superIntensive' },
        16: { level: 'secondary', type: 'superIntensive' },
        // Upper Secondary Level
        17: { level: 'upper-secondary', type: 'regular' },
        18: { level: 'upper-secondary', type: 'regular' },
        19: { level: 'upper-secondary', type: 'regular' },
        20: { level: 'upper-secondary', type: 'maintenance' },
        21: { level: 'upper-secondary', type: 'intensive' },
        22: { level: 'upper-secondary', type: 'intensive' },
        23: { level: 'upper-secondary', type: 'superIntensive' },
        24: { level: 'upper-secondary', type: 'superIntensive' },
        // Post Secondary Level
        25: { level: 'post-secondary', type: 'regular' },
        26: { level: 'post-secondary', type: 'regular' },
        27: { level: 'post-secondary', type: 'regular' },
        28: { level: 'post-secondary', type: 'maintenance' },
        29: { level: 'post-secondary', type: 'intensive' },
        30: { level: 'post-secondary', type: 'intensive' },
        31: { level: 'post-secondary', type: 'superIntensive' },
        32: { level: 'post-secondary', type: 'superIntensive' }
    };

    // Find the matching package details
    const packageDetail = packageDetails[packageId];
    console.log('Package detail found:', packageDetail);

    if (!packageDetail) {
        console.log('No package detail found for ID:', packageId);
        return;
    }

    const { level: packageLevel, type: packageType } = packageDetail;
    console.log('Package level and type:', { packageLevel, packageType });

    // Check if the package belongs to the current selected level
    if (packageLevel !== currentLevel) {
        console.log('Level mismatch:', { packageLevel, currentLevel });
        if (!isAutoSwitch) {
            // If levels don't match and this isn't an auto-switch, 
            // find and click the correct level button
            const correctLevelButton = document.querySelector(`.level-btn[data-level="${packageLevel}"]`);
            if (correctLevelButton) {
                correctLevelButton.click();
            }
        }
        return;
    }

    const programTypes = {
        'regular': '#regular-program-card',
        'maintenance': '#maintenance-program-card',
        'intensive': '#intensive-program-card',
        'superIntensive': '#super-intensive-program-card'
    };

    const cardId = programTypes[packageType];
    console.log('Card ID:', cardId);
    
    const card = document.querySelector(cardId);
    if (card) {
        card.classList.add('featured');
        card.querySelector('.current-package-tag').style.display = 'block';
        console.log('Updated card:', cardId);
    } else {
        console.log('Card not found:', cardId);
    }
}

// Call this on initial load
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student-select');
    if (studentSelect && studentSelect.options.length > 0) {
        const currentPackage = studentSelect.options[0].getAttribute('data-package');
        const packageId = studentSelect.options[0].getAttribute('data-package-id');
        updateCurrentPackageTag(currentPackage, packageId, false);
    }
}); 