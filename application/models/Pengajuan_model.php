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


		// Tambahkan sorting berdasarkan kode_pengajuan
		$this->db->order_by($order_by, $order_direction);

		// Tambahkan limit dan offset untuk server-side processing
		if (is_int($start) && is_int($length)) {
			$this->db->limit($length, $start);
		}

		// print_r($this->db->get_compiled_select()); 
		// die;
		$query = $this->db->get();
		return $query->result_array();
	}

	function getDetail($id)
	{
		$this->db->select("official_trip.*, area.area, ar.area as from_ar, user.name");
		$this->db->from('official_trip');
		$this->db->join("cabang", "cabang.id_area = official_trip.area_id", "inner");
		$this->db->join("user", "user.id = official_trip.user_id", "inner");
		$this->db->where('official_trip.id', $id);
		$query = $this->db->get()->row_array();

		// relation one to many
		$official_trip_destination = $this->db->from('official_trip_destination')->where('official_trip_id', $id)->get()->result_array();
		$official_trip_detail = $this->db->from('official_trip_detail')
			->where('official_trip_id')
			->where('official_trip_activity_id')
			->get()
			->result_array();
		$official_trip_approval = $this->db->select('created_at, user_id, status')->from('official_trip_approval')->where('official_trip_id', $id)->get()->result_array();
		$users = [];

		foreach ($official_trip_approval as $key => $user) {
			$data = $this->db->select('ttd, role_id')->from('user')->where('id', $user['id_user'])->get()->row_array();
			if ($user['status'] == "REJECT") {
				$data['ttd'] = 'rejected.png';
			} else if ($data['ttd'] == null) {
				$data['ttd'] = '';
			}
			$data['created_at'] = $user['created_at'];
			array_push($users, $data);
			unset($official_trip_approval[$key]);
		}

		$official_trip_approval['users'] = $users;
		$query['official_trip_destination'] = $official_trip_destination;
		$query['official_trip_detail'] = $official_trip_detail;
		$query['official_trip_approval'] = $official_trip_approval;
		return $query;
	}

	public function getById($id)
	{
		return $this->db->get_where('official_trip', ['id' => $id])->row_array();
	}


	public function save($data)
	{
		$this->db->insert('official_trip', $data);
		$insert_id = $this->db->insert_id();

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
