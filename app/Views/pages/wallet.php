<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="topUpForm" action="<?= base_url('wallet/add_wallet') ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Top Up Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2 d-flex align-items-end">
                        <div class="flex-grow-1">
                            <label for="emailInput" class="form-label">Email</label>
                            <input type="text" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" id="emailInput" placeholder="Ketik Email Users" name="email">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                <?= $validation->getError('email'); ?>
                            </div>
                        </div>
                        <div class="ms-2">
                            <button type="button" id="checkEmailButton" class="btn btn-primary">Check</button>
                        </div>
                    </div>
                    <div id="loading" class="spinner-border text-primary" role="status" style="display: none;">
                    </div>
                    <div id="emailInfo" style="display: none;">
                        <p class="text-success"><i class='bx bx-check'></i> Email terdaftar.</p>
                    </div>
                    <div id="validasi" style="display: none;">
                        <p class="text-danger"><i class='bx bx-x'></i> Email tidak terdaftar.</p>
                    </div>
                    <div class="mb-3">
                        <label for="nominalInput" class="form-label">Nominal</label>
                        <input type="text" class="form-control <?= ($validation->hasError('nominal')) ? 'is-invalid' : '' ?>" id="nominalInput" placeholder="Ketik Nominal Top Up" name="nominal" disabled>
                        <div id="validationServerUsernameFeedback" class="invalid-feedback">
                            <?= $validation->getError('nominal'); ?>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Keluar</button>
                    <button type="submit" id="saveButton" class="btn btn-primary" disabled>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

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

            <button id="btnAdd" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g data-name="Layer 2">
                        <g data-name="plus">
                            <rect width="24" height="24" transform="rotate(180 12 12)" opacity="0" />
                            <path d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2z" />
                        </g>
                    </g>
                </svg>
                Top Up</button>

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
                            NO
                        </th>
                        <th>
                            ID Transaksi
                        </th>
                        <th>
                            Tanggal Transaksi
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            Metode Pembayaran
                        </th>
                        <th>
                            Nominal Saldo
                        </th>
                        <th>
                            Jenis Transaksi
                        </th>
                        <th>
                            Status Pembayaran
                        </th>
                        <th>
                            Aksi
                        </th>
                    </tr>
                    <?php $no = 1 + (5 * ($current_page - 1)); ?>
                    <?php if (empty($wallets)) {
                    ?>
                        <tr>
                            <td colspan="9">
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
                        $no = 1;
                        foreach ($wallets as $row) : ?>
                            <tr id="<?= $row['id_transaction']; ?>">
                                <td>
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <?php echo $row['id_transaction']; ?>
                                </td>
                                <td>
                                    <?= tanggal($row['date']); ?>
                                </td>
                                <td>
                                    <?php echo $row['user_name']; ?>
                                </td>
                                <td>
                                    <?= $row['method_payment']; ?>
                                </td>
                                <td>
                                    <?= rupiah($row['balance']); ?>
                                </td>
                                <td>
                                    <?= $row['type_payment']; ?>
                                </td>
                                <td>
                                    <?php if ($row['status_payment'] == "pending") {
                                    ?>
                                        <span class="badge bg-warning px-4 py-2 rounded-pill">
                                            Menunggu
                                        </span>
                                    <?php
                                    } else if ($row['status_payment'] == "success") {
                                    ?>
                                        <span class="badge bg-success px-4 py-2 rounded-pill">
                                            Berhasil
                                        </span>
                                    <?php
                                    } else if ($row['status_payment'] == "cancel") {
                                    ?>
                                        <span class="badge bg-danger px-4 py-2 rounded-pill">
                                            Dibatalkan
                                        </span>
                                    <?php
                                    } else if ($row['status_payment'] == "expire") {
                                    ?>
                                        <span class="badge bg-danger px-4 py-2 rounded-pill">
                                            Expired
                                        </span>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($row['status_payment'] == "pending") {
                                    ?>
                                        <div class="d-flex justify-content-center">
                                            <form action="<?= base_url() ?>/update_balance" method="post">
                                                <input type="hidden" value="<?= $row['id_transaction']; ?>" name="id_transaction">
                                                <input type="hidden" value="accept" name="action">
                                                <input type="hidden" value="<?= $row['role']; ?>" name="role">
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
                                            </form>
                                            <form action="<?= base_url() ?>/update_balance" method="post">
                                                <input type="hidden" value="<?= $row['id_transaction']; ?>" name="id_transaction">
                                                <input type="hidden" value="decline" name="action">
                                                <input type="hidden" value="<?= $row['role']; ?>" name="role">
                                                <button class=" btnDelete" id="btnDelete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                        <g data-name="Layer 2">
                                                            <g data-name="close">
                                                                <rect width="24" height="24" transform="rotate(180 12 12)" opacity="0" />
                                                                <path d="M13.41 12l4.3-4.29a1 1 0 1 0-1.42-1.42L12 10.59l-4.29-4.3a1 1 0 0 0-1.42 1.42l4.3 4.29-4.3 4.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0l4.29-4.3 4.29 4.3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42z" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    <?php
                                    } else {
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php
                    }
                    ?>
                </table>
                <?= $pager->links('wallet', 'pager_bootstrap') ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url('js/check_email.js') ?>"></script>

<?= $this->endSection(); ?>