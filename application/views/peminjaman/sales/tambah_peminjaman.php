<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h3 class="">FORM PEMINJAMAN DATA PUSAT</h3>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-5">
            <form id="form" class="form-horizontal">
              <input type="hidden" value="<?= $this->session->userdata('id') ?>" name="userid" id="userid" />
              <input type="hidden" value="<?= $this->session->userdata('role_id') ?>" name="roleId" id="roleId" />
              <div class="form-group row ">
                <input type="hidden" name="kode_pengajuan" id="kode_pengajuan" value="">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <select class="form-control" id="direction" name="direction" require>
                      <option value="">Kepada Cabang ... </option>
                      <?php foreach ($cabangs as $cabang) { ?>
                        <option value="<?= $cabang['id_cabang'] ?>"><?= $cabang['nama_cabang'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" name="date" id="date" placeholder="Tanggal" readonly value="<?= date('d/m/Y') ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row ">
                <?php
                $roleId = $this->session->userdata('role_id');
                if ($roleId == "1") { ?>
                  <!-- super agemin -->
                  <input type="hidden" id="typeuser" value="SA">
                  <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                    <div class="kosong">
                      <select class="form-control" id="from" name="from" require>
                        <option value="">Dari Cabang ...</option>
                        <?php foreach ($cabangs as $cabang) { ?>
                          <option value="<?= $cabang['id_area'] ?>"><?= $cabang['nama_cabang'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                    <div class="kosong">
                      <select class="form-control" id="submitter" name="submitter" require>
                        <option value="">Peminjam...</option>
                      </select>
                    </div>
                  </div>
                 <?php  } else {
                  $area = $this->session->userdata('area');
                  $areaId = $area[0]["area_id"];
                ?>
                  <input type="hidden" class="form-control" name="from" value="<?= $areaId ?>" id="from" placeholder="Dari" require>
                <?php } ?> 
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <input type="text" class="form-control" name="number" id="number" placeholder="Nomor" readonly value="<?= date('m') ?>/PB/<?= date('y') ?>">
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
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td>Nomor</td>
                          <td>Nama Barang</td>
                          <td>Jumlah</td>
                          <td>Harga Satuan</td>
                          <td>Total Harga</td>
                          <td>Maks Delivery</td>
                          <td>Action</td>
                        </tr>
                      </thead>
                      <tbody id="dynamic">
                        <tr class="tb_row">
                          <td><label>1</label></td>
                          <td>
                            <textarea rows="3" cols="20" id="name-ikiuniqueyo" placeholder="Nama Barang" class="form-control" required></textarea>    
                            </td>
                          </td>
                          <td><input type="number" id="qty-ikiuniqueyo" placeholder="QTY" onkeyup="getTotalFromQty(this)" class="form-control" required /></td>
                          <td><input type="text" id="price-ikiuniqueyo" placeholder="Harga Satuan" onkeyup="getTotalFromPrice(this)" class="form-control" required /></td>
                          <td><input type="text" id="total-ikiuniqueyo" placeholder="Total" onchange="change()" readonly class="form-control" required /></td>
                          <td><input type="date" id="maks-ikiuniqueyo" placeholder="Maks Delivery" class="form-control date" required /></td>
                          <td><button type="button" id="tambah" class="btn btn- btn-success">Add <i class="fas fa-fw fa-plus"></i></button></td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4" class="text-center font-weight-bold">Total</td>
                          <td colspan="3" class="font-weight-bold text-center">Rp. <span id="total"></span> </td>
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
                    <input type="date" class="form-control" name="closingdate" id="closingdate" placeholder="Tanggal maksimal closing" required>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='note'>Note</label>
                    <input type="text" class="form-control" name="note" id="note" placeholder="catatan" required>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='dinas'>Dinas</label>
                    <input type="text" class="form-control" name="dinas" id="dinas" placeholder="nama dinas" required>
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="kosong">
                    <label for='lokasi'>Lokasi</label>
                    <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="lokasi dinas" required>
                  </div>
                </div>
              </div>
              <div class="form-group row mt-5">
                <input type="hidden" name="" id="url_peminjaman" value="<?= base_url('peminjaman') ?>">
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-danger ml-auto mr-3" data-dismiss="modal">Cancel</a>
                <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
              </div>

            </form>
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
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
  let uniqIds = ["ikiuniqueyo"]; //one default iniquid

  function createRandomString(length) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  // DELETE WHITE SPACE ON THE TEXTAREA
  function deleteWhiteSpace(uniqId) {
    var textarea = document.getElementById(uniqId);
    var text = textarea.value;
    text = text.replace(/\s+/g, ''); // Menghapus semua whitespace
    textarea.value = text;
  }

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
      url: `userdropdown/${areaId}`,
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

  // set total
  function change() {
    let total = 0;
    uniqIds.forEach((uid, i) => {
      let data = parseInt(($(`#total-${uid}`).val()).replace(/[^0-9]/g, ""))
      if (total !== 0) {
        total = total.replace(/[.]/g, "")
      }
      total = parseInt(total) + parseInt(data ? data : 0);
      total = formatRupiah(total.toString());

      $(`#no-${uid}`).text(`${i + 1}`)
    })

    $('#total').text(total)
  }

  // change text of number in list table
  function changeNumber() {
    uniqIds.forEach((uid, i) => {
      $(`#no-${uid}`).text(`${i + 1}`)
    })
  }

  function getTotalFromQty(e) {
    const uid = e.id.replace(/qty-/, "")
    const qty = e.value;
    const price = $(`#price-${uid}`).val().replace(/[^0-9]/g, "");
    const total = parseInt((qty ? qty : 0) * parseInt(price ? price : 0))
    $(`#total-${uid}`).val(formatRupiah(total.toString(), "Rp. "))
    change()
  }

  function getTotalFromPrice(e) {
    const uid = e.id.replace(/price-/, "")
    const price = e.value.replace(/[^0-9]/g, "")
    $(`#${e.id}`).val(formatRupiah(price, "Rp. "))
    const qty = $(`#qty-${uid}`).val();
    const total = parseInt((qty ? qty : 0) * parseInt(price ? price : 0))
    $(`#total-${uid}`).val(formatRupiah(total.toString(), "Rp. "))
    change()
  }

  $(document).ready(function() {

    $('#tambah').click(function() {
      let no = uniqIds.length + 1

      let unique = createRandomString(10);
      uniqIds = [...uniqIds, unique] // save every uniq id into array to get value input in the looping calculation

      $('#dynamic').append(`
        <tr id="row-${unique}" class="tb_row"> 
          <td><label id="no-${unique}">${no}</label></td>  
          <td><textarea rows="3" cols="20" id="name-${unique}" placeholder="Nama Barang" class="form-control" required></textarea></td> 
          <td><input type="number" placeholder="QTY" id="qty-${unique}" onkeyup="getTotalFromQty(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Harga Satuan" id="price-${unique}"  onkeyup="getTotalFromPrice(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Total" id="total-${unique}" onchange="change()" readonly class="form-control" required /></td> 
          <td><input type="date" placeholder="Maks Delivery" id="maks-${unique}" class="form-control date" required /></td> 
          <td> <button type="button" id="${unique}" class="btn btn-danger btn_remove">Hapus</button></td>
        </tr>`);

      // update NO of list
      changeNumber()
    });

    $(document).on('click', '.btn_remove', function() {
      var button_id = $(this).attr("id");
      $('#row-' + button_id + '').remove();
      uniqIds = uniqIds.filter((uid) => uid != button_id)

      change() // update total price
      changeNumber() // update number of list
    });


    $('#form').submit(function(e) {
      e.preventDefault()
      let barang = []

      uniqIds.forEach((uid) => {
        let name = $(`#name-${uid}`).val()
        let qty = $(`#qty-${uid}`).val()
        let price = parseInt(($(`#price-${uid}`).val()).replace(/[^0-9]/g, ""))
        let total = parseInt(($(`#total-${uid}`).val()).replace(/[^0-9]/g, ""))
        let maks = $(`#maks-${uid}`).val()
        barang = [...barang, {
          name,
          qty,
          price,
          total,
          maks
        }]
      })

      const direction = $('#direction').val();
      const userId = $('#roleId').val() == "1" ? $('#submitter').val() : null; // kalau admin dia ambil id dari input submitter
      const date = $('#date').val()
      const from = $('#roleId').val() == "1" ? $('#from').val() : null;
      const number = $('#number').val()
      const closingDate = $('#closingdate').val()
      const note = $('#note').val()
      const dinas = $('#dinas').val()
      const lokasi = $('#lokasi').val()

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
        barang
      }

      $.ajax({
          method: 'POST',
          cache: false,
          data: payload,
          url: 'insert',
        })
        .done(function(data) {
          const redirectUrl = $('#url_peminjaman').val()
          window.location = `${redirectUrl}`;
        })
    })
  });
</script>