<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">

    <header>
        <h4>Broadcast</h4>
    </header>

    <div class="content">
        <div class="content_menu block">

            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <form action="" method="post">
                        <h5 class="pt-3">Kirim ke</h5>
                        <div class="mt-3">
                            <select class="form-select mt-2" aria-label="Default select example" name="topic">
                                <option value="All">Semua</option>
                                <option value="User">Pengguna</option>
                                <option value="Driver">Pengendara</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Judul</label>
                            <input type="text" name="title_message" class="form-control" id="exampleFormControlInput1" placeholder="Tulis Judul Disini...">
                        </div>
                        <div class="mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Kirim Pesan</label>
                            <textarea name="text_message" class="form-control" id="exampleFormControlInput1" placeholder="Tulis Pesan Disini..."></textarea>
                        </div>
                        <div class="mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Gambar (Optional)</label>
                            <input type="text" name="image_message" class="form-control" id="exampleFormControlInput1" placeholder="url gambar">
                        </div>
                        <div class="footer-btn">
                            <button type="submit">Kirim Siaran</button>
                            <button style="margin-left: 10px;" type="reset" class="btnReset">Reset</button>
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