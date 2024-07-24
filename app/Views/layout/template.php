<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= application('app_name') ?></title>

    <!-- boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">

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

    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
</head>

<body>
    <!-- sidebar -->
    <?= $this->include('layout/sidebar'); ?>

    <!-- content -->
    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="<?= base_url('js/index.js') ?>"></script>
    <script>
        $(".swal2-container").css('background-color', 'rgb(0 0 0 /10%)');
    </script>


</body>

</html>