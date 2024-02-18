<?= $this->extend('layout/page_layout') ?>

<?= $this->section('content') ?>
<!-- Modal -->
<div class="modal" tabindex="-1" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="display: contents;">P-16</h5> Surat Perintah Penunjukan Jaksa untuk Mengikuti Perkembangan Penyidikan
                <p class="mb-1">Nomor SPDP : <span class="text-warning">SPDP/09/I/RES.1.8./2024/Reskrim</span> Tanggal Dikeluarkan <span class="text-warning">31-01-2024</span> Tanggal Diterima : <span class="text-warning">01-02-2024</span></p>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="card">
                            <div class="p-2 row">
                                <div class="col-2">
                                    Nomor :
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="formGroupExampleInput">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 row">
                        <div class="card">
                            <div class="card-header">
                                Tersangka
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="dataPerkara">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>ALDI KONDIHI Alias ALDI</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 row">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary">Hapus</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#myModal">Tambah</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="dataPerkara">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Nama / NIP</th>
                                                <th>Pangkat / Golongan Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="checkbox" aria-label="Checkbox for following text input"></td>
                                                <td>1</td>
                                                <td>ABDULLAH BACHRUDDIN, S.H.
                                                    196305311991031002</td>
                                                <td>Jaksa Muda
                                                    Jaksa Fungsional KEJAKSAAN TINGGI</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $( "#datepicker-tglSprindik" ).datepicker();
            $( "#datepicker-tglSPDP" ).datepicker();
        });
    </script>
<?= $this->endSection() ?>