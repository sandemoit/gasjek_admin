<?php
$request = \Config\Services::request();
$page = $request->uri->getSegment(1);

$menuItems = [
    'Beranda' => [''],
    'Pesanan' => ['order'],
    'Top Up' => ['wallet'],
    'Mitra' => ['mitra'],
    'Restoran' => ['restaurant', 'view_restaurant', 'comment_restaurant'],
    'Pengguna' => ['user'],
    'Pengemudi' => ['driver'],
    'Banner' => ['banner'],
    'Siaran' => ['broadcast'],
    'Peta' => ['map'],
    'Pengaturan' => ['setting'],
    'Dokumentasi API' => ['api']
];
?>

<nav class="sidebar" id="sidebar">
    <div class="logo_content">
        <div class="logo">
            <img class="logo_image" src="<?= base_url('assets/image/gasjek.jpg') ?>" alt="">
            <span class="app_name"><?= application('app_name') ?></span>
        </div>
        <i class='bx bx-menu' id="btnSidebar"></i>
    </div>

    <ul class="nav_list">
        <?php foreach ($menuItems as $itemName => $itemSegments) : ?>
            <li>
                <a href="<?= base_url("/$itemSegments[0]") ?>" class="<?= in_array($page, $itemSegments) ? 'active' : '' ?>">
                    <?php if ($itemName === 'Beranda') : ?>
                        <i class='bx bx-grid-alt'></i>
                    <?php elseif ($itemName === 'Pesanan') : ?>
                        <i class='bx bx-receipt'></i>
                    <?php elseif ($itemName === 'Top Up') : ?>
                        <i class='bx bx-wallet-alt'></i>
                    <?php elseif ($itemName === 'Mitra') : ?>
                        <i class='bx bx-buildings'></i>
                    <?php elseif ($itemName === 'Restoran') : ?>
                        <i class='bx bx-restaurant'></i>
                    <?php elseif ($itemName === 'Pengguna') : ?>
                        <i class='bx bx-user'></i>
                    <?php elseif ($itemName === 'Pengemudi') : ?>
                        <span class="material-symbols-outlined">sports_motorsports</span>
                    <?php elseif ($itemName === 'Banner') : ?>
                        <i class='bx bx-chalkboard'></i>
                    <?php elseif ($itemName === 'Siaran') : ?>
                        <i class='bx bx-broadcast'></i>
                    <?php elseif ($itemName === 'Peta') : ?>
                        <i class='bx bx-map-alt'></i>
                    <?php elseif ($itemName === 'Pengaturan') : ?>
                        <i class='bx bx-cog'></i>
                    <?php elseif ($itemName === 'Dokumentasi API') : ?>
                        <i class='bx bx-expand-horizontal'></i>
                    <?php endif; ?>
                    <span class="link_name"><?= $itemName ?></span>
                    <span class="tooltip"><?= $itemName ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="profile_content">
        <div class="profile">
            <div class="profile_details">
                <img src="<?= base_url('assets/image/' . user()->image) ?>" alt="">
                <div class="name_job">
                    <div class="name"><?= user()->username ?></div>
                    <div class="job">Admin</div>
                </div>
            </div>
            <a href="logout">
                <i class='bx bx-log-out-circle' id="btnLogout"></i>
            </a>
        </div>
    </div>
</nav>