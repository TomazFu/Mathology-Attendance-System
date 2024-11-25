<?php
// Set the base path if not already set
if (!isset($base_path)) {
    $base_path = isset($is_index) && $is_index ? '' : '../';
}
?>
<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section footer-left">
            <h3>About Us</h3>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum, qui asperiores. Aliquam quasi aperiam, mollitia repellat, suscipit autem exercitationem aut excepturi, fugiat sunt nihil nemo cumque possimus delectus placeat eveniet.</p>
        </div>
        <div class="footer-section footer-right">
            <h3>Contact Information</h3>
            <p>Address: 123, Bayan Lepas, Penang</p>
            <p>Phone: (60) 123456789</p>
            <p>Email: Mathology@gmail.com</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Mathology. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo $base_path; ?>assets/js/script.js"></script>
</body>
</html>
