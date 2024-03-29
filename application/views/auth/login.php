<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><?= $title; ?></h1>
                                    <?= $this->session->flashdata('message'); ?>
                                </div>
                                <form class="user" method="post" action="<?= base_url('auth'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email" value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-user btn-block">
                                        Login Akun
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="btn btn-warning btn-user btn-block" href="<?= site_url('/auth/registration') ?>">Registrasi Akun</a>
                                </div>
                                <div class="text-center">
                                    <a class="small">Perlu Bantuan ?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="https://api.whatsapp.com/send/?phone=%2B6283866306912&text&type=phone_number&app_absent=0">Hubungi Tim IT Sekarang!</a>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>