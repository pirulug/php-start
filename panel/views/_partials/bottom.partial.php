</div>
</main>

<!-- Js Bootstrap-->
<script src="<?= $static_url->assets("js", "feathericons.js") ?>"></script>
<script src="<?= $static_url->assets("js", "toastifyjs.js") ?>"></script>
<script src="<?= $static_url->assets("js", "piruadmin.js") ?>"></script>

<!-- Block Script -->
<?php $theme->block("script"); ?>

<!-- Mostrar las notificaciones Toastify -->
<?= $messageHandler->displayToasts(); ?>
</body>

</html>