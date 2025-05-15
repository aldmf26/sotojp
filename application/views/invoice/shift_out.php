<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Out Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #FFF;
        }

        .invoice {
            margin: auto;
            width: 80mm;
            padding: 10px;
            background: #FFF;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .huruf {
            font-size: 12px;
            margin: 5px 0;
        }

        .header,
        .footer {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .date-range {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        tr {
            line-height: 1.6;
        }

        td {
            padding: 3px 0;
            vertical-align: top;
        }

        .divider {
            border-top: 1px dashed #999;
            margin: 10px 0;
        }

        .double-divider {
            border-top: 2px solid #333;
            margin: 10px 0;
        }

        .right-align {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .spacer {
            height: 10px;
        }

        @media print {
            .invoice {
                width: 80mm;
                padding: 0;
                margin: 0 auto;
            }

            body {
                margin: 0;
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body>
    <div class="invoice">
        <div class="header huruf">================ MULAI ================</div>
        <p class="section-title huruf">Laporan Shift Out</p>
        <p class="date-range huruf"><?= date('d-M-y', strtotime($tgl1)) ?> ~ <?= date('d-M-y', strtotime($tgl2)) ?></p>

        <div class="section-title huruf">Penj. Menu Berdasarkan Mode Transaksi</div>
        <div class="divider"></div>

        <div class="section-title huruf">DINE IN</div>
        <table>
            <?php
            $total = 0;
            $jlh = 0;
            foreach ($dinein as $s) : ?>
                <tr class="huruf">
                    <td><?= $s->nm_servis ?></td >
                    <td></td>
                    <td></td>
                </tr>
                <tr class="huruf">
                    <td>Jumlah</td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($s->jumlah) ?></td>
                </tr>
                <tr class="huruf">
                    <td>Total</td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($s->total) ?></td>
                </tr>
            <?php
                $total += $s->total;
                $jlh += $s->jumlah;
            endforeach ?>
            <tr>
                <td colspan="3" class="divider"></td>
            </tr>
            <tr class="huruf bold">
                <td>Jumlah Total</td>
                <td>:</td>
                <td class="right-align"><?= number_format($jlh) ?></td>
            </tr>
            <tr class="huruf bold">
                <td>Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($total) ?></td>
            </tr>
        </table>

        <div class="divider"></div>
        <div class="section-title huruf">ONLINE</div>
        <table>
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
                    <td class="right-align"><?= number_format($s->jumlah) ?></td>
                </tr>
                <tr class="huruf">
                    <td>Total</td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($s->total) ?></td>
                </tr>
            <?php
                $total1 += $s->total;
                $jlh1 += $s->jumlah;
            endforeach ?>
            <tr>
                <td colspan="3" class="divider"></td>
            </tr>
            <tr class="huruf bold">
                <td>Jumlah Total</td>
                <td>:</td>
                <td class="right-align"><?= number_format($jlh1) ?></td>
            </tr>
            <tr class="huruf bold">
                <td>Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($total1) ?></td>
            </tr>
        </table>

        <div class="double-divider"></div>
        <table>
            <tr class="huruf bold">
                <td>Total Semua Jumlah</td>
                <td>:</td>
                <td class="right-align"><?= number_format($jlh + $jlh1) ?></td>
            </tr>
            <tr class="huruf bold">
                <td>Total Semua Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($total + $total1) ?></td>
            </tr>
        </table>

        <div class="spacer"></div>
        <div class="footer huruf">================ AKHIR ================</div>
    </div>
</body>

</html>