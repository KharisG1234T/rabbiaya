<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <h5>Akses Sebagai : <?= $users['name']; ?></h5>
    <div class="col-lg-7">
        <?= $this->session->flashdata('message'); ?>
    </div>

    <form method="post" action="<?= base_url('admin/changearea') ?>">
        <input type="hidden" name="userId" value="<?= $users['id']; ?>">

        <div class="card col-lg-7 shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><a href="<?= base_url('admin/datamember') ?>"><i class="fas fa-arrow-left"></i> Back</a></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Area</th>
                                <th>Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach($area as $ar) : ?>
                                <tr>
                                    <td><?= $index; ?></td>
                                    <td><?= $ar['area']; ?></td>
                                    <td>
                                        <div class="form-check">
                                            <?php $checked = check_area($users['id'], $ar['id_area']); ?>
                                            <input class="form-check-input" type="checkbox" name="areas[]" value="<?= $ar['id_area'] ?>" <?= $checked ?>>
                                        </div>
                                    </td>
                                </tr>
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>

</div>
<!-- End of Main Content -->
