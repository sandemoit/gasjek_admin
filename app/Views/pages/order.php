<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<section class="home_content">

    <header>
        <h4><?= $title ?></h4>
    </header>

    <div class="content">
        <div class="content_menu block flex-wrap">
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
                            Download Excel</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table_content flex_99">
            <table id="table">
                <thead>
                    <tr>
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
                            Penjemputan
                        </th>

                        <th>
                            Tujuan
                        </th>

                        <th>
                            Harga
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Tanggal
                        </th>
                        <th>
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
<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-analytics.js";
    import {
        getFirestore,
        collection,
        query,
        where,
        orderBy,
        getDocs
    } from 'https://www.gstatic.com/firebasejs/10.10.0/firebase-firestore.js';

    const firebaseConfig = {
        apiKey: "AIzaSyBpE7erRgxGZhTE34LrjZkbE-UxOb44BRE",
        authDomain: "gasjek-1ed19.firebaseapp.com",
        projectId: "gasjek-1ed19",
        storageBucket: "gasjek-1ed19.appspot.com",
        messagingSenderId: "757010990046",
        appId: "1:757010990046:web:c503012bf35516d5f6934f",
        measurementId: "G-4CKW8FN4V6"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    $(document).ready(function() {
        window.setTimeout(function() {
            getDatas();
        }, 5000);
    });

    function fetchTable(orders) {
        let content = '';
        let page = '';

        orders.forEach(element => {
            content += '<tr>';
            content += `<td>#${element.id_order}</td>`;
            content += `<td>${element.user_name}</td>`;
            content += `<td>${element.police_number}</td>`;
            content += `<td>${element.username_pickup}</td>`;
            content += `<td>${element.username_destination}</td>`;
            content += `<td>Rp. ${new Intl.NumberFormat('id-ID').format(element.price_order)}</td>`;

            let type;
            if (element.type_order === "gas_food") {
                type = 'Gas Food';
            } else if (element.type_order === "gas_ride") {
                type = 'Gas Ride';
            } else {
                type = 'Gas Send';
            }

            content += `<td>${type}</td>`;
            content += `<td>${element.date_order}</td>`;

            let status;
            if (element.isOrder === "onWaiting") {
                status = 'Menunggu Driver';
                type = 'badge bg-warning';
            } else if (element.isOrder === "onProcessing") {
                status = 'Dalam Perjalanan';
                type = 'badge bg-primary';
            } else {
                status = 'Selesai';
                type = 'badge bg-success';
            }
            content += `<td><span class="${type} px-4 py-2 rounded-pill">${status}</span></td>`;
            content += '</tr>';
        });

        $('#table').append(content);
        $('#pagination').append(page);
        if (orders != null) {
            getDatas();
        }
    }

    const getPosts = async () => {
        let docs;
        let postRef = await app.firebase().collection("orders");

        let _size = await app.firebase().collection("orders").get();
        size = _size.size;

        await postRef.get().then((documentSnapshot) => {

            lastVisible = documentSnapshot.docs[documentSnapshot.docs.length - 1];
            console.log("last", lastVisible);
        });

        docs["docs"].forEach(doc => {
            postsArray.push({
                "data": doc.data()
            });
        })

        if (postsArray.length > 0) {
            pagination.style.display = "block";
        } else {
            pagination.style.display = "none";
        }
        await createChildren(postsArray);
        postsSize = table.childNodes.length;
        console.log(postsSize);
    }

    async function getOrders() {
        try {
            const col = collection(db, "orders");
            const isOrder = query(col, orderBy("date_order", "desc"));
            const orderSnapshot = await getDocs(isOrder);
            const orders = orderSnapshot.docs.map(doc => doc.data());
            fetchTable(orders);
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    window.onload = getOrders;

    function getDatas() {
        var table = $('#table').DataTable();
    }
</script>

<?= $this->endSection(); ?>