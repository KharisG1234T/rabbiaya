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
    $this->load->model([
        'Official_trip_activity_model',
        'Official_trip_approval_model',
        'Official_trip_destination_model',
        'Official_trip_detail_model',
        'Userapproval_model',
        'Pengajuan_model',
        'Cabang_model',
        'Area_model'
    ]);
    $this->load->library('form_validation');
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
          $userApprovalData = $this->Pengajuan_model->getUserApprovalByPengajuanId($itemArray['official_trip_id']);

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
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/restore/') . $itemArray['official_trip_id'] . '" onclick="return confirm(' . "'Anda yakin ingin me-restore data ini?'" . ')"><i class="fas fa fa-recycle"></i>&nbsp;&nbsp; Restore</a>';
        }
      } else {
        // btn action for list actived data

        // Menggabungkan nilai dari kolom userApprovals dan keterangan_sku
        $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/detail/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-eye"></i>&nbsp;&nbsp; Detail</a>';

        // Admin atau Sales
        $roleId = $this->session->userdata('role_id');
        $action = '';

        // Jika status item adalah "PENDING", hanya role_id 1 atau 2 yang bisa mengedit.
        if ($itemArray["status"] == "PENDING") {
            if (in_array($roleId, array(1, 2))) {
                $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/edit/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-pen"></i>&nbsp;&nbsp; Perbarui</a>';
            }
        }
        // Jika status item bukan "PENDING", hanya role_id 1, 4, 5, atau 11 yang bisa mengedit.
        else {
            if (in_array($roleId, array(1, 4, 5, 11))) {
                $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/edit/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-pen"></i>&nbsp;&nbsp; Perbarui</a>';
            }
        }

        // Bukan Sales dan PM
        if ($itemArray["status"] == "PROCESS" && in_array($this->session->userdata('role_id'), array(4, 5, 6, 7, 8))) {
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/approve/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-check-double"></i>&nbsp;&nbsp; Approve</a>';
        }

        // Bukan Sales dan PM
        if ($itemArray["status"] == "PROCESS" && in_array($this->session->userdata('role_id'), array(1, 3, 8, 9, 10))) {
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/reject/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-minus"></i>&nbsp;&nbsp; Tolak</a>';
        }

        // Admin
        if ($itemArray["status"] == "REJECTED" && in_array($this->session->userdata('role_id'), array(1))) {
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/unreject/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-recycle"></i>&nbsp;&nbsp; Batal Tolak</a>';
        }

        // Admin atau Sales
        if ($this->session->userdata('role_id') == 1) {
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/delete/') . $itemArray['official_trip_id'] . '" onclick="return confirm(' . "'Anda yakin ingin menghapus data ini?'" . ')"><i class="fas fa fa-trash"></i>&nbsp;&nbsp; Hapus</a>';
        }

        // Admin atau Sales
        if (in_array($this->session->userdata('role_id'), array(1, 2, 4))) {
          $action .= '<a class="dropdown-item" href="' . base_url('pengajuan/print/') . $itemArray['official_trip_id'] . '"><i class="fas fa fa-print"></i>&nbsp;&nbsp; Cetak</a>';
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

  // page tambah pengajuan
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

    // update pengajuan
    public function update()
    {
      $userId = $this->session->userdata("id");
      $roleId = $this->session->userdata('role_id');
      $area = $this->session->userdata('area');
      $areaId = $area[0]["area_id"];
  
      $idPengajuan = $this->input->post('id');
      $dataPengajuan = array(
        'user_id' => $roleId == "1" ? $this->input->post('userId') : $userId,
        'area_id' => $roleId == "1" ? $this->input->post('from') : $areaId,
        'request_date' => date('Y-m-d'),
        'departure_date' => date('Y-m-d'),
        'destination' => $this->input->post('destination'),
        'total_amount' => $this->input->post('total_amount'),
        'level_id' => $this->input->post('level_id')
      );
  
      $this->Peminjaman_model->update($dataPengajuan, $idPengajuan);
  
      // hapus barang lama
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


  public function detail($id_pengajuan)
  {
    $data['title'] = 'Detail Pengajuan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['pengajuan'] = $this->Pengajuan_model->getDetail($id_pengajuan);
    $data['pengajuan']['approve']['sales'] = ['ttd' => '', 'createdat' => ''];
    $data['pengajuan']['approve']['ks'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['pengajuan']['approve']['hr'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['pengajuan']['approve']['hrd'] = ['ttd' => 'waiting.png', 'createdat' => ''];


    foreach ($data['pengajuan']['userapproval']['users'] as $user) {
      if ($user['role_id'] == 2) {
        $data['pengajuan']['approve']['sales'] = $user;
      }
      if ($user['role_id'] == 4) {
        $data['pengajuan']['approve']['ks'] = $user;
      }
      if ($user['role_id'] == 5) {
        $data['pengajuan']['approve']['hr'] = $user;
      }
      if ($user['role_id'] == 11) {
        $data['pengajuan']['approve']['hrd'] = $user;
      }
    }

    // unset array userapproval
    unset($data['pengajuan']['userapproval']);

    $this->load->view('templates/admin_header', $data);
    $this->load->view('templates/admin_sidebar');
    $this->load->view('templates/admin_topbar', $data);
    $this->load->view('pengajuan/detail_pengajuan', $data);
    $this->load->view('templates/admin_footer');
  }

  public function print($id)
  {
    $data['title'] = 'Detail Pengajuan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['pengajuan'] = $this->Pengajuan_model->getDetail($id_pengajuan);
    $data['pengajuan']['approve']['sales'] = ['ttd' => '', 'createdat' => ''];
    $data['pengajuan']['approve']['ks'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['pengajuan']['approve']['hr'] = ['ttd' => 'waiting.png', 'createdat' => ''];
    $data['pengajuan']['approve']['hrd'] = ['ttd' => 'waiting.png', 'createdat' => ''];

    foreach ($data['peminjaman']['userapproval']['users'] as $user) {
      if ($user['role_id'] == 2) {
        $data['pengajuan']['approve']['sales'] = $user;
      }
      if ($user['role_id'] == 4) {
        $data['pengajuan']['approve']['ks'] = $user;
      }
      if ($user['role_id'] == 5) {
        $data['pengajuan']['approve']['hr'] = $user;
      }
      if ($user['role_id'] == 11) {
        $data['pengajuan']['approve']['hrd'] = $user;
      }
    }

    $pdf = $this->load->view("pengajuan/print", $data, true);

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

  public function approve_trip()
      {
          // Validasi input
          $this->form_validation->set_rules('official_trip_id', 'Official Trip ID', 'required|integer');
          $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');

          if ($this->form_validation->run() === FALSE) {
              // Jika validasi gagal, kembalikan pesan kesalahan
              return $this->output->set_content_type('application/json')->set_output(json_encode([
                  'status' => 'error',
                  'message' => validation_errors()
              ]));
          }

          $official_trip_id = $this->input->post('official_trip_id');
          $user_id = $this->input->post('user_id');

          // Pastikan official_trip ada dan memiliki status 'PROCESS'
          $official_trip = $this->Official_trip_activity_model->get_by_id($official_trip_id); // Pastikan metode ini ada
          if (!$official_trip || $official_trip->status !== 'PROCESS') {
              return $this->output->set_content_type('application/json')->set_output(json_encode([
                  'status' => 'error',
                  'message' => 'Official trip tidak valid atau tidak dalam status PROCESS'
              ]));
          }

          // Update status official_trip ke 'APPROVE'
          $update_data = ['status' => 'APPROVE'];
          $this->Official_trip_activity_model->update($official_trip_id, $update_data); // Pastikan metode ini ada

          // Tambahkan entri ke official_trip_approval
          $approval_data = [
              'official_trip_id' => $official_trip_id,
              'user_id' => $user_id,
              'level_id' => 1, // Koordinator level
              'status' => 'APPROVE',
              'created_at' => date('Y-m-d H:i:s') // Timestamp sekarang
          ];
          $this->Official_trip_approval_model->insert($approval_data); // Pastikan metode ini ada

          // Kembalikan respons sukses
          return $this->output->set_content_type('application/json')->set_output(json_encode([
              'status' => 'success',
              'message' => 'Official trip telah di-approve oleh koor'
          ]));
      }
  }


  public function reject($official_trip_id)
    {
        // Periksa apakah pengguna memiliki peran yang tepat
        $allowed_roles = [4, 5, 11]; // Misalnya, 1 = koor, 2 = headreg, 3 = HRD
        if (!in_array($this->session->userdata('role_id'), $allowed_roles)) {
            redirect(base_url('official_trip'));
        }

        // Periksa apakah official_trip memiliki status PROCESS
        $official_trip = $this->Official_trip_activity_model->get_by_id($official_trip_id);
        if (!$official_trip || $official_trip->status !== 'PROCESS') {
            redirect(base_url('official_trip'));
        }

        // Periksa apakah sudah pernah direject oleh peran yang sama
        $rejected_list = $this->db->from('official_trip_approval')
            ->where(['official_trip_id' => $official_trip_id, 'status' => 'REJECT'])
            ->get()
            ->result_array();

        $exist = false;
        foreach ($rejected_list as $user) {
            $data = $this->db->select('level_id')
                ->from('user')
                ->where('id', $user['user_id'])
                ->get()
                ->row_array();
            if ($data['level_id'] == $this->session->userdata('role_id')) {
                $exist = true;
                break;
            }
        }

        if ($exist) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sudah pernah ditolak oleh peran yang sama!</div>');
            redirect(base_url('official_trip'));
        } else {
            // Jika belum direject, lakukan operasi reject
            $approval_data = [
                'created_at' => date('Y-m-d H:i:s'),
                'official_trip_id' => $official_trip_id,
                'user_id' => $this->session->userdata('id'),
                'level_id' => $this->session->userdata('role_id'),
                'status' => 'REJECT'
            ];

            $this->Official_trip_approval_model->insert($approval_data); // Pastikan metode ini ada

            // Update status official_trip ke REJECTED
            $this->db->where('official_trip_id', $official_trip_id)->update('official_trip', ['status' => 'REJECTED']);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengajuan berhasil ditolak!</div>');
            redirect(base_url('official_trip'));
        }
    }


    public function unreject($official_trip_id)
    {
        // Periksa apakah pengguna memiliki hak akses untuk melakukan unreject
        $allowed_roles = [1]; // Contoh, hanya koordinator dan headreg yang bisa unreject
        if (!in_array($this->session->userdata('role_id'), $allowed_roles)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak memiliki hak untuk membatalkan penolakan!</div>');
            redirect(base_url('official_trip'));
        }
    
        // Periksa apakah official_trip memiliki status REJECTED
        $official_trip = $this->Official_trip_activity_model->get_by_id($official_trip_id);
        if (!$official_trip || $official_trip->status !== 'REJECTED') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pengajuan tidak dalam status ditolak!</div>');
            redirect(base_url('official_trip'));
        }
    
        // Ubah status official_trip menjadi PROCESS
        $this->Official_trip_activity_model->update($official_trip_id, ['status' => 'PROCESS']);
    
        // Hapus semua entri di official_trip_approval dengan status REJECT untuk ID ini
        $this->db->delete('official_trip_approval', ['official_trip_id' => $official_trip_id, 'status' => 'REJECT']);
    
        // Tambahkan entri approval dengan status UNREJECT
        $approval_data = [
            'created_at' => date('Y-m-d H:i:s'),
            'official_trip_id' => $official_trip_id,
            'user_id' => $this->session->userdata('id'),
            'level_id' => $this->session->userdata('role_id'),
            'status' => 'UNREJECT'
        ];
    
        $this->Official_trip_approval_model->insert($approval_data); // Pastikan metode ini ada
    
        // Beri pesan sukses
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Penolakan telah dibatalkan. Status kembali menjadi PROSES.</div>');
        redirect(base_url('official_trip'));
    }
    
}
