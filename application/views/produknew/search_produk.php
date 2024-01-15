<div class="row">
    <?php foreach ($data as $key => $value) : ?>
        <div class="col-lg-3">
            <a type="button" class="klikdetail" id_produk="<?= $value->id_servis ?>" data-toggle="modal" data-target="#myModal" style="width: 100%;height: 100%;">
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($value->foto)) : ?>
                            <img src="" alt="">
                        <?php else : ?>
                            <img class="img-thumbnail" loading=”lazy” width="170" src="<?= base_url() ?>upload/produk/<?= $value->foto  ?> " alt="">
                        <?php endif ?>
                        <h6 class="mt-2 text-sm"><?= word_limiter($value->nm_servis, 4)  ?></h6>
                        <h6 style="font-weight: bold;">Rp. <?= number_format($value->biaya) ?></h6>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach ?>
</div>