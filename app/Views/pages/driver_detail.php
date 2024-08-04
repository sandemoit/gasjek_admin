<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- home content -->
<section class="home_content">

    <header>
        <h4><?= $drivers['username_rider'] . ' - ' . $drivers['police_number'] ?></h4>
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
                            <h4 id="total_order_driver"></h4>
                            <span>Total Pesanan Masuk</span>
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
                            <h4 id="total_order_batal"></h4>
                            <span>Total Pesanan Batal</span>
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
                            <h4 id="total_order_selesai"></h4>
                            <span>Total Pesanan Selesai</span>
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
                            <h4 id="today_order_batal"></h4>
                            <span>Total Batal Harini</span>
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
                            <h4 id="today_order_selesai"></h4>
                            <span>Total Sukses Harini</span>
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
                            <h4 id="total_keuntungan"></h4>
                            <span>Total Keuntungan</span>
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
                            <h4 id="today_keuntungan"></h4>
                            <span>Total Keuntungan Harini</span>
                        </div>
                    </div>
                </a>
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
        getFirestore,
        collection,
        query,
        where,
        getDocs
    } from 'https://www.gstatic.com/firebasejs/10.10.0/firebase-firestore.js';

    // Konfigurasi Firebase
    const firebaseConfig = {
        apiKey: "AIzaSyBpE7erRgxGZhTE34LrjZkbE-UxOb44BRE",
        authDomain: "gasjek-1ed19.firebaseapp.com",
        projectId: "gasjek-1ed19",
        storageBucket: "gasjek-1ed19.appspot.com",
        messagingSenderId: "757010990046",
        appId: "1:757010990046:web:c503012bf35516d5f6934f",
        measurementId: "G-4CKW8FN4V6"
    };

    // Inisialisasi Firebase
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    // Fungsi untuk mendapatkan tanggal saat ini dalam format dd/mm/yyyy
    const getCurrentDate = () => {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan dalam JavaScript dimulai dari 0
        const day = String(today.getDate()).padStart(2, '0');
        return `${day}/${month}/${year}`;
    };

    // Fungsi untuk mengambil jumlah pesanan dan total keuntungan
    async function getOrderCounts(policeNumber) {
        const col = collection(db, "orders");

        // Query untuk mengambil data pesanan
        const totalOrdersSnapshot = await getDocs(query(col, where("police_number", "==", policeNumber)));
        const canceledOrdersSnapshot = await getDocs(query(col, where("police_number", "==", policeNumber), where("isOrder", "==", "Decline")));
        const finishedOrdersSnapshot = await getDocs(query(col, where("police_number", "==", policeNumber), where("isOrder", "==", "Finished")));
        const todayCanceledOrdersSnapshot = await getDocs(query(col, where("police_number", "==", policeNumber), where("isOrder", "==", "Decline"), where("order_date", "==", getCurrentDate())));
        const todayFinishedOrdersSnapshot = await getDocs(query(col, where("police_number", "==", policeNumber), where("isOrder", "==", "Finished"), where("order_date", "==", getCurrentDate())));

        // Menghitung total keuntungan
        const totalIncomes = finishedOrdersSnapshot.docs.reduce((acc, doc) => {
            const orderPrice = doc.data().order_price;
            return acc + (orderPrice - 500);
        }, 0);

        // Menghitung total keuntungan hari ini
        const todayIncomes = todayFinishedOrdersSnapshot.docs.reduce((acc, doc) => {
            const orderPrice = doc.data().order_price;
            return acc + (orderPrice + 500);
        }, 0);

        console.log(totalIncomes, todayIncomes);

        // Mendapatkan jumlah pesanan
        const totalOrders = totalOrdersSnapshot.size;
        const canceledOrders = canceledOrdersSnapshot.size;
        const finishedOrders = finishedOrdersSnapshot.size;
        const todayCanceledOrders = todayCanceledOrdersSnapshot.size;
        const todayFinishedOrders = todayFinishedOrdersSnapshot.size;

        // Menampilkan jumlah pesanan dan keuntungan pada elemen HTML
        document.getElementById('total_order_driver').innerText = totalOrders;
        document.getElementById('total_order_batal').innerText = canceledOrders;
        document.getElementById('total_order_selesai').innerText = finishedOrders;
        document.getElementById('today_order_batal').innerText = todayCanceledOrders;
        document.getElementById('today_order_selesai').innerText = todayFinishedOrders;
        document.getElementById('total_keuntungan').innerText = convertToRupiah(totalIncomes);
        document.getElementById('today_keuntungan').innerText = convertToRupiah(todayIncomes);
    }

    // Fungsi untuk mengonversi angka ke format Rupiah
    const convertToRupiah = (angka) => {
        if (angka == null) return "Rp. 0";
        const rupiah = angka.toString().split('').reverse().join('').match(/\d{1,3}/g).join('.').split('').reverse().join('');
        return `Rp. ${rupiah}`;
    };

    // Memanggil fungsi getOrderCounts saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        const policeNumber = '<?= $drivers['police_number'] ?>';
        getOrderCounts(policeNumber).catch(error => {
            console.error("Error fetching orders:", error);
        });
    });
</script>

<?= $this->endSection(); ?>