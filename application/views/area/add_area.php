<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            Form Penambahan Data Area
        </div>

        <div class="card-body">
        	<form action="<?php echo site_url('area/addarea') ?>" method="post" enctype="multipart/form-data" >
                <div class="form-group">
                    <label for="area">Nama Cabang*</label>
                    <input class="form-control"
                    type="text" name="area" placeholder="Masukan Nama Area" value="<?= set_value('area'); ?>">
                    <?= form_error('area', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <!-- button save -->
                <input class="btn btn-success" type="submit" name="btn" value="Tambahkan" />
            </form>
        </div>

        <div class="card-footer small text-muted">
            Harap isi dengan data yang benar !
        </div>
	</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->