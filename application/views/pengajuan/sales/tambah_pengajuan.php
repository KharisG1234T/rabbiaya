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
              <input type="hidden" value="<?= $this->session->userdata('area_id') ?>" name="area_id" id="areaid" />
              <input type="hidden" name="kode_pengajuan" id="kode_pengajuan" value="">
              <div class="form-group row">
                  <div class="col col-sm-4 col-md-2 col-lg-2">
                      <div class="form-group">
                          <label for="request_date">Tanggal Pengajuan RAB</label>
                          <input type="date" class="form-control" name="request_date" id="request_date" placeholder="Tanggal Pengajuan" required readonly>
                      </div>
                  </div>
                  <div class="col col-sm-4 col-md-2 col-lg-2">
                      <div class="form-group">
                          <label for="departure_date">Tanggal Berangkat</label>
                          <input type="date" class="form-control" name="departure_date" id="departure_date" placeholder="Tanggal Berangkat" required>
                      </div>
                  </div>
                  <div class="col col-sm-4 col-md-4 col-lg-4">
                      <div class="form-group">
                          <label for="destination">Tujuan Perjalanan</label>
                          <input type="text" class="form-control" name="destination" id="destination" placeholder="Contoh : Jakarta" required>
                      </div>
                  </div>
                  <div class="col col-sm-4 col-md-4 col-lg-4">
                      <div class="form-group">
                          <label for="destination">Jenis Agenda</label>
                          <input type="text" class="form-control" name="title" id="title" placeholder="Contoh : Visit Dinas Mingguan" required>
                      </div>
                  </div>
              </div>
              <!-- SECTION TRIP DETAIL -->
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
                          <td>Deskripsi</td>
                          <td>Makan</td>
                          <td>QTY</td>
                          <td>Jumlah Hari</td>
                          <td>Unit Price (IDR)</td>
                          <td>Total Harga</td>
                          <td>Action</td>
                        </tr>
                      </thead>
                      <tbody id="official-trip-detail-table">
                        <tr class="tb_row">
                          <td><label>1</label></td>
                          <td><select class="form-control" id="activity-ikiuniqueyo" onchange="setFoodOption(this, 'ikiuniqueyo')" required></select>
                          <td>
                            <textarea rows="3" cols="20" id="description-ikiuniqueyo" class="form-control" placeholder="Deskripsi" required>
                            </textarea>
                          </td>
                          <td>
                            <select class="form-control" id="is-food-ikiuniqueyo" readonly disabled>
                              <option value="NO">Tidak</option>
                              <option value="YES">Ya</option>
                            </select>
                          </td>
                          <td><input type="number" placeholder="QTY" id="qty-ikiuniqueyo" min="1" value="1" onkeyup="getTotalFromQty(this)" class="form-control" required /></td>
                          <td><input type="number" placeholder="Jumlah Hari" id="duration-ikiuniqueyo" min="1" value="1" onkeyup="getTotalFromDuration(this)" class="form-control" required /></td>
                          <td><input type="text" placeholder="Unit Price (IDR)" id="amount-ikiuniqueyo" onkeyup="getTotalFromAmount(this)" class="form-control" required /></td>
                          <td><input type="text" placeholder="Total Price (IDR)" id="total-amount-ikiuniqueyo" disabled readonly class="form-control" required /></td>
                          <td><button type="button" id="add-trip-detail" class="btn btn- btn-success">Add <i class="fas fa-fw fa-plus"></i></button></td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="5" class="text-center font-weight-bold">Total</td>
                          <td colspan="4" class="font-weight-bold text-center">Rp. <span id="total"></span> </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <!-- SECTION TRIP DESTINATION -->
              <div class="form-group row">
                <div class="col col-20 ml-auto">
                  <p>Rincian Agenda Visit Dinas :</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-10 mr-auto">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <td>Nomor</td>
                          <td>Nama Dinas</td>
                          <td>Kota / Kabupaten</td>
                          <td>Nomor Tiket</td>
                          <td>Keterangan</td>
                          <td>Action</td>
                        </tr>
                      </thead>
                      <tbody id="trip-destination-table">
                        <tr class="tb_row">
                          <td><label>1</label></td>
                          <td><input type="text" placeholder="Nama Dinas Tujuan" id="name-gaisoditiru" class="form-control" required /></td>
                          <td><input type="text" placeholder="Kota / Kabupaten" id="destination-gaisoditiru" class="form-control" required /></td>
                          <td><input type="text" placeholder="Nomor Tiket Activity Chatbot" id="ticket-number-gaisoditiru" class="form-control" /></td>
                          <td>
                            <textarea rows="3" cols="20" id="remark-gaisoditiru" class="form-control" placeholder="Contoh : Mencari Lead" required>
                            </textarea>
                          </td>
                          <td><button type="button" id="add-trip-destination" class="btn btn- btn-success">Add <i class="fas fa-fw fa-plus"></i></button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="form-group row mt-5">
                <input type="hidden" name="" id="url_pengajuan" value="<?= base_url('pengajuan') ?>">
                <a href="<?= base_url('pengajuan') ?>" class="btn btn-danger ml-auto mr-3" data-dismiss="modal">Cancel</a>
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
  let activityData = {};
  $(function() {
    $("#request_date").val(moment().startOf("days").format("YYYY-MM-DD"));
    $("#departure_date").val(moment().endOf("days").format("YYYY-MM-DD"));
    setActivity(`activity-ikiuniqueyo`)

    deleteWhiteSpace(`description-ikiuniqueyo`);
    deleteWhiteSpace('remark-gaisoditiru');
  });

  let uniqueTripDetailIds = ["ikiuniqueyo"]; //one default uniqueid
  let uniqueDestinationIds = ["gaisoditiru"]; //one default uniqueid

  // DELETE WHITE SPACE ON THE TEXTAREA
  function deleteWhiteSpace(uniqId) {
    var textarea = document.getElementById(uniqId);
    var text = textarea.value;
    text = text.replace(/\s+/g, ''); // Menghapus semua whitespace
    textarea.value = text;
  }

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

  // SET TOTAL AMOUNT
  function change() {
    let total = 0;
    uniqueTripDetailIds.forEach((uid, i) => {
      let data = parseInt(($(`#total-amount-${uid}`).val()).replace(/[^0-9]/g, ""))
      if (total !== 0) {
        total = total.replace(/[.]/g, "")
      }
      total = parseInt(total) + parseInt(data ? data : 0);
      total = formatRupiah(total.toString());

      $(`#no-${uid}`).text(`${i + 1}`)
    })

    $('#total').text(total)
  }

  // change text of number in list trip detail
  function changeNumberTripDetail() {
    uniqueTripDetailIds.forEach((uid, i) => {
      $(`#no-${uid}`).text(`${i + 1}`)
    })
  }

  // change text of number in list trip destination
  function changeNumberTripDestination() {
    uniqueDestinationIds.forEach((uid, i) => {
      $(`#no-${uid}`).text(`${i + 1}`)
    })
  }

  // CALCULATION
  function getTotalFromQty(e) {
    const uid = e.id.replace(/qty-/, "");
    const qty = e.value;
    const duration = $(`#duration-${uid}`).val();
    const amount = $(`#amount-${uid}`).val().replace(/[^0-9]/g, "");

    // durasi / jml hari default 1
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount.toString(), "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change()
  }

  function getTotalFromDuration(e) {
    const uid = e.id.replace(/duration-/, "");
    const duration = e.value;
    const qty = $(`#qty-${uid}`).val();
    const amount = $(`#amount-${uid}`).val().replace(/[^0-9]/g, "");

    // durasi / jml hari default 1
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount.toString(), "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change()
  }

  function getTotalFromAmount(e) {
    const uid = e.id.replace(/amount-/, "");

    const amount = e.value.replace(/[^0-9]/g, "");
    $(`#${e.id}`).val(formatRupiah(amount, "Rp. ")); // update new amount

    const qty = $(`#qty-${uid}`).val();
    const duration = $(`#duration-${uid}`).val();

    // durasi / jml hari default 1
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount.toString(), "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change()
  }

  $(document).ready(function() {
    // OFFICIAL TRIP DETAIL
    $('#add-trip-detail').click(function() {
      let no = uniqueTripDetailIds.length + 1

      let unique = createRandomString(10);
      uniqueTripDetailIds = [...uniqueTripDetailIds, unique] // save every uniq id into array to get value input in the looping calculation

      $('#official-trip-detail-table').append(`
        <tr id="row-trip-detail-${unique}" class="tb_row"> 
          <td><label id="no-${unique}">${no}</label></td>  
          <td>
            <select class="form-control" id="activity-${unique}" onchange="setFoodOption(this, '${unique}')">
            </select>
          </td> 
          <td>
            <textarea rows="3" cols="20" id="description-${unique}" class="form-control" placeholder="Deskripsi" required>
            </textarea>
          </td> 
          <td>
            <select class="form-control" id="is-food-${unique}" readonly disabled>
              <option value="NO">Tidak</option>
              <option value="YES">Ya</option>
            </select>
          </td> 
          <td><input type="number" placeholder="QTY" id="qty-${unique}" min="1" value="1" onkeyup="getTotalFromQty(this)" class="form-control" required /></td> 
          <td><input type="number" placeholder="Jumlah Hari" id="duration-${unique}" min="1" value="1" onkeyup="getTotalFromDuration(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Unit Price (IDR)" id="amount-${unique}"  onkeyup="getTotalFromAmount(this)" class="form-control" required /></td> 
          <td><input type="text" placeholder="Total Price (IDR)" id="total-amount-${unique}" disabled readonly class="form-control" required /></td>
          <td> <button type="button" id="${unique}" class="btn btn-danger remove-trip-detail">Hapus</button></td>
        </tr>`);

      // DELETE WHITE SPACE ON THE TEXTAREA
      deleteWhiteSpace(`description-${unique}`);

      // update NO of list trip detail
      changeNumberTripDetail()
      // get master data
      setActivity(`activity-${unique}`);
    });

    $(document).on('click', '.remove-trip-detail', function() {
      var button_id = $(this).attr("id");
      $('#row-trip-detail-' + button_id + '').remove();
      uniqueTripDetailIds = uniqueTripDetailIds.filter((uid) => uid != button_id)

      change() // update total price
      changeNumberTripDetail() // update number of list trip detail
    });


    // OFFICIAL TRIP DESTINATION
    $('#add-trip-destination').click(function() {
      let no = uniqueDestinationIds.length + 1

      let unique = createRandomString(10);
      uniqueDestinationIds = [...uniqueDestinationIds, unique] // save every uniq id into array to get value input in the looping calculation

      $('#trip-destination-table').append(`
        <tr id="row-trip-destination-${unique}" class="tb_row"> 
          <td><label id="no-${unique}">${no}</label></td>  
          <td><input type="text" placeholder="Nama Dinas Tujuan" id="name-${unique}" class="form-control" required /></td>
          <td><input type="text" placeholder="Kota / Kabupaten" id="destination-${unique}" class="form-control" required /></td>
          <td><input type="text" placeholder="Nomor Tiket" id="ticket-number-${unique}" class="form-control" /></td>
          <td>
            <textarea rows="3" cols="20" id="remark-${unique}" class="form-control" placeholder="Keterangan" required>
            </textarea>
          </td> 
          <td> <button type="button" id="${unique}" class="btn btn-danger remove-trip-destination">Hapus</button></td>
        </tr>`);

      // DELETE WHITE SPACE ON THE TEXTAREA
      deleteWhiteSpace(`remark-${unique}`);

      // update NO of list destination
      changeNumberTripDestination()
    });


    $(document).on('click', '.remove-trip-destination', function() {
      var button_id = $(this).attr("id");
      $('#row-trip-destination-' + button_id + '').remove();
      uniqueDestinationIds = uniqueDestinationIds.filter((uid) => uid != button_id)

      changeNumberTripDestination() // update number of list destination
    });



    // SUMBIT ALL DATA
    $('#form').submit(function(e) {
      e.preventDefault()
      let official_trip_detail = []

      uniqueTripDetailIds.forEach((uid) => {
        let official_trip_activity_id = $(`#activity-${uid}`).val()
        let remark = $(`#description-${uid}`).val()
        let is_food = $(`#is-food-${uid}`).val()
        let qty = $(`#qty-${uid}`).val()
        let duration = $(`#duration-${uid}`).val()
        let amount = parseInt(($(`#amount-${uid}`).val()).replace(/[^0-9]/g, ""))
        let total_amount = parseInt(($(`#total-amount-${uid}`).val()).replace(/[^0-9]/g, ""))

        official_trip_detail = [...official_trip_detail, {
          official_trip_activity_id,
          remark,
          is_food,
          qty,
          duration,
          amount,
          total_amount,
        }];
      });

      let official_trip_destination = [];
      uniqueDestinationIds.forEach((uid) => {
        let name = $(`#name-${uid}`).val();
        let destination = $(`#destination-${uid}`).val();
        let remark = $(`#remark-${uid}`).val();
        let ticket_number = $(`#ticket-number-${uid}`).val();

        official_trip_destination = [...official_trip_destination, {
          name,
          destination,
          remark,
          ticket_number,
        }];
      });

      // optinal if admin can create rab, take the code here
      let request_date = $(`#request_date`).val();
      let departure_date = $(`#departure_date`).val();
      let title = $(`#title`).val();
      let destination = $(`#destination`).val();
      let total_amount = parseInt(($(`#total`).text()).replace(/[^0-9]/g, ""))

      const payload = {
        request_date,
        departure_date,
        title,
        destination,
        total_amount,
        official_trip_detail,
        official_trip_destination,
      }

      $.ajax({
          method: 'POST',
          cache: false,
          data: payload,
          url: 'insert',
        })
        .done(function(data) {
          const redirectUrl = $('#url_pengajuan').val()
          window.location = `${redirectUrl}`;
        })

      console.log("data to send :", payload);
    })


  });

  // set food option when actifity is selected as makan
  function setFoodOption(elm, uniqueId) {
    let option = $(elm).val();
    $(`#is-food-${uniqueId}`).val(option == 2 ? "YES" : "NO");
  }

  async function setActivity(uniqueId) {
    try {
      // jika data activity masih kosong, ambil dulu data dari api
      if (!activityData.status) {
        activityData = await getActivity();
      }

      // set data activity
      if (activityData.status && activityData.status == 200) {
        let data = activityData.data;
        let selectElm = $(`#${uniqueId}`);
        data.forEach((item) => {
          let option = $('<option></option>').attr("value", item.id).text(item.name);
          selectElm.append(option);
        });
      }
    } catch (err) {
      console.log("something error when get data activity", err.message)
    }
  }

  // API GET MASTER AKTIFITAS AND ADD NEW INPUT OPTION INTO DYNAMIC FORM
  function getActivity() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "master_activity",
        method: "GET",
        headers: {
          'Content-Type': 'application/json'
        },
        success: function(res) {
          resolve(JSON.parse(res))
        },
        error: function(err) {
          console.log("error fetch master activity :", err);
          let errResp = {
            status: 500,
            message: err
          };
          reject(errResp);
        }
      })
    })
  }
</script>