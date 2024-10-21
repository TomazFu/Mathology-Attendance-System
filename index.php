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
    <div class="welcome-container">
        <h1>Welcome to Mathology</h1>
        <p>Mathology is an innovative online platform designed to make learning mathematics fun and engaging for students. Our interactive lessons, practice exercises, and progress tracking tools help students build confidence and excel in math.</p>
        <img src="assets/images/math-illustration.jpg" alt="Math illustration" class="welcome-image">
    </div>
    
    <div class="features-container">
        <h2>Our Features</h2>
        <div class="feature-grid">
            <div class="feature-item">
                <i class="fas fa-book-open"></i>
                <h3>Interactive Lessons</h3>
                <p>Engaging content that makes learning math enjoyable.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <h3>Progress Tracking</h3>
                <p>Monitor your child's improvement over time.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-puzzle-piece"></i>
                <h3>Practice Exercises</h3>
                <p>Reinforce concepts with our diverse set of problems.</p>
            </div>
        </div>
    </div>
    
    <div class="login-options">
        <h2>Choose Your Login Option</h2>
        <a href="parent/parent-login.php" class="login-button">Parent Login</a>
        <a href="#" class="login-button">Staff Login</a>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
