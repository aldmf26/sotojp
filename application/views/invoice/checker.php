<style>
    .invoice {
        margin: auto;
        width: 80mm;
        background: #FFF;
    }

    .huruf {
        font-size: 20px;
    }
</style>
<script>
    window.print();
</script>
<div class="invoice">
    <table width="100%">
        <tr>
            <td width="40%" class="huruf">#<?= $invoice->no_nota; ?></td>
        </tr>
        <tr>
            <td width="40%" class="huruf"><?= date('d M Y', strtotime($invoice->tgl_jam)) ?></td>
        </tr>
        <tr>
            <td width="40%" class="huruf">ANTRIAN: <?= $invoice->antrian ?></td>
        </tr>
        <tr>
            <td width="40%" class="huruf"><?= $invoice->id_distribusi == '1' ? 'DINE IN' : 'GRABFOOD' ?></td>
        </tr>
    </table>
    <hr style="border: 1px solid black;">
    <h4 align="center">KASIR</h4>
    <hr style="border: 1px solid black;">
    <table width="100%">
        <?php
        $total_produk = 0;
        $qty_produk = 0;
        ?>
        <?php if (!empty($produk)) : ?>
            <?php $total_toping = 0;
            foreach ($produk as $p) :
                $toping = $this->db->query("SELECT a.*, b.nm_produk
				FROM tb_pembelian as a 
				left join tb_produk as b on b.id_produk = a.id_produk
				where a.id_produk_toping = '$p->id_produk' and a.no_nota = '$no_nota'
				")->result();
            ?>
                <?php
                $total_produk += ($p->jumlah * $p->harga) - $p->diskon;
                $qty_produk += $p->jumlah;
                $nm_servis = strtolower($p->nm_servis);
                $hrg_produk = $p->jumlah * $p->harga;
                $hrg_asli = $p->jumlah * $p->harga_asli;
                ?>
                <tr class="huruf" style="margin-bottom: 2px;">
                    <td width="2%"><?= $p->jumlah; ?></td>
                    <td width="98%" colspan="2"><?= ucwords($nm_servis); ?></td>
                </tr>
                <?php

                foreach ($toping as $t) :

                ?>
                    <tr class="huruf" style="margin-bottom: 2px;">
                        <td width="2%">
                        </td>
                        <td width="98%" colspan="2">
                            <?= $t->jumlah; ?> &nbsp; <?= ucwords(strtolower($t->nm_produk)); ?>
                        </td>
                    </tr>
                <?php $total_toping += $t->harga;
                endforeach; ?>
            <?php
            endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="3">===============================</td>
        </tr>
    </table>