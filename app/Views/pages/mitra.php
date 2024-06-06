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
                        '<?= session()->getFlashData('message_error') ?>',
                        'error'
                    )
                </script>
            <?php endif; ?>
            <!-- <button id="btnAdd" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button> -->

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
                            No
                        </th>
                        <th>
                            Tanggal Terdaftar
                        </th>
                        <th>
                            Nomor Mitra
                        </th>
                        <th>
                            Email Mitra
                        </th>
                        <th>Status</th>
                        <th>
                            Aksi
                        </th>
                    </tr>
                    <?php $no = 1 + (5 * ($current_page - 1)); ?>
                    <?php
                    if (empty($mitra)) {
                    ?>
                        <tr>
                            <td colspan="5">
                                <div>
                                    <h4 class="mt-4">Tidak ada data</h4>
                                    <svg class="empty   " xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" data-name="Layer 1" width="647.63626" height="632.17383" viewBox="0 0 647.63626 632.17383">
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
                        $no = 1;
                        foreach ($mitra as $row) : ?>
                            <tr id="<?= $row['id_mitra']; ?>">
                                <td><?= $no++ ?></td>
                                <td><?= tanggal($row['date_register']) ?></td>
                                <td><?= $row['user_phone_mitra']; ?></td>
                                <td>
                                    <?php if (!empty($row['id_restaurant'])) : ?>
                                        <a href="<?= base_url('view_restaurant/' . $row['id_restaurant']); ?>">
                                            <?= $row['user_email_mitra']; ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="m13 3 3.293 3.293-7 7 1.414 1.414 7-7L21 11V3z"></path>
                                                <path d="M19 19H5V5h7l-2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2v-5l-2-2v7z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?= empty($row['id_restaurant']) ? $row['user_email_mitra'] : ''; ?>
                                </td>
                                <td>
                                    <div class="badge px-4 py-2 rounded-pill <?= $row['status'] === 'pending' ? 'bg-warning' : ($row['status'] === 'deleted' ? 'bg-danger' : ($row['status'] === 'cancel' ? 'bg-danger' : 'success')); ?>"><?= $row['status'] === 'pending' ? 'Menunggu' : ($row['status'] === 'deleted' ? 'Dinonaktifkan' : ($row['status'] === 'cancel' ? 'Dibatalkan' : 'Diterima')); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'pending') : ?>
                                        <a href="<?= base_url('accept_mitra/' . $row['id_mitra']) ?>" class="ms-3">
                                            <button class="btnEdit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <g data-name="Layer 2">
                                                        <g data-name="checkmark">
                                                            <rect width="24" height="24" opacity="0" />
                                                            <path d="M9.86 18a1 1 0 0 1-.73-.32l-4.86-5.17a1 1 0 1 1 1.46-1.37l4.12 4.39 8.41-9.2a1 1 0 1 1 1.48 1.34l-9.14 10a1 1 0 0 1-.73.33z" />
                                                        </g>
                                                    </g>
                                                </svg>
                                            </button>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($row['status'] !== 'deleted') : ?>
                                        <?php if ($row['status'] === 'pending') : ?>
                                            <button class="btnDelete" id="btnCancel" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <g data-name="Layer 2">
                                                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4 12c0-1.846.634-3.542 1.688-4.897l11.209 11.209A7.946 7.946 0 0 1 12 20c-4.411 0-8-3.589-8-8zm14.312 4.897L7.103 5.688A7.948 7.948 0 0 1 12 4c4.411 0 8 3.589 8 8a7.954 7.954 0 0 1-1.688 4.897z">
                                                        </path>
                                                </svg>
                                            </button>
                                        <?php else : ?>
                                            <button class="btnDelete" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4 12c0-1.846.634-3.542 1.688-4.897l11.209 11.209A7.946 7.946 0 0 1 12 20c-4.411 0-8-3.589-8-8zm14.312 4.897L7.103 5.688A7.948 7.948 0 0 1 12 4c4.411 0 8 3.589 8 8a7.954 7.954 0 0 1-1.688 4.897z">
                                                    </path>
                                                    </g>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php
                    }
                    ?>
                </table>
                <?= $pager->links('mitras', 'pager_bootstrap') ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(".btnDelete").click(function() {

        var id = $(this).parents("tr").attr("id");

        Swal.fire({
            title: "Apakah kamu yakin ingin menonaktifkan mitra?",
            text: "Kamu tidak akan dapat mengembalikan data",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, nonaktifkan!",
            cancelButtonText: "Tidak, batalkan!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'mitra/delete/' + id,
                    type: 'PUT',
                    success: function(data) {
                        $("#" + id).remove();
                        Swal.fire(
                            'Dinonaktifkan!',
                            'Mitra Telah Dinonaktifkan.',
                            'success'
                        )
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal Update Data",
                        })
                    },
                });
            }
        });
    }, );
    $("#btnCancel").click(function() {

        var id = $(this).parents("tr").attr("id");

        Swal.fire({
            title: "Apakah kamu yakin ingin mecancel mitra?",
            text: "Kamu tidak akan dapat mengembalikan data",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, cancel!",
            cancelButtonText: "Tidak, batalkan!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'mitra/cancel/' + id,
                    type: 'PUT',
                    success: function(data) {
                        $("#" + id).remove();
                        Swal.fire(
                            'Dicancel!',
                            'Mitra Telah Dicancel.',
                            'success'
                        )

                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal Update Data",
                        })
                    },
                });
            }
        });
    }, );
</script>

<?= $this->endSection(); ?>