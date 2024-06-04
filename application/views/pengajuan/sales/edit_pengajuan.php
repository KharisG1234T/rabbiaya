<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <div class="text-center">
              <h3 class="">FORM EDIT PENGAJUAN RAB BIAYA PERJALANAN</h3>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-5">
            <form id="form" class="form-horizontal" method="post" action="<?= base_url('pengajuan/update') ?>">
              <!-- Hidden inputs -->
              <input type="hidden" value="<?= $pengajuan['id'] ?>" name="id">
              <input type="hidden" value="<?= $this->session->userdata('id') ?>" name="userid" id="userid" />
              <input type="hidden" value="<?= $this->session->userdata('area_id') ?>" name="area_id" id="areaid" />
              <input type="hidden" name="kode_pengajuan" id="kode_pengajuan" value="<?= $pengajuan['kode_pengajuan'] ?>">

              <!-- Tanggal Pengajuan -->
              <div class="form-group row">
                <div class="col col-sm-4 col-md-2 col-lg-2">
                  <div class="form-group">
                    <label for="request_date">Tanggal Pengajuan RAB</label>
                    <input type="date" class="form-control" name="request_date" id="request_date" placeholder="Tanggal Pengajuan" value="<?= $pengajuan['request_date'] ?>" required readonly>
                  </div>
                </div>
                <!-- Tanggal Berangkat -->
                <div class="col col-sm-4 col-md-2 col-lg-2">
                  <div class="form-group">
                    <label for="departure_date">Tanggal Berangkat</label>
                    <input type="date" class="form-control" name="departure_date" id="departure_date" placeholder="Tanggal Berangkat" value="<?= $pengajuan['departure_date'] ?>" required>
                  </div>
                </div>
                <!-- Tujuan Perjalanan -->
                <div class="col col-sm-4 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label for="destination">Tujuan Perjalanan</label>
                    <input type="text" class="form-control" name="destination" id="destination" placeholder="Contoh : Jakarta" value="<?= $pengajuan['destination'] ?>" required>
                  </div>
                </div>
                <!-- Jenis Agenda -->
                <div class="col col-sm-4 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label for="title">Jenis Agenda</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Contoh : Visit Dinas Mingguan" value="<?= $pengajuan['title'] ?>" required>
                  </div>
                </div>
                <!-- Total Amount-->
                <div class="col col-sm-4 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label for="title">Total Semua</label>
                    <input type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Total Pengajuan" value="<?= $pengajuan['total_amount'] ?>">
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
                    <input type="hidden" data-trip-details='<?= json_encode($pengajuan['official_trip_detail']) ?>' id="tripdetails">
                    <table class="table table-bordered" id="official-trip-detail-table">
                      <!-- Table headers -->
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
                      <tbody id="trip-detail-body">
                        <!-- Rows will be dynamically added/removed via JavaScript -->
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="5" class="text-center font-weight-bold">Total</td>
                          <td colspan="4" class="font-weight-bold text-center"><span id="total"></span> </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12 text-right">
                  <button type="button" id="add-trip-detail" class="btn btn-success"><i class="fas fa-plus"></i> Add Detail</button>
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
                    <input type="hidden" data-trip-destinations='<?= json_encode($pengajuan['official_trip_destination']) ?>' id="tripdestinations">
                    <table class="table table-bordered" id="trip-destination-table">
                      <!-- Table headers -->
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
                      <tbody id="trip-destination-body">
                        <!-- Rows will be dynamically added/removed via JavaScript -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12 text-right">
                  <button type="button" id="add-trip-destination" class="btn btn-success"><i class="fas fa-plus"></i> Add Destination</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>
<script>
  let uniqueTripDetailIds = [];
  let uniqueDestinationIds = [];
  let activityData = {};


  $(function() {
    // Set default values or perform any necessary operations on document ready
    $("#request_date").val(moment().startOf("days").format("YYYY-MM-DD"));
    $("#departure_date").val(moment().endOf("days").format("YYYY-MM-DD"));
  });

  // Function to handle whitespace deletion
  function deleteWhiteSpace(uniqId) {
    var textarea = $(`#${uniqId}`);
    var text = textarea.val();
    text = text.replace(/\s+/g, ''); // Remove all whitespace
    textarea.val(text);
  }

  // Function to create a random string
  function createRandomString(length) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  // Function to format currency
  function formatRupiah(angka = 0, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
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

  // Function to handle total amount calculation
  function change() {
    let total = 0;
    uniqueTripDetailIds.forEach((uid, i) => {
      let data = parseInt(($(`#total-amount-${uid}`).val()).replace(/[^0-9]/g, ""));
      if (total !== 0) {
        total = total.toString().replace(/[.]/g, "")
      }
      total = parseInt(total) + parseInt(data ? data : 0);
      total = formatRupiah(total.toString());

      $(`#no-${uid}`).text(`${i + 1}`);
    });

    $('#total').text(total);
  }

  // Function to handle calculation based on quantity input
  function getTotalFromQty(e) {
    const uid = e.id.replace(/qty-/, "");
    const qty = e.value;
    const duration = $(`#duration-${uid}`).val();
    const amount = $(`#amount-${uid}`).val().replace(/[^0-9]/g, "");

    // Calculate total amount
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount, "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change();
  }

  // Function to handle calculation based on duration input
  function getTotalFromDuration(e) {
    const uid = e.id.replace(/duration-/, "");
    const duration = e.value;
    const qty = $(`#qty-${uid}`).val();
    const amount = $(`#amount-${uid}`).val().replace(/[^0-9]/g, "");

    // Calculate total amount
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount, "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change();
  }

  // Function to handle calculation based on amount input
  function getTotalFromAmount(e) {
    const uid = e.id.replace(/amount-/, "");

    const amount = e.value.replace(/[^0-9]/g, "");
    $(`#${e.id}`).val(formatRupiah(amount, "Rp. ")); // Update new amount

    const qty = $(`#qty-${uid}`).val();
    const duration = $(`#duration-${uid}`).val();

    // Calculate total amount
    let totalAmount = parseInt((qty ? qty : 0) * parseInt(duration ? duration : 1) * parseInt(amount ? amount : 0));
    totalAmount = formatRupiah(totalAmount, "Rp. ");

    $(`#total-amount-${uid}`).val(totalAmount);
    change();
  }

  $(document).ready(function() {
    // Event listeners and other operations on document ready
    const tripDetails = $("#tripdetails");
    const dataTripDetails = tripDetails.data("trip-details");
    console.log("trip detail :", dataTripDetails)

    dataTripDetails.forEach((item, idx) => {
      let unique = createRandomString(10);
      uniqueTripDetailIds = [...uniqueTripDetailIds, unique];

      $('#official-trip-detail-table').append(`
        <tr id="row-trip-detail-${unique}" class="tb_row"> 
            <td><label id="no-${unique}">${idx + 1}</label></td>  
            <td>
              <select class="form-control" id="activity-${unique}" onchange="setFoodOption(this, '${unique}')">
              </select>
            </td> 
            <td>
                <textarea rows="3" cols="20" id="description-${unique}" class="form-control" placeholder="Deskripsi" required>${item.remark}</textarea>
            </td> 
            <td>
                <select class="form-control" id="is-food-${unique}" readonly disabled>
                    <option value="NO" ${item.is_food == 'NO' ? 'selected' : ''}>Tidak</option>
                    <option value="YES" ${item.is_food == 'YES' ? "selected" : ''}>Ya</option>
                </select>
            </td> 
            <td><input type="number" placeholder="QTY" id="qty-${unique}" min="1" value="1" onkeyup="getTotalFromQty(this)" class="form-control" required value="${item.qty}"/></td> 
            <td><input type="number" placeholder="Jumlah Hari" id="duration-${unique}" min="1" value="1" onkeyup="getTotalFromDuration(this)" class="form-control" required value="${item.duration}" /></td> 
            <td><input type="text" placeholder="Unit Price (IDR)" id="amount-${unique}"  onkeyup="getTotalFromAmount(this)" class="form-control" required value="${formatRupiah(item.amount, "Rp. ")}")}" /></td> 
            <td><input type="text" placeholder="Total Price (IDR)" id="total-amount-${unique}" disabled readonly class="form-control" required value="${formatRupiah(item.total_amount, "Rp. ")}")}"/></td>
            <td> <button type="button" id="${unique}" class="btn btn-danger remove-trip-detail">Hapus</button></td>
        </tr>`);

      setActivity(`activity-${unique}`, `${item.official_trip_activity_id}`);
      
    })

    // Event listener to add trip detail row
    $('#add-trip-detail').click(function() {
      let no = uniqueTripDetailIds.length + 1;
      let unique = createRandomString(10);
      uniqueTripDetailIds.push(unique);

      $('#official-trip-detail-table').append(`
        <tr id="row-trip-detail-${unique}" class="tb_row"> 
            <td><label id="no-${unique}">${no}</label></td>  
            <td>
              <select class="form-control" id="activity-${unique}" onchange="setFoodOption(this, '${unique}')">
              </select>
            </td> 
            <td>
                <textarea rows="3" cols="20" id="description-${unique}" class="form-control" placeholder="Deskripsi" required></textarea>
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

      // Delete whitespace on textarea
      deleteWhiteSpace(`description-${unique}`);

      // Update the number of list trip detail
      changeNumberTripDetail();

      // Get master data for activity
      setActivity(`activity-${unique}`);



      // TRIP DESTINATION
      const tripDestinations = $("#tripdestinations");
      const dataTripDestinations = tripDestinations.data("trip-destinations");
      console.log("trip destination :", dataTripDestinations)
    });


    // Event listener to remove trip detail row
    $(document).on('click', '.remove-trip-detail', function() {
      var button_id = $(this).attr("id");
      $('#row-trip-detail-' + button_id + '').remove();
      uniqueTripDetailIds = uniqueTripDetailIds.filter((uid) => uid != button_id);

      change(); // Update total price
      changeNumberTripDetail(); // Update number of list trip detail
    });

    // Event listener to add trip destination row
    $('#add-trip-destination').click(function() {
      let no = uniqueDestinationIds.length + 1;

      let unique = createRandomString(10);
      uniqueDestinationIds.push(unique);

      $('#trip-destination-table').append(`
        <tr id="row-trip-destination-${unique}" class="tb_row"> 
          <td><label id="no-${unique}">${no}</label></td>  
          <td><input type="text" placeholder="Nama Dinas Tujuan" id="name-${unique}" class="form-control" required /></td>
          <td><input type="text" placeholder="Kota / Kabupaten" id="destination-${unique}" class="form-control" required /></td>
          <td><input type="text" placeholder="Nomor Tiket" id="ticket-number-${unique}" class="form-control" /></td>
          <td>
            <textarea rows="3" cols="20" id="remark-${unique}" class="form-control" placeholder="Keterangan" required></textarea>
          </td> 
          <td> <button type="button" id="${unique}" class="btn btn-danger remove-trip-destination">Hapus</button></td>
        </tr>`);

      // Delete whitespace on textarea
      deleteWhiteSpace(`remark-${unique}`);

      // Update number of list destination
      changeNumberTripDestination();
    });

    // Event listener to remove trip destination row
    $(document).on('click', '.remove-trip-destination', function() {
      var button_id = $(this).attr("id");
      $('#row-trip-destination-' + button_id + '').remove();
      uniqueDestinationIds = uniqueDestinationIds.filter((uid) => uid != button_id);

      changeNumberTripDestination(); // Update number of list destination
    });
  });

  // Event listener for form submission
  $('#form').submit(function(e) {
    e.preventDefault();

    // Collect official trip detail data
    let official_trip_detail = [];
    uniqueTripDetailIds.forEach((uid) => {
      let official_trip_activity_id = $(`#activity-${uid}`).val();
      let remark = $(`#description-${uid}`).val();
      let is_food = $(`#is-food-${uid}`).val();
      let qty = $(`#qty-${uid}`).val();
      let duration = $(`#duration-${uid}`).val();
      let amount = parseInt(($(`#amount-${uid}`).val()).replace(/[^0-9]/g, ""));
      let total_amount = parseInt(($(`#total-amount-${uid}`).val()).replace(/[^0-9]/g, ""));

      official_trip_detail.push({
        official_trip_activity_id,
        remark,
        is_food,
        qty,
        duration,
        amount,
        total_amount,
      });
    });

    // Collect official trip destination data
    let official_trip_destination = [];
    uniqueDestinationIds.forEach((uid) => {
      let name = $(`#name-${uid}`).val();
      let destination = $(`#destination-${uid}`).val();
      let remark = $(`#remark-${uid}`).val();
      let ticket_number = $(`#ticket-number-${uid}`).val();

      official_trip_destination.push({
        name,
        destination,
        remark,
        ticket_number,
      });
    });

    // Collect other form data
    let request_date = $("#request_date").val();
    let departure_date = $("#departure_date").val();
    let title = $("#title").val();
    let destination = $("#destination").val();
    let total_amount = parseInt(($("#total").text()).replace(/[^0-9]/g, ""));

    // Create payload for AJAX request
    const payload = {
      request_date,
      departure_date,
      title,
      destination,
      total_amount,
      official_trip_detail,
      official_trip_destination,
    };

    // Perform AJAX request
    $.ajax({
      method: 'POST',
      cache: false,
      data: payload,
      url: 'update', // Update URL to correct endpoint
      success: function(data) {
        const redirectUrl = $('#url_pengajuan').val();
        window.location = `${redirectUrl}`;
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
      }
    });
    console.log("Payload to send:", JSON.stringify(payload, null, 2));
  });

  // Function to set food option when activity is selected as makan
  function setFoodOption(elm, uniqueId) {
    let option = $(elm).val();
    $(`#is-food-${uniqueId}`).val(option == 2 ? "YES" : "NO");
  }

  // Function to retrieve activity data
  async function setActivity(uniqueId, activitySelected = null) {
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
          let option = $('<option></option>').attr("value", item.id).text(item.name).attr("selected", activitySelected == item.id);
          selectElm.append(option);
        });
      }
    } catch (err) {
      console.log("something error when get data activity", err.message)
    }
  }


  // Function to fetch activity data from the backend
  function getActivity() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../master_activity",
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

  // Function to update the number of trip detail list
  function changeNumberTripDetail() {
    $("#no-trip-detail").text(uniqueTripDetailIds.length);
  }

  // Function to update the number of destination list
  function changeNumberTripDestination() {
    $("#no-trip-destination").text(uniqueDestinationIds.length);
  }
</script>