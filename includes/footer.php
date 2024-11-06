<?php
// Set the base path if not already set
if (!isset($base_path)) {
    $base_path = isset($is_index) && $is_index ? '' : '../';
}
?>
<footer class="site-footer">
    <div class="footer-background"></div>
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Mathology</h3>
            <p>Empowering students through innovative mathematics education. Our mission is to make learning mathematics engaging, accessible, and effective for every student.</p>
        </div>
        
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="#"><i class="fas fa-chevron-right"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> About Us</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Services</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Courses</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Contact</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Contact Us</h3>
            <div class="footer-contact">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>123, Bayan Lepas, Penang</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>(60) 123456789</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>Mathology@gmail.com</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Mathology. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo $base_path; ?>assets/javascript/script.js"></script>
</body>
</html>
