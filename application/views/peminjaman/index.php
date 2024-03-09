<?php
$user = $this->session->userdata();
?>

<!-- Tambahkan link CSS DataTables -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.bootstrap4.min.css"> -->

<link href="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


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
    <div class="card-header">
      <div class="card-head-row">
        <div style="width: 100%;">
          <!-- <form class="navbar-left navbar-form mr-md-1" id="formFilter"> -->
          <div class="row">

            <div class="col-md-3">
              <div class="form-group">
                <label for="fTglAwal">Tanggal Awal</label>
                <input class="form-control datepicker" id="fTglAwal" type="date" name="fTglAwal" placeholder="Enter Start Date" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="fTglAkhir">Tanggal Akhir</label>
                <input class="form-control datepicker" id="fTglAkhir" type="date" name="fTglAkhir" placeholder="Enter End Date" />
              </div>
            </div>
            <?php if ($status == "ALL") { ?>
              <div class="col-md-3">
                <div class="form-group"><label for="fStatus">Status</label><select class="form-control" id="fStatus" name="fStatus">
                    <option value="ALL">All</option>
                    <option value="PENDING">Pending</option>
                    <option value="PROCESS">Proses</option>
                    <option value="SUCCESS">Sukses</option>
                    <option value="REJECTED">Di Tolak</option>
                  </select>
                </div>
              </div>
            <?php } ?>

            <?php if ($user["role_id"] != "2") { ?>
              <div class="col-md-3">
                <div class="form-group"><label for="fFrom">Dari</label><select class="form-control" id="fFrom" name="fFrom">
                    <option value="ALL">All</option>
                    <?php foreach ($cabangs as $cabang) { ?>
                      <!-- disini menggunakan id_area karena from itu relasi ke area -->
                      <option value="<?= $cabang['id_area'] ?>"><?= $cabang['nama_cabang'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group"><label for="fDirection">Tujuan </label><select class="form-control" id="fDirection" name="fDirection">
                    <option value="ALL">All</option>
                    <?php foreach ($cabangs as $cbg) { ?>
                      <option value="<?= $cbg['id_cabang'] ?>"><?= $cbg['nama_cabang'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?>
            <div class="col-md-3">
              <div class="pt-3">
                <button class="mt-3 btn btn-md btn-success mr-3" id="btn-submit" onclick="return filter()" type="submit">Submit </button>
                <?php if ($user["role_id"] == "1") { ?>
                  <button class="mt-3 btn btn-md btn-primary mr-3" id="btn-submit" onclick="return exportExcel()" type="submit">Export to Excel </button>
                <?php } ?>
              </div>
            </div>
          </div>
          <!-- </form> -->
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" id="peminjamanTable" width="100%" cellspacing="0">
          <thead class="thead-dark">
            <tr>
              <th>Action</th>
              <th>Kode Pengajuan</th>
              <th>Peminjam</th>
              <th>Kepada Dinas</th>
              <th>Peminjaman Dari</th>
              <th>Tujuan</th>
              <th>Tanggal Dibuat</th>
              <th>Closing Date</th>
              <th>Catatan</th>
              <th>Nomor SQ</th>
              <th>Status</th>
              <th>Keterangan Status</th>
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
<!-- Tambahkan link JavaScript DataTables dan DataTables Buttons -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>



<script>
  let dataTable;


  $(function() {
    let payload = {
      status: '<?= $status ?>'
    }
    renderTable($.param(payload))
  })

  function renderTable(filter) {
    let url = "<?php echo site_url('peminjaman/datatable') ?>";

    if (filter) url += '?' + filter
    dataTable = $('#peminjamanTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      paging: true,
      "ajax": url,
      "oLanguage": {
        "sSearch": "CARI KODE PENGAJUAN : "
      },
      "order": [
        [0, "desc"] // Set default sorting direction to "desc" for the first column
      ],
      "columns": [{
          "data": "action"
        },
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
          "data": "to_cb"
        },
        {
          "data": "date"
        },
        {
          "data": "closingdate"
        },
        {
          "data": "note",
          // "width": "550px"
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
      ],
    });
  };

  function filter() {
    let status = "<?= $status ?>"

    // yang boleh ada filter by status hanya di halaman index (default staus ALL)
    if (status == "ALL") {
      status = $("#fStatus").val()
    }
    let payload = {
      tgl_awal: $("#fTglAwal").val(),
      tgl_akhir: $("#fTglAkhir").val(),
      status: status,
      from: $("#fFrom").val(),
      direction: $("#fDirection").val()
    }

    dataTable.clear();
    dataTable.destroy();
    renderTable($.param(payload))
  }

  function exportExcel() {
    let status = "<?= $status ?>"
    if (status == "ALL") {
      status = $("#fStatus").val()
    }
    let payload = {
      tgl_awal: $("#fTglAwal").val(),
      tgl_akhir: $("#fTglAkhir").val(),
      status: status,
      from: $("#fFrom").val(),
      direction: $("#fDirection").val()
    }
    window.location.href = "export_excel?" + $.param(payload)
  }
</script>