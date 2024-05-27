<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">
    <header>
        <h4><?= $title ?></h4>
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
                    <div class="nav nav-tabs no_border" id="nav-tab" role="tablist">
                        <button class="nav-link custom_tab active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Banner</button>
                        <button class="nav-link custom_tab" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Restoran</button>
                        <button class="nav-link custom_tab" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Makanan</button>
                        <button class="nav-link custom_tab" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review" aria-selected="false">Review</button>
                    </div>
                </nav>

                <div class="tab-content mt-3 px-3" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                        <div class="api">
                            <div class="url">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/banner_api'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataBanners": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_banner": "9",</li>
                                                            <li> "position_banner": "1",</li>
                                                            <li> "url_image_banner": "banner(2).png"</li>
                                                        </ul>
                                                        },
                                                    </li>

                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="api">
                            <div class="url">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/restaurant'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataRestaurant": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_restaurant": "9",</li>
                                                            <li> "restaurant_name": "Ayam Lepas Geprek",</li>
                                                            <li> "restaurant_location": "Gang Lampung"</li>
                                                            <li> "latitude_restaurant": "-3.1940"</li>
                                                            <li> "longitude_restaurant": "104.6616"</li>
                                                            <li> "open_restaurant": "10"</li>
                                                            <li> "close_restaurant": "24"</li>
                                                            <li> "restaurant_image": "r523-Lintau-Buo-design.jpg"</li>
                                                            <li> "restaurant_rating": "5"</li>



                                                        </ul>
                                                        },
                                                    </li>
                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">
                        <div class="api mt-3 pb-4">
                            <h4>Ambil Semua Review</h4>
                            <div class="url mt-2">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/review'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataReview": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_review": "23",</li>
                                                            <li> "id_restaurant": "5,</li>
                                                            <li> "id_user": "52"</li>
                                                            <li> "rating": "3"</li>
                                                            <li> "review_user": "mantapsdsdman"</li>
                                                            <li> "user_name": "Rahmatullah"</li>



                                                        </ul>
                                                        },
                                                    </li>

                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_review": "24",</li>
                                                            <li> "id_restaurant": "5,</li>
                                                            <li> "id_user": "53"</li>
                                                            <li> "rating": "5"</li>
                                                            <li> "review_user": "nasi ny banyak"</li>
                                                            <li> "user_name": "isan"</li>



                                                        </ul>
                                                        },
                                                    </li>
                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                            <h4 class="mt-4"> Ambil Review Berdasarkan Restoran</h4>
                            <div class="url mt-2">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/review?id_restaurant=5'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataReview": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_review": "23",</li>
                                                            <li> "id_restaurant": "5,</li>
                                                            <li> "id_user": "52"</li>
                                                            <li> "rating": "3"</li>
                                                            <li> "review_user": "mantapsdsdman"</li>
                                                            <li> "user_name": "Rahmatullah"</li>



                                                        </ul>
                                                        },
                                                    </li>
                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="api mt-3 pb-4">
                            <h4>Ambil Semua Makanan</h4>
                            <div class="url mt-2">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/food'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataFood": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_food": "8",</li>
                                                            <li> "id_restaurant": "5,</li>
                                                            <li> "food_name": "Ayam Geprek"</li>
                                                            <li> "food_price": "12000"</li>
                                                            <li> "food_quantity": "1"</li>
                                                            <li> "food_image": "wallpaperflare.com_wallpaper.jpg"</li>




                                                        </ul>
                                                        },
                                                    </li>

                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_food": "7",</li>
                                                            <li> "id_restaurant": "4,</li>
                                                            <li> "food_name": "Telur"</li>
                                                            <li> "food_price": "8000"</li>
                                                            <li> "food_quantity": "999"</li>
                                                            <li> "food_image": "63a34fa6-4d64-469d-b83f-d1a12c624d5b.jpeg"</li>




                                                        </ul>
                                                        },
                                                    </li>
                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                            <h4 class="mt-4"> Ambil Makanan Berdasarkan Restoran</h4>
                            <div class="url mt-2">
                                <label for="">Url</label>

                                <p class="mt-2"> <span>GET</span> "<?php echo base_url('api/food?id_restaurant=5'); ?>"</p>
                            </div>

                            <div class="url mt-4">
                                <label for="value">Value</label>
                                <div id="value" class="mt-2 aliceblue p-3">
                                    <ul>

                                        {
                                        <li>

                                            <ul>
                                                <li>"status": 1,</li>

                                                <li>"message": "success",</li>

                                                <li>"dataFood": [</li>
                                                <ul>
                                                    <li>
                                                        {
                                                        <ul>

                                                            <li> "id_food": "8",</li>
                                                            <li> "id_restaurant": "5,</li>
                                                            <li> "food_name": "Ayam Geprek"</li>
                                                            <li> "food_price": "12000"</li>
                                                            <li> "food_quantity": "1"</li>
                                                            <li> "food_image": "wallpaperflare.com_wallpaper.jpg"</li>




                                                        </ul>
                                                        },
                                                    </li>
                                                </ul>

                                                <li>]</li>
                                            </ul>
                                        </li>


                                        }
                                    </ul>
                                </div>
                            </div>

                        </div>
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
                //  error: function() {
                //     alert('Something is wrong');
                //  },




            });
            return true;

        },

        // function (dismiss) {
        //     if (dismiss==="cancel") {

        //     }
        // }
    );
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