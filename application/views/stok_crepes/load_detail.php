<div class="row mt-4">
    <div class="col-lg-12">
        <table id="tb_detail_stok" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($produk as $k) :
                ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><a href="<?= base_url(); ?>match/detail_invoice?invoice=<?= $k->invoice; ?>"><?= $k->invoice; ?></a> </td>
                        <td><?= ucwords(strtolower($k->nm_servis)); ?></td>
                        <td align="right"><?= $k->ttl; ?></td>
                    </tr>
                <?php $i++;
                endforeach ?>
            </tbody>
        </table>
    </div>
</div>