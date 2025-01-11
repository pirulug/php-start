<script src="<?= $url->js("piruui.js") ?>"></script>
<script src="<?= $url->js("prism.js") ?>"></script>
<script src="<?= $url->js("extra.js") ?>"></script>
<script src="<?= $url->js("toastifyjs.js") ?>"></script>

<?php $theme->block("script"); ?>

<?= $messageHandler->displayToasts(); ?>
</body>

</html>