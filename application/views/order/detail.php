<div class="row">
    <div class="col-sm-4 col-md-4">

        <?php if (empty($produk->foto)) : ?>
            <img class="img-thumbnail" width="270" src="<?= base_url() ?>upload/produk/default.png" alt="">
        <?php else : ?>
            <img class="img-thumbnail" width="270" src="<?= base_url() ?>upload/produk/<?= $produk->foto ?>" alt="">
        <?php endif ?>

    </div>
    <div class="col-sm-8 col-md-8">
        <h6 class="mt-2"><?= $produk->nm_servis ?></h6>
        <h6 style="font-weight: bold; color: #FA778E; font-size: 20px;">Rp. <?= number_format($produk->biaya) ?></h6>
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="">Jumlah *</label>
                    <input type="number" name="jumlah" id="cart_jumlah" class="form-control" value="1" required="">
                    <input id="cart_sku" type="hidden" name="sku" value="<?= $produk->id_servis ?>">
                    <input id="cart_id_produk" type="hidden" name="id_produk" value="<?= $produk->id_servis ?>">
                </div>
            </div>
        </div>
    </div>
</div>