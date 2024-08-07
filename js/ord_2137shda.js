import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
import {
    getFirestore,
    collection,
    query,
    orderBy,
    getDocs,
    where
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
        getOrders();
    }, 3000);
});

async function getOrders() {
    try {
        const col = collection(db, "orders");
        const ordersQuery = query(col, orderBy("date_order", "asc"), orderBy("time_order", "asc"));
        const orderSnapshot = await getDocs(ordersQuery);
        const orders = orderSnapshot.docs.map(doc => {
            const data = doc.data();
            // Ambil hanya field yang diperlukan
            return {
                id_order: data.id_order,
                user_name: data.user_name,
                police_number: data.police_number,
                date_order: data.date_order,
                time_order: data.time_order,
                type_order: data.type_order,
                price_order: data.price_order,
                price_food: data.price_food,
                isOrder: data.isOrder,
                distance_order: data.distance_order,
                username_pickup: data.username_pickup,
                username_destination: data.username_destination,
                method_payment: data.method_payment,
            };
        });
        
        // Fetch foods and join with orders
        const joinedOrders = await joinOrdersWithFoods(orders);
        fetchTable(joinedOrders);
    } catch (error) {
        console.error("Error fetching orders:", error);
    }
}

async function joinOrdersWithFoods(orders) {
    const foodsCol = collection(db, "foods");
    const joinedOrders = await Promise.all(orders.map(async order => {
        if (order.type_order === "gas_food") {
            const foodsQuery = query(foodsCol, where("id_order", "==", order.id_order));
            const foodsSnapshot = await getDocs(foodsQuery);
            const foods = foodsSnapshot.docs.map(doc => doc.data());
            return { ...order, foods };
        }
        return order;
    }));
    return joinedOrders;
}

function fetchTable(orders) {
    let content = '';
    orders.forEach(element => {
        let type;
        const totalPrice = element.price_food ? new Intl.NumberFormat('id-ID').format(element.price_order + element.price_food) : new Intl.NumberFormat('id-ID').format(element.price_order);
        const ongkir = new Intl.NumberFormat('id-ID').format(element.price_order);

        const price = element.price_food ? element.price_order + element.price_food : 0;
        const ongkos = element.price_order;
        const totalFull = new Intl.NumberFormat('id-ID').format(price + ongkos);

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
        content += `<td>${orders.indexOf(element) + 1}</td>`;
        content += `<td>#${element.id_order}</td>`;
        content += `<td><a href="${baseurl}/user?search=${element.user_name}">${element.user_name}</a></td>`;
        content += `<td><a href="${baseurl}/driver?search=${element.police_number}">${element.police_number}</a>`;
        content += `<td>${type}</td>`;
        content += `<td>${element.date_order} - ${element.time_order}</td>`;
        content += `<td class="text-center"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#detailModal"
                            data-id-order="${element.id_order}"
                            data-buyer-name="${element.user_name}"
                            data-total-price="${totalPrice}"
                            data-price-order="${ongkir}"
                            data-jarak="${element.distance_order}"
                            data-pickup-location="${element.username_pickup}"
                            data-destination-location="${element.username_destination}"
                            data-type-order="${element.type_order}"
                            data-total-full="${totalFull}"
                            data-method-payment="${element.method_payment}"
                            data-foods='${JSON.stringify(element.foods || [])}'>
                            <span class="${badgeClass} px-4 py-2 rounded-pill">${status}</span></a></td>`;
        content += `</tr>`;
    });

    $('#table').append(content);
    getDatas();
}

function getDatas() {
    $('#table').DataTable();
}

$('#table').on('click', '[data-bs-toggle="modal"]', function() {
    const orderId = $(this).data('id-order');
    const buyerName = $(this).data('buyer-name');
    const methodPayment = $(this).data('method-payment');
    const totalPrice = 'Rp. ' + $(this).data('total-price');
    const priceOrder = 'Rp. ' + $(this).data('price-order');
    const jarak = $(this).data('jarak');
    const pickupLocation = $(this).data('pickup-location');
    const destinationLocation = $(this).data('destination-location');
    const typeOrder = $(this).data('type-order');
    const foods = $(this).data('foods');
    const totalFull = 'Rp. ' + $(this).data('total-full');

    // Update modal content with order data
    $('#order-id').text(orderId);
    $('#buyerName').text(buyerName);
    $('#methodPayment').text(methodPayment);
    $('#totalPrice').text(totalPrice);
    $('#priceOrder').text(priceOrder);
    $('#totalFull').text(totalFull);
    $('#jarak').text(jarak);
    $('#pickup-location').text(pickupLocation);
    $('#destination-location').text(destinationLocation);

    if (typeOrder === "gas_food") {
        $('#food-list-container').show();
        $('#subTotal').show();
        $('#food-list').empty();
        foods.forEach(food => {
            $('#food-list').append(`<li class="detail-item"><span>${food.food_name}</span><span>${food.food_quantity}x</span></li>`);
        });
    } else {
        $('#food-list-container').hide();
        $('#subTotal').hide();
    }
});