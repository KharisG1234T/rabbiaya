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
              <input type="hidden" id="userid" value="<?= $peminjaman["id_user"] ?>">
              <input type="hidden" value="<?= $this->session->userdata('role_id') ?>" name="roleId" id="roleId" />
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">                    
                    <input type="text" class="form-control" name="kepada" id="kepada" placeholder="kepada" readonly value="<?= $peminjaman['nama_cabang'] ?>">
                  </div>
                </div>

                <input type="hidden" class="form-control" name="direction" id="direction" placeholder="direction" readonly value="<?= $peminjaman['id_cabang'] ?>">
                
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" placeholder="Tanggal" readonly value="<?= date_format(date_create($peminjaman['date']), 'd/m/Y') ?>">
                    <input type="hidden" class="form-control" name="date" id="date" placeholder="Tanggal" readonly value="<?= $peminjaman['date'] ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row ">
                <?php
                $roleId = $this->session->userdata('role_id');
                if ($roleId == "1") { ?>
                  <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                    <div class="kosong">
                      <select class="form-control" id="from" name="from" require readonly>
                        <option value="">Dari Cabang ...</option>
                        <?php foreach ($cabangs as $cabang) { ?>
                          <option value="<?= $cabang['id_area'] ?>" <?php if ($cabang['id_area'] == $peminjaman['from']) echo ('selected') ?>><?= $cabang['nama_cabang'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                    <div class="kosong">
                      <select class="form-control" id="submitter" name="submitter" require>
                        <option value="<?= $peminjaman["id_user"] ?>"><?= $peminjaman["name"] ?></option>

                      </select>
                    </div>
                  </div>
                <?php  } else {
                  $area = $this->session->userdata('area');
                  $areaId = $area[0]["area_id"];
                ?>
                  <input type="hidden" class="form-control" name="from" value="<?= $areaId ?>" id="from" placeholder="Dari" require readonly>
                <?php } ?>
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
                    <input type="hidden" readonly data-barang='<?= json_encode($peminjaman['barangpeminjaman']) ?>' id="barangpeminjaman">
                    <table class="table table-bordered" id="dynamic">
                      <thead>
                        <tr>
                          <td>Nomor</td>
                          <td>SKU</td>                         
                          <td>Nama Barang</td>
                          <td>Jumlah</td>
                          <td>Harga Satuan</td>
                          <td>Total Harga</td>
                          <td>Stok/PO</td>
                          <td>Maks Delivery</td>
                          
                        </tr>
                      </thead>
                      <tbody id="dynamic">
                      </tbody>
                      <tfoot>
                        <tr>                         
                          <td colspan="5" class="text-center font-weight-bold">Total</td>
                          
                          <td class="font-weight-bold">Rp. <span id="total"></span> </td>
                          <td colspan="3"></td>
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
                    <input type="date" class="form-control" name="closingdate" id="closingdate" placeholder="Tanggal maksimal closing" required readonly value="<?= $peminjaman['closingdate'] ?>">
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='note'>Note</label>
                    <input type="text" class="form-control" name="note" id="note" placeholder="catatan" required readonly value="<?= $peminjaman['note'] ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row">
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='dinas'>Dinas</label>
                    <input type="text" class="form-control" name="dinas" id="dinas" placeholder="nama dinas" required readonly value="<?= $peminjaman['dinas'] ?>">
                  </div>
              </div>
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='lokasi'>Lokasi</label>
                    <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="lokasi dinas" required readonly value="<?= $peminjaman['lokasi'] ?>">
                  </div>
              </div>
              </div>
              <div class="form-group row">
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='nosq'>Nomor SQ</label>
                    <input type="text" class="form-control" name="nosq" id="nosq" placeholder="nomor SQ" required readonly value="<?= $peminjaman['nosq'] ?>">
                  </div>
              </div>
              <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='nopo'>Nomor PO</label>
                    <input type="text" class="form-control" name="nopo" id="nopo" placeholder="nomor PO" required value="<?= $peminjaman['nopo'] ?>">
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
  function formatRupiah(angka = 0, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }

  let areaId = "";
  $('#from').on('change', function() {
    areaId = this.value;

    // clear select box option
    $('#submitter')
      .empty()
      .append('<option value="">Peminjam...</option>')

    $.ajax({
      url: `../userdropdown/${areaId}`,
      method: 'GET',
      cache: false,
      success: function(data) {
        $.each(JSON.parse(data), function(i, item) {
          $('#submitter').append($('<option>', {
            value: item.id,
            text: item.name
          }));
        });
      }
    })

  });
  // set total step two
  function change() {
    let total = 0;
    const tbRow = document.getElementsByClassName("tb_row");
    for (let i = 1; i <= tbRow.length; i++) {
      let data = parseInt(($(`#total${i}`).val()).replace(/[^0-9]/g, ""))
      if (total !== 0) {
        total = total.replace(/[.]/g, "")
      }
      total = parseInt(total) + parseInt(data ? data : 0);
      total = formatRupiah(total.toString());
    }
    $('#total').text(total)
  }

  function getTotalFromQty(e) {
    const index = e.id.replace(/qty/, "")
    const qty = e.value;
    const price = $(`#price${index}`).val().replace(/[^0-9]/g, "");
    const total = parseInt((qty ? qty : 0) * parseInt(price ? price : 0))
    $(`#total${index}`).val(formatRupiah(total.toString(), "Rp. "))
    change()
  }

  function getTotalFromPrice(e) {
    const index = e.id.replace(/price/, "")
    const price = e.value.replace(/[^0-9]/g, "")
    $(`#${e.id}`).val(formatRupiah(price, "Rp. "))
    const qty = $(`#qty${index}`).val();
    const total = parseInt((qty ? qty : 0) * parseInt(price ? price : 0))
    $(`#total${index}`).val(formatRupiah(total.toString(), "Rp. "))
    change()
  }

  $(document).ready(function() {

    var no = 1;

    const barangPeminjaman = $('#barangpeminjaman');
    const barangs = barangPeminjaman.data('barang');

    // show barang
    barangs.forEach((item, no) => {
      no = no + 1
      $('#dynamic').append(`
        <tr id="row${no}" class="tb_row">
          <td><label>No.${no}</label></td> 
          <td><input type="text" id="sku${no}" placeholder="SKU" readonly class="form-control" value="${item.sku}" required /></td> 
          <td><input type="text" id="name${no}" placeholder="Nama Barang" readonly class="form-control" value="${item.nama}" required />
          </td> <td><input type="number" placeholder="QTY" id="qty${no}" onkeyup="getTotalFromQty(this)" class="form-control" readonly value="${item.qty}" required /></td> 
          <td><input type="text" placeholder="Harga Satuan" id="price${no}" onkeyup="getTotalFromPrice(this)" class="form-control" readonly value="${formatRupiah(item.harga, "Rp. ")}")}" required /></td> 
          <td><input type="text" placeholder="Total" id="total${no}" onchange="change()" readonly class="form-control" value="${formatRupiah(item.jumlah, "Rp. ")}" required /></td> 
          <td><input type="text" id="stok_po${no}" placeholder="Stok/PO" readonly class="form-control" value="${item.stok_po}" required /></td> 
          <td><input type="date" placeholder="Maks Delivery" id="maks${no}" class="form-control date" readonly value="${item.maks_delivery}" required /></td> 
        </tr>`);
    })

    // add barang
    no = barangs.length;
    $('#tambah').click(function() {
      no++;
      $('#dynamic').append(`
        <tr id="row${no}" class="tb_row"> 
          <td><label>No.${no}</label></td>  
          <td><input type="text" id="sku${no}" placeholder="SKU" readonly class="form-control" required /></td>          
          <td><input type="text" id="name${no}" placeholder="Nama Barang" class="form-control" required /></td> 
          <td><input type="number" placeholder="QTY" id="qty${no}" onkeyup="getTotalFromQty(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Harga Satuan" id="price${no}" onkeyup="getTotalFromPrice(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Total" id="total${no}" onchange="change()" readonly class="form-control" required /></td> 
          <td><input type="text" id="stok_po${no}" placeholder="Stok/PO" readonly class="form-control" required /></td> 
          <td><input type="date" placeholder="Maks Delivery" id="maks${no}" class="form-control date" required /></td> 
        </tr>`);
    });

    // set total step one
    let total = 0;
    const tbRow = document.getElementsByClassName("tb_row");
    for (let i = 1; i <= tbRow.length; i++) {
      let data = parseInt(($(`#total${i}`).val()).replace(/[^0-9]/g, ""))
      total = parseInt(total) + parseInt(data ? data : 0);
      total = formatRupiah(total.toString());

    }
    $('#total').text(total)


    // remote barang
    $(document).on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#row' + button_id + '').remove();
      no--;
      change()
    });


    // submit update data
    $('#form').submit(function(e) {
      e.preventDefault()
      let barang = []

      for (let i = 1; i <= no; i++) {
        let sku = $(`#sku${i}`).val()       
        let name = $(`#name${i}`).val()
        let qty = $(`#qty${i}`).val()
        let price =parseInt(($(`#price${i}`).val()).replace(/[^0-9]/g, ""))
        let total = parseInt(($(`#total${i}`).val()).replace(/[^0-9]/g, ""))
        let stok_po = $(`#stok_po${i}`).val()
        let maks = $(`#maks${i}`).val()
        barang = [...barang, {
          sku,
          name,
          qty,
          price,
          total,
          stok_po,
          maks
        }]
      }

      const direction = $('#direction').val();
      const userId = $('#roleId').val() == 1 ? $('#submitter').val() : $('#userid').val(); // kalau admin dia ambil id dari input submitter
      const date = $('#date').val()
      const from = $('#from').val()
      const number = $('#number').val()
      const closingDate = $('#closingdate').val()
      const note = $('#note').val()
      const dinas = $('#dinas').val()
      const lokasi = $('#lokasi').val()
      const nosq = $('#nosq').val()
      const nopo = $('#nopo').val()
      const id = $('#id').val()

      const payload = {
        direction,
        userId,
        date,
        from,
        number,
        closingDate,
        note,
        dinas,
        lokasi,
        nosq,
        nopo,
        barang,
        id
      }

      $.ajax({
          method: 'POST',
          cache: false,
          data: payload,
          url: '../update',
        })
        .done(function(data) {
          const redirectUrl = $('#url_peminjaman').val()
          window.location = `${redirectUrl}`;
        })
    })
  });
</script>