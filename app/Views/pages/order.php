<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Order:</strong> <span id="order-id"></span></p>
                <p><strong>Nama Pembeli:</strong> <span id="buyerName"></span></p>
                <p><strong>Ongkir:</strong> <span id="priceOrder"></span></p>
                <p id="subTotal"><strong>Sub Total:</strong> <span id="totalPrice"></span></p>
                <p><strong>Total Biaya:</strong> <span id="totalFull"></span></p>
                <p><strong>Jarak:</strong> <span id="jarak"></span></p>
                <p><strong>Lokasi Penjemputan:</strong> <span id="pickup-location"></span></p>
                <p><strong>Lokasi Tujuan:</strong> <span id="destination-location"></span></p>
                <p id="driverLocation"></p>

                <!-- List for Food Orders -->
                <div id="food-list-container">
                    <h6>Detail Makanan:</h6>
                    <ul id="food-list">
                        <!-- Food items will be appended here -->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<section class="home_content">

    <header>
        <h4><?= $title ?></h4>
    </header>

    <div class="content">
        <!-- <div class="content_menu block flex-wrap">
            <form action="<?= base_url('laporan') ?>" method="POST">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="form-control">
                                <option selected disabled>-- Pilih Bulan --</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control">
                                <option disabled>-- Pilih Tahun --</option>
                                <?php
                                $thn = date('Y');
                                for ($i = 2020; $i < $thn + 5; $i++) {
                                    $selected = ($i == $thn) ? 'selected' : '';
                                ?>
                                    <option value="<?= $i; ?>" <?= $selected; ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                        <button type="submit" value="download" name="action" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="pie-chart-2">
                                        <rect width="24" height="24" opacity="0" />
                                        <path d="M14.5 10.33h6.67A.83.83 0 0 0 22 9.5 7.5 7.5 0 0 0 14.5 2a.83.83 0 0 0-.83.83V9.5a.83.83 0 0 0 .83.83zm.83-6.6a5.83 5.83 0 0 1 4.94 4.94h-4.94z" />
                                        <path d="M21.08 12h-8.15a.91.91 0 0 1-.91-.91V2.92A.92.92 0 0 0 11 2a10 10 0 1 0 11 11 .92.92 0 0 0-.92-1z" />
                                    </g>
                                </g>
                            </svg>
                            Download Excel
                        </button>
                    </div>
                </div>
            </form>
        </div> -->

        <div class="table_content flex_99">
            <table id="table">
                <thead>
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            ID
                        </th>

                        <th>
                            Nama Pemesan
                        </th>

                        <th>
                            Plat Driver
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Waktu
                        </th>
                        <th class="text-center">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    </div>
</section>

<!-- menampilkan data dari firestore ke table -->
<script type="module" src="<?= base_url('js/ord_2137shda.js') ?>"></script>

<?= $this->endSection(); ?>