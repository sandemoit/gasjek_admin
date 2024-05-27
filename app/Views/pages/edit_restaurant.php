<?= $this->extend('layout/template');?>
<?=$this->section('content');?>
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
                        '<?= session()->getFlashData('message_error')?>',
                        'error'
                        )
                    </script>            
            

                <?php endif; ?>

                    <a href="<?= base_url()?>/restaurant">
                        <button class="btnBack">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="arrow-back">
                                        <rect width="24" height="24" transform="rotate(90 12 12)" opacity="0"/>
                                        <path d="M19 11H7.14l3.63-4.36a1 1 0 1 0-1.54-1.28l-5 6a1.19 1.19 0 0 0-.09.15c0 .05 0 .08-.07.13A1 1 0 0 0 4 12a1 1 0 0 0 .07.36c0 .05 0 .08.07.13a1.19 1.19 0 0 0 .09.15l5 6A1 1 0 0 0 10 19a1 1 0 0 0 .64-.23 1 1 0 0 0 .13-1.41L7.14 13H19a1 1 0 0 0 0-2z"/>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </a>

                
                    <form class="mt-2 p-3" action="<?=base_url()?>/restaurant/edit" method="POST" enctype="multipart/form-data">
                            <?= csrf_field(); ?>   

                            <input type="hidden" value="<?=$restaurants['id_restaurant'];?>" name="id_restaurant">

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama Restoran</label>
                                <input type="text" class="form-control <?= ($validation->hasError('restaurant_name')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Nama Restoran" name="restaurant_name" value="<?=$restaurants['restaurant_name'];?>" >
                                        
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    <?= $validation->getError('restaurant_name'); ?>
                                </div>
                            </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Lokasi Restoran</label>
                                            <input type="text" class="form-control <?= ($validation->hasError('restaurant_location')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Lokasi Restoran" name="restaurant_location" value="<?=$restaurants['restaurant_location'];?>" >
                                        
                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('restaurant_location'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Jam Buka Restoran</label>
                                            <input type="number" class="form-control <?= ($validation->hasError('open_restaurant')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Jam Buka Restoran" name="open_restaurant" value="<?=$restaurants['open_restaurant'];?>">
                                        
                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('open_restaurant'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Jam Tutup Restoran</label>
                                            <input type="number" class="form-control <?= ($validation->hasError('close_restaurant')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Jam Tutup Restoran" name="close_restaurant" value="<?=$restaurants['close_restaurant'];?>" >
                                        
                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('close_restaurant'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Latitude Restoran</label>
                                            <input type="text" class="form-control <?= ($validation->hasError('latitude_restaurant')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Latitude Restoran" name="latitude_restaurant" value="<?=$restaurants['latitude_restaurant'];?>">
                                        
                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('latitude_restaurant'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Longitude Restoran</label>
                                            <input type="text" class="form-control <?= ($validation->hasError('longitude_restaurant')) ? 'is-invalid' : '' ?>"" id="exampleFormControlInput1" placeholder="Ketik Longitude Restoran" name="longitude_restaurant" value="<?=$restaurants['longitude_restaurant'];?>">
                                        
                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('longitude_restaurant'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Gambar Restoran</label>
                                            <input class="form-control <?= ($validation->hasError('url_image_restaurant')) ? 'is-invalid' : '' ?>"" type="file" id="img_file" name="url_image_restaurant" onchange="preview()">

                                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                <?= $validation->getError('url_image_restaurant'); ?>
                                            </div>

                                            <input type="hidden" value="<?=$restaurants['restaurant_image'];?>" name="old_image">
                                            <p class="mt-3">Preview</p>
                                            <img class="preview_image" src="<?= base_url()?>/assets/restaurants/<?= $restaurants['restaurant_image']; ?>" alt="">
                                        </div>

                                        <div class="d-flex mt-5">
                                            <button type="reset" class="btnReset">Reset</button>
                                            <button type="submit" class="btn btn-primary">Ubah</button>
                                        </div>

                    </form>
                

            </div>



        </div>
        </section>
   

<?=$this->endSection();?>