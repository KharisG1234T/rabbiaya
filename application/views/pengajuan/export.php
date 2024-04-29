<!DOCTYPE html>
<html>

<head>
    <title>Export Data Peminjaman</title>
</head>

<body>
    <style type="text/css">
        body {
            font-family: sans-serif;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #3c3c3c;
            padding: 3px 8px;

        }

        a {
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
    </style>

    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Peminjaman.xls");
    ?>

    <center>
        <h1>Export Data Peminjaman </h1>
        <?php if ($tgl_awal != "" && $tgl_akhir != "") { ?>
            <h3>Periode : (<?= $tgl_awal ?>) - (<?= $tgl_akhir ?></h3>)
        <?php } ?>
    </center>

    <table border="1">
        <tr>
            <th>Kode</th>
            <th>Peminjam</th>
            <th>Kepada Dinas</th>
            <th>Dari Cabang</th>
            <th>Tujuan</th>
            <th>Tanggal Dibuat</th>
            <th>Closing Date</th>
            <th>Catatan</th>
            <th>No SQ</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
        <!-- <?php var_dump($data) ?> -->
        <?php foreach ($data as $d) { ?>
            <tr>
                <td><?= $d["kode_pengajuan"] ?></td>
                <td><?= $d["name"] ?></td>
                <td><?= $d["dinas"] ?></td>
                <td><?= $d["from_cb"] ?></td>
                <td><?= $d["to_cb"] ?></td>
                <td><?= $d["date"] ?></td>
                <td><?= $d["closingdate"] ?></td>
                <td><?= $d["note"] ?></td>
                <td><?= $d["nosq"] ?></td>
                <td><?= $d["status"] ?></td>
                <td><?= $d["keterangan_sku"] ?> <?= $d["userApprovals"] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>