<?php if (empty($cart_content)) :  ?>
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">Keranjang Belanja</h3>
            <hr>
            <center><br><br>
                <img width="100" src="<?= base_url("upload/icon/cart.png")  ?>" alt=""><br><br>
                <h5>keranjang belanja kosong!</h5>
            </center><br>

        </div>
    </div>
<?php else : ?>
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">Produk</h3>
            <hr>
            <?php
            $subtotal_produk = 0;
            $jumlah = 0;
            $total_toping = 0;
            foreach ($cart_content as $key => $value) :
                $allCartItems = $this->cart->contents();

                // Filter item yang memiliki properti 'ibu' sesuai dengan 'id_produk' saat ini
                $productItems = array_filter($allCartItems, function ($item) use ($value) {
                    return isset($item['ibu']) && $item['ibu'] == $value['id_produk'];
                });
                $toping = $productItems;
            ?>

                <div class="row">
                    <?php
                    $subtotal_produk += ($value['price'] * $value['qty']) - $value['diskon'];
                    $jumlah += $value['qty'];
                    ?>
                    <div class="col-6">
                        <!-- <?php foreach ($value['nm_karyawan'] as $key => $nm_karyawan) : ?>
                                <span class="badge badge-secondary"><?= $nm_karyawan ?></span>
                            <?php endforeach ?> -->
                        <p><?= $value['name'] ?></p>
                    </div>
                    <div class="col-1">
                        <p><?= $value['qty'] ?></p>
                    </div>
                    <div class="col-4">
                        <?php if (empty($value['diskon'])) : ?>
                            <strong class="float-right"> Rp. <?= number_format($value['qty'] * $value['price'], 0) ?></strong>
                        <?php else : ?>
                            <strong class="float-right"><s> Rp. <?= number_format($value['qty'] * $value['price'], 0) ?></s></strong>
                            <strong class="float-right"> Rp. <?= number_format(($value['qty'] * $value['price']) - $value['diskon'], 0) ?></strong>
                        <?php endif ?>

                    </div>
                    <div class="col-1">
                        <a class="delete_cart mr-2" id="<?= $value['rowid'] ?>" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <?php

                foreach ($toping as $key => $t) :

                ?>
                    <div class="row">

                        <div class="col-6">
                            <p class="ml-4 text-sm"><?= $t['name'] ?></p>
                        </div>
                        <div class="col-1">
                            <p class=" text-sm"><?= $t['qty'] ?></p>
                        </div>
                        <div class="col-4">
                            <p class=" text-sm float-right">+ Rp. <?= number_format($t['qty'] * $t['price'], 0) ?></p>
                        </div>
                        <div class="col-1">
                            <?php if ($t['kategori_produk'] == '29') : ?>
                            <?php else : ?>
                                <a class=" text-sm delete_cart mr-2" id="<?= $t['rowid'] ?>" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-times"></i></a>
                            <?php endif ?>

                        </div>
                    </div>
                <?php $total_toping += $t['qty'] * $t['price'];
                endforeach ?>
            <?php

            endforeach ?>
            <div class="container">
                <hr>
                <strong style="font-size: 20px;">Total</strong> <strong style="float: right; font-size: 22px;">Rp. <?= number_format($subtotal_produk + $total_toping) ?></strong>
                <hr>
                <a type="button" data-toggle="modal" data-target="#myModalp" class="btn btn-primary btn-block" style="background-image: linear-gradient(to right, #FFF192 0%, #FFF192 19%, #FFEA61 60%, #FFDD3C 100%); border-color: #F7889D; font-weight: bold; color: #EF0097;">LANJUTKAN KE PEMBAYARAN</a>
            </div>
        </div>
    </div>

<?php endif ?>