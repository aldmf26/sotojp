<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h5 class="text-center">Laporan Penjualan Per Jam</h5>
                <p class="date-range huruf text-center"><?= date('d-M-y', strtotime($tgl1)) ?> ~ <?= date('d-M-y', strtotime($tgl2)) ?></p>

                <table class="table table-bordered border-dark table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">Jam</th>
                            <th>Nama Menu</th>
                            <th class="text-end">Qty</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $last_jam = '';
                            $total_qty = 0;
                            $total_harga = 0;
                            foreach ($perJam as $row):
                        ?>
                            <tr>
                                <td align="center"><?= ($last_jam != $row->rentang_jam) ? $row->rentang_jam : '' ?></td>
                                <td><?= $row->nama_menu ?></td>
                                <td align="right"><?= $row->qty ?></td>
                                <td align="right"><?= number_format($row->total_rp, 0) ?></td>
                            </tr>
                        <?php
                            $last_jam = $row->rentang_jam;
                            $total_qty += $row->qty;
                            $total_harga += $row->total_rp;
                        endforeach;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-end"><?= $total_qty ?></th>
                            <th class="text-end"><?= number_format($total_harga, 0) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>