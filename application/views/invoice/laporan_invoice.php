<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <h3>Laporan Invoice Periode <?= date('d M Y', strtotime($tgl1)) ?> - <?= date('d M Y', strtotime($tgl2)) ?></h3>
            </div>
            <!-- <div class="col-12">
                <p class="float-right">Waktu Cetak</p>
                <br><br>
                <p class="float-right"><?= date('d M Y, H:i') ?></p>
            </div> -->
            <div class="col-lg-8">

            </div>
            <div class="col-lg-4 mb-2">
                <a href="" class="btn  btn-primary float-right"><i class="fas fa-print"></i> Print</a>
            </div>
            <div class="col-12">
                <table class="table" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>TANGGAL</th>
                            <th>NO NOTA</th>
                            <th>CASH</th>
                            <th>QRIS</th>
                            <th>TOTAL</th>
                            <th>BAYAR</th>
                            <th>KEMBALIAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $mandiri_debit = 0;
                        $mandiri_kredit = 0;
                        $bca_debit = 0;
                        $bca_kredit = 0;
                        $cash = 0;
                        $gopay = 0;
                        // $shoope = 0;
                        // $tokped = 0;
                        $total = 0;
                        $bayar = 0;
                        $ttlkembalian = 0;
                        ?>
                        <?php foreach ($invoice as $d) : ?>
                            <?php
                            $kembalian = $d->bayar - $d->total;
                            $mandiri_debit += $d->mandiri_kredit;
                            $mandiri_kredit += $d->mandiri_debit;
                            $bca_debit += $d->bca_kredit;
                            $bca_kredit += $d->bca_debit;
                            $cash += $d->cash;
                            // $shoope += $d->shoope;
                            // $tokped += $d->tokped;
                            $total += $d->total;
                            $bayar += $d->bayar;
                            $ttlkembalian += $kembalian;
                            $gopay += $d->gopay;
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= date('d-m-Y', strtotime($d->tgl_jam)) ?></td>
                                <td><?= $d->no_nota ?></td>
                                <td><?= number_format($d->cash, 0) ?></td>
                                <td><?= number_format($d->gopay, 0) ?></td>
                                <td><?= number_format($d->total, 0) ?></td>
                                <td><?= number_format($d->bayar, 0) ?></td>
                                <td><?= number_format($kembalian, 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-secondary text-light">
                        <tr>
                            <td colspan="3" class="text-center">Total</td>
                            <td><?= number_format($cash, 0) ?></td>
                            <td><?= number_format($gopay, 0) ?></td>
                            <!-- <td><?= number_format($shoope, 0) ?></td>
                            <td><?= number_format($tokped, 0) ?></td> -->
                            <td><?= number_format($total, 0) ?></td>
                            <td><?= number_format($bayar, 0) ?></td>
                            <td><?= number_format($ttlkembalian, 0) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


</body>

</html>