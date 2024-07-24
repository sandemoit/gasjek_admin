<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<section class="home_content">

    <header>
        <h4><?= $title ?></h4>
    </header>

    <div class="content">

        <div class="content_menu block">



            <!-- <div class="info">
                    <h5>Harap Jika ingin Refresh Setelah Menambahkan Restoran Baru untuk Menggunakan Tombol Refresh yang sudah Disediakan Bukan dari Web </h5>
                </div> -->

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

            <a href="<?= base_url() ?>/view_restaurant/<?= $foods['id_restaurant']; ?>">
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


            <form class="mt-2 p-3" action="<?= base_url() ?>/restaurant/edit_food" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" value="<?= $foods['id_food']; ?>" name="id_food">
                <input type="hidden" value="<?= $foods['id_restaurant']; ?>" name="id_restaurant">


                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Makanan</label>
                    <input type="text" class="form-control <?= ($validation->hasError('food_name')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Ketik Nama Restoran" name="food_name" value="<?= $foods['food_name']; ?>">

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_name'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Harga Makanan</label>
                    <input type="number" class="form-control <?= ($validation->hasError('food_price')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Ketik Harga Makanan" name="food_price" value="<?= $foods['food_price']; ?>">

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_price'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Jumlah Makanan</label>
                    <input type="number" class="form-control <?= ($validation->hasError('food_quantity')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Ketik Jumlah Makanan" name="food_quantity" value="<?= $foods['food_quantity']; ?>">

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_quantity'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Deskripsi Makanan</label>
                    <input type="text" class="form-control <?= ($validation->hasError('food_desc')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Ketik Deskripsi Makanan" name="food_desc" value="<?= $foods['food_desc']; ?>">

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_desc'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Kategori</label>
                    <select name="food_category" id="food_category" class="form-select">
                        <option value="food" <?= ($foods['food_category'] == 'food') ? 'selected' : '' ?>>Food</option>
                        <option value="drink" <?= ($foods['food_category'] == 'drink') ? 'selected' : '' ?>>Drink</option>
                    </select>

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_category'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Gambar Makanan</label>
                    <input class="form-control <?= ($validation->hasError('food_image')) ? 'is-invalid' : '' ?>" type="file" id="formFile" name="food_image" onchange="preview()">

                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        <?= $validation->getError('food_image'); ?>
                    </div>

                    <input type="hidden" value="<?= $foods['food_image']; ?>" name="old_image">
                    <p class="mt-3">Preview</p>
                    <img class="preview_image" src="<?= base_url() ?>/assets/foods/<?= $foods['food_image']; ?>" alt="">
                </div>
                <div class="d-flex mt-5">
                    <button type="reset" class="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>



    </div>
</section>


<?= $this->endSection(); ?>