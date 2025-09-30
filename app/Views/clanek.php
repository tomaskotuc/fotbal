<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container my-4">
    <h1><?= $article->title?></h1>
    <img src="<?= base_url("obrazky/sigma/".$article->photo)?>" alt="">
    <?= $article->text?>
</div>
<?= $this->endSection(); ?>