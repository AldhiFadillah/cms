<?= $this->extend('layout/page_layout') ?>

<?= $this->section('content') ?>
    <!-- Modal -->
    <div id="exampleModalLive" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" style="display: none; opacity:100;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLiveLabel">Daftar Jaksa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered zero-configuration" id="dataJaksa" style="width: 100%;">
                        <thead>
                            <tr>
                                <!-- <th></th> -->
                                <th>Nama / NIP</th>
                                <th>Pangkat</th>
                                <th>Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataJaksa as $data) { ?>
                                <tr>
                                    <!-- <td><input type="checkbox" aria-label="Checkbox for following text input" data-id="<?= $data['nip']; ?>"></td> -->
                                    <td><span><?= $data['nama']; ?></span><br>
                                        <span><?= $data['nip']; ?></span></td>
                                    <td><?= $data['golpang']; ?></td>
                                    <td><?= $data['jabatan']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary tambah-btn" data-nip="<?= $data['nip']; ?>" data-nama="<?= $data['nama']; ?>" data-golpang="<?= $data['golpang']; ?>" data-jabatan="<?= $data['jabatan']; ?>">Tambah</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="addsJaksa-btn">Tambah Terpilih</button>
            </div> -->
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="display: contents;">P-16</h5> Surat Perintah Penunjukan Jaksa untuk Mengikuti Perkembangan Penyidikan
                <p class="mb-1">Nomor SPDP : <span class="text-warning"><?= $IDPerkaraSPDPBaru; ?></span> Tanggal Dikeluarkan : <span class="text-warning"><?= date('d-m-Y', strtotime($tgl_Surat)); ?></span> Tanggal Diterima : <span class="text-warning"><?= date('d-m-Y', strtotime($tgl_Terima)); ?></span></p>
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
                                    <input type="text" class="form-control" id="noP16">
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
                                                <!-- <th>#</th> -->
                                                <th>Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach (json_decode($dataTersangka) as $data) { ?>
                                                <tr>
                                                    <td><?= $data->nama; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 row">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-danger" id="hapusJaksaP16-btn">Hapus</button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLive" fdprocessedid="lq8tli">Tambah</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="dataJaksaP16">
                                        <thead>
                                            <tr>
                                                <!-- <th></th> -->
                                                <!-- <th>#</th> -->
                                                <th>Nama / NIP</th>
                                                <th>Pangkat / Golongan</th>
                                                <th>Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary" id="simpanP16-btn">Simpan</button>
                        <a class="btn btn-danger" href="<?= route_to('p16') ?>" role="button">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // let counter = 1;
        var nipJaksa = [];

        $(document).ready(function() {
            function closeMyModal() {
                $('#exampleModalLive').modal('hide');
            }

            var pilihJaksa = new DataTable('#dataJaksa', {
                order: [],
                processing: true,
                responsive: true,
            });

            var dtJaksa = new DataTable('#dataJaksaP16', {
                order: [],
                processing: true,
                responsive: true,
            });

            dtJaksa.on('click', 'tbody tr', function (e) {
                e.currentTarget.classList.toggle('selected');
            });
            
            function addNewRow(nip, nama, golpang, jabatan) {
                // var checkBoxRow = '<input type="checkbox" aria-label="Checkbox for following text input" data-nip="'+nip+'">';
                var namaNipRow = '<span>'+nama+'</span><br><span>'+nip+'</span>';

                dtJaksa.row
                    .add([
                        // checkBoxRow,
                        // counter,
                        namaNipRow,
                        golpang,
                        jabatan
                    ])
                    .draw(false);
            
                // counter++;
            };

            function deleteRow(nip, nama, golpang, jabatan) {
                // var checkBoxRow = '<input type="checkbox" aria-label="Checkbox for following text input" data-nip="'+nip+'">';
                var namaNipRow = '<span>'+nama+'</span><br><span>'+nip+'</span>';

                dtJaksa.row
                    .add([
                        // checkBoxRow,
                        counter,
                        namaNipRow,
                        golpang,
                        jabatan
                    ])
                    .draw(false);
            
                counter++;
            };

            $('.tambah-btn').click(function() {
                // Ambil data dari atribut data pada tombol
                var nip = $(this).data('nip');
                var nama = $(this).data('nama');
                var golpang = $(this).data('golpang');
                var jabatan = $(this).data('jabatan');

                if (!nipJaksa.includes(nip)) {
                    // Jika nip belum ada, maka tambahkan ke dalam array
                    nipJaksa.push(nip);
                    addNewRow(nip, nama, golpang, jabatan);

                    console.log("NIP " + nip + " berhasil ditambahkan.");
                } else {
                    console.log("NIP " + nip + " sudah ada dalam array.");
                }
                
                closeMyModal();
            });

            $('#hapusJaksaP16-btn').click(function() {
                var dataSelected = dtJaksa.rows('.selected').data();

                $.each(dataSelected, function(index, value) {
                    var dataString = value[0];

                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = dataString;

                    // Mengambil elemen span kedua
                    var span2 = tempDiv.querySelectorAll('span')[1];
                    var nip = span2.textContent; // Mendapatkan teks di dalam span kedua

                    var index = nipJaksa.indexOf(nip);

                    // Hapus elemen dengan menggunakan splice
                    if (index !== -1) {
                        nipJaksa.splice(index, 1);
                    }
                });

                dtJaksa.rows('.selected').remove().draw(false);
            });

            $('#simpanP16-btn').click(function() {
                var data = {
                    dataJaksa: nipJaksa,
                    noP16: $('#noP16').val(),
                    id_perkara: '<?= $IDPerkaraSPDPBaru; ?>',
                    dataSPDP: '<?= $dataSPDP; ?>',
                    dataBerkasThp1: '<?= $dataBerkasThp1; ?>',
                    dataPengantarThp1: '<?= $dataPengantarThp1; ?>',
                    dataTersangka: '<?= $dataTersangka; ?>'
                    // Tambahkan data lainnya sesuai kebutuhan
                };

                $.ajax({
                    url: '<?= route_to('simpanP16Jaksa') ?>', // Ganti dengan URL yang sesuai
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        // Tindakan lanjutan setelah berhasil
                        if (response.status === 'success') {
                            // Jika respons berhasil, tampilkan pesan sukses
                            alert(response.message);
                            // Redirect ke halaman lain atau sebelumnya
                            window.location.href = '<?= route_to('p16') ?>'; // Ganti dengan URL tujuan
                        } else {
                            // Jika respons gagal, tampilkan pesan error
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tangani kesalahan jika terjadi
                        console.error(error);
                        // Tampilkan pesan kesalahan
                        alert('Terjadi kesalahan saat memproses permintaan.');
                    }
                });
            });
        });
    </script>
<?= $this->endSection() ?>