<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= application('app_name') ?></title>

    <!-- boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>

    <!-- content css -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <link rel="manifest" href="<?= base_url('assets/image/favicon/site.webmanifest') ?>">
    <link rel="icon" href="<?= base_url('assets/image/favicon/favicon.ico') ?>" type="image/gif">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/image/favicon/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/image/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/image/favicon/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/image/favicon/android-chrome-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/image/favicon/android-chrome-512x512.png') ?>">

    <!-- icon google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- swal fire -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <style>
        #loading p {
            text-align: center;
            font-size: 1.2em;
            color: #007bff;
        }
    </style>
</head>

<body>
    <!-- sidebar -->
    <?= $this->include('layout/sidebar'); ?>

    <!-- content -->
    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/index.js') ?>"></script>
    <script>
        $(".swal2-container").css('background-color', 'rgb(0 0 0 /10%)');
    </script>

    <script>
        function preview() {
            const img_preview = document.querySelector('.preview_image');
            const img_file = document.querySelector('#img_file');
            const cover = new FileReader();

            cover.readAsDataURL(img_file.files[0]);
            cover.onload = function(e) {
                img_preview.src = e.target.result;
            }
        }
    </script>

    <script>
        function preview_account() {
            const img_preview = document.querySelector('#preview_image');
            const img_file = document.querySelector('#formFile');
            const cover = new FileReader();

            cover.readAsDataURL(img_file.files[0]);
            cover.onload = function(e) {
                img_preview.src = e.target.result;
            }
        }
    </script>
    <script>
        // JavaScript untuk mengaktifkan dan menonaktifkan sidebar
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Cek lebar layar saat di-load dan ketika berubah
        window.onload = function() {
            checkWidth();
        }

        window.onresize = function() {
            checkWidth();
        }

        // Fungsi untuk memeriksa lebar layar dan mengaktifkan/menonaktifkan sidebar
        function checkWidth() {
            var sidebar = document.getElementById('sidebar');
            var screenWidth = window.innerWidth;

            if (screenWidth <= 768) {
                sidebar.classList.remove('active');
            } else {
                sidebar.classList.add('active');
            }
        }
    </script>
</body>

</html>