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

// Function to preview image
function preview() {
    const img_preview = document.querySelector('.preview_image');
    const img_file = document.querySelector('#img_file');
    const cover = new FileReader();

    cover.readAsDataURL(img_file.files[0]);
    cover.onload = function(e) {
        img_preview.src = e.target.result;
    }
}

// Function to preview account image
function preview_account() {
    const img_preview = document.querySelector('#preview_image');
    const img_file = document.querySelector('#formFile');
    const cover = new FileReader();

    cover.readAsDataURL(img_file.files[0]);
    cover.onload = function(e) {
        img_preview.src = e.target.result;
    }
}

// JavaScript to toggle sidebar
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

// Check screen width on load and resize
window.onload = function() {
    checkWidth();
}

window.onresize = function() {
    checkWidth();
}

// Function to check screen width and toggle sidebar
function checkWidth() {
    var sidebar = document.getElementById('sidebar');
    var screenWidth = window.innerWidth;

    if (screenWidth <= 768) {
        sidebar.classList.remove('active');
    } else {
        sidebar.classList.add('active');
    }
}
