<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container my-4">
    <h1>Soutěže v sezóně</h1>

    <?php if (!empty($souteze)): ?>
        <ul class="list-group">
            <?php foreach ($souteze as $s): ?>
                <li class="list-group-item"><?= esc($s->name) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>V této sezóně nejsou evidovány žádné soutěže.</p>
    <?php endif; ?>

    <a href="<?= site_url('main') ?>" class="btn btn-secondary mt-3">Zpět</a>
</div>

<?= $this->endSection(); ?>