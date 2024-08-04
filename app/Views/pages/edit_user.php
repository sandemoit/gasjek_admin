<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<section class="home_content">
    <header>
        <h4><?= $title ?></h4>
    </header>

    <div class="content">
        <div class="content_menu block">
            <a href="<?= base_url() ?>/user">
                <button class="btnBack">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g data-name="Layer 2">
                            <g data-name="arrow-back">
                                <rect width="24" height="24" transform="rotate(90 12 12)" opacity="0" />
                                <path d="M19 11H7.14l3.63-4.36a1 1 0 1 0-1.54-1.28l-5 6a1.19 1.19 0 0 0-.09.15c0 .05 0 .08-.07.13A1 1 0 0 0 4 12a1 1 0 0 0 .07.36c0 .05 0 .08.07.13a1.19 1.19 0 0 0 .09.15l5 6A1 1 0 0 0 10 19a1 1 0 0 0 .64-.23 1 1 0 0 0 .13-1.41L7.14 13H19a1 1 0 0 0 0-2z" />
                            </g>
                        </g>
                    </svg>
                </button>
            </a>

            <div class="row">
                <div class="col-6">
                    <form class="mt-2 p-3" action="<?= base_url('user/edit_user/' . $users['id_pengguna']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="form-group mb-3">
                            <label class="form-label">Email Pengguna</label>
                            <input type="text" class="form-control" value="<?= $users['email_pengguna']; ?>" disabled readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor WA Pengguna</label>
                            <input type="text" class="form-control <?= ($validation->hasError('nomor_pengguna')) ? 'is-invalid' : '' ?>" placeholder="Ketik Nomor WA Anda" name="nomor_pengguna" value="<?= $users['nomor_pengguna']; ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('nomor_pengguna'); ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control <?= ($validation->hasError('nama_pengguna')) ? 'is-invalid' : '' ?>" placeholder="Ketik Nama Anda" name="nama_pengguna" value="<?= $users['nama_pengguna']; ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama_pengguna'); ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" placeholder="Ketik Password Baru" name="password_new">
                            <div class="invalid-feedback">
                                <?= $validation->getError('password_new'); ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control <?= ($validation->hasError('konfirmasi_password')) ? 'is-invalid' : '' ?>" placeholder="Ketik Ulang Password" name="konfirmasi_password">
                            <div class="invalid-feedback">
                                <?= $validation->getError('konfirmasi_password'); ?>
                            </div>
                        </div>

                        <div class="d-flex mt-5">
                            <button type="reset" class="btnReset">Reset</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

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
            '<?= session()->getFlashData('message_error') ?>',
            'error'
        )
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>