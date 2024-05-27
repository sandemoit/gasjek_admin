let btnSidebar = document.getElementById("btnSidebar");
let sidebar = document.getElementById("sidebar");

function toggleSidebar() {
    sidebar.classList.toggle("active");
}

btnSidebar.addEventListener("click", function() {
    toggleSidebar();
});

// Tambahkan pengecekan saat halaman dimuat
window.addEventListener("load", function() {
    // Cek lebar layar saat halaman dimuat
    if (window.innerWidth <= 768) {
        sidebar.classList.remove("active");
    } else {
        sidebar.classList.add("active");
    }
});

// Tambahkan pengecekan saat lebar layar berubah
window.addEventListener("resize", function() {
    // Cek lebar layar saat lebar layar berubah
    if (window.innerWidth <= 768) {
        sidebar.classList.remove("active");
    } else {
        sidebar.classList.add("active");
    }
});