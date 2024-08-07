import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
import { getDatabase, ref, onValue, remove } from 'https://www.gstatic.com/firebasejs/10.10.0/firebase-database.js';

// Konfigurasi Firebase
const firebaseConfig = {
    apiKey: "AIzaSyBpE7erRgxGZhTE34LrjZkbE-UxOb44BRE",
    authDomain: "gasjek-1ed19.firebaseapp.com",
    databaseURL: "https://gasjek-1ed19-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "gasjek-1ed19",
    storageBucket: "gasjek-1ed19.appspot.com",
    messagingSenderId: "757010990046",
    appId: "1:757010990046:web:c503012bf35516d5f6934f",
    measurementId: "G-4CKW8FN4V6"
};

// Inisialisasi Firebase
const app = initializeApp(firebaseConfig);
const realtimeDb = getDatabase(app);

// Inisialisasi peta
const map = L.map('map').setView([-3.2388942, 104.4359333], 13); // Set initial view to a default location

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

// Custom icon for the markers
const riderIcon = L.icon({
    iconUrl: baseurl + '/assets/image/rider.png',
    iconSize: [32, 32], // size of the icon
    iconAnchor: [16, 32], // point of the icon which will correspond to marker's location
    popupAnchor: [0, -32] // point from which the popup should open relative to the iconAnchor
});

// Fungsi untuk mengambil data lokasi driver
async function fetchDriverLocation() {
    const driverLocationRef = ref(realtimeDb, 'DriverLocation/Gelumbang');
    onValue(driverLocationRef, (snapshot) => {
        const data = snapshot.val();
        if (data) {
            for (const driver in data) {
                if (data[driver]['l']) {
                    const lat = data[driver]['l'][0];
                    const lon = data[driver]['l'][1];
                    const marker = L.marker([lat, lon], { icon: riderIcon }).addTo(map);
                    marker.bindPopup(`Driver: ${driver}`).openPopup();
                }
            }
        } else {
            console.log('No data found at specified path.');
        }
    }, (error) => {
        console.error('Error fetching data from Firebase:', error);
    });
}

fetchDriverLocation();

// remove driver from database firebase
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('button[id^="is_limited"]').forEach(button => {
        button.addEventListener('click', function() {
            const policeNumber = $(this).data('police-number');
            const isLimited = $(this).data('is-limited');
            
            removeDriverFromFirebase(policeNumber, isLimited);
        });
    });
});

function removeDriverFromFirebase(policeNumber, isLimited) {
    const driverRef = ref(realtimeDb, `DriverLocation/Gelumbang/${policeNumber}`);

    remove(driverRef).then(() => {
            alert(`Driver ${policeNumber} removed from Firebase successfully.`);
            updateLocalDatabase(policeNumber, isLimited);
        }).catch((error) => {
            alert(`Error removing driver ${policeNumber} from Firebase:`, error);
        });
}

function updateLocalDatabase(policeNumber, isLimited) {
    $.ajax({
        url: baseurl + '/update_is_limited',  // Endpoint yang baru dibuat
        type: 'POST',
        data: {
            police_number: policeNumber,
            is_limited: isLimited
        },
        success: function(response) {
            if (response.status) {
                alert(`Driver ${policeNumber} status updated successfully in local database.`);
                // Refresh halaman
                location.reload();
            } else {
                console.error(`Failed to update driver ${policeNumber} status in local database:`, response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(`Error updating driver ${policeNumber} status in local database:`, error);
        }
    });
}
