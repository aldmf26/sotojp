<div class="row mt-4">
    <div class="col-lg-12">
        <table id="tb_servis2" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Nama Produk</th>
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
                   
                    </tr>
                <?php $i++;
                endforeach ?>
            </tbody>
        </table>
    </div>
</div>