<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

require_once "../config/connect.php";
include "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<link rel="stylesheet" href="../assets/css/parent.css">

<div class="dashboard-layout">
    <?php require_once "../includes/sidebar.php"; ?>
    <?php renderSidebar('parent'); ?>
    
    <div class="main-content">
        <div class="package-container">
            <div class="package-header">
                <h1>Choose Your Learning Journey</h1>
                <p>Select the perfect package that suits your child's educational needs</p>
            </div>

            <!-- Level Selection -->
            <div class="level-selector">
                <button class="level-btn active" data-level="pre-primary">Pre-Primary & Primary</button>
                <button class="level-btn" data-level="secondary">Secondary</button>
                <button class="level-btn" data-level="upper-secondary">Upper Secondary</button>
                <button class="level-btn" data-level="post-secondary">Post Secondary</button>
            </div>

            <!-- Package Grid -->
            <div class="packages-grid">
                <!-- Regular Program -->
                <div class="package-card">
                    <div class="package-badge">Regular Program</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">280</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">8 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (1 hour each)</li>
                        <li><i class="material-icons">schedule</i> OR 1 visit per week (2 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package Available (24 hours): RM800</li>
                        <li><i class="material-icons">check_circle</i> Half-Yearly Package (48 hours): RM1560</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>

                <!-- Maintenance Program -->
                <div class="package-card">
                    <div class="package-badge">Maintenance Program</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">280</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">6 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 1 visit per week (1.5 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package Available (18 hours): RM690</li>
                        <li><i class="material-icons">check_circle</i> Flexible Schedule</li>
                        <li><i class="material-icons">check_circle</i> Personalized Learning Path</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>

                <!-- Intensive Program -->
                <div class="package-card featured">
                    <div class="package-badge">Intensive Program</div>
                    <div class="popular-tag">Most Popular</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">420</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">12 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (1.5 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package Available (36 hours): RM1200</li>
                        <li><i class="material-icons">check_circle</i> Enhanced Learning Support</li>
                        <li><i class="material-icons">check_circle</i> Progress Tracking</li>
                    </ul>
                    <button class="package-btn primary">Get Started</button>
                </div>

                <!-- Super Intensive Program -->
                <div class="package-card">
                    <div class="package-badge">Super Intensive Program</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">560</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">16 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (2 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package Available (48 hours): RM1600</li>
                        <li><i class="material-icons">check_circle</i> Comprehensive Support</li>
                        <li><i class="material-icons">check_circle</i> Advanced Progress Tracking</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>
            </div>

            <!-- Additional Fees Section -->
            <div class="additional-fees">
                <h3>Additional Fees</h3>
                <div class="fees-grid">
                    <div class="fee-item">
                        <span class="fee-label">Registration</span>
                        <span class="fee-amount">RM50</span>
                    </div>
                    <div class="fee-item">
                        <span class="fee-label">Mathology Assessment (Lifetime)</span>
                        <span class="fee-amount">RM100</span>
                    </div>
                    <div class="fee-item">
                        <span class="fee-label">Deposit (1 month)</span>
                        <span class="fee-amount">RM280</span>
                    </div>
                </div>
            </div>

            <div class="package-footer">
                <div class="guarantee">
                    <i class="material-icons">verified</i>
                    <p>30-day satisfaction guarantee • Cancel anytime</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/package.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>

