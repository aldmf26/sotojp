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
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Jam</th>
                            <th>Nama Menu</th>
                            <th>Qty Terjual</th>
                            <!-- <th>Harga</th> -->
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
                                <td><?= ($last_jam != $row->rentang_jam) ? $row->rentang_jam : '' ?></td>
                                <td><?= $row->nama_menu ?></td>
                                <td><?= $row->qty ?></td>
                            </tr>
                        <?php
                            $last_jam = $row->rentang_jam;
                            $total_qty += $row->qty;
                        endforeach;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th><?= $total_qty ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>