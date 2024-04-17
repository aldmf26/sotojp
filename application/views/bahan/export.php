<?php
$file = "DATA BAHAN & RESEP.xls";
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>
<table class="table" border="1">
    <thead>
        <tr>
            <th>no</th>
            <th>nama bahan</th>
            <th>qty</th>
            <th>satuan</th>
        </tr>
    </thead>
    <tbody id="myTable">
        <?php
        $n = 1;
        foreach ($produk as $p) : ?>
            <tr>
                <td><?= $n++ ?></td>
                <td><?= $p->nm_produk ?></td>
                <td><?= number_format($p->stok_program,2) ?></td>
                <td><?= $p->satuan ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>

</table>