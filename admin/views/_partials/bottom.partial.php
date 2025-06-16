</div>
</main>

<!-- Js Bootstrap-->
<script src="<?= $url_static->js("feathericons.js") ?>"></script>
<script src="<?= $url_static->js("toastifyjs.js") ?>"></script>
<script src="<?= $url_static->js("piruadmin.js") ?>"></script>

<!-- Block Script -->
<?php $theme->block("script"); ?>

<!-- Mostrar las notificaciones Toastify -->
<?= $messageHandler->displayToasts(); ?>
</body>

</html>