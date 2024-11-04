</div>
</main>
<!-- Js Bootstrap-->
<script src="<?= SITE_URL ?>/admin/assets/js/feathericons.js"></script>
<script src="<?= SITE_URL ?>/admin/assets/js/toastifyjs.js"></script>
<script src="<?= SITE_URL ?>/admin/assets/js/piruadmin.js"></script>

<!-- Block Script -->
<?php block("script"); ?>

<!-- Mostrar las notificaciones Toastify -->
<?= $messageHandler->displayToasts(); ?>
</body>

</html>