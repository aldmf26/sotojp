<div class="row">
    <?php foreach ($kategori as $k) : ?>
        <div class="col-lg-3">
            <a type="button" style="width: 100%;" class="menu_muncul" id_kategori="<?= $k->id_kategori ?>">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mt-2 text-sm text-center font-weight-bold"><?= word_limiter($k->nm_kategori, 2)  ?></h6>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>