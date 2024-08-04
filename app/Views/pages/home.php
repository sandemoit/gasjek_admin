<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- home content -->
<section class="home_content">

    <header>
        <h4>Beranda</h4>
    </header>

    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url('order') ?>">
                    <div class="card_menu">
                        <div class="icon_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="trending-up">
                                        <rect width="24" height="24" transform="rotate(-90 12 12)" opacity="0" />
                                        <path d="M21 7a.78.78 0 0 0 0-.21.64.64 0 0 0-.05-.17 1.1 1.1 0 0 0-.09-.14.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34 4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0V7z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="text_card_menu">
                            <h4 id="total_order"></h4>
                            <span>Total Pesanan</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url('order') ?>">
                    <div class="card_menu">
                        <div class="icon_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="trending-up">
                                        <rect width="24" height="24" transform="rotate(-90 12 12)" opacity="0" />
                                        <path d="M21 7a.78.78 0 0 0 0-.21.64.64 0 0 0-.05-.17 1.1 1.1 0 0 0-.09-.14.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34 4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0V7z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="text_card_menu">
                            <h4 id="total_order_harini"></h4>
                            <span>Total Pesanan Harini</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url('restaurant') ?>">
                    <div class="card_menu">
                        <div class="icon_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d=" M12 10h-2V3H8v7H6V3H4v8c0 1.654 1.346 3 3 3h1v7h2v-7h1c1.654 0 3-1.346 3-3V3h-2v7zm7-7h-1c-1.159 0-2 1.262-2 3v8h2v7h2V4a1 1 0 0 0-1-1z">
                                </path>
                            </svg>
                        </div>
                        <div class="text_card_menu">
                            <h4><?php echo $restaurants; ?></h4>
                            <span>Restoran</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url('user') ?>">
                    <div class="card_menu">
                        <div class="icon_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g data-name="Layer 2">
                                    <g data-name="person">
                                        <rect width="24" height="24" opacity="0" />
                                        <path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0-6a2 2 0 1 1-2 2 2 2 0 0 1 2-2z" />
                                        <path d="M12 13a7 7 0 0 0-7 7 1 1 0 0 0 2 0 5 5 0 0 1 10 0 1 1 0 0 0 2 0 7 7 0 0 0-7-7z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="text_card_menu">
                            <h4><?php echo $users ?></h4>
                            <span>Pengguna</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url('driver') ?>">
                    <div class="card_menu">
                        <div class="icon_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="@color/white" d="M19,7c0,-1.1 -0.9,-2 -2,-2h-3v2h3v2.65L13.52,14H10V9H6c-2.21,0 -4,1.79 -4,4v3h2c0,1.66 1.34,3 3,3s3,-1.34 3,-3h4.48L19,10.35V7zM4,14v-1c0,-1.1 0.9,-2 2,-2h2v3H4zM7,17c-0.55,0 -1,-0.45 -1,-1h2C8,16.55 7.55,17 7,17z" />
                                <path fill="@color/white" d="M5,6h5v2h-5z" />
                                <path fill="@color/white" d="M19,13c-1.66,0 -3,1.34 -3,3s1.34,3 3,3s3,-1.34 3,-3S20.66,13 19,13zM19,17c-0.55,0 -1,-0.45 -1,-1s0.45,-1 1,-1s1,0.45 1,1S19.55,17 19,17z" />
                            </svg>
                        </div>
                        <div class="text_card_menu">
                            <h4><?= $drivers ?></h4>
                            <span>Pengendara</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="cards_profit">
                    <div class="card_profit">
                        <h5>Transaksi GAS-Ride</h5>
                        <div class="balance_card_profit">
                            <h6>Pendapatan</h6>
                            <h3 id="total_income_ride"></h3>
                        </div>
                        <div class="order_card_profit">
                            <h6>Pesanan</h6>
                            <h3 id="total_order_ride"></h3>
                        </div>
                    </div>
                    <div class="card_profit">
                        <h5>Transaksi GAS-Food</h5>
                        <div class="balance_card_profit">
                            <h6>Pendapatan</h6>
                            <h3 id="total_income_food"></h3>
                        </div>
                        <div class="order_card_profit">
                            <h6>Pesanan</h6>
                            <h3 id="total_order_food"></h3>
                        </div>
                    </div>
                    <div class="card_profit">
                        <h5>Transaksi GAS-Send</h5>
                        <div class="balance_card_profit">
                            <h6>Pendapatan</h6>
                            <h3 id="total_income_send"></h3>
                        </div>
                        <div class="order_card_profit">
                            <h6>Pesanan</h6>
                            <h3 id="total_order_send"></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-6 col-sm-12">
                <div class="table_content flex_99" style="margin-top: 25px;">
                    <table id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Plat</th>
                                <th>Nama Pengorder</th>
                                <th>Penjemputan</th>
                                <th>Tujuan</th>
                                <th>Harga</th>
                                <th>Metode</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Isi tabel akan ditambahkan secara dinamis melalui JavaScript -->
                        </tbody>
                    </table>
                </div>
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
        limit,
        getDocs,
        onSnapshot,
        orderBy
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

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    const db = getFirestore(app);

    $(document).ready(function() {
        window.setTimeout(function() {
            getDatas();
        }, 1000);
    });

    const fetchTable = (orders, totalData) => {
        let content = '';
        let page = '';

        orders.forEach(element => {
            content += '<tr>';
            content += `<td>${orders.indexOf(element) + 1}</td>`;
            content += `<td>${element.police_number}</td>`;
            content += `<td>${element.user_name}</td>`;
            content += `<td>${element.username_pickup}</td>`;
            content += `<td>${element.username_destination}</td>`;
            content += `<td>Rp. ${element.price_food ? new Intl.NumberFormat('id-ID').format(element.price_order + element.price_food) : new Intl.NumberFormat('id-ID').format(element.price_order)}</td>`;
            content += `<td>${element.method_payment}</td>`;
            content += `<td>${element.time_order}</td>`;
            content += '</tr>';
        });

        $('#table').append(content);
        $('#pagination').append(`<p>Total Pengguna: ${totalData}</p>`);
    };

    const getCurrentDate = () => {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based in JavaScript
        const day = String(today.getDate()).padStart(2, '0');
        return `${day}/${month}/${year}`;
    };

    const getOrders = async () => {
        const col = collection(db, "orders");
        const isOrder = query(col, where("isOrder", "==", "onProcessing"), orderBy("date_order", "desc"));
        const totalOrderSnapshot = await getDocs(isOrder);
        const totalOrders = await getDocs(col);

        const totalOrderRideSnapshot = await getDocs(query(col, where("isOrder", "==", "Finished"), where("type_order", "==", "gas_ride"), where("date_order", "==", getCurrentDate())));
        const totalOrderFoodSnapshot = await getDocs(query(col, where("isOrder", "==", "Finished"), where("type_order", "==", "gas_food"), where("date_order", "==", getCurrentDate())));
        const totalOrderSendSnapshot = await getDocs(query(col, where("isOrder", "==", "Finished"), where("type_order", "==", "gas_send"), where("date_order", "==", getCurrentDate())));
        const todayOrderSnapshot = await getDocs(query(col, where("isOrder", "==", "Finished"), where("date_order", "==", getCurrentDate())));
        const totalOrdersSnapshot = await getDocs(query(col, where("isOrder", "==", "Finished")));

        const totalOrderRide = totalOrderRideSnapshot.size;
        const totalOrderFood = totalOrderFoodSnapshot.size;
        const totalOrderSend = totalOrderSendSnapshot.size;
        const totalOrderHarini = todayOrderSnapshot.size;
        const totalOrder = totalOrdersSnapshot.size;

        const totalOrderIncomeRide = totalOrderRideSnapshot.docs.reduce((acc, doc) => acc + 500, 0);
        const totalOrderIncomeFood = totalOrderFoodSnapshot.docs.reduce((acc, doc) => {
            const foodPrice = doc.data().price_food;
            const originalAmount = doc.data().original_amount;
            return acc + (foodPrice - originalAmount + 1000);
        }, 0);
        const totalOrderIncomeSend = totalOrderSendSnapshot.docs.reduce((acc, doc) => acc + 500, 0);

        $('#total_order_ride').append(totalOrderRide);
        $('#total_order_food').append(totalOrderFood);
        $('#total_order_send').append(totalOrderSend);
        $('#total_order').append(totalOrder);
        $('#total_income_ride').append(convertToRupiah(totalOrderIncomeRide));
        $('#total_income_food').append(convertToRupiah(totalOrderIncomeFood));
        $('#total_income_send').append(convertToRupiah(totalOrderIncomeSend));
        $('#total_order_harini').append(totalOrderHarini);

        const orders = totalOrderSnapshot.docs.map(doc => doc.data());
        fetchTable(orders, totalOrderSnapshot.size);
    };

    const convertToRupiah = (angka) => {
        const rupiah = angka.toString().split('').reverse().join('').match(/\d{1,3}/g).join('.').split('').reverse().join('');
        return `Rp. ${rupiah}`;
    };

    window.onload = () => {
        getOrders().catch(error => {
            alert.error("Error fetching orders:", error);
            console.error("Error fetching orders:", error);
        });
    };

    async function getDatas() {
        $('#table').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'Todos'] // Menampilkan tombol "Todos"
            ]
        });
    }
</script>

<?= $this->endSection(); ?>