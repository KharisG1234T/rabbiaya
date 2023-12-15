<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="col-lg-7">
        <?= $this->session->flashdata('message'); ?>
    </div>

    <div class="card col-lg-7 shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><a href="<?= base_url('cabang') ?>"><i class="fas fa-arrow-left"></i> Back</a></h6>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_cabang" value="<?= $nama_cabang['id_cabang']; ?>" />
                <div class="form-group">
                    <label for="nama_cabang">Nama Cabang</label>
                    <input class="form-control" type="text" name="nama_cabang" placeholder="Nama Cabang" value="<?= $nama_cabang['nama_cabang'] ?>" />
                </div>
                <div class="form-group">
                    <input type="hidden" name="old_area" value="<?= $nama_cabang['id_area'] ?>">
                    <label for="id_area">Area Cabang</label>
                    <select class="form-control" id="id_area" name="id_area">
                        <option value="">Pilih Area</option>
                        <?php foreach ($areas as $a) : ?>
                            <option value="<?= $a['id_area'] ?>" <?= ($a['id_area'] == $nama_cabang['id_area']) ? 'selected' : '' ?>>
                                <?= $a['area'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit" name="btn">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Implement Select2 -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.min.css">
<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_area').select2({
            placeholder: 'Pilih Area',
            ajax: {
                url: '<?= base_url('area/get_areas') ?>',
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
        <?php if ($nama_cabang['id_area']) : ?>
            var selectedArea = {
                id: <?= $nama_cabang['id_area'] ?>,
                text: '<?= $nama_cabang['area'] ?>'
            };
            var newOption = new Option(selectedArea.text, selectedArea.id, true, true);
            $('#id_area').append(newOption).trigger('change');
        <?php endif; ?>
    });
</script>