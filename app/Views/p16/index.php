<?= $this->extend('layout/new_layout') ?>

<?= $this->section('content') ?>

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">P16</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data P16</h4>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Asal, No & Tgl Berkas, No & Tgl Penyerahan</th>
                                        <th>Melanggar UU dan Pasal</th>
                                        <th>P16 Nomor dan Tanggal, Jaksa Peneliti</th>
                                        <th>Nama Tersangka</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataP16 as $p16) { ?>
                                        <tr>
                                            <td> <?= $p16['akronim']; ?> <br>
                                                <?= $p16['no_berkas']; ?> Tgl Berkas <?= $p16['tgl_berkas']; ?><br>
                                                <?= $p16['no_pengantar']; ?> Tgl Berkas <?= $p16['tgl_pengantar']; ?><br>
                                                Tgl Berkas <?= $p16['tgl_terima_pengantar']; ?>
                                            </td>
                                            <td>
                                                <?= $p16['UUPasal']; ?>
                                            </td>
                                            <td>
                                                <?= (explode("#", $p16['p16']))[2] ?><br>
                                                <?php $no = 1;
                                                foreach ($p16['jp16'] as $jp16) { ?>
                                                    <?= $no; ?>. <?= $jp16['nama']; ?> <br>
                                                <?php $no++;
                                                } ?>
                                            </td>
                                            <td>
                                                <?= $p16['tersangka']; ?>
                                            </td>
                                            <td>
                                                <?= $p16['status']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                new DataTable('#p16', {
                    order: [],
                    processing: true,
                    responsive: true,
                });

                var newButton = $('<a class="ml-5 btn btn-primary" href="<?= base_url('tambahSPDP'); ?>" role="button">Tambah</a>');
                $('#p16_filter label').after(newButton);
            });
        </script>

    </div>
    <!-- #/ container -->
</div>

<?= $this->endSection() ?>