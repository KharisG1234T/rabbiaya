<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h4 class="font-weight-bold">DETAIL PENGAJUAN RAB BIAYA PERJALANAN</h4>
              <p class="font-weight-bold">Kode Pengajuan: <?= $pengajuan['kode_pengajuan'] ?></p>
            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body">
            <!-- Display main request details -->
            <div class="form-group row">
              <div class="col-sm-6 col-md-4">
                <p class="font-weight-bold">Pengirim : <?= $pengajuan['name'] ?></p>
                <p class="font-weight-bold">Total Pengajuan : Rp. <?= number_format($pengajuan['total_amount'], 0, ',', '.') ?></p>
                <p class="font-weight-bold">Jenis Agenda : <?= $pengajuan['title'] ?></p>
              </div>
              <div class="col col-sm-4 col-md-4 col-lg-4"></div>
              <div class="col-sm-6 col-md-4">
                <p class="font-weight-bold">Tanggal Pengajuan : <?= date('d/m/Y', strtotime($pengajuan['request_date'])) ?></p>
                <p class="font-weight-bold">Tanggal Berangkat : <?= date('d/m/Y', strtotime($pengajuan['departure_date'])) ?></p>
                <p class="font-weight-bold">Tujuan: <?= $pengajuan['destination'] ?></p>
              </div>
            </div>
            <div class="form-group row"></div>
            <div class="form-group row"></div>
            <div class="form-group row"></div>
            <!-- Display trip details -->
            <div class="form-group row">
              <div class="col col-20 ml-auto">
                <p class="font-weight-bold">Rincian & Detail RAB Perjalanan :</p>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12 mr-auto">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Nomor</th>
                        <th>Kegiatan / Aktivitas</th>
                        <th>Deskripsi</th>
                        <th>Makan</th>
                        <th>QTY</th>
                        <th>Jumlah Hari</th>
                        <th>Unit Price (IDR)</th>
                        <th>Total Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($pengajuan['official_trip_detail'] as $index => $detail) : ?>
                        <tr>
                          <td><?= $index + 1 ?></td>
                          <td><?= $detail['activity']['name'] ?></td>
                          <td style="width: 400px;"><?= $detail['remark'] ?></td>
                          <td><?= $detail['is_food'] ?></td>
                          <td><?= $detail['qty'] ?></td>
                          <td><?= $detail['duration'] ?></td>
                          <td><?= number_format($detail['amount'], 0, ',', '.') ?></td>
                          <td><?= number_format($detail['total_amount'], 0, ',', '.') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="5" class="text-center font-weight-bold">Total</td>
                        <td colspan="4" class="font-weight-bold text-center">Rp. <?= number_format($pengajuan['total_amount'], 0, ',', '.') ?></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <!-- Display trip destinations -->
            <div class="form-group row">
              <div class="col col-20 ml-auto">
                <p class="font-weight-bold">Rincian Dinas Tujuan:</p>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-10 mr-auto">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Nomor</th>
                        <th>Nama Dinas</th>
                        <th>Kota / Kabupaten</th>
                        <th>Nomor Tiket</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($pengajuan['official_trip_destination'] as $index => $destination) : ?>
                        <tr>
                          <td><?= $index + 1 ?></td>
                          <td><?= $destination['name'] ?></td>
                          <td><?= $destination['destination'] ?></td>
                          <td><?= $destination['ticket_number'] ?></td>
                          <td><?= $destination['remark'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Display approval statuses -->
            <div class="form-group row">
              <div class="col col-20 ml-auto">
                <p class="font-weight-bold">Status Persetujuan:</p>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12 mr-auto">
                <div class="table-responsive">
                  <table class="table table-bordered text-center">
                    <thead>
                      <tr>
                        <th>Yang Mengajukan</th>
                        <th>Koor Sales</th>
                        <th>HR</th>
                        <th>HRD</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <!-- Sales -->
                        <td>
                          <?php if (!empty($pengajuan['approve']['sales']['ttd'])) { ?>
                            <img src="<?= base_url('assets/img/profile/ttd/') . $pengajuan['approve']['sales']['ttd'] ?>" class="img-fluid" style="width:100px!important; height:100px!important; padding: 10px!important;">
                            <p>Tgl: <?= !empty($pengajuan['approve']['sales']['created_at']) ? $pengajuan['approve']['sales']['created_at'] : 'Belum Disetujui' ?></p>
                          <?php } else { ?>
                            <p>Belum Disetujui</p>
                          <?php } ?>
                        </td>
                        <!-- Koor Sales -->
                        <td>
                          <?php if (!empty($pengajuan['approve']['ks']['ttd'])) { ?>
                            <img src="<?= base_url('assets/img/profile/ttd/') . $pengajuan['approve']['ks']['ttd'] ?>" class="img-fluid" style="width:100px!important; height:100px!important; padding: 10px!important;">
                            <p>Tgl: <?= !empty($pengajuan['approve']['ks']['created_at']) ? $pengajuan['approve']['ks']['created_at'] : 'Belum Disetujui' ?></p>
                          <?php } else { ?>
                            <p>Belum Disetujui</p>
                          <?php } ?>
                        </td>
                        <!-- HR -->
                        <td>
                          <?php if (!empty($pengajuan['approve']['hr']['ttd'])) { ?>
                            <img src="<?= base_url('assets/img/profile/ttd/') . $pengajuan['approve']['hr']['ttd'] ?>" class="img-fluid" style="width:100px!important; height:100px!important; padding: 10px!important;">
                            <p>Tgl: <?= !empty($pengajuan['approve']['hr']['created_at']) ? $pengajuan['approve']['hr']['created_at'] : 'Belum Disetujui' ?></p>
                          <?php } else { ?>
                            <p>Belum Disetujui</p>
                          <?php } ?>
                        </td>
                        <!-- HRD -->
                        <td>
                          <?php if (!empty($pengajuan['approve']['hrd']['ttd'])) { ?>
                            <img src="<?= base_url('assets/img/profile/ttd/') . $pengajuan['approve']['hrd']['ttd'] ?>" class="img-fluid" style="width:100px!important; height:100px!important; padding: 10px!important;">
                            <p>Tgl: <?= !empty($pengajuan['approve']['hrd']['created_at']) ? $pengajuan['approve']['hrd']['created_at'] : 'Belum Disetujui' ?></p>
                          <?php } else { ?>
                            <p>Belum Disetujui</p>
                          <?php } ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>


            <!-- Tombol Kebutuhan -->
            <div class="row mt-5">
              <input type="hidden" name="" id="url_pengajuan" value="<?= base_url('pengajuan') ?>">
              <div class="col-md-12 text-center">
                <a href="<?= base_url('pengajuan') ?>" class="btn btn-warning mr-3" data-dismiss="modal">Kembali</a>

                <!-- Admin dan Sales -->
                <?php if (in_array($user["role_id"], array(1)) || ($pengajuan['status'] == "PENDING" && in_array($user["role_id"], array(1, 2)))) { ?>
                  <a class="btn btn-warning mx-1" style="font-size:14px;" href="<?= site_url('pengajuan/edit/' . $pengajuan['id']); ?>"><i class="fas fa fa-pen"></i> Perbarui</a>
                <?php } ?>

                <!-- Admin, PM, dan PM Manager -->
                <?php if (in_array($user["role_id"], array(1)) || ($pengajuan['status'] == "PENDING" || $pengajuan['status'] == "PROCESS") && in_array($user["role_id"], array(3, 8))) { ?>
                  <a class="btn btn-info mx-1" style="font-size:14px;" href="<?= site_url('pengajuan/process/' . $pengajuan['id']); ?>"><i class="fas fa fa-file"></i> Update SKU</a>
                <?php } ?>

                <!-- Bukan Sales dan PM -->
                <?php if ($pengajuan['status'] == "PROCESS" && in_array($user["role_id"], array(4, 5, 6, 7, 8))) { ?>
                  <a class="btn btn-success mx-1" style="font-size:14px;" href="<?= site_url('pengajuan/approve/' . $pengajuan['id']); ?>"><i class="fas fa fa-check-double"></i> Approve</a>
                <?php } ?>

                <!-- Bukan Sales dan PM -->
                <?php if ($pengajuan['status'] == "PROCESS" && !in_array($user["role_id"], array(1, 2, 3, 8, 9, 10))) { ?>
                  <a class="btn btn-dark mx-1" style="font-size:14px;" href="<?= site_url('pengajuan/reject/' . $pengajuan['id']); ?>"><i class="fas fa fa-minus"></i> Tolak</a>
                <?php } ?>

                <!-- Admin -->
                <?php if ($pengajuan['status'] == "REJECTED" && $user["role_id"] == 1) { ?>
                  <a class="btn btn-secondary mx-1" style="font-size:14px;" href="<?= site_url('pengajuan/unreject/' . $pengajuan['id']); ?>"><i class="fas fa fa-recycle"></i> Batal Tolak</a>
                <?php } ?>

                <!-- Admin dan Sales -->
                <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(2)) && $pengajuan['status'] == "PENDING")) { ?>
                  <a class="btn btn-danger mx-1" style="font-size:14px;" href="#!" onclick="deleteConfirm('<?= site_url('pengajuan/delete/' . $pengajuan['id']); ?>')"><i class="fas fa fa-trash"></i> Hapus</a>
                <?php } ?>

                <!-- Admin dan Sales -->
                <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(1, 2, 4)))) { ?>
                  <a class="btn btn-secondary mx-1" style="font-size:14px;" target="_blank" href="<?= site_url('pengajuan/print/' . $pengajuan['id']); ?>"><i class="fas fa fa-print"></i> Cetak</a>
                <?php } ?>
              </div>
            </div>

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
        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin ?</h5 <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Apakah Anda yakin?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Data yang dihapus tidak dapat dipulihkan!</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
        <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
      </div>