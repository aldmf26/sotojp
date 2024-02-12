<style>
    .invoice {
        margin: auto;
        width: 80mm;
        background: #FFF;
    }

    .huruf {
        font-size: 14px;
    }
</style>
<script>
    window.print();
</script>





<div class="invoice">
    <br>
    <br>
    <p style=" margin-top: -10px;" align="center" class="huruf">****************MULAI****************</p>
    <p style=" margin-top: -10px;" align="center" class="huruf">Laporan Shift Out</p>
    <p style=" margin-top: -10px;" align="center" class="huruf"><?= date('d-M-y', strtotime($tgl1)) ?> ~ <?= date('d-M-y', strtotime($tgl2)) ?></p>

    <table width="100%">
        <tr>
            <td colspan="3">===============================</td>
        </tr>
        <tr>
            <td colspan="3" align="center">Penj. Menu Berdasarkan Mode Transaksi</td>
        </tr>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <tr>
            <td colspan="3" align="center">DINE IN</td>
        </tr>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <?php
        $total = 0;
        $jlh = 0;
        foreach ($dinein as $s) : ?>

            <tr class="huruf">
                <td><?= $s->nm_servis ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="huruf">
                <td>Jumlah</td>
                <td>:</td>
                <td align="right"><?= number_format($s->jumlah) ?></td>
            </tr>
            <tr class="huruf">
                <td>Total</td>
                <td>:</td>
                <td align="right"><?= number_format($s->total) ?></td>
            </tr>
        <?php
            $total += $s->total;
            $jlh += $s->jumlah;
        endforeach ?>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <tr class="huruf">
            <td>Jumlah Total</td>
            <td>:</td>
            <td align="right"><?= number_format($jlh) ?></td>
        </tr>
        <tr class="huruf">
            <td>Penjualan</td>
            <td>:</td>
            <td align="right"><?= number_format($total) ?></td>
        </tr>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <tr>
            <td colspan="3" align="center">ONLINE</td>
        </tr>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <?php
        $total1 = 0;
        $jlh1 = 0;
        foreach ($gojek as $s) : ?>

            <tr class="huruf">
                <td><?= $s->nm_servis ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="huruf">
                <td>Jumlah</td>
                <td>:</td>
                <td align="right"><?= number_format($s->jumlah) ?></td>
            </tr>
            <tr class="huruf">
                <td>Total</td>
                <td>:</td>
                <td align="right"><?= number_format($s->total) ?></td>
            </tr>
        <?php
            $total1 += $s->total;
            $jlh1 += $s->jumlah;
        endforeach ?>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <tr class="huruf">
            <td>Jumlah Total</td>
            <td>:</td>
            <td align="right"><?= number_format($jlh1) ?></td>
        </tr>
        <tr class="huruf">
            <td>Penjualan</td>
            <td>:</td>
            <td align="right"><?= number_format($total1) ?></td>
        </tr>
        <tr>
            <td colspan="3">=================================</td>
        </tr>
        <tr class="huruf">
            <td>Total Semua Jumlah</td>
            <td>:</td>
            <td align="right"><?= number_format($jlh + $jlh1) ?></td>
        </tr>
        <tr class="huruf">
            <td>Total Semua Penjualan</td>
            <td>:</td>
            <td align="right"><?= number_format($total + $total1) ?></td>
        </tr>
    </table>
    <hr>
    <hr>
    <br>
    <p style=" margin-top: -10px;" align="center" class="huruf">****************AKHIR****************</p>