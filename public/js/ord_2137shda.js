import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
import {
    getFirestore,
    collection,
    query,
    orderBy,
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

$(document).ready(function() {
    window.setTimeout(function() {
        getDatas();
    }, 5000);
});

function fetchTable(orders) {
    let content = '';
    let page = '';

    orders.forEach(element => {
        const formattedPrice = new Intl.NumberFormat('id-ID').format(element.price_order);
        let type;
        switch(element.type_order) {
            case "gas_food":
                type = 'Gas Food';
                break;
            case "gas_ride":
                type = 'Gas Ride';
                break;
            default:
                type = 'Gas Send';
        }

        let status, badgeClass;
        switch(element.isOrder) {
            case "onWaiting":
                status = 'Menunggu Driver';
                badgeClass = 'badge bg-warning';
                break;
            case "onProcessing":
                status = 'Dalam Perjalanan';
                badgeClass = 'badge bg-primary';
                break;
            case "Decline":
                status = 'Batal';
                badgeClass = 'badge bg-danger';
                break;
            case "Finished":
                status = 'Selesai';
                badgeClass = 'badge bg-success';
                break;
        }

        content += `<tr>`;
        content += `<td>#${element.id_order}</td>`;
        content += `<td>${element.user_name}</td>`;
        content += `<td>${element.police_number}</td>`;
        content += `<td>${element.username_pickup}</td>`;
        content += `<td>${element.username_destination}</td>`;
        content += `<td>Rp. ${formattedPrice}</td>`;
        content += `<td>${type}</td>`;
        const formattedDate = new Date(element.date_order)
            .toLocaleString('id-ID', { 
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });
        content += `<td>${formattedDate}</td>`;
        content += `<td><span class="${badgeClass} px-4 py-2 rounded-pill">${status}</span></td>`;
        content += `</tr>`;
    });

    $('#table').append(content);
    $('#pagination').append(page);
    if (orders != null) {
        getDatas();
    }
}

async function getOrders() {
    try {
        const col = collection(db, "orders");
        const ordersQuery = query(col, orderBy("date_order", "desc"));
        const orderSnapshot = await getDocs(ordersQuery);
        const orders = orderSnapshot.docs.map(doc => doc.data());
        fetchTable(orders);
    } catch (error) {
        console.error("Error fetching orders:", error);
    }
}

window.onload = getOrders;

function getDatas() {
    $('#table').DataTable();
}