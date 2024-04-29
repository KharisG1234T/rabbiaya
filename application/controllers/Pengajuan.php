<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . '/vendor/autoload.php';

use Dompdf\Dompdf;

class Peminjaman extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    is_logged_in();
    // load model
    $this->load->model(array('Barangpeminjaman_model', 'Userapproval_model', 'Peminjaman_model', 'Cabang_model'));
  }

  // load view list
  public function index()
  {
    $data['title'] = 'List Pengajuan RAB';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'ALL';
    $data['cabangs'] = $this->Cabang_model->getAll();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/index', $data);
    $this->load->view('templates/admin_footer');
  }

  function archieve()
  {
    $data['title'] = 'List Archieve Pengajuan RAB';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'ALL';
    $data['cabangs'] = $this->Cabang_model->getAll();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/archieve', $data);
    $this->load->view('templates/admin_footer');
  }

  public function export_excel()
  {
    $start = false;
    $length = false;
    $tgl_awal = $this->input->get('tgl_awal') ?? "";
    $tgl_akhir = $this->input->get('tgl_akhir') ?? "";
    $status = $this->input->get("status");
    if ($status == "") {
      $status = "ALL";
    }

    $from = $this->input->get("from");
    if ($from == "") {
      $from = "ALL";
    }

    $isArchieve = false;

    $order_by = 'kode_pengajuan';  // Kolom untuk sorting
    $order_direction = 'desc';
    $searchValue = '';

    $data = $this->Peminjaman_model->getAll($status, $start, $length, $order_by, $order_direction, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve);
    $data = $this->_persingData($data);

    $this->load->view("pengajuan/export", [
      'tgl_awal' => $tgl_awal,
      'tgl_akhir' => $tgl_akhir,
      'data' => $data
    ]);
  }

  private function _persingData($data, $isArchieve = false)
  {
    $outputData = [];

    foreach ($data as $idx => $item) {
      $action = '';
      // Konversi objek $item ke array (jika belum)
      $itemArray = (array)$item;

      $itemArray['no'] = $idx + 1;
      switch ($itemArray['status']) {
        case "PROCESS":
          $userApprovalData = $this->Peminjaman_model->getUserApprovalByPengajuanId($itemArray['official_trip_id']);

          if ($userApprovalData) {
            $userApprovals = [];
            foreach ($userApprovalData as $userApproval) {
              $useracc = $this->Userapproval_model->getUserById($userApproval['id_user']);
              $statusBadge = ($userApproval['status'] === 'APPROVE') ? 'badge-success' : 'badge-warning';
              $userApprovals[] = '<span class="badge ' . $statusBadge . '">' . $useracc['name'] . '</span>';
            }
            $itemArray['userApprovals'] = implode(' ', $userApprovals);
          } else {
            $itemArray['userApprovals'] = 'Pengajuan Anda Sedang Proses Persetujuan';
          }
          break;
        case "SUCCESS":
          $itemArray['userApprovals'] = "<small class='badge badge-success'>Pengajuan Selesai</small>";

          break;
        case "PENDING": // Tambahkan case untuk status "PENDING"
          $itemArray['userApprovals'] = "<small class='badge badge-warning'>Menunggu Persetujuan</small>";

          break;
        default:
          $itemArray['userApprovals'] = "<small class='badge badge-danger'>Pengajuan Anda Di Tolak</small>";

          break;
      }

      // code untuk button action
      $action .= '<div class="dropdown show">';
      $action .= '<a class="btn btn-sm bg-primary text-white dropdown-toggle" href="#" role="button" id="dropdownAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
      $action .= 'Action';
      $action .= '</a>';
      $action .= '<div class="dropdown-menu" aria-labelledby="dropdownAction">';

      if ($isArchieve) {
        // btn action for list archieve data
        if ($this->session->userdata("role_id") == 1) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/restore/') . $itemArray['id_peminjaman'] . '" onclick="return confirm(' . "'Anda yakin ingin me-restore data ini?'" . ')"><i class="fas fa fa-recycle"></i>&nbsp;&nbsp; Restore</a>';
        }
      } else {
        // btn action for list actived data

        // Menggabungkan nilai dari kolom userApprovals dan keterangan_sku
        $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/detail/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-eye"></i>&nbsp;&nbsp; Detail</a>';

        // Admin atau Sales
        if (in_array($this->session->userdata('role_id'), array(1)) || ($itemArray["status"] == "PENDING" && in_array($this->session->userdata('role_id'), array(1, 2)))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/edit/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-pen"></i>&nbsp;&nbsp; Perbarui</a>';
        }

        // Admin, PM, atau PM Manager
        if (in_array($this->session->userdata('role_id'), array(1)) || ($itemArray["status"] == "PENDING" || $itemArray["status"] == "PROCESS") && in_array($this->session->userdata('role_id'), array(3, 8))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/process/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-file"></i>&nbsp;&nbsp; Update SKU</a>';
        }

        // Admin atau CS
        if (in_array($this->session->userdata('role_id'), array(1, 9))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/editcs/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-tags"></i>&nbsp;&nbsp; Update No SQ</a>';
        }

        // Admin atau Purchasing
        if (in_array($this->session->userdata('role_id'), array(1, 10))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/editpurc/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-tags"></i>&nbsp;&nbsp; Update No PO</a>';
        }

        // Bukan Sales dan PM
        if ($itemArray["status"] == "PROCESS" && in_array($this->session->userdata('role_id'), array(4, 5, 6, 7, 8))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/approve/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-check-double"></i>&nbsp;&nbsp; Approve</a>';
        }

        // Bukan Sales dan PM
        if ($itemArray["status"] == "PROCESS" && in_array($this->session->userdata('role_id'), array(1, 3, 8, 9, 10))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/reject/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-minus"></i>&nbsp;&nbsp; Tolak</a>';
        }

        // Admin
        if ($itemArray["status"] == "REJECTED" && in_array($this->session->userdata('role_id'), array(1))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/unreject/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-recycle"></i>&nbsp;&nbsp; Batal Tolak</a>';
        }

        // Admin atau Sales
        if ($this->session->userdata('role_id') == 1) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/delete/') . $itemArray['id_peminjaman'] . '" onclick="return confirm(' . "'Anda yakin ingin menghapus data ini?'" . ')"><i class="fas fa fa-trash"></i>&nbsp;&nbsp; Hapus</a>';
        }

        // Admin atau Sales
        if (in_array($this->session->userdata('role_id'), array(1, 2, 4))) {
          $action .= '<a class="dropdown-item" href="' . base_url('peminjaman/print/') . $itemArray['id_peminjaman'] . '"><i class="fas fa fa-print"></i>&nbsp;&nbsp; Cetak</a>';
        }
      }

      $action .= '</div>';
      $action .= '</div>';

      $itemArray['action'] = $action;

      $outputData[] = $itemArray; // Tambahkan item yang diubah ke array output
    }

    return $outputData;
  }
  public function datatable()
  {
    // Ambil parameter yang diperlukan oleh DataTables
    $draw = intval($this->input->get("draw"));
    $start = intval($this->input->get("start"));
    $length = intval($this->input->get("length"));
    $tgl_awal = $this->input->get('tgl_awal');
    $tgl_akhir = $this->input->get('tgl_akhir');
    $status = $this->input->get("status") ?? "ALL";
    $from = $this->input->get('from')  ?? "ALL";
    $isArchieve = false;
    $order_by = $this->input->get('data') ?? 'kode_pengajuan';  // Kolom untuk sorting
    $order_direction = $this->input->get("order[0][dir]") ?? 'desc';      // Arah sorting

    // Dapatkan data dari model dengan penambahan sorting
    $searchValue = $this->input->get('search')['value'] ?? '';
    $data = $this->Pengajuan_model->getAll($status, $start, $length, $order_by, $order_direction, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve);
    $outputData = $this->_persingData($data);


    // Buat array untuk menyimpan data yang akan dikirimkan ke DataTables
    $output = array(
      "draw" => $draw,
      "recordsTotal" => $this->Pengajuan_model->getCountAll(),
      "recordsFiltered" => $this->Pengajuan_model->getCountFiltered($status, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve),
      "data" => $outputData,
    );

    // Kirim data dalam format JSON
    echo json_encode($output);
    exit();
  }

  public function archieveDataTable()
  {
    // Ambil parameter yang diperlukan oleh DataTables
    $draw = intval($this->input->get("draw"));
    $start = intval($this->input->get("start"));
    $length = intval($this->input->get("length"));
    $tgl_awal = $this->input->get('tgl_awal');
    $tgl_akhir = $this->input->get('tgl_akhir');
    $from = $this->input->get('from')  ?? "ALL";
    $status = $this->input->get("status") ?? "ALL";
    $order_by = $this->input->get('data') ?? 'kode_pengajuan';  // Kolom untuk sorting
    $order_direction = $this->input->get("order[0][dir]") ?? 'desc';      // Arah sorting

    // Dapatkan data dari model dengan penambahan sorting
    $searchValue = $this->input->get('search')['value'] ?? '';
    $isArchieve = true;
    $data = $this->Pengajuan_model->getAll($status, $start, $length, $order_by, $order_direction, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve);
    $outputData = $this->_persingData($data, $isArchieve);


    // Buat array untuk menyimpan data yang akan dikirimkan ke DataTables
    $output = array(
      "draw" => $draw,
      "recordsTotal" => $this->Pengajuan_model->getCountAll("ALL", "", "", "", "", "", $isArchieve),
      "recordsFiltered" => $this->Pengajuan_model->getCountFiltered($status, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve),
      "data" => $outputData,
    );

    // Kirim data dalam format JSON
    echo json_encode($output);
    exit();
  }

  public function new()
  {
    // Mendapatkan data pengguna dan informasi lainnya
    $data['title'] = 'List Pengajuan RAB Terbaru';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'PENDING';
    $data['cabangs'] = $this->Cabang_model->getAll();

    // Memuat tampilan dengan data yang telah diproses
    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/index', $data);
    $this->load->view('templates/admin_footer');
  }



  public function onprocess()
  {
    $data['title'] = 'List Pengajuan RAB Di Proses';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'PROCESS';
    $data['cabangs'] = $this->Cabang_model->getAll();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar', $data);
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/index', $data);
    $this->load->view('templates/admin_footer');
  }


  public function rejected()
  {
    $data['title'] = 'List Pengajuan RAB Di Tolak';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'REJECTED';
    $data['cabangs'] = $this->Cabang_model->getAll();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar', $data);
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/index', $data);
    $this->load->view('templates/admin_footer');
  }

  public function success()
  {
    $data['title'] = 'List Pengajuan RAB Sukses';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['status'] = 'SUCCESS';
    $data['cabangs'] = $this->Cabang_model->getAll();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar', $data);
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/index', $data);
    $this->load->view('templates/admin_footer');
  }

  // end load view list

  // page tambah peminjaman
  public function add()
  {
    if (!in_array($this->session->userdata('role_id'), [1, 2])) {
      redirect(base_url() . '/pengajuan');
    }
    $data['title'] = 'Tambah Pengajuan RAB';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['cabangs'] = $this->Cabang_model->getAll();


    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/sales/tambah_pengajuan', $data);
    $this->load->view('templates/admin_footer');
  }

  // user peminjaman droprown
  public function userdropdown($id_area)
  {
    $userarea = $this->db->get_where('user_area', ['area_id' => $id_area])->result_array();

    $userIds = [0];
    foreach ($userarea as $area) {
      array_push($userIds, $area["user_id"]);
    }
    $users = $this->db->select("id, name")->from("user")->where_in('id', $userIds)->where("role_id", 2)->get()->result_array();
    echo json_encode($users);
  }

  // insert peminjaman
  public function insert()
  {
    $userId = $this->session->userdata("id");
    $roleId = $this->session->userdata('role_id');
    $area = $this->session->userdata('area');
    $areaId = $area[0]["area_id"];

    $dataPengajuan = array(
      'user_id' => $roleId == "1" ? $this->input->post('userId') : $userId,
      'area_id' => $roleId == "1" ? $this->input->post('from') : $areaId,
      'request_date' => date('Y-m-d'),
      'departure_date' => date('Y-m-d'),
      'destination' => $this->input->post('destination'),
      'total_amount' => $this->input->post('total_amount'),
      'level_id' => $this->input->post('level_id')
    );

    $PengajuanId = $this->Pengajuan_model->save($dataPengajuan);

    // Setelah peminjaman disimpan, ambil ID peminjaman yang baru
    $newKodePengajuan = $this->Pengajuan_model->generateKodePengajuan($PengajuanId);

    $dataPengajuan['kode_pengajuan'] = $newKodePengajuan; // Set kode pengajuan dengan nilai baru

    $this->Pengajuan_model->update($dataPengajuan, $PengajuanId);

    $official_trip_activity = $this->input->post('official_trip_activity');
    foreach ($official_trip_activity as $trip_activity) {
      $data = array(
        'name' => $trip_activity['name'],
        'remark' => $trip_activity["remark"],
      );
      $this->Official_trip_activity_model->save($data);
    }

    $official_trip_destination = $this->input->post('official_trip_destination');
    foreach ($official_trip_destination as $trip_destination) {
      $data = array(
        'official_trip_id' => $official_trip_id,
        'name' => $trip_destination['name'],
        'destination' => $trip_destination["destination"],
        'remark' => $trip_destination["remark"],
        'ticket_number' => $trip_destination["ticket_number"],
      );
      $this->Official_trip_destination_model->save($data);
    }

    $official_trip_detail = $this->input->post('official_trip_detail');
    foreach ($official_trip_detail as $trip_detail) {
      $data = array(
        'official_trip_id' => $official_trip_id,
        'official_trip_activity_id' => $official_trip_activity_id,
        'remark' => $trip_detail["remark"],
        'qty' => $trip_detail["qty"],
        'is_food' => $trip_detail["is_food"],
        'duration' => $trip_detail["duration"],
        'amount' => $trip_detail["amount"],
        'total_amount' => $trip_detail["total_amount"],
      );
      $this->Official_trip_detail_model->save($data);
    }


    $payloadOfficialTripApproval = array(
      'created_at' => date('Y-m-d'),
      'official_trip_id' => $official_trip_id,
      'user_id' => $this->session->userdata('id'),
    );

    $resultId = $this->Official_trip_approval_model->save($payloadOfficialTripApproval);
  }

  public function delete($id)
  {
    if (!in_array($this->session->userdata('role_id'), [1, 2])) {
      redirect(base_url() . 'pengajuan');
    }
    $this->Pengajuan_model->delete($id);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Data berhasil dihapus!
        </div>');
    redirect(base_url('pengajuan'));
  }

  public function restore($id)
  {
    if (!in_array($this->session->userdata('role_id'), [1])) {
      redirect(base_url() . 'pengajuan/archieve');
    }

    $this->Pengajuan_model->restore($id);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Data berhasil restore!
        </div>');
    redirect(base_url('pengajuan/archieve'));
  }

  public function edit($id)
  {
    if (!in_array($this->session->userdata('role_id'), [1, 2])) {
      redirect(base_url() . 'pengajuan');
    }
    $data['title'] = 'Edit Pengajuan RAB';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['cabangs'] = $this->Cabang_model->getAll();
    $data['pengajuan'] = $this->Pengajuan_model->getDetail($id);

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/sales/edit_pengajuan', $data);
    $this->load->view('templates/admin_footer');
  }

  public function process($id)
  {
    // edit sku & po by PM
    if (!in_array($this->session->userdata('role_id'), [1, 3, 8])) {
      redirect(base_url() . 'pengajuan');
    }
    //check if pengajuan status isnot pending
    $pengajuan = $this->Pengajuan_model->getById($id);
    if ($pengajuan['status'] != "PENDING" && $pengajuan['status'] != "PROCESS") {
      redirect(base_url() . 'pengajuan');
    }

    $data['title'] = 'Edit Pengajuan RAB';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['cabangs'] = $this->Cabang_model->getAll();
    $data['pengajuan'] = $this->Pengajuan_model->getDetail($id);


    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/user/edit_pengajuan', $data);
    $this->load->view('templates/admin_footer');
  }


  public function detail($id_peminjaman)
  {
    $data['title'] = 'Detail Peminjaman';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['peminjaman'] = $this->Peminjaman_model->getDetail($id_peminjaman);
    $data['peminjaman']['approve']['sales'] = ['ttd' => '', 'createdat' => ''];
    $data['peminjaman']['approve']['pm'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['ks'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['hr'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['ms'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['mo'] = ['ttd' => 'waiting.png', 'createdat' => ''];

    foreach ($data['peminjaman']['userapproval']['users'] as $user) {
      if ($user['role_id'] == 2) {
        $data['peminjaman']['approve']['sales'] = $user;
      }
      if ($user['role_id'] == 8) {
        $data['peminjaman']['approve']['pm'] = $user;
      }
      if ($user['role_id'] == 4) {
        $data['peminjaman']['approve']['ks'] = $user;
      }
      if ($user['role_id'] == 5) {
        $data['peminjaman']['approve']['hr'] = $user;
      }
      if ($user['role_id'] == 6) {
        $data['peminjaman']['approve']['ms'] = $user;
      }
      if ($user['role_id'] == 7) {
        $data['peminjaman']['approve']['mo'] = $user;
      }
    }

    // unset array userapproval
    unset($data['peminjaman']['userapproval']);

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('peminjaman/detail_peminjaman', $data);
    $this->load->view('templates/admin_footer');
  }

  public function print($id_peminjaman)
  {
    $data['title'] = 'Detail Peminjaman';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['peminjaman'] = $this->Peminjaman_model->getDetail($id_peminjaman);
    $data['peminjaman']['approve']['sales'] = ['ttd' => '', 'createdat' => ''];
    $data['peminjaman']['approve']['pm'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['ks'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['hr'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['ms'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['peminjaman']['approve']['mo'] = ['ttd' => 'waiting.png', 'createdat' => ''];

    foreach ($data['peminjaman']['userapproval']['users'] as $user) {
      if ($user['role_id'] == 2) {
        $data['peminjaman']['approve']['sales'] = $user;
      }
      if ($user['role_id'] == 8) {
        $data['peminjaman']['approve']['pm'] = $user;
      }
      if ($user['role_id'] == 4) {
        $data['peminjaman']['approve']['ks'] = $user;
      }
      if ($user['role_id'] == 5) {
        $data['peminjaman']['approve']['hr'] = $user;
      }
      if ($user['role_id'] == 6) {
        $data['peminjaman']['approve']['ms'] = $user;
      }
      if ($user['role_id'] == 7) {
        $data['peminjaman']['approve']['mo'] = $user;
      }
    }

    $pdf = $this->load->view("peminjaman/print", $data, true);

    $dompdf = new Dompdf();

    //mengatur opsi dompdf
    $option = array(
      'enable_css_parsing' => true,
      'enable_javascript' => true,
      'enable_remote' => true,
      //tambah opsi lain disini
    );
    $dompdf->set_options($option);


    $dompdf->loadHtml($pdf);
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser
    //$dompdf->stream();
    $dompdf->stream('my.pdf', array('Attachment' => 0));
  }
  // update peminjaman
  public function update()
  {
    $userId = $this->session->userdata("id");
    $roleId = $this->session->userdata('role_id');
    $area = $this->session->userdata('area');
    $areaId = $area[0]["area_id"];

    $idPeminjaman = $this->input->post('id');
    $dataPeminjaman = array(
      'id_cabang' => $this->input->post('direction'),
      'id_user' => $roleId == "1" ? $this->input->post('userId') : $userId, // jika admin, ambil value dari view
      'from' => $roleId == "1" ? $this->input->post('from') : $areaId, // jika admin, ambil value dari view
      'date' => $this->input->post('date'),
      'number' => $this->input->post('number'),
      'closingdate' => $this->input->post('closingDate'),
      'note' => $this->input->post('note'),
      'dinas' => $this->input->post('dinas'),
      'lokasi' => $this->input->post('lokasi'),
      'nosq' => $this->input->post('nosq'),
      'nopo' => $this->input->post('nopo')
    );

    $this->Peminjaman_model->update($dataPeminjaman, $idPeminjaman);

    // hapus barang lama
    $barangLama = $this->Barangpeminjaman_model->getAllBy($idPeminjaman);
    foreach ($barangLama as $barang) {
      $this->Barangpeminjaman_model->delete($barang['id_bp']);
    }

    // update / tambah barang baru
    $barang = $this->input->post('barang');
    foreach ($barang as $item) {
      $data = array(
        'id_peminjaman' => $idPeminjaman,
        'sku' => $item['sku'],
        'nama' => $item['name'],
        'harga' => $item["price"],
        'qty' => $item["qty"],
        'jumlah' => $item["total"],
        'stok_po' => $item['stok_po'],
        'maks_delivery' => $item['maks'],
      );
      $this->Barangpeminjaman_model->save($data);
    }
  }


  // set sku, stok/po, status="process"
  public function setstatus()
  {
    $idPeminjaman = $this->input->post('id');
    $barang = $this->input->post('barang');
    foreach ($barang as $item) {
      $id = $item['id'];
      $data = array(
        'sku' => $item['sku'],
        'stok_po' => $item['stokpo'],
      );
      $this->Barangpeminjaman_model->update($id, $data);
    }
    // update status
    $this->db->where('id_peminjaman', $idPeminjaman)->update('peminjaman', ['status' => 'PROCESS']);
  }

  public function approve($id_peminjaman)
  {
    //selain admin dan sales bisa approve
    if (!in_array($this->session->userdata('role_id'), [1, 2])) {
      //check if peminjaman status isnot process
      $peminjaman = $this->Peminjaman_model->getById($id_peminjaman);
      if ($peminjaman['status'] !== "PROCESS") {
        redirect(base_url() . 'peminjaman');
      }

      $userapproval = $this->db->from('userapproval')->where(['id_peminjaman' => $id_peminjaman, 'status' => 'APPROVE'])->get()->result_array();

      $exist = false;
      foreach ($userapproval as $user) {
        $data = $this->db->select('role_id')->from('user')->where('id', $user['id_user'])->get()->row_array();
        if ($data['role_id'] == $this->session->userdata('role_id')) {
          $exist = true;
        }
      }

      // cek apakah sudah pernah di setujui oleh role yg sama atau belum
      if ($exist) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
          Sudah pernah di setujui!
        </div>');
        redirect(base_url('peminjaman'));
      } else {
        $data = array(
          'createdat' => date('Y-m-d'),
          'id_peminjaman' => $id_peminjaman,
          'id_user' => $this->session->userdata('id'),
          'status' => 'APPROVE',
        );

        $this->Userapproval_model->save($data);
        if (count($userapproval) == 3) {
          $this->db->where('id_peminjaman', $id_peminjaman)->update('peminjaman', ['status' => 'SUCCESS']);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
          Pengajuan berhasil di setujui!
        </div>');
        redirect(base_url('peminjaman'));
      }
    } else {
      redirect(base_url() . 'peminjaman');
    }
  }

  public function reject($id_peminjaman)
  {
    if (!in_array($this->session->userdata('role_id'), [1, 2, 3])) {

      //check if peminjaman status isnot process
      $peminjaman = $this->Peminjaman_model->getById($id_peminjaman);
      if ($peminjaman['status'] !== "PROCESS") {
        redirect(base_url() . 'peminjaman');
      }

      $rejectedlist = $this->db->from('userapproval')->where(['id_peminjaman' => $id_peminjaman, 'status' => 'REJECT'])->get()->result_array();

      $exist = false;
      foreach ($rejectedlist as $user) {
        $data = $this->db->select('role_id')->from('user')->where('id', $user['id_user'])->get()->row_array();
        if ($data['role_id'] == $this->session->userdata('role_id')) {
          $exist = true;
        }
      }

      // cek apakah sudah pernah di reject oleh role yg sama atau belum
      if ($exist) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
          Sudah pernah di tolak!
        </div>');
        redirect(base_url('peminjaman'));
      } else {
        // delete data with status approve if exist
        $userapproval = $this->db->from('userapproval')->where(['id_peminjaman' => $id_peminjaman, 'status' => 'APPROVE'])->get()->result_array();
        foreach ($userapproval as $user) {
          $data = $this->db->select('role_id')->from('user')->where('id', $user['id_user'])->get()->row_array();
          if ($data['role_id'] == $this->session->userdata('role_id')) {
            $this->Userapproval_model->delete($user['id']);
          }
        }

        // add approval status reject
        $data = array(
          'createdat' => date('Y-m-d'),
          'id_peminjaman' => $id_peminjaman,
          'id_user' => $this->session->userdata('id'),
          'status' => 'REJECT',
        );

        $this->Userapproval_model->save($data);
        $this->db->where('id_peminjaman', $id_peminjaman)->update('peminjaman', ['status' => 'REJECTED']);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
          Pengajuan berhasil di ditolak!
        </div>');
        redirect(base_url('peminjaman'));
      }
    } else {
      redirect(base_url() . 'peminjaman');
    }
  }

  public function unreject($id_peminjaman)
  {
    if ($this->session->userdata('role_id') != 1) {
      redirect(base_url() . 'peminjaman');
    }

    //set status peminjaman to process
    $this->Peminjaman_model->update(['status' => 'PROCESS'], $id_peminjaman);

    //delete userapproval with status reject
    $this->db->delete('userapproval', ['id_peminjaman' => $id_peminjaman, 'status' => 'REJECT']);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
          Pembatal Tolak berhasil!
        </div>');
    redirect(base_url('peminjaman'));
  }

  // 
  // add cabang
  public function addcabang()
  {
    $data['title'] = 'Daftar Cabang';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['nama_cabang'] = $this->db->get('cabang')->result_array();

    $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required', [
      'required' => 'Nama Cabang harus di isi !'
    ]);

    if ($this->form_validation->run() == false) {
      $this->load->view('templates/admin_header', $data);
      $this->load->view('templates/admin_sidebar');
      $this->load->view('templates/admin_topbar', $data);
      $this->load->view('cabang/index', $data);
      $this->load->view('templates/admin_footer');
    } else {
      $this->db->insert('cabang', ['nama_cabang' => $this->input->post('nama_cabang')]);
      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Cabang baru berhasil ditambahkan!</div>');
      redirect('cabang');
    }
  }

  public function editcabang($id_cabang = null)
  {
    $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required', [
      'required' => 'Nama Cabang tidak boleh kosong !'
    ]);

    if ($this->form_validation->run() == false) {
      $data['title'] = 'Cabang Edit';
      $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
      $data['nama_cabang'] = $this->db->get_where('cabang', ['id_cabang' => $id_cabang])->row_array();

      $this->load->view('templates/admin_header', $data);
      $this->load->view('templates/admin_sidebar');
      $this->load->view('templates/admin_topbar', $data);
      $this->load->view('cabang/edit_cabang', $data);
      $this->load->view('templates/admin_footer');
      $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal merubah cabang!</div>');
    } else {
      $data = [
        'id_cabang' => $this->input->post('id_cabang'),
        'nama_cabang' => $this->input->post('nama_cabang')
      ];

      $this->db->update('cabang', $data, ['id_cabang' => $id_cabang]);
      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Cabang berhasil dirubah !</div>');
      redirect('cabang');
    }
  }

  // delete cabang
  public function deletecabang($id_cabang = null)
  {
    if (!isset($id_cabang)) show_404();

    $cabangs = $this->Cabang_model;
    if ($cabangs->delete($id_cabang)) {
      redirect('cabang');
    }
  }
}
