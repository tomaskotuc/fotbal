<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<script>
    tinymce.init({
        selector: '#mytextarea',
        license_key: 'gpl',
        plugins: "code",
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
        promotion: false
    });
</script>

<div class="container my-4">
    <h1>Upravit článek</h1>

    <form method="post" action="<?= base_url('update/' . $article->id) ?>" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Titulek</label>
            <input type="text" name="title" class="form-control" value="<?= esc($article->title) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Odkaz (link)</label>
            <div class="input-group">
                <span class="input-group-text">article/<?= $article->id ?>- </span>
                <input type="text" name="link" class="form-control"
                    value="<?= explode("-", str_replace('article/', '', $article->link))[1] ?>">
            </div>
        </div>


        <!-- Obrázek s emoji tlačítkem a náhledem -->
        <div class="mb-3">
            <label class="form-label">Obrázek</label>
            <div class="input-group">
                <input type="text" name="photo" class="form-control" id="photoInput" value="<?= esc($article->photo ?? '') ?>" readonly>
                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center"
                    type="button" id="browseBtn" style="font-size: 1.5rem; line-height:1;">
                    📂
                </button>
            </div>
            <input type="file" id="filePicker" accept="image/*" style="display:none">
        </div>
 
        <!-- náhled obrázku -->
        <div class="mb-3">
            <img id="photoPreview"
                src="<?= isset($article) && $article->photo ? base_url('obrazky/sigma/' . $article->photo) : '' ?>"
                alt="Náhled obrázku"
                style="max-width: 300px; display: <?= isset($article) && $article->photo ? 'block' : 'none' ?>;">
        </div>
        
        <div class="mb-3">
            <label class="form-label"></label>
            <textarea name="text" id="mytextarea" class="form-control" rows="6"><?= esc($article->text) ?></textarea>
        </div>
        <!-- Datum -->
        <div class="mb-3">
            <label class="form-label">Datum</label>
            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d', $article->date) ?>" required>
        </div>
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="top" value="no">
            <input class="form-check-input" type="checkbox" name="top" id="topSwitch" value="1"
                <?= $article->top ? 'checked' : '' ?>>
            <label class="form-check-label" for="topSwitch">Top článek</label>
        </div>

        <div class="form-check form-switch mb-3">
            <input type="hidden" name="published" value="no">
            <input class="form-check-input" type="checkbox" name="published" id="publishedSwitch" value="1"
                <?= $article->published ? 'checked' : '' ?>>
            <label class="form-check-label" for="publishedSwitch">Publikováno</label>
        </div>

        <button type="submit" class="btn btn-primary">💾 Uložit</button>
        <a href="<?= base_url('administrace') ?>" class="btn btn-secondary">⬅ Zpět</a>
    </form>
</div>

<script>
    const browseBtn = document.getElementById('browseBtn');
    const filePicker = document.getElementById('filePicker');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
 
    // otevření file pickeru po kliknutí na tlačítko
    browseBtn.addEventListener('click', () => filePicker.click());
 
    // aktualizace inputu a náhledu po výběru souboru
    filePicker.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            photoInput.value = file.name; // zobrazí název souboru v readonly inputu
            photoPreview.src = URL.createObjectURL(file); // zobrazí náhled
            photoPreview.style.display = 'block';
        }
    });
</script>

<?= $this->endSection(); ?>