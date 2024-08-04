<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">

    <header>
        <h4>Peta</h4>
    </header>

    <div class="content">
        <div class="content_menu block">
            <a href="map">
                <button class="btnRefresh">Refresh</button>
            </a>

            <div class="row">
                <div class="col-lg-3 col-sm-12">
                    <h4 class="pt-3">Harga dan Jarak</h4>
                    <form action="<?= base_url() ?>/update_map" method="post" class="mt-3">
                        <input type="hidden" value="<?= $distances['id'] ?>" name="id">
                        <input type="hidden" value="price" name="type">
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 0Km - 1Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('1km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="1km" value="<?= $distances['1km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('1km'); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 1.1Km - 2Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('2km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="2km" value="<?= $distances['2km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('2km'); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 2.1Km - 2.7Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('dua_koma_tujuh_km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="dua_koma_tujuh_km" value="<?= $distances['dua_koma_tujuh_km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('dua_koma_tujuh_km'); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 2.7Km - 3Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('3km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="3km" value="<?= $distances['3km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('3km'); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 3.1Km - 3.5Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('tiga_setengah_km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="tiga_setengah_km" value="<?= $distances['tiga_setengah_km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('tiga_setengah_km'); ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="exampleInputEmail1" class="form-label">Jarak 3.6Km - 4Km</label>
                            <input type="text" class="form-control <?= ($validation->hasError('4km')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="4km" value="<?= $distances['4km'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('4km'); ?>
                            </div>
                        </div>
                        <button style="margin-top: 20px;" type="submit">Ubah Jarak</button>
                    </form>
                </div>

                <div class="col-lg-3 col-sm-12">
                    <div class="row">
                        <h4 class="mt-3">Saldo Beku</h4>
                        <form action="<?= base_url() ?>/update_map" method="post">
                            <input type="hidden" value="<?= $distances['id'] ?>" name="id">
                            <input type="hidden" value="minimum" name="type">
                            <div class="mt-3">
                                <label for="exampleInputEmail1" class="form-label">Saldo</label>
                                <input type="text" class="form-control <?= ($validation->hasError('minimum_balance')) ? 'is-invalid' : '' ?>" id=" exampleInputEmail1" name="minimum_balance" value="<?= $distances['minimum_balance'] ?>">
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('minimum_balance'); ?>
                                </div>
                            </div>
                            <button style="margin-top: 20px;" type="submit">Ubah Minimum Saldo</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <div class="row">
                        <h4 class="mt-4">Api Key</h4>
                        <form action="<?= base_url() ?>/update_map" method="post">
                            <input type="hidden" value="<?= $distances['id'] ?>" name="id">
                            <input type="hidden" value="api_key" name="type">
                            <div class="mt-3">
                                <label class="form-label">Pengguna (Google Maps) </label>
                                <input type="text" class="form-control <?= ($validation->hasError('api_key_user')) ? 'is-invalid' : '' ?>" name="api_key_user" value="<?= base64_decode($distances['api_key_user']) ?>">
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('api_key_user'); ?>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Driver (MapBox) </label>
                                <input type="text" class="form-control <?= ($validation->hasError('api_key_user')) ? 'is-invalid' : '' ?>" name="api_key_user" value="<?= base64_decode($distances['api_key_user']) ?>">
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('api_key_user'); ?>
                                </div>
                            </div>
                            <button style="margin-top: 20px;" type="submit">Ubah Api Key</button>
                        </form>
                    </div>
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