<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?= $title; ?></title>

  <!-- Custom fonts for this template -->
  <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?= base_url('assets/'); ?>css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="<?= base_url('assets/'); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Jquery ajax -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <style>
        * {
            font-size: 10px !important;
        }

        td {
          margin: 0px !important;
          padding: 0px !important;
          padding-left: 3px !important;
        }

    </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <!-- <div id="wrapper"> -->


  <!-- Main content -->
  <!-- <section class="content"> -->
  <!-- <div class="container-fluid"> -->
  <!-- <div class="row"> -->
  <!-- <div class="col-10"> -->
  <div class="card" p="0" m="0">
    <div class="card-header bg-light">
      <div class="text-center">
        <h6 class="font-weight-bold" style="font-size: 18px!important;">FORM PEMINJAMAN DATA PUSAT</h6>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="card-body">
        <div class="form-group row ">
          <div class="col col-sm-4">
            <small class="font-weight-bold">Kepada : <?= $peminjaman['nama_cabang'] ?></small>
          </div>
          <div class="col col-sm-4  ml-auto" style="margin-top:-50px">
            <small class="font-weight-bold ml-auto">Tgl : <?= date_format(date_create($peminjaman['date']), 'd/m/Y') ?></small>
          </div>
        </div>
        <div class="form-group row ">
          <div class="col col-sm-4">
            <div class="kosong">
              <small class="font-weight-bold">Dari : <?= $peminjaman['from_cb'] ?></small>
            </div>
          </div>
          <div class="col col-sm-4 ml-auto" style="margin-top:-50px">
            <div class="kosong">
              <small class="font-weight-bold">Nomor : <?= $peminjaman['kode_pengajuan'] ?>/<?= $peminjaman['number'] ?></small>
            </div>
          </div>
        </div>

        <div class="form-group row">
                <div class="col col-sm-12 col-md-6 col-lg-6">
                  <p class="font-weight-bold" style="background-color: ; display: inline-block;">Nama Dinas : <?= $peminjaman['dinas'] ?></p>
                </div>
              
                
                <div class="col col-md-1"></div>
                <div class="col col-sm-12 col-md-6 col-lg-6">
                  <p class="font-weight-bold" style="background-color: ; display: inline-block;">Lokasi Dinas : <?= $peminjaman['lokasi'] ?></p>
                </div>
                
              </div>
            </div>
    

        <div class="form-group row mt-5">
          <div class="col col-11 ml-auto">
            <p class="font-weight-bold" style="margin-left: 45px;">Dengan ini mengajukan permohonan pemakaian stock barang dari CV. Solusi Arya Prima Pusat berupa :</p>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-12 mr-auto">
            <div class="table-responsive">
              <table class="table table-bordered" id="dynamic">
                <thead>
                  <tr>
                    <td class="font-weight-bold">No</td>
                    <td class="font-weight-bold">SKU</td>
                    <td class="font-weight-bold">Nama Barang</td>
                    <td class="font-weight-bold">QTY</td>
                    <td class="font-weight-bold">Harga Satuan</td>
                    <td class="font-weight-bold">Total Harga</td>
                    <td class="font-weight-bold">Stok/PO</td>
                    <td class="font-weight-bold">Maks Delivery</td>
                  </tr>
                </thead>
                <tbody>
                  <?php $total = 0 ?>
                  <?php foreach ($peminjaman['barangpeminjaman'] as $key => $barang) { ?>
                    <?php $total = $total + $barang['jumlah'] ?>
                    <tr>
                      <td><label><?= $key + 1 ?></label></td>
                      <td><?= $barang['sku'] ? $barang['sku'] : "-" ?></td>
                      <td><?= $barang['nama'] ?></td>
                      <td><?= $barang['qty'] ?></td>
                      <td>Rp. <?= number_format($barang['harga'], 0, ',', '.') ?></td>
                      <td>Rp. <?= number_format($barang['jumlah'], 0, ',', '.') ?></td>
                      <td><?= $barang['stok_po'] ? $barang['stok_po'] : "-" ?></td>
                      <td><?= $barang['maks_delivery'] ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="text-center font-weight-bold">Total</td>
                    <td></td>
                    <td></td>
                    <td class="font-weight-bold">Rp. <?= number_format($total, 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col col-md-1"></div>
          <div class="col col-12 col-md-10" style="width:80%!important; margin-left: 7.5%!important;">
            <p style="border: solid 1px ; width: 30%;" >Tanggal Maksimal Closing : <?= $peminjaman['closingdate'] ?></p>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center font-weight-bold">
                  <tr>
                    <td>Yang Mengajukan</td>
                    <td colspan="5">Menyetujui</td>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <td class="p-0">
                    <p>Sales</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['sales']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                  <td>
                    <p>PM</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['pm']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                  <td>
                    <p>Koor Sales</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['ks']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                  <td>
                    <p>Head Region</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['hr']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                  <td>
                    <p>Manager Sales</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['ms']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                  <td>
                    <p>Manager Operasional</p>
                    <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['mo']['ttd'] ?>" style="width:50px!important; height:50px!important; padding: 10px!important;">
                  </td>
                </tbody>
                <tfoot>
                  <td>tgl: <?= $peminjaman['approve']['sales']['createdat'] ?></td>
                  <td>tgl: <?= $peminjaman['approve']['pm']['createdat'] ?></td>
                  <td>tgl: <?= $peminjaman['approve']['ks']['createdat'] ?></td>
                  <td>tgl: <?= $peminjaman['approve']['hr']['createdat']  ?></td>
                  <td>tgl: <?= $peminjaman['approve']['ms']['createdat'] ?></td>
                  <td>tgl: <?= $peminjaman['approve']['mo']['createdat'] ?></td>
                </tfoot>
              </table>
            </div>
            <p class="font-weight-bold" style="background-color: yellow; display: inline-block;">Note : <?= $peminjaman['note'] ?></p>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <!-- </div> -->
  <!-- /.row -->
  <!-- </div> -->
  <!-- /.container-fluid -->
  <!-- </section> -->

  <!-- </div> -->
  <!-- End of Content Wrapper -->

  <!-- </div> -->
  <!-- End of Page Wrapper -->

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="<?= base_url('assets/'); ?>vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url('assets/'); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?= base_url('assets/'); ?>js/demo/datatables-demo.js"></script>

</body>

</html>