<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h3 class="">FORM PENGAJUAN RAB BIAYA PERJALANAN</h3>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-5">
            <form id="form" class="form-horizontal">
              <input type="hidden" value="<?= $this->session->userdata('id') ?>" name="userid" id="userid" />
              <input type="hidden" value="<?= $this->session->userdata('role_id') ?>" name="roleId" id="roleId" />
              <div class="form-group row ">
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="form-group">
                    <label for="departure_date">Tanggal Pengajuan</label>
                    <input type="date" class="form-control" name="request_date" id="request_date" placeholder="Tanggal Pengajuan">
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="form-group">
                    <label for="departure_date">Tanggal Berangkat</label>
                    <input type="date" class="form-control" name="departure_date" id="departure_date" placeholder="Tanggal Berangkat">
                  </div>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-4 col-lg-4">
                  <div class="form-group">
                    <label for="destination">Tujuan</label>
                    <input type="text" class="form-control" name="destination" id="destination" placeholder="Tujuan">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col col-20 ml-auto">
                  <p>Pengajuan RAB Biaya Perjalanan kepada Solusi Arya Prima Pusat Dengan Rincian :</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12 mr-auto">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td>Nomor</td>
                          <td>Kegiatan / Aktivitas</td>
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
                          <td><select class="form-control" id="activity-ikiuniqueyo" required></select>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>
<script>
  $(function () {
      $("#request_date").val(moment().startOf("days").format("YYYY-MM-DD"));
      $("#departure_date").val(moment().endOf("days").format("YYYY-MM-DD"));
      getActivity(`activity-ikiuniqueyo`)
    });
    
  let uniqIds = ["ikiuniqueyo"]; //one default iniquid

  function createRandomString(length) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
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
      .append('<option value="">Pengajuan dari...</option>')

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
          <td>
            <select class="form-control" id="activity-${unique}">
            </select>
          </td> 
          <td><input type="number" placeholder="QTY" id="qty-${unique}" onkeyup="getTotalFromQty(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Harga Satuan" id="price-${unique}"  onkeyup="getTotalFromPrice(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Total" id="total-${unique}" onchange="change()" readonly class="form-control" required /></td> 
          <td><input type="date" placeholder="Maks Delivery" id="maks-${unique}" class="form-control date" required /></td> 
          <td> <button type="button" id="${unique}" class="btn btn-danger btn_remove">Hapus</button></td>
        </tr>`);

      // update NO of list
      changeNumber()

      // get master data
      getActivity(`activity-${unique}`);
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

  // API GET MASTER AKTIFITAS AND ADD NEW INPUT OPTION INTO DYNAMIC FORM
  function getActivity(uniqueId) {
    $.ajax({
      url: "master_activity",
      method: "GET",
      headers: {
          'Content-Type' : 'application/json'
      },
      success: function(res) {
        res = JSON.parse(res);
        let data = res.data;
        let selectElm = $(`#${uniqueId}`);
        if(res.status == 200 && data.length) {
          data.forEach((item) => {
            let option = $('<option></option>').attr("value", item.id).text(item.name);
            selectElm.append(option);
          });
        }
      },
      error: function(err) {
        console.log("error fetch master activity :", err);
      }
    })
  }
</script>