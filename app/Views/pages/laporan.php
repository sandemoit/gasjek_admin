<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="home_content">

    <header>
        <h4><?= $title ?></h4>
    </header>

    <div class="content">
        <div class="content_menu block">
        </div>
    </div>
</section>
<script src="<?= base_url('js/check_email.js') ?>"></script>

<?= $this->endSection(); ?>