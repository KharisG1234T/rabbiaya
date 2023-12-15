<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-3">
        <div class="card-header">
            Form Penambahan Data Cabang
        </div>

        <div class="card-body">
        	<form action="<?php echo site_url('cabang/addcabang') ?>" method="post" enctype="multipart/form-data" >
                <div class="form-group">
                    <label for="nik">Nama Cabang*</label>
                    <input class="form-control"
                    type="text" name="nama_cabang" placeholder="Masukan Nama Cabang" value="<?= set_value('nama_cabang'); ?>">
                    <?= form_error('nama_cabang', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <!-- button save -->
                <input class="btn btn-success" type="submit" name="btn" value="Tambahkan" />
            </form>
        </div>

        <div class="card-footer small text-muted">
            Harap isi dengan nama yang benar !
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