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
    <p style=" margin-top: -10px;" align="center" class="huruf">Ringkasan Metode Pembayaran</p>
    <p style=" margin-top: -10px;" align="center" class="huruf"><?= date('d-M-y', strtotime($tgl1)) ?> ~ <?= date('d-M-y', strtotime($tgl2)) ?></p>

    <table width="100%">
        <?php
        $total = 0;
        foreach ($summary as $s) : ?>
            <tr>
                <td colspan="3">-------------------------------------------------------</td>
            </tr>
            <tr class="huruf">
                <td><?= $s->payment_method ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="huruf">
                <td>Jumlah</td>
                <td>:</td>
                <td align="right"><?= number_format($s->transaction_count) ?></td>
            </tr>
            <tr class="huruf">
                <td>Total</td>
                <td>:</td>
                <td align="right"><?= number_format($s->total_amount) ?></td>
            </tr>
        <?php
            $total += $s->total_amount;
        endforeach ?>
        <tr>
            <td colspan="3">-------------------------------------------------------</td>
        </tr>
        <tr class="huruf">
            <td>Total Pembayaran</td>
            <td>:</td>
            <td align="right"><?= number_format($total) ?></td>
        </tr>
    </table>
    <hr>
    <hr>
    <br>
    <p style=" margin-top: -10px;" align="center" class="huruf">****************AKHIR****************</p>