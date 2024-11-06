<!-- tmp -->
<?php
session_start();

// Set this variable to true for the index page
$is_index = true;

// Check if the user is already logged in, if yes then redirect to appropriate dashboard
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] === "parent") {
            header("location: parent_dashboard.php");
            exit;
        } elseif ($_SESSION["role"] === "staff") {
            header("location: staff_dashboard.php");
            exit;
        }
    } else {
        // Handle the case where the role is not set
        // You might want to log them out or redirect to a default page
        session_destroy();
        header("location: index.php");
        exit;
    }
}

include_once 'includes/header.php';
?>

<div class="main-content index-main-content">
    <!-- 1. Hero Section (Welcome) - Stays first as main attraction -->
    <div class="welcome-container">
        <div class="hero-content">
            <h1>Welcome to Mathology</h1>
            <p>Transform your mathematical journey with our innovative learning platform. Interactive lessons, real-time progress tracking, and personalized support to help every student excel.</p>
            <div class="hero-buttons">
                <a href="#login-section" class="hero-button primary">Get Started</a>
                <a href="#features" class="hero-button secondary">Learn More</a>
            </div>
        </div>
    </div>
    
    <!-- 2. Features Section - Immediately show value proposition -->
    <div id="features" class="features-container section">
        <h2>Our Features</h2>
        <div class="feature-grid">
            <div class="feature-item">
                <i class="fas fa-book-open"></i>
                <h3>Interactive Lessons</h3>
                <p>Engaging content that makes learning math enjoyable and effective for every student.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <h3>Progress Tracking</h3>
                <p>Monitor your child's improvement with detailed analytics and progress reports.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-puzzle-piece"></i>
                <h3>Practice Exercises</h3>
                <p>Reinforce concepts with our diverse set of problems and interactive quizzes.</p>
            </div>
        </div>
    </div>

    <!-- 3. Stats Section - Build credibility with numbers -->
    <div class="stats section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">5000+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">95%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Expert Teachers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">15+</div>
                <div class="stat-label">Years Experience</div>
            </div>
        </div>
    </div>
    
    <!-- 4. Testimonials - Social proof after showing features and stats -->
    <div class="testimonials section">
        <div class="section-header">
            <h2>What Parents Say</h2>
            <p>Hear from our community of satisfied parents and students</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <i class="fas fa-quote-left testimonial-quote"></i>
                <div class="testimonial-content">
                    "My daughter's confidence in mathematics has grown tremendously since joining Mathology. The interactive lessons and supportive teachers have made a real difference in her learning journey."
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/img/testimonial-1.jpg" alt="Parent">
                    </div>
                    <div class="author-info">
                        <h4>Sarah Thompson</h4>
                        <p>Parent of Amy, Grade 8</p>
                        <div class="testimonial-rating">
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-left testimonial-quote"></i>
                <div class="testimonial-content">
                    "The progress tracking feature helps me stay involved in my son's learning. The detailed reports and regular updates give me peace of mind about his mathematical development."
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/img/testimonial-2.jpg" alt="Parent">
                    </div>
                    <div class="author-info">
                        <h4>Michael Chen</h4>
                        <p>Parent of Kevin, Grade 6</p>
                        <div class="testimonial-rating">
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-left testimonial-quote"></i>
                <div class="testimonial-content">
                    "The personalized attention and structured approach at Mathology has helped my son overcome his fear of mathematics. The teachers are excellent and very supportive."
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/img/testimonial-3.jpg" alt="Parent">
                    </div>
                    <div class="author-info">
                        <h4>Emily Rodriguez</h4>
                        <p>Parent of Lucas, Grade 7</p>
                        <div class="testimonial-rating">
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Login Options - Call to action after building trust -->
    <div id="login-section" class="login-options section">
        <div class="login-options-content">
            <h2>Begin Your Journey</h2>
            <p>Experience the future of mathematics education with our premium learning platform. Join our community of successful learners today.</p>
            <div class="login-buttons-container">
                <a href="parent/parent-login.php" class="login-button">
                    <i class="fas fa-user-friends"></i>
                    Parent Portal
                </a>
                <a href="staff/staff-login.php" class="login-button">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Staff Portal
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add smooth scroll behavior -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
