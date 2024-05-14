<!-- Main content -->
<script>
  function deleteConfirm(url) {
    $('#btn-delete').attr('href', url);
    $('#deleteModal').modal();
  }
</script>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h4 class="font-weight-bold">FORM PEMINJAMAN DATA PUSAT</h4>
              <p class="font-weight-bold">Kode Peminjaman Barang : <?= $peminjaman['kode_pengajuan'] ?></p>
            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body">
            <div class="card-body">
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <p class="font-weight-bold">Kepada : <?= $peminjaman['nama_cabang'] ?></p>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4 ml-auto">
                  <div class="kosong">
                    <p class="font-weight-bold">Tgl : <?= date_format(date_create($peminjaman['date']), 'd/m/Y') ?></p>
                  </div>
                </div>
              </div>
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <p class="font-weight-bold">Dari : <?= $peminjaman['from_cb'] ?></p>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4 ml-auto">
                  <div class="kosong">
                    <p class="font-weight-bold">Nomor : <?= $peminjaman['number'] ?></p>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <p class="font-weight-bold" style="background-color: ; display: inline-block;">Nama Dinas : <?= $peminjaman['dinas'] ?></p>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4 ml-auto">
                  <p class="font-weight-bold">Nomor SQ : <?= $peminjaman['nosq'] ?></p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <p class="font-weight-bold" style="background-color: ; display: inline-block;">Lokasi Dinas : <?= $peminjaman['lokasi'] ?></p>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4 ml-auto">
                  <p class="font-weight-bold">Nomor PO : <?= $peminjaman['nopo'] ?></p>
                </div>
              </div>
          </div>

              <div class="form-group row mt-5">
                <div class="col col-11 ml-auto">
                  <p class="font-weight-bold">Dengan ini mengajukan permohonan pemakaian stock barang dari CV. Solusi Arya Prima Pusat berupa :</p>
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
                <div class="col col-sm-12 col-md-10 col-lg-4">
                  <div class="kosong">
                    <p style="border: solid 1px black;">Tanggal Maksimal Closing : <?= $peminjaman['closingdate'] ?></p>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-md-1"></div>
                <div class="col col-12 col-md-10">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead class="text-center font-weight-bold">
                        <tr>
                          <td>Yang Mengajukan</td>
                          <td colspan="5">Menyetujui</td>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                          <td style="border-bottom: none;">
                            <p>Sales</p>
                          </td>
                          <td style="border-bottom: none;">
                            <p>PM</p>
                          </td>
                          <td style="border-bottom: none;">
                            <p>Koor Sales</p>
                          </td>
                          <td style="border-bottom: none;">
                            <p>Head Region</p>
                          </td>
                          <td style="border-bottom: none;">
                            <p>Manager Sales</p>
                          </td>
                          <td style="border-bottom: none;">
                            <p>Manager Operasional</p>
                          </td>
                        </tr>
                        <tr>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['sales']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['pm']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['ks']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['hr']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['ms']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                          <td style="border-top: none">
                            <img src="<?= base_url('assets/img/profile/ttd/') . $peminjaman['approve']['mo']['ttd'] ?>" style="width:100px!important; height:100px!important; padding: 10px!important;">
                          </td>
                        </tr>
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
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-md-1"></div>
                <div class="col col-sm-12 col-md-6 col-lg-6">
                  <p class="font-weight-bold" style="background-color: yellow; display: inline-block;">Note : <?= $peminjaman['note'] ?></p>
                </div>
              </div>
              <div class="row mt-3">
              <!-- Tombol Kebutuhan -->
              <div class="col col-md-4"></div>
              <div class="col">
                  <!-- Tombol Perbarui -->
                  <?php if (in_array($user["role_id"], array(1)) || ($peminjaman['status'] == "PENDING" && in_array($user["role_id"], array(1, 2)))): ?>
                      <a class="btn btn-warning mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/edit/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-pen"></i> Perbarui
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Update SKU -->
                  <?php if (in_array($user["role_id"], array(1)) || ($peminjaman['status'] == "PENDING" || $peminjaman['status'] == "PROCESS") && in_array($user["role_id"], array(3, 8))): ?>
                      <a class="btn btn-info mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/process/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-file"></i> Update SKU
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Update No SQ -->
                  <?php if (in_array($user["role_id"], array(1, 9))): ?>
                      <a class="btn btn-info mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/editcs/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-tags"></i> Update No SQ
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Update No PO -->
                  <?php if (in_array($user["role_id"], array(1, 10))): ?>
                      <a class="btn btn-info mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/editpurc/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-tags"></i> Update No PO
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Approve -->
                  <?php if ($peminjaman['status'] == "PROCESS" && in_array($user["role_id"], array(4,5,6,7,8))): ?>
                      <a class="btn btn-success mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/approve/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-check-double"></i> Approve
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Tolak -->
                  <?php if ($peminjaman['status'] == "PROCESS" && !in_array($user["role_id"], array(1, 2, 3, 8, 9, 10))): ?>
                      <a class="btn btn-dark mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/reject/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-minus"></i> Tolak
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Batal Tolak -->
                  <?php if ($peminjaman['status'] == "REJECTED" && $user["role_id"] == 1): ?>
                      <a class="btn btn-secondary mx-1" style="font-size:14px;" href="<?= site_url('peminjaman/unreject/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-recycle"></i> Batal Tolak
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Hapus -->
                  <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(2)) && $peminjaman['status'] == "PENDING")): ?>
                      <a class="btn btn-danger mx-1" style="font-size:14px;" href="#!" onclick="deleteConfirm('<?= site_url('peminjaman/delete/' . $peminjaman['id_peminjaman']); ?>')">
                          <i class="fas fa fa-trash"></i> Hapus
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Cetak -->
                  <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(1, 2, 4)))): ?>
                      <a class="btn btn-secondary mx-1" style="font-size:14px;" target="_blank" href="<?= site_url('peminjaman/print/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-print"></i> Cetak
                      </a>
                  <?php endif; ?>

                  <!-- Tombol Cetak Eksternal -->
                  <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(1, 2, 4)))): ?>
                      <a class="btn btn-secondary mx-1" style="font-size:14px;" target="_blank" href="<?= site_url('peminjaman/print2/' . $peminjaman['id_peminjaman']); ?>">
                          <i class="fas fa fa-print"></i> Cetak Eksternal
                      </a>
                  <?php endif; ?>
              </div>


            </div>
            <div class="file-info">
              <!-- Form untuk mengunggah file PDF -->
              <form action="<?= site_url('peminjaman/upload_file'); ?>" method="POST" enctype="multipart/form-data">
  <!-- Input tersembunyi untuk mengirimkan peminjaman_id -->
              <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id_peminjaman'] ?>" /> <!-- Pastikan $peminjaman_id diisi -->

              <div class="upload-section">
                <label for="pdf-upload" class="btn btn-primary" style="font-size:14px;">
                  <i class="fas fa-upload"></i> Unggah FPB Dinas
                </label>
                <input id="pdf-upload" type="file" name="pdf_file" accept=".pdf" style="display: none;" onchange="this.form.submit()">
              </div>
            </form>

            </div>
            <div class="file-info">
              <?php if (isset($file_data) && $file_data): ?>
                <!-- Jika ada file, tampilkan nama file -->
                <p>Lampiran : <?= htmlspecialchars($file_data->file_name); ?></p>
              <?php else: ?>
                <!-- Jika tidak ada file -->
                <p>Belum Ada Lampiran</p>
              <?php endif; ?>
            </div>

            <div class="file-info">
            <?php if (isset($file_data) && $file_data): ?>
              <!-- Tombol untuk mengunduh file -->
              <a class="btn btn-success" href="<?= base_url($file_data->file_url); ?>" download>
                <i class="fas fa-download"></i> Unduh Lampiran : <?= htmlspecialchars($file_data->file_name); ?>
              </a>
            <?php else: ?>
              <!-- Jika tidak ada file -->
              <p>Lengkapi Lampiran FPB Anda</p>
            <?php endif; ?>
          </div>

                        
            <div class="row mt-5">
                <input type="hidden" name="" id="url_peminjaman" value="<?= base_url('peminjaman') ?>">
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-warning mr-3" data-dismiss="modal">Kembali</a>
              </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- modal delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Apa anda yakin ?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Data yg dihapus tidak dapat dipulihkan !</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
        <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
      </div>
    </div>
  </div>
</div>