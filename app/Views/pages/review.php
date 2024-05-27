<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<section class="home_content">
    <header>

        <h4><?= $restaurants['restaurant_name']; ?></h4>

    </header>

    <div class="content">

        <div class="content_menu block">

            <a href="<?= base_url() ?>/restaurant">
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

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= base_url('/food/save') ?>" method=" POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Makanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>


                            <input type="hidden" value="<?= $restaurants['id_restaurant']; ?>" name="id_restaurant">

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama Makanan</label>
                                    <input type="text" class="form-control <?= ($validation->hasError('food_name')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Nama Makanan" name="food_name" type="text">

                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        <?= $validation->getError('food_name'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Harga Makanan</label>
                                    <input type="number" class="form-control <?= ($validation->hasError('food_price')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Harga Makanan" name="food_price">

                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        <?= $validation->getError('food_price'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Jumlah Makanan</label>
                                    <input type="number" class="form-control <?= ($validation->hasError('food_quantity')) ? 'is-invalid' : '' ?>"" id=" exampleFormControlInput1" placeholder="Jumlah Makanan" name="food_quantity">

                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        <?= $validation->getError('food_quantity'); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Gambar Makanan</label>
                                    <input class="form-control <?= ($validation->hasError('food_image')) ? 'is-invalid' : '' ?>"" type=" file" id="formFile" name="food_image">

                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        <?= $validation->getError('food_image'); ?>
                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btnReset">Reset</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="bg">
                <div id="modal-kotak">

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class=" header_dialog">
                            <h5>Tambah Makanan</h5>

                            <div class="bg_close_button">
                                <button id="btnClose">X</button>
                            </div>

                        </div>
                        <div class="content_dialog">
                            <div class="fields">

                                <h5>Nama Makanan</h5>
                                <div class="field">
                                    <input name="food_name" type="text" placeholder="Nama Makanan" required>
                                </div>

                                <h5>Harga Makanan</h5>
                                <div class="field">
                                    <input name="food_price" type="number" placeholder="Harga Makanan" required>
                                </div>

                                <h5>Jumlah Makanan</h5>
                                <div class="field">
                                    <input name="food_quantity" type="number" placeholder="Jumlah Makanan" required>
                                </div>

                                <h5>Gambar Makanan</h5>
                                <div class="field">
                                    <input type="file" name="food_image" required>

                                </div>

                            </div>
                        </div>

                        <div class="footer_dialog">

                            <button type="submit">Tambah</button>
                            <button class="btnReset" type="reset">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

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


            <a href="">
                <button class="btnRefresh">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g data-name="Layer 2">
                            <g data-name="refresh">
                                <rect width="24" height="24" opacity="0" />
                                <path d="M20.3 13.43a1 1 0 0 0-1.25.65A7.14 7.14 0 0 1 12.18 19 7.1 7.1 0 0 1 5 12a7.1 7.1 0 0 1 7.18-7 7.26 7.26 0 0 1 4.65 1.67l-2.17-.36a1 1 0 0 0-1.15.83 1 1 0 0 0 .83 1.15l4.24.7h.17a1 1 0 0 0 .34-.06.33.33 0 0 0 .1-.06.78.78 0 0 0 .2-.11l.09-.11c0-.05.09-.09.13-.15s0-.1.05-.14a1.34 1.34 0 0 0 .07-.18l.75-4a1 1 0 0 0-2-.38l-.27 1.45A9.21 9.21 0 0 0 12.18 3 9.1 9.1 0 0 0 3 12a9.1 9.1 0 0 0 9.18 9A9.12 9.12 0 0 0 21 14.68a1 1 0 0 0-.7-1.25z" />
                            </g>
                        </g>
                    </svg>
                </button>
            </a>

            <div class="table_content flex_99">

                <table>

                    <tr>
                        <th>
                            ID
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Komentar
                        </th>

                        <th>
                            Penilaian
                        </th>

                    </tr>

                    <?php $no = 1 + (5 * ($current_page - 1)); ?>
                    <?php if (empty($reviews)) {
                    ?>

                        <tr>
                            <td colspan="6">
                                <div>
                                    <h4 class="mt-4">Tidak ada data</h4>
                                    <svg class="empty mt-3 pb-5 pt-2 ps-5 pe-5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" data-name="Layer 1" width="647.63626" height="632.17383" viewBox="0 0 647.63626 632.17383">
                                        <path d="M687.3279,276.08691H512.81813a15.01828,15.01828,0,0,0-15,15v387.85l-2,.61005-42.81006,13.11a8.00676,8.00676,0,0,1-9.98974-5.31L315.678,271.39691a8.00313,8.00313,0,0,1,5.31006-9.99l65.97022-20.2,191.25-58.54,65.96972-20.2a7.98927,7.98927,0,0,1,9.99024,5.3l32.5498,106.32Z" transform="translate(-276.18187 -133.91309)" fill="#f2f2f2" />
                                        <path d="M725.408,274.08691l-39.23-128.14a16.99368,16.99368,0,0,0-21.23-11.28l-92.75,28.39L380.95827,221.60693l-92.75,28.4a17.0152,17.0152,0,0,0-11.28028,21.23l134.08008,437.93a17.02661,17.02661,0,0,0,16.26026,12.03,16.78926,16.78926,0,0,0,4.96972-.75l63.58008-19.46,2-.62v-2.09l-2,.61-64.16992,19.65a15.01489,15.01489,0,0,1-18.73-9.95l-134.06983-437.94a14.97935,14.97935,0,0,1,9.94971-18.73l92.75-28.4,191.24024-58.54,92.75-28.4a15.15551,15.15551,0,0,1,4.40966-.66,15.01461,15.01461,0,0,1,14.32032,10.61l39.0498,127.56.62012,2h2.08008Z" transform="translate(-276.18187 -133.91309)" fill="#3f3d56" />
                                        <path d="M398.86279,261.73389a9.0157,9.0157,0,0,1-8.61133-6.3667l-12.88037-42.07178a8.99884,8.99884,0,0,1,5.9712-11.24023l175.939-53.86377a9.00867,9.00867,0,0,1,11.24072,5.9707l12.88037,42.07227a9.01029,9.01029,0,0,1-5.9707,11.24072L401.49219,261.33887A8.976,8.976,0,0,1,398.86279,261.73389Z" transform="translate(-276.18187 -133.91309)" fill="#ef9904" />
                                        <circle cx="190.15351" cy="24.95465" r="20" fill="#ef9904" />
                                        <circle cx="190.15351" cy="24.95465" r="12.66462" fill="#fff" />
                                        <path d="M878.81836,716.08691h-338a8.50981,8.50981,0,0,1-8.5-8.5v-405a8.50951,8.50951,0,0,1,8.5-8.5h338a8.50982,8.50982,0,0,1,8.5,8.5v405A8.51013,8.51013,0,0,1,878.81836,716.08691Z" transform="translate(-276.18187 -133.91309)" fill="#e6e6e6" />
                                        <path d="M723.31813,274.08691h-210.5a17.02411,17.02411,0,0,0-17,17v407.8l2-.61v-407.19a15.01828,15.01828,0,0,1,15-15H723.93825Zm183.5,0h-394a17.02411,17.02411,0,0,0-17,17v458a17.0241,17.0241,0,0,0,17,17h394a17.0241,17.0241,0,0,0,17-17v-458A17.02411,17.02411,0,0,0,906.81813,274.08691Zm15,475a15.01828,15.01828,0,0,1-15,15h-394a15.01828,15.01828,0,0,1-15-15v-458a15.01828,15.01828,0,0,1,15-15h394a15.01828,15.01828,0,0,1,15,15Z" transform="translate(-276.18187 -133.91309)" fill="#3f3d56" />
                                        <path d="M801.81836,318.08691h-184a9.01015,9.01015,0,0,1-9-9v-44a9.01016,9.01016,0,0,1,9-9h184a9.01016,9.01016,0,0,1,9,9v44A9.01015,9.01015,0,0,1,801.81836,318.08691Z" transform="translate(-276.18187 -133.91309)" fill="#ef9904" />
                                        <circle cx="433.63626" cy="105.17383" r="20" fill="#ef9904" />
                                        <circle cx="433.63626" cy="105.17383" r="12.18187" fill="#fff" />
                                    </svg>
                                </div>
                            </td>
                        </tr>

                        <?php
                    } else {
                        foreach ($reviews as $row) : ?>

                            <tr id="<?= $row['id_review']; ?>">
                                <td>
                                    <?= $row['id_review']; ?>
                                </td>

                                <td>
                                    <?php echo $row['user_name']; ?>
                                </td>

                                <td>
                                    <?php echo $row['review_user']; ?>
                                </td>

                                <td>
                                    <?php echo $row['rating']; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>
                    <?php

                    }

                    ?>

                </table>

                <?= $pager->links('reviews', 'pager_bootstrap') ?>
            </div>

        </div>



    </div>
</section>
<script type="text/javascript">
    $(".btnDelete").click(function() {

            var id = $(this).parents("tr").attr("id");

            console.log(id);

            Swal.fire({
                title: "Apakah kamu yakin ingin menghapus makanan?",
                text: "Kamu tidak akan dapat mengembalikan data",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'view_restaurant/delete/' + id,
                        type: 'DELETE',
                        success: function(data) {
                            $("#" + id).remove();
                            Swal.fire(
                                'Dihapus!',
                                'Makanan Telah Dihapus.',
                                'success'
                            )

                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Gagal Hapus Data",
                            })
                        },


                    });
                }

            });


        },

        // function (dismiss) {
        //     if (dismiss==="cancel") {

        //     }
        // }
    );
</script>

<?= $this->endSection(); ?>