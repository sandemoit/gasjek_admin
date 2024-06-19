<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">
    <header>
        <h4>Pengaturan</h4>
    </header>

    <div class="content">

        <div class="content_menu block">

            <?php if (session()->getFlashData('message')) : ?>
                <script>
                    Swal.fire(
                        'Berhasil!',
                        '<?= session()->getFlashdata('message'); ?> ',
                        'success'
                    )
                </script>
            <?php endif; ?>

            <?php if (session()->getFlashData('message_error')) : ?>
                <script>
                    Swal.fire(
                        'Gagal!',
                        '<?= session()->getFlashdata('message_error'); ?> ',
                        'error'
                    )
                </script>
            <?php endif; ?>
            <div class="container">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Akun</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Aplikasi</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Password</button>
                        <button class="nav-link" id="nav-integrasi-tab" data-bs-toggle="tab" data-bs-target="#nav-integrasi" type="button" role="tab" aria-controls="nav-integrasi" aria-selected="false">Integrasi</button>
                    </div>
                </nav>
                <div class="tab-content mt-4 px-3" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row">

                            <div class="col-lg-9 col-sm-12">
                                <form action="<?= base_url() ?>/setting/update_account/<?= user()->id ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>

                                    <input type="hidden" value="text" name="type">
                                    <label>Role</label>

                                    <input class="form-control" type="text" value="Admin" aria-label="Disabled input example" disabled readonly>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="Nama Admin" value="<?= user()->username ?>" name="username">
                                        <label for="floatingInput">Nama</label>
                                    </div>

                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="floatingEmail" placeholder="Email" value="<?= user()->email ?>" name="email">
                                        <label for="floatingEmail">Email</label>
                                    </div>

                                    <button class="mt-4 ms-0" type="submit">Ubah Akun</button>
                                </form>
                            </div>

                            <div class="col-lg-3 col-sm-12 d-block">
                                <form action="<?= base_url() ?>/setting/update_account/<?= user()->id ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="image" name="type">
                                    <input type="hidden" value="<?= user()->image ?>" name="old_image">
                                    <img src="assets/image/<?= user()->image ?>" alt="" class=" rounded d-block mt-4 w-100" id="preview_image">
                                    <div class="mt-3">
                                        <label for="formFile" class="form-label">Pilih Foto</label>
                                        <input class="form-control" type="file" id="formFile" onchange="preview_account()" ; name="image">
                                    </div>
                                    <button class="mt-4 ms-0" type="submit">Ubah Foto Akun</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <h4>Fitur</h4>
                        <div class="d-flex flex-wrap"> <!-- Flex wrap ditambahkan untuk penyesuaian responsif -->
                            <?php foreach ($features as $row) : ?>
                                <div class="card_fitur mx-2 mb-3 col-lg-4 col-sm-6"> <!-- Mengatur lebar kolom untuk lg dan sm -->
                                    <div class="row">
                                        <div class="col d-flex"> <!-- Mengatur lebar kolom untuk lg dan sm -->
                                            <?= $row['feature_image'] ?>
                                            <div class="d-flex align-items-center">
                                                <h6 class="ms-2 mb-0"> <?= $row['feature_name'] ?> </h6>
                                            </div>
                                        </div>
                                        <div class="col d-flex align-items-center justify-content-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input switch_feature" type="checkbox" id="flexSwitchCheckDefault" <?= $row['feature_status'] ==  "active" ?  "checked" : "" ?> value="<?= $row['id_fitur']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0"><?= $row['feature_description'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="helper mt-3">
                            <h4>
                                General
                            </h4>
                            <form action="<?= base_url('setting/update_aplikasi/1') ?>" method="post">
                                <div class="form-floating mb-3 mt-3">
                                    <input type="text" class="form-control" id="floatingInputAppName" placeholder="Nama Aplikasi" value="<?= $applications['app_name']; ?>" name="app_name">
                                    <label for="floatingInputAppName">Nama Aplikasi</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="number" class="form-control" id="floatingInputAdminPhone" placeholder="Ketik Nomor" value="<?= $applications['admin_phone']; ?>" name="admin_phone">
                                    <label for="floatingInputAdminPhone">Nomor HP Admin</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <input type="number" class="form-control" id="floatingInputWaktuOperasional" placeholder="Batas Waktu Operasional" value="<?= $applications['waktu_operasional']; ?>" name="waktu_operasional">
                                    <label for="floatingInputWaktuOperasional">Waktu Operasional</label>
                                </div>

                                <button class="mt-1 ms-0" type="submit">Update Aplikasi</button>
                            </form>
                        </div>
                        <div class="helper mt-3">
                            <h4>Tampilan</h4>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <form action="<?= base_url() ?>/setting/update_password/<?= user()->id ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="form-floating mt-2">
                                <input name="password" type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" id=" floatingPasswordOld" placeholder="Password">
                                <label for="floatingPasswordOld">Password Lama</label>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('password'); ?>
                                </div>
                            </div>

                            <div class="form-floating mt-3">
                                <input name="new_password" type="password" class="form-control <?= ($validation->hasError('new_password')) ? 'is-invalid' : '' ?>" id=" floatingPasswordNew" placeholder="Password">
                                <label for="floatingPasswordNew">Password Baru</label>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('new_password'); ?>
                                </div>
                            </div>

                            <div class="form-floating mt-3">
                                <input name="confirm_password" type="password" class="form-control <?= ($validation->hasError('confirm_password')) ? 'is-invalid' : '' ?>" id=" floatingPassword" placeholder="Password">
                                <label for="floatingPassword">Konfirmasi Password</label>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('confirm_password'); ?>
                                </div>
                            </div>

                            <button class="mt-4 ms-0" type="submit">Ubah Password</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-integrasi" role="tabpanel" aria-labelledby="nav-integrasi-tab">
                        <form action="<?= base_url('setting/update_integrasi/1') ?>" method="post">
                            <?= csrf_field(); ?>
                            <div class="form-floating mt-2">
                                <input name="key_message" id="key_message" type="text" class="form-control <?= ($validation->hasError('key_message')) ? 'is-invalid' : '' ?>" placeholder="Key Auth Message Firebase" value="<?= $applications['key_message']; ?>">
                                <label for="key_message">Key Auth Message Firebase</label>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('key_message'); ?>
                                </div>
                            </div>

                            <button class="mt-4 ms-0" type="submit">Simpan</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>

    </div>

</section>

<script type="text/javascript">
    $(".switch_feature").click(function() {

        var check_active = $(this).is(':checked') ? "active" : "not_active";

        var check_id = $(this).attr('value');

        $.ajax({
            url: '<?php echo base_url(); ?>/update_fitur/' + check_id,
            type: "POST",
            dataType: 'json',
            data: {
                'id_fitur': check_id,
                'feature_status': check_active
            },
            success: function(data) {
                alert("data");
            },
        });
        return true;
    }, );
</script>

<script>
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
    triggerTabList.forEach(function(triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener('click', function(event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>
<?= $this->endSection(); ?>