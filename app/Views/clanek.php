<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
 
<div class="container my-4">
 
    <!-- Úvodní fotka s nadpisem -->
    <div class="position-relative mb-4">
        <img src="<?= base_url('obrazky/sigma/' . $article->photo) ?>"
             alt="<?= esc($article->title) ?>"
             class="img-fluid w-100">
        <div class="position-absolute bottom-0 start-0 w-100 p-3"
             style="background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));">
            <h1 class="text-white"><?= esc($article->title) ?></h1>
        </div>
    </div>
 
    <!-- Text článku -->
    <div class="article-text">
        <?= $article->text ?>
    </div>
 
</div>
 
<?= $this->endSection(); ?>
 