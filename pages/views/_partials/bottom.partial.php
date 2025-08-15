<script src="<?= $static_url->assets("js", "piruui.js") ?>"></script>
<script src="<?= $static_url->assets("js", "prism.js") ?>"></script>
<script src="<?= $static_url->assets("js", "extra.js") ?>"></script>
<script src="<?= $static_url->assets("js", "toastifyjs.js") ?>"></script>

<?php $theme->block("script"); ?>

<?= $messageHandler->displayToasts(); ?>
</body>

</html>