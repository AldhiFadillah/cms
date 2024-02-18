<?= $this->extend('layout/new_layout') ?>

<?= $this->section('content') ?>

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Daftar Jaksa</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Pegawai</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>NIP / NRP</th>
                                        <th>Nama</th>
                                        <th>Pangkat</th>
                                        <th>Jabatan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataJaksa as $data) { ?>
                                        <tr>
                                            <td><?= $data['nip_nrp']; ?></td>
                                            <td><?= $data['nama']; ?></td>
                                            <td><?= $data['golpang']; ?></td>
                                            <td><?= $data['jabatan']; ?></td>
                                            <td>
                                                <div class="pagination"><a class="btn btn-primary" role="button" href="<?= base_url('dataJaksa/' . $data['nip']); ?>">View</a></div>
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
                new DataTable('#dataJaksa', {
                    order: [],
                    processing: true,
                    responsive: true,
                });
            });
        </script>

    </div>
    <!-- #/ container -->
</div>

<?= $this->endSection() ?>