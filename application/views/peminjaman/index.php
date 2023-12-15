<?php
$user = $this->session->userdata();
?>


<!-- Tambahkan link CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">

<!-- Tambahkan link JavaScript DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#dataTable').DataTable({
      "order": [[1, "desc"]] // Urutkan berdasarkan kolom ke-5 (tanggal) secara descending (terbaru ke yang lama)
    });
  });
</script>

<script>
  function deleteConfirm(url) {
    $('#btn-delete').attr('href', url);
    $('#deleteModal').modal();
  }
</script>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

  <?php if ($this->session->flashdata('message')) { ?>
    <?= $this->session->flashdata('message'); ?>
  <?php } ?>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left"><?= $title; ?></h6>
      <h6 class="m-0 font-weight-bold text-primary float-right">
        <?php if (in_array($user["role_id"], array(1, 2))) { ?>
          <a href="<?= site_url('/peminjaman/add') ?>"><i class="fas fa-plus"></i> Tambah Peminjaman</a>
        <?php } ?>
      </h6>
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Pengajuan</th>
                    <th>Peminjam</th>
                    <th>Kepada Dinas</th>
                    <th>Peminjaman Dari</th>
                    <th>Tanggal Dibuat</th>
                    <th>Closing Date</th>
                    <th>Catatan</th>
                    <th>Nomor SQ</th>
                    <th>Status</th>
                    <th>Keterangan Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($peminjaman) > 0) { ?>
                    <?php foreach ($peminjaman as $key => $item) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $item['kode_pengajuan']; ?></td>
                            <td><?= $item['name']; ?></td>
                            <td><?= $item['dinas']; ?></td>
                            <td><?= $item['from_cb']; ?></td>
                            <td><?= $item['date']; ?></td>
                            <td><?= $item['closingdate']; ?></td>
                            <td><?= $item['note']; ?></td>
                            <td><?= $item['nosq']; ?></td>
                            <td>
                                <?php if ($item['status'] == "PENDING") echo ('<p class="badge badge-warning">' . $item['status'] . '</p>');
                                else if ($item['status'] == "PROCESS") echo ('<p class="badge badge-primary">' . $item['status'] . '</p>');
                                else if ($item['status'] == "SUCCESS") echo ('<p class="badge badge-success">' . $item['status'] . '</p>');
                                else echo ('<p class="badge badge-danger">' . $item['status'] . '</p>');
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($item['status'] == "PENDING") {
                                    echo 'PM Belum Mengisi SKU';
                                } elseif ($item['status'] == "PROCESS") {
                                    echo $item['keterangan_sku'];
                                } elseif ($item['status'] == "SUCCESS") {
                                    echo 'Pengajuan Selesai, Telah di Setujui Oleh : ';
                                } else {
                                    echo '';
                                }
                                ?>
                                
                                <!-- KETERANGAN LOGIC PROSES SIAPA YANG SUDAH APPROVE -->
                                <?php
                                $userApprovalData = $this->Peminjaman_model->getUserApprovalByPeminjamanId($item['id_peminjaman']);
                                if (!empty($userApprovalData)) {
                                    $userApprovals = [];
                                    foreach ($userApprovalData as $userApproval) {
                                        $useracc = $this->Userapproval_model->getUserById($userApproval['id_user']);
                                        $status = $userApproval['status'];

                                        // Tambahkan logika untuk menentukan warna badge berdasarkan status
                                        if (in_array($useracc["role_id"], [3, 4, 5, 6, 7, 8])) {
                                            if ($status == 'APPROVE') {
                                                $userApprovals[] = '<span class="badge badge-success">' . $useracc['name'] . '</span>';
                                            } elseif ($status == 'REJECT') {
                                                $userApprovals[] = '<span class="badge badge-danger">' . $useracc['name'] . '</span>';
                                            }
                                        }
                                    }

                                    if (!empty($userApprovals)) {
                                        echo implode(' ', $userApprovals);
                                    }
                                }
                         
                                ?>
                            </td>




                            <td>
                                <!-- public -->
                                <a class="badge badge-primary" style="font-size:14px;" href="<?= site_url('peminjaman/detail/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-eye"></i> Detail</a>
                                <!-- admin, sales -->
                                <?php if (in_array($user["role_id"], array(1)) || ($item['status'] == "PENDING" && in_array($user["role_id"], array(1, 2)))) { ?>
                                    <a class="badge badge-warning" style="font-size:14px;" href="<?= site_url('peminjaman/edit/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-pen"></i> Perbarui</a>
                                <?php } ?>
                                <!-- admin, pm, pm manager -->
                                <?php if (in_array($user["role_id"], array(1)) || ($item['status'] == "PENDING" || $item['status'] == "PROCESS") && in_array($user["role_id"], array(3, 8))) { ?>
                                    <a class="badge badge-info" style="font-size:14px;" href="<?= site_url('peminjaman/process/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-file"></i> Update SKU</a>
                                <?php } ?>
                                <!-- admin, cs -->
                                <?php if (in_array($user["role_id"], array(1, 9))) { ?>
                                <a class="badge badge-info" style="font-size:14px;" href="<?= site_url('peminjaman/editcs/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-tags"></i> Update No SQ</a>
                                <?php } ?>
                                <!-- admin, purchasing -->
                                <?php if (in_array($user["role_id"], array(1, 10))) { ?>
                                <a class="badge badge-info" style="font-size:14px;" href="<?= site_url('peminjaman/editpurc/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-tags"></i> Update No PO</a>
                                <?php } ?>
                                <!-- is not sales and pm -->
                                <?php if ($item['status'] == "PROCESS" && in_array($user["role_id"], array(4,5,6,7,8))) { ?>
                                    <a class="badge badge-success" style="font-size:14px;" href="<?= site_url('peminjaman/approve/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-check-double"></i> Approve</a>
                                <?php } ?>
                                <!-- is not sales and pm -->
                                <?php if ($item['status'] == "PROCESS" && !in_array($user["role_id"], array(1, 2, 3, 8, 9, 10))) { ?>
                                    <a class="badge badge-dark" style="font-size:14px;" href="<?= site_url('peminjaman/reject/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-minus"></i> Tolak</a>
                                <?php } ?>
                                <!-- is admin -->
                                <?php if ($item['status'] == "REJECTED" && $user["role_id"] == 1) { ?>
                                    <a class="badge badge-secondary" style="font-size:14px;" href="<?= site_url('peminjaman/unreject/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-recycle"></i> Batal Tolak</a>
                                <?php } ?>
                                <!-- admin, sales -->
                                <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(2)) && $item['status'] == "PENDING")) { ?>
                                    <a class="badge badge-danger" style="font-size:14px;" href="#!" onclick="deleteConfirm('<?= site_url('peminjaman/delete/' . $item['id_peminjaman']); ?>')"><i class="fas fa fa-trash"></i> Hapus</a>
                                <?php } ?>
                                <!-- admin, sales -->
                                <?php if (in_array($user["role_id"], array(1)) || (in_array($user["role_id"], array(1, 2, 4)))) { ?>
                                    <a class="badge badge-secondary" style="font-size:14px;" target="_blank" href="<?= site_url('peminjaman/print/' . $item['id_peminjaman']); ?>"><i class="fas fa fa-print"></i> Cetak</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="10" class="text-center">Data Peminjaman Masih Kosong</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

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