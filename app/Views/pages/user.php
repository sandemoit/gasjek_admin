<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">

    <header>
        <h4>Pengguna</h4>
    </header>

    <div class="content">
        <div class="content_menu block">
            <a href=""><button class="btnRefresh">Refresh</button></a>

            <div class="card-header">
                <div class="card-tools">
                    <form action="<?= site_url('user') ?>" method="GET">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" id="search" value="<?= isset($search) ? $search : '' ?>" class="form-control float-right" placeholder="Search" autocomplete="off" autofocus="">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-search'></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table_content flex_99">
                <table>
                    <tr>
                        <th class="text-center">
                            No
                        </th>
                        <th>
                            Nama Pengguna
                        </th>
                        <th>
                            Nomor Pengguna
                        </th>
                        <th>
                            Email Pengguna
                        </th>
                        <th>
                            Saldo Pengguna
                        </th>
                        <th class="text-center">
                            Gambar Pengguna
                        </th>
                        <th class="text-center">
                            Verifikasi
                        </th>
                        <th class="text-center">
                            Aksi
                        </th>
                    </tr>
                    <?php $no = 1 + (5 * ($current_page - 1)); ?>
                    <?php if (empty($users)) {
                    ?>
                        <tr class="text-center">
                            <td colspan="7">
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
                        $no = $offset + 1;
                        foreach ($users as $row) :
                            // Mengubah nomor telepon dari format 0xxx ke 62xxx
                            $nomorPengguna = $row['nomor_pengguna'];
                            if (strpos($nomorPengguna, '0') === 0) {
                                $nomorPengguna = '62' . substr($nomorPengguna, 1);
                            }
                        ?>
                            <tr id="<?= $row['id_pengguna']; ?>">
                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>
                                <td>
                                    <?= $row['nama_pengguna']; ?>
                                </td>
                                <td>
                                    <a href="https://wa.me/<?= $nomorPengguna; ?>" target="_blank"><?= $row['nomor_pengguna']; ?></a>
                                </td>
                                <td>
                                    <?= $row['email_pengguna']; ?>
                                </td>
                                <td>
                                    <?= rupiah($row['saldo_pengguna']); ?>
                                </td>
                                <td class="text-center">
                                    <img src="assets/profile_photo/<?= $row['gambar_pengguna']; ?>" alt="">
                                </td>
                                <td class="text-center">
                                    <?php if ($row['is_verify'] != 1) : ?>
                                        <div class="badge px-4 py-2 rounded-pill bg-danger">Tidak Terverifikasi </div>
                                    <?php else : ?>
                                        <div class="badge px-4 py-2 rounded-pill bg-success">Terverifikasi</div>
                                    <?php endif ?>
                                </td>
                                <td class="text-center">
                                    <a class="ms-3" href="<?= base_url() ?>/user/edit_user/<?= $row['id_pengguna']; ?>">
                                        <button class="btnEdit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <g data-name="Layer 2">
                                                    <g data-name="edit">
                                                        <rect width="24" height="24" opacity="0" />
                                                        <path d="M19.4 7.34L16.66 4.6A2 2 0 0 0 14 4.53l-9 9a2 2 0 0 0-.57 1.21L4 18.91a1 1 0 0 0 .29.8A1 1 0 0 0 5 20h.09l4.17-.38a2 2 0 0 0 1.21-.57l9-9a1.92 1.92 0 0 0-.07-2.71zM9.08 17.62l-3 .28.27-3L12 9.32l2.7 2.7zM16 10.68L13.32 8l1.95-2L18 8.73z" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    </a>
                                    <a class="btnDelete" id="btnDelete" type="submit">
                                        <button class="btnDelete">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <g data-name="Layer 2">
                                                    <g data-name="trash">
                                                        <rect width="24" height="24" opacity="0" />
                                                        <path d="M21 6h-5V4.33A2.42 2.42 0 0 0 13.5 2h-3A2.42 2.42 0 0 0 8 4.33V6H3a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2zM10 4.33c0-.16.21-.33.5-.33h3c.29 0 .5.17.5.33V6h-4zM18 19a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V8h12z" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php
                    }
                    ?>
                </table>
                <?= $pager->links('users', 'pager_bootstrap') ?>
                </ul>
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
<script type="text/javascript">
    $(".btnDelete").click(function() {
        var id = $(this).parents("tr").attr("id");

        Swal.fire({
            title: "Apakah kamu yakin ingin menghapus pengguna?",
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
                    url: 'user/delete/' + id,
                    type: 'DELETE',
                    success: function(data) {
                        $("#" + id).remove();
                        Swal.fire(
                            'Dihapus!',
                            'Pengguna Telah Dihapus.',
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
    }, );
</script>

<?= $this->endSection(); ?>