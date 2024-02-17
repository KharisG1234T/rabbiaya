<?php
$user = $this->session->userdata();
?>

<!-- Tambahkan link CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">

<!-- Tambahkan link JavaScript DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": "<?php echo site_url('peminjaman/datatable') ?>",
      "columns": [
        {
          "data": "kode_pengajuan"
        },
        {
          "data": "name"
        },
        {
          "data": "dinas"
        },
        {
          "data": "from_cb"
        },
        {
          "data": "date"
        },
        {
          "data": "closingdate"
        },
        {
          "data": "note"
        },
        {
          "data": "nosq"
        },
        {
          "data": "status"
        },
        {
          "data": null,
          "render": function(data, type, row) {
            // Menggabungkan nilai dari kolom userApprovals dan keterangan_sku
            var combinedData = row.keterangan_sku + ' ' + row.userApprovals;

            // Mengembalikan nilai gabungan
            return combinedData;
          }
        },
        {
          "data": null,
          "render": function(data, type, row) {
            var idPeminjaman = row.id_peminjaman;

            // Menggabungkan nilai dari kolom userApprovals dan keterangan_sku
            var combinedData = ` <a class="badge badge-primary" style="font-size:14px;" href="<?= site_url('peminjaman/detail/${idPeminjaman}'); ?>"><i class="fas fa fa-eye"></i> Detail</a>`;

            // Mengembalikan nilai gabungan
            return combinedData;
          }
        },
        // { "data": "actions" }
      ]
    });
  });

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
          <tbody></tbody>
        </table>
      </div>
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