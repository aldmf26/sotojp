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
        <div class="section-title huruf">PENJUALAN PER-ITEM</div>
        <table>
            <tr>
                <th align="left">Nama Menu</th>
                <th></th>
                <th class="right-align">Qty</th>
                <th class="right-align">Total (Rp)</th>
            </tr>
            <?php
            // Gabungkan data dari $dinein dan $gojek
            $combined = [];

            // Proses data Dine-In
            foreach ($dinein as $s) {
                $menu = $s->nm_servis;
                if (!isset($combined[$menu])) {
                    $combined[$menu] = ['jumlah' => 0, 'total' => 0];
                }
                $combined[$menu]['jumlah'] += $s->jumlah;
                $combined[$menu]['total'] += $s->total;
            }

            // Proses data Gojek
            foreach ($gojek as $s) {
                $menu = $s->nm_servis;
                if (!isset($combined[$menu])) {
                    $combined[$menu] = ['jumlah' => 0, 'total' => 0];
                }
                $combined[$menu]['jumlah'] += $s->jumlah;
                $combined[$menu]['total'] += $s->total;
            }

            // Hitung total keseluruhan
            $total_qty = 0;
            $total_amount = 0;

            // Tampilkan data per item
            foreach ($combined as $menu => $data) : ?>
                <tr class="huruf">
                    <td><?= $menu ?></td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($data['jumlah']) ?></td>
                    <td class="right-align"><?= number_format($data['total']) ?></td>
                </tr>
            <?php
                $total_qty += $data['jumlah'];
                $total_amount += $data['total'];
            endforeach;
            ?>
            <tr>
                <td colspan="4" class="divider"></td>
            </tr>
            <tr class="huruf bold">
                <td>Jumlah Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($total_qty) ?></td>
                <td class="right-align"><?= number_format($total_amount) ?></td>
            </tr>
            <?php
            $cash_today = 0;
            $transfer_today = 0;

            foreach ($invoice as $value) {
                $cash_today += $value->cash - $value->kembali;
                $transfer_today += $value->bca_debit;
            }


            ?>
            <tr class="huruf bold">
                <td>Cash</td>
                <td>:</td>
                <td class="right-align"></td>
                <td class="right-align"><?= number_format($cash_today) ?></td>
            </tr>
            <tr class="huruf bold">
                <td>Transfer</td>
                <td>:</td>
                <td class="right-align"></td>
                <td class="right-align"><?= number_format($transfer_today) ?></td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="section-title huruf">PENJUALAN OFFLINE</div>

        <table>
            <tr>
                <th align="left">Nama Menu</th>
                <th></th>
                <th class="right-align">Qty</th>
                <th class="right-align">Total (Rp)</th>
            </tr>
            <?php
            $total = 0;
            $jlh = 0;
            foreach ($dinein as $s) : ?>

                <tr class="huruf">
                    <td><?= $s->nm_servis ?></td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($s->jumlah) ?></td>
                    <td class="right-align"><?= number_format($s->total) ?></td>
                </tr>

            <?php
                $total += $s->total;
                $jlh += $s->jumlah;
            endforeach ?>
            <tr>
                <td colspan="4" class="divider"></td>
            </tr>
            <tr class="huruf bold">
                <td>Jumlah Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($jlh) ?></td>
                <td class="right-align"><?= number_format($total) ?></td>

            </tr>

        </table>

        <div class="divider"></div>
        <div class="section-title huruf">PENJUALAN ONLINE</div>
        <table>
            <tr>
                <th align="left">Nama Menu</th>
                <th></th>
                <th class="right-align">Qty</th>
                <th class="right-align">Total (Rp)</th>
            </tr>
            <?php
            $total1 = 0;
            $jlh1 = 0;
            foreach ($gojek as $s) : ?>
                <tr class="huruf">
                    <td><?= $s->nm_servis ?></td>
                    <td>:</td>
                    <td class="right-align"><?= number_format($s->jumlah) ?></td>
                    <td class="right-align"><?= number_format($s->total) ?></td>
                </tr>
            <?php
                $total1 += $s->total;
                $jlh1 += $s->jumlah;
            endforeach ?>
            <tr>
                <td colspan="4" class="divider"></td>
            </tr>
            <tr class="huruf bold">
                <td>Jumlah Penjualan</td>
                <td>:</td>
                <td class="right-align"><?= number_format($jlh1) ?></td>
                <td class="right-align"><?= number_format($total1) ?></td>
            </tr>

        </table>

        <!-- <div class="double-divider"></div>
        <table>
            <tr class="huruf bold">
                <td width="55%">Total Semua Penjualan</td>
                <td width="10%">:</td>
                <td class="right-align"><?= number_format($jlh + $jlh1) ?></td>
                <td class="right-align"><?= number_format($total + $total1) ?></td>
            </tr>

        </table> -->

        <div class="spacer"></div>
        <div class="footer huruf">================ AKHIR ================</div>
    </div>
</body>

</html>