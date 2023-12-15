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

    <?= $this->session->flashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $title; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Akses</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($user_member as $um) : ?>
                            <tr>
                                <td><?= $index; ?></td>
                                <td><?= $um['name']; ?></td>
                                <td><?= $um['email']; ?></td>
                                <td>
                                    <?php if($um['role_id'] == 1) : ?> <?= 'Administrator' ?>
                                    <?php elseif($um['role_id'] == 2) : ?> <?= 'Sales' ?>
                                    <?php elseif($um['role_id'] == 3) : ?> <?= 'PM' ?>
                                    <?php elseif($um['role_id'] == 4) : ?> <?= 'KoorSales' ?>
                                    <?php elseif($um['role_id'] == 5) : ?> <?= 'HeadRegion' ?>
                                    <?php elseif($um['role_id'] == 6) : ?> <?= 'ManagerSales' ?>
                                    <?php elseif($um['role_id'] == 7) : ?> <?= 'ManagerOps' ?>
                                    <?php elseif($um['role_id'] == 8) : ?> <?= 'PMManager' ?>
                                    <?php elseif($um['role_id'] == 9) : ?> <?= 'CS' ?>
                                    <?php else : ?> <?= 'Purchasing' ?> <?php endif; ?> <br>
                                </td>
                                <td>
                                    <!-- <?php var_dump($um["area"]); ?> -->
                                    <?php foreach ($um['area'] as $key => $area) {

                                        if ($key == (count($um["area"]) - 1)) {
                                            echo ($area['area']);
                                        } elseif (count($um["area"]) > 1) {
                                            echo ($area['area'] . ", ");
                                        } else {
                                            echo ($area['area']);
                                        }
                                    } ?>
                                </td>
                                <td>
                                    <?php if ($um['is_active'] == 1) {
                                        echo 'Active';
                                    } else {
                                        echo 'InActive';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="badge badge-primary" style="font-size:14px;" href="<?= site_url('admin/areaaccess/' . $um['id']); ?>">Atur Area</a>
                                    <a class="badge badge-primary" style="font-size:14px;" href="<?= site_url('admin/detailmember/' . $um['id']); ?>">Detail</a>
                                    <a class="badge badge-success" style="font-size:14px;" href="<?= site_url('admin/editmember/' . $um['id']); ?>">Perbarui</a>
                                    <a class="badge badge-danger" style="font-size:14px;" href="#!" onclick="deleteConfirm('<?= site_url('admin/deletemember/' . $um['id']); ?>')">Hapus</a>
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

<!-- modal delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apa anda yakin ?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Data yg dihapus tidak dapat dipulihkan !</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
            </div>
        </div>
    </div>
</div>