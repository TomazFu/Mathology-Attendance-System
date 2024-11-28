<?php
// Set the base path if not already set
if (!isset($base_path)) {
    $base_path = isset($is_index) && $is_index ? '' : '../';
}
?>
<footer class="site-footer">
    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Mathology. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo $base_path; ?>assets/js/script.js"></script>
</body>
</html>
