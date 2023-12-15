<!-- Tambahkan library jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function deleteConfirm(url) {
        $('#btn-delete').attr('href', url);
        $('#deleteModal').modal();
    }
</script>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="col-lg-7">
        <?= form_error('nama_cabang', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
        <?= $this->session->flashdata('message'); ?>
    </div>

    <div class="card col-lg-7 shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><a href="" data-toggle="modal" data-target="#newCabangModal"><i class="fas fa-plus"></i> Cabang</a></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Cabang</th>
                            <th>Area</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($cabangs as $cab) : ?>
                            <tr>
                                <td><?= $index; ?></td>
                                <td><?php echo $cab['nama_cabang'] ?></td>
                                <td><?php echo $cab['area']; ?></td>
                                <td>
                                    <a class="badge badge-success mr-2" style="font-size:14px;" href="<?= site_url('cabang/editcabang/' . $cab['id_cabang']); ?>">Perbarui</a>
                                    <form action="<?= site_url('cabang/deletecabang') ?>" method="post">
                                        <input type="hidden" name="id_cabang" value="<?= $cab['id_cabang'] ?>">
                                        <button class="btn btn-sm badge badge-danger" type="submit" onclick="return confirm('Hapus Cabang ?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal add new cabang-->
<div class="modal fade" id="newCabangModal" tabindex="-1" role="dialog" aria-labelledby="newCabangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCabangModalLabel">Tambah Cabang Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- form -->
            <form action="<?= site_url('cabang/addcabang'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nama_cabang" name="nama_cabang" placeholder="Nama Cabang">
                    </div>
                    <div class="form-group">
                        <label for="id_area">Area Cabang</label>
                        <select class="form-control" id="id_area" name="id_area">
                            <option value="">Pilih Area</option>
                            <?php foreach ($areas as $a) : ?>
                                <option value="<?= $a['id_area'] ?>">
                                    <?= $a['area'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
