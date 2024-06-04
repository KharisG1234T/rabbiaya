<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getData($start, $length)
	{
		$this->db->limit($length, $start);
		$query = $this->db->get("official_trip");
		return $query->result();
	}

	public function getCountAll($status = "ALL", $searchValue = "", $tgl_awal = "", $tgl_akhir = "", $from = "ALL", $isArchieve = false)
	{
		$this->db->select("official_trip.*, user.name, cabang.nama_cabang");
		$this->db->from('official_trip');
		$this->db->join("user", 'user.id = official_trip.user_id', 'inner');
		$this->db->join("cabang", "cabang.id_area = official_trip.area_id", "inner");


		if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
			$areas = $this->session->userdata("area");
			$areaIds = [0];

			foreach ($areas as $area) {
				array_push($areaIds, (int) $area['area_id']);
			}
			$this->db->where_in('official_trip.area_id', $areaIds);
		}

		if ($this->session->userdata('role_id') == 2) {
			$this->db->where('user.id', $this->session->userdata('id'));
		}

		if ($status !== 'ALL') {
			$this->db->where('official_trip.status', $status);
		}

		if ($from != "ALL") {
			// from mengambil dari id area suatu cabang
			$this->db->where("official_trip.area_id", intval($from));
		}

		if ($isArchieve) {
			$this->db->where('official_trip.deleted_at !=', NULL);
		} else {
			// filter data no softdeleted
			$this->db->where('official_trip.deleted_at IS NULL');
		}

		if ($searchValue && $searchValue != "") {
			$this->db->group_start();
			$this->db->like('user.name', $searchValue);
			$this->db->or_like('official_trip.kode_pengajuan', $searchValue);
			$this->db->or_like('official_trip.destination', $searchValue);
			$this->db->group_end();
		}


		if ($tgl_awal != "" && $tgl_akhir != "") {
			$this->db->group_start();
			$this->db->where('date >=', $tgl_awal);
			$this->db->where('date <=', $tgl_akhir);
			$this->db->group_end();
		}

		// return $this->db->count_all("peminjaman");
		return $this->db->count_all_results(); // count with filtered
	}

	public function getCountFiltered($status, $searchValue, $tgl_awal, $tgl_akhir, $from, $isArchieve = false)
	{
		// Jika Anda memiliki logika filter, implementasikan di sini
		// Untuk sederhana, asumsikan sama dengan getCountAll
		return $this->getCountAll($status, $searchValue, $tgl_awal, $tgl_akhir, $from,  $isArchieve);
	}

	public function getAll($status, $start, $length, $order_by = 'kode_pengajuan', $order_direction = 'asc', $searchValue = "", $tgl_awal = "", $tgl_akhir = "", $from = "ALL", $direction = "ALL", $isArchieve = false)
	{
		$this->db->select("official_trip.*, user.name, cabang.nama_cabang, official_trip.total_amount");
		$this->db->from('official_trip');
		$this->db->join("user", 'user.id = official_trip.user_id', 'inner');
		$this->db->join("cabang", "cabang.id_area = official_trip.area_id", "inner");

		// Filter by area if role is not admin
		if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
			$areas = $this->session->userdata("area");
			$areaIds = [0];

			foreach ($areas as $area) {
				array_push($areaIds, (int) $area['area_id']);
			}
			$this->db->where_in('official_trip.area_id', $areaIds);
		}

		// Filter by user if role is user
		if ($this->session->userdata('role_id') == 2) {
			$this->db->where('user.id', $this->session->userdata('id'));
		}

		// Filter by status
		if ($status !== 'ALL') {
			$this->db->where('official_trip.status', $status);
		}

		// Filter by area
		if ($from != "ALL") {
			$this->db->where("official_trip.area_id", intval($from));
		}

		// Filter by archive status
		if ($isArchieve) {
			$this->db->where('official_trip.deleted_at !=', NULL);
		} else {
			$this->db->where('official_trip.deleted_at IS NULL');
		}

		// Filter by search value
		if ($searchValue && $searchValue != "") {
			$this->db->group_start();
			$this->db->like('user.name', $searchValue);
			$this->db->or_like('official_trip.kode_pengajuan', $searchValue);
			$this->db->or_like('official_trip.destination', $searchValue);
			$this->db->group_end();
		}

		// Filter by date range
		if ($tgl_awal != "" && $tgl_akhir != "") {
			$this->db->group_start();
			$this->db->where('date >=', $tgl_awal);
			$this->db->where('date <=', $tgl_akhir);
			$this->db->group_end();
		}

		// Add sorting
		$this->db->order_by($order_by, $order_direction);

		// Add limit and offset for server-side processing
		if (is_int($start) && is_int($length)) {
			$this->db->limit($length, $start);
		}

		$query = $this->db->get();
		return $query->result_array();
	}

    public function getDetail($id)
    {
        // Query to get main trip details
        $this->db->select("official_trip.*, user.name, cabang.nama_cabang, official_trip.total_amount");
        $this->db->from('official_trip');
        $this->db->join("user", 'user.id = official_trip.user_id', 'inner');
        $this->db->join("cabang", "cabang.id_area = official_trip.area_id", "inner");

        // Filter by area if role is not admin
        if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
            $areas = $this->session->userdata("area");
            $areaIds = [0];

            foreach ($areas as $area) {
                array_push($areaIds, (int) $area['area_id']);
            }
            $this->db->where_in('official_trip.area_id', $areaIds);
        }

        // Filter by user if role is user
        if ($this->session->userdata('role_id') == 2) {
            $this->db->where('user.id', $this->session->userdata('id'));
        }

        // Filter by ID
        $this->db->where('official_trip.id', $id);

        $query = $this->db->get()->row_array();

        // Query to get trip details including activity information
        $tripDetails = $this->db->select('*')->from('official_trip_detail')->where('official_trip_id', $id)->get()->result_array();

        foreach ($tripDetails as &$detail) {
            $activityId = $detail['official_trip_activity_id'];
            $activityInfo = $this->db->select('*')->from('official_trip_activity')->where('id', $activityId)->get()->row_array();
            $detail['activity'] = $activityInfo; // Add activity information to trip detail
        }

        // Query to get trip destinations
        $official_trip_destination = $this->db->from('official_trip_destination')->where('official_trip_id', $id)->get()->result_array();

        // Query to get approval statuses
        $official_trip_approval = $this->db->select('created_at, user_id, status')->from('official_trip_approval')->where('official_trip_id', $id)->get()->result_array();
        $users = [];

        foreach ($official_trip_approval as $key => $user) {
            $data = $this->db->select('ttd, role_id')->from('user')->where('id', $user['user_id'])->get()->row_array();
            if ($user['status'] == "REJECT") {
                $data['ttd'] = 'rejected.png';
            } else if ($data['ttd'] == null) {
                $data['ttd'] = '';
            }
            $data['created_at'] = $user['created_at'];
            array_push($users, $data);
        }

        $official_trip_approval_data['users'] = $users;

        // Build the result array
        $query['official_trip_detail'] = $tripDetails;
        $query['official_trip_destination'] = $official_trip_destination;
        $query['official_trip_approval'] = $official_trip_approval_data;

        return $query;
    }


	public function getById($id)
	{
		return $this->db->get_where('official_trip', ['id' => $id])->row_array();
	}


	public function save($data)
	{
		// Tentukan nilai user_id dan area_id jika tidak ada dalam data
		if (!isset($data['user_id'])) {
			// Set user_id berdasarkan sesi pengguna
			$data['user_id'] = $this->session->userdata('id');
		}

		if (!isset($data['area_id'])) {
			// Set area_id berdasarkan area pertama dari sesi pengguna
			$area = $this->session->userdata('area');
			$data['area_id'] = $area[0]["area_id"];
		}

		// Simpan data pengajuan ke dalam tabel official_trip
		$this->db->insert('official_trip', $data);

		// Dapatkan ID yang baru saja diinsert
		$insert_id = $this->db->insert_id();

		// Kembalikan ID yang baru saja diinsert
		return $insert_id;
	}

	public function update($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('official_trip', $data);
	}

	public function delete($id)
	{

		$data["deleted_at"] =  date('Y-m-d');

		$this->db->where('id', $id);
		$this->db->update('official_trip', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_destination', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_detail', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_approval', $data);
	}

	public function restore($id)
	{
		$data["deleted_at"] =  NULL;

		$this->db->where('id', $id);
		$this->db->update('official_trip', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_destination', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_detail', $data);

		$this->db->where('official_trip_id', $id);
		$this->db->update('official_trip_approval', $data);
	}

	public function generateKodePengajuan($pengajuanId)
	{
		$newNumberFormatted = sprintf('%06d', $pengajuanId);
		$newKodePengajuan = 'RAB-' . $newNumberFormatted;
		return $newKodePengajuan;
	}



	public function savekp($kode_pengajuan)
	{
		// Generate kode pengajuan baru.
		$newKodePengajuan = $this->generateKodePengajuan();

		// Set kode pengajuan dalam data.
		$kode_pengajuan['kode_pengajuan'] = $newKodePengajuan;

		$this->db->trans_start(); // Memulai transaksi database

		// Simpan data ke tabel peminjaman
		$this->db->insert('official_trip', $kode_pengajuan);

		$insert_id = $this->db->insert_id(); // Ambil ID dari data yang baru disimpan

		$this->db->trans_complete(); // Selesaikan transaksi database

		if ($this->db->trans_status() === FALSE) {
			return false; // Transaksi gagal
		} else {
			return $insert_id; // Transaksi berhasil
		}
	}

	public function getUserApprovalByPengajuanId($official_trip_id)
	{
		$this->db->select('status, user_id');
		$this->db->where(' ', $official_trip_id);
		return $this->db->get('official_trip_approval')->result_array();
	}
}
