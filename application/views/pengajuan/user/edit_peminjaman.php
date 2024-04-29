<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h3 class="">FORM EDIT PEMINJAMAN DATA PUSAT</h3>
              <p class="font-weight-bold">Kode Pengajuan : <?= $peminjaman['kode_pengajuan'] ?></p>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-5">
            <form id="form" class="form-horizontal">
              <input type="hidden" name="id" id="id" value="<?= $peminjaman['id_peminjaman'] ?>">
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <select class="form-control" id="direction" name="direction" required readonly>
                      <option value="">Pilih Cabang ...</option>
                      <?php foreach ($cabangs as $cabang) { ?>
                        <option value="<?= $cabang['id_cabang'] ?>" <?php if ($cabang['id_cabang'] == $peminjaman['id_cabang']) echo ('selected') ?>> <?= $cabang['nama_cabang'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" placeholder="Tanggal" readonly value="<?= date_format(date_create($peminjaman['date']), 'd/m/Y') ?>">
                    <input type="hidden" class="form-control" name="date" id="date" placeholder="Tanggal" readonly value="<?= $peminjaman['date'] ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" name="from" id="from" placeholder="Dari" required value="<?= $peminjaman['from'] ?>" readonly>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" name="number" id="number" placeholder="Nomor" readonly value="<?= $peminjaman['number'] ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-10 ml-auto">
                  <p>Dengan ini mengajukan permohonan pemakaian stock barang dari CV. Solusi Arya Prima Pusat berupa :</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12 mr-auto">
                  <div class="table-responsive">
                    <!-- data barang -->
                    <input type="hidden" data-barang='<?= json_encode($peminjaman['barangpeminjaman']) ?>' id="barangpeminjaman">
                    <table class="table table-bordered" id="dynamic">
                      <thead>
                        <tr>
                          <td>Nomor</td>
                          <td>SKU</td>
                          <td>Stok/Po</td>
                          <td>Nama Barang</td>
                          <td>Jumlah</td>
                          <td>Harga Satuan</td>
                          <td>Total Harga</td>
                          <td>Maks Delivery</td>
                        </tr>
                      </thead>
                      <tbody id="dynamic">

                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4" class="text-center font-weight-bold">Total</td>
                          <td colspan="4" class="font-weight-bold text-center">Rp. <span id="total"></span> </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='closingdate'>Tanggal closing</label>
                    <input type="date" class="form-control" name="closingdate" id="closingdate" placeholder="Tanggal maksimal closing" required value="<?= $peminjaman['closingdate'] ?>" readonly>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='note'>Note</label>
                    <input type="text" class="form-control" name="note" id="note" placeholder="catatan" required value="<?= $peminjaman['note'] ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="form-group row">
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='dinas'>Dinas</label>
                    <input type="text" class="form-control" name="dinas" id="dinas" placeholder="nama dinas" required value="<?= $peminjaman['dinas'] ?>" readonly>
                  </div>
              </div>
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='lokasi'>Lokasi</label>
                    <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="lokasi dinas" required value="<?= $peminjaman['lokasi'] ?>" readonly>
                  </div>
              </div>
            </div>
              <div class="form-group row mt-5">
                <input type="hidden" name="" id="url_peminjaman" value="<?= base_url('peminjaman') ?>">
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-danger ml-auto mr-3" data-dismiss="modal">Cancel</a>
                <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
              </div>
              <!-- /.card-body -->
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
 $(document).ready(function() {
  var no = 1;

  const barangPeminjaman = $('#barangpeminjaman');
  const barangs = barangPeminjaman.data('barang');

  // Show barang
  barangs.forEach((item, no) => {
    no = no + 1
    $('#dynamic').append(`
      <tr id="row${no}" class="tb_row">
        <td><label>${no}</label></td>
        <input type="hidden" id="id${no}" value="${item.id_bp}" required />
        <td><input type="text" id="sku${no}" placeholder="SKU" value="${item.sku}" class="form-control" /></td>
        <td><input type="text" id="stokpo${no}" placeholder="Stok/PO" value="${item.stok_po}" class="form-control" /></td>
        <td><input type="text" id="name${no}" placeholder="Nama Barang" class="form-control" value="${item.nama}" required readonly /></td>
        <td><input type="number" placeholder="QTY" id="qty${no}" onchange="getTotalFromQty(this)" class="form-control" value="${item.qty}" required readonly /></td>
        <td><input type="number" placeholder="Harga Satuan" id="price${no}" onchange="getTotalFromPrice(this)" class="form-control" value="${item.harga}" required readonly /></td>
        <td><input type="number" placeholder="Total" id="total${no}" onchange="change()" class="form-control" value="${item.jumlah}" required readonly /></td>
        <td><input type="date" placeholder="Maks Delivery" id="maks${no}" class="form-control date" value="${item.maks_delivery}" required readonly /></td>
      </tr>
    `);
  });

  // Set total step one
  let total = 0;
  const tbRow = document.getElementsByClassName("tb_row");
  for (let i = 1; i <= tbRow.length; i++) {
    let data = $(`#total${i}`).val();
    total = total + parseInt(data ? data : 0);
  }
  $('#total').text(total);

  // Submit update data
  $('#form').submit(function(e) {
    e.preventDefault();
    let barang = [];

    for (let i = 1; i <= barangs.length; i++) {
      let id = $(`#id${i}`).val();
      let sku = $(`#sku${i}`).val();
      let stokpo = $(`#stokpo${i}`).val();
      let name = $(`#name${i}`).val();
      let qty = $(`#qty${i}`).val() || 0;
      let price = $(`#price${i}`).val() || 0;
      let total = $(`#total${i}`).val() || 0;
      let maks = $(`#maks${i}`).val();

      // Replace SKU and Stok/PO with "N/A" if empty
      if (!sku) {
        sku = "N/A";
      }
      if (!stokpo) {
        stokpo = "N/A";
      }

      barang.push({
        id,
        sku,
        stokpo,
        name,
        qty,
        price,
        total,
        maks
      });
    }
    const id = $('#id').val();

    const payload = {
      barang,
      id
    };

    $.ajax({
      method: 'POST',
      cache: false,
      data: payload,
      url: '../setstatus',
    }).done(function(data) {
      const redirectUrl = $('#url_peminjaman').val();
      window.location = `${redirectUrl}`;
    });
  });
});
</script>