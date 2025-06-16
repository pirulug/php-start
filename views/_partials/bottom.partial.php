<script src="<?= $url_static->js("piruui.js") ?>"></script>
<script src="<?= $url_static->js("prism.js") ?>"></script>
<script src="<?= $url_static->js("extra.js") ?>"></script>
<script src="<?= $url_static->js("toastifyjs.js") ?>"></script>

<?php $theme->block("script"); ?>

<?= $messageHandler->displayToasts(); ?>
</body>

</html>