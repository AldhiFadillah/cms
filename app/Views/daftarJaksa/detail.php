<?= $this->extend('layout/new_layout') ?>

<?= $this->section('content') ?>

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Daftar Jaksa</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Jaksa</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $jaksa->nama; ?> / <?= $jaksa->peg_nip_baru; ?></h5>
                        <!-- <form>
                            <div class="row">
                                <div class="col-2">
                                    <label>NIP :</label>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $jaksa->peg_nip_baru; ?>" readonly>
                                </div>
                                <div class="col-1">
                                    <h6 class="card-title">NRP :</h5>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $jaksa->peg_nrp; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-2">
                                    <h6 class="card-title">Nama Lengkap :</h5>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $jaksa->nama; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-2">
                                    <h6 class="card-title">Jabatan :</h5>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= ucwords($jaksa->jabatan); ?>" readonly>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-2">
                                    <h6 class="card-title">Satuan Kerja :</h5>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= ucwords($jaksa->instansi); ?>" readonly>
                                </div>
                                <div class="col-1">
                                    <h6 class="card-title">Pangkat :</h5>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $jaksa->gol_pangkat2; ?> / (<?= $jaksa->gol_kd; ?>)" readonly>
                                </div>
                            </div>
                        </form> -->
                        <div class="basic-form">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>NIP</label>
                                        <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $jaksa->peg_nip_baru; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>NRP</label>
                                        <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $jaksa->peg_nrp; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nama Lengkap </label>
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $jaksa->nama; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= ucwords($jaksa->jabatan); ?>" readonly>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Satuan Kerja</label>
                                        <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= ucwords($jaksa->instansi); ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Pangkat</label>
                                        <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $jaksa->gol_pangkat2; ?> / (<?= $jaksa->gol_kd; ?>)" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="dataPerkara">
                                <thead>
                                    <tr>
                                        <th>Nomor P16 dan Tanggal</th>
                                        <th>Kasus</th>
                                        <th>UU dan Pasal</th>
                                        <th>Nama Tersangka</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataPerkara as $data) { ?>
                                        <tr>
                                            <td><?= $data['no_surat']; ?> tanggal <?= $data['tgl_dikeluarkan']; ?></td>
                                            <td><?= $data['ket_kasus']; ?></td>
                                            <td><?= $data['undang_pasal']; ?></td>
                                            <td><?= $data['nama']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            new DataTable('#dataPerkara', {
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