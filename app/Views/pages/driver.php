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

            <a href="driver"><button class="btnRefresh">Refresh</button></a>
            <div class="table_content flex_99">
                <table>

                    <tr>
                        <th>
                            No
                        </th>

                        <th>
                            Nama Pengendara
                        </th>

                        <th>
                            Gambar Pengendara
                        </th>

                        <th>
                            Nomor Pengendara
                        </th>

                        <th>
                            Email Pengendara
                        </th>

                        <th>
                            Saldo Pengendara
                        </th>

                        <th>
                            Plat Kendaraan
                        </th>

                        <th>
                            Status Driver
                        </th>

                        <th>
                            Aksi
                        </th>
                    </tr>
                    <?php $no = 1 + (5 * ($current_page - 1)); ?>
                    <?php foreach ($drivers as $row) : ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $row['username_rider']; ?>
                            </td>
                            <td>
                                <img src="<?= base_url('assets/drivers/' . $row['image_rider']) ?>">
                            </td>
                            <td>
                                <?php echo $row['phone_rider']; ?>
                            </td>
                            <td>
                                <?php echo $row['email_rider']; ?>
                            </td>
                            <td>
                                <?php echo rupiah($row['balance_rider']); ?>
                            </td>
                            <td>
                                <?= $row['police_number']; ?>
                            </td>
                            <td>
                                <?php
                                $statuses = [
                                    'accept' => ['Diterima', 'success'],
                                    'waiting' => ['Menunggu', 'warning'],
                                    'block' => ['Diblokir', 'danger'],
                                    'cancel' => ['Ditolak', 'danger'],
                                ];
                                [$statusText, $statusColor] = $statuses[$row['is_status']];
                                echo "<span class='badge px-4 py-2 rounded-pill bg-$statusColor'>$statusText</span>";
                                ?>
                            </td>
                            <td>
                                <?php if ($row['is_status'] === 'waiting') : ?>
                                    <a href="<?= base_url('driver_accept/' . $row['id_driver']) ?>" class="ms-3">
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
                                <?php if ($row['is_status'] !== 'block' && $row['is_status'] !== 'cancel') : ?>
                                    <?php if ($row['is_status'] === 'waiting') : ?>
                                        <button class="btnDelete" id="btnCancel_<?= $row['id_driver'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4 12c0-1.846.634-3.542 1.688-4.897l11.209 11.209A7.946 7.946 0 0 1 12 20c-4.411 0-8-3.589-8-8zm14.312 4.897L7.103 5.688A7.948 7.948 0 0 1 12 4c4.411 0 8 3.589 8 8a7.954 7.954 0 0 1-1.688 4.897z">
                                                </path>
                                            </svg>
                                        </button>
                                    <?php else : ?>
                                        <button class="btnDelete" id="btnBlock_<?= $row['id_driver'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path>
                                                <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?= $pager->links('drivers', 'pager_bootstrap') ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).on("click", "[id^='btnBlock_']", function() {
        var id = this.id.split("_")[1];

        Swal.fire({
            title: "Apakah kamu yakin ingin meblokir driver?",
            text: "Kamu tidak akan dapat mengembalikan data",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, blokir!",
            cancelButtonText: "Tidak, batalkan!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'driver_block/' + id,
                    type: 'post',
                    success: function(data) {
                        $("#btnBlock_" + id).closest("tr").remove();
                        Swal.fire(
                            'Diblokir!',
                            'Driver Telah Diblokir.',
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
    });

    $(document).on("click", "[id^='btnCancel_']", function() {
        var id = this.id.split("_")[1];

        Swal.fire({
            title: "Apakah kamu yakin ingin menolak driver?",
            text: "Kamu tidak akan dapat mengembalikan data",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, tolak!",
            cancelButtonText: "Tidak, batalkan!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'driver_cancel/' + id,
                    type: 'post',
                    success: function(data) {
                        $("#btnCancel_" + id).closest("tr").remove();
                        Swal.fire(
                            'Ditolak!',
                            'Driver Telah Ditolak.',
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
    });
</script>

<?= $this->endSection(); ?>