</div>
<!-- Js Bootstrap-->
<script src="<?= APP_URL ?>/admin/assets/js/app.js"></script>
<script src="<?= APP_URL ?>/admin/assets/js/feathericons.js"></script>
<?php
if (isset($theme_scripts)) {
  foreach ($theme_scripts as $script) {
    echo "<script src=\"" . APP_URL . "/admin/assets/" . $script . "\"></script>";
  }
} else {
  echo "<!-- No scripsts -->";
}
?>
</body>

</html>