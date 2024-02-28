<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\RawSql;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use App\Models\PdmP16;
use App\Models\PdmSpdp;
use App\Models\PdmJaksaP16;
use App\Models\Pegawai;
use App\Models\PdmBerkasTahap1;
use App\Models\PdmPengantarTahap1;
use App\Models\MsTersangkaBerkas;

class P16 extends BaseController
{
    public function index()
    {
        $p16 = new PdmP16();
        $spdp = new PdmSpdp();
        $jp16 = new PdmJaksaP16();
        $data['dataP16'] = $p16->getP16(null, null);
        
        foreach ($data['dataP16'] as &$dataP16) {
            $result = $spdp->where('id_perkara', $dataP16['id_perkara'])->first();
            $dataP16['UUPasal'] = $result['undang_pasal'] ?? null;

            $dataP16['jp16'] = $jp16->select('nama')->where('id_p16', (explode("#",$dataP16['p16']))[0])->get()->getResultArray();
        }

        return view('p16/index', $data);
    }

    public function daftarJaksa()
    {
        $db = db_connect();

        $data['dataJaksa'] = $db->table('pidum.vw_dataJaksa')->select('nip_nrp, nama, golpang, jabatan, nip')->get()->getResultArray();

        return view('daftarJaksa/index',$data);
    }

    public function detailJaksa($nip)
    {
        $data['nip'] = $nip;
        $p16 = new PdmP16();
        $peg = new Pegawai();

        $data['jaksa'] = $peg->getDataJaksa($nip);
        $data['dataPerkara'] = $p16->getDataPerkaraJaksa($nip);
        return view('daftarJaksa/detail', $data);
    }

    public function tambahSPDP()
    {
        $dataSPDP = $this->addDataSPDP();
        $dataBerkasThp1 = $this->addDataBerkasThp1($dataSPDP['id_perkara']);
        $dataPengantarThp1 = $this->addDataPengantarTahap1($dataBerkasThp1['id_berkas']);
        $dataTersangka = $this->addDataTersangkaBerkas($dataPengantarThp1['id_pengantar'], $dataPengantarThp1['id_berkas'], $dataPengantarThp1['no_pengantar']);

        $db = db_connect();
        $data['IDPerkaraSPDPBaru'] = $dataSPDP['id_perkara'];
        $data['tgl_Surat'] = $dataSPDP['tgl_surat'];
        $data['tgl_Terima'] = $dataSPDP['tgl_terima'];
        $data['dataJaksa'] = $db->table('pidum.vw_dataJaksa')->select('nip_nrp, nama, golpang, jabatan, nip')->get()->getResultArray();
        $data['dataTersangka'] = json_encode($dataTersangka);
        $data['dataPengantarThp1'] = json_encode($dataPengantarThp1);
        $data['dataBerkasThp1'] = json_encode($dataBerkasThp1);
        $data['dataSPDP'] = json_encode($dataSPDP);
        
        return view('spdp/create', $data);
    }

    public function simpanP16Jaksa()
    {
        $request = $this->request->getJSON(); // Ambil data JSON dari permintaan AJAX
        $spdp = new PdmSpdp();
        $spdp->insert(json_decode($request->dataSPDP));

        $berkasThp1 = new PdmBerkasTahap1();
        $berkasThp1->insert(json_decode($request->dataBerkasThp1));

        $pengantarThp1 = new PdmPengantarTahap1();
        $pengantarThp1->insert(json_decode($request->dataPengantarThp1));
        
        foreach (json_decode($request->dataTersangka) as $tersangka) {
            $tskBerkas = new MsTersangkaBerkas();
            $tskBerkas->insert($tersangka);
        }

        $p16 = $this->addDataP16($request);
        $dataJaksaP16 = $this->addDataJaksaP16($request->dataJaksa, $p16['id_p16'], $p16['id_perkara']);

        // consume API
        $token = $this->getToken();

        $respInputData = $this->ConsumeAPIInsertDataP16(json_decode($token, true)['token'], 
        json_decode($request->dataSPDP), json_decode($request->dataBerkasThp1), json_decode($request->dataPengantarThp1), 
        json_decode($request->dataTersangka), $p16, $dataJaksaP16);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Data Tersimpan']);
    }

    private function ConsumeAPIInsertDataP16($token, $spdp, $berkasThp1, $pengantarThp1, $dataTersangka, $p16, $jaksaP16){
        // URL API
        $url = env('API_URL')."/api/inputDataPerkara";

        $client = service('curlrequest');

        // Set header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer '.$token,
            'Content-Type' => 'application/json', // Sesuaikan dengan tipe konten yang sesuai
        ];

        // Set data yang akan dikirim (jika diperlukan)
        $data = [
            'dataSPDP' => $spdp,
            'dataBerkasThp1' => $berkasThp1,
            'dataPengantarThp1' => $pengantarThp1,
            'dataTersangka' => $dataTersangka,
            'dataP16' => $p16,
            'dataJaksaP16' => $jaksaP16,
        ];

        // Lakukan request POST dengan bearer token
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $data, // Menggunakan json untuk mengirim data dalam format JSON
        ]);

        // Lakukan sesuatu dengan respons
        // echo $response->getBody();
    }

    private function getToken(){
        // URL API
        $url = env('API_URL')."/api/login";
        
        // Data yang akan dikirim dalam body permintaan (misalnya dalam format JSON)
        $data = [
            'email' => env('API_USERNAME'),
            'password' => env('API_PASSWORD')
        ];

        // Menginisialisasi session cURL
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        // Jalankan permintaan cURL
        $response = curl_exec($ch);

        // Periksa apakah ada kesalahan
        if(curl_errno($ch)){
            echo 'Error: ' . curl_error($ch);
        }

        // Tutup session cURL
        curl_close($ch);

        // Tampilkan respons
        // echo $response;

        return $response;
    }

    private function addDataJaksaP16($dataJaksa, $idP16, $id_perkara){
        $dataJaksaP16 = [];
        $jp16 = new PdmJaksaP16();
        $i = 1;

        foreach ($dataJaksa as $nipJaksa) {
            $peg = new Pegawai();
            $jaksa = $peg->where('peg_nip_baru', $nipJaksa)->first();

            $dataJP16 = [
                'id_jpp' => $idP16."|".$i,
                'id_perkara' => $id_perkara,
                'id_p16' => $idP16,
                'nip' => $nipJaksa,
                'nama' => $jaksa['nama'],
                'jabatan' => $jaksa['jabatan'],
                'pangkat' => $jaksa['gol_pangkat2'],
                'no_urut' => $i++,
                'id_kejati' => '13',
                'id_kejari' => '32',
                'id_cabjari' => '00'
            ];
            
            $jp16->insert($dataJP16);
            array_push($dataJaksaP16, $dataJP16);
        }

        return $dataJaksaP16;
    }

    private function addDataP16($request){
        $p16 = new PdmP16();
        $jumlahDataTerakhir = $p16->countAllResults() + 1;
        $idP16 = dechex($jumlahDataTerakhir);

        $dataP16 = [
            'id_p16' => $idP16,
            'id_perkara' => $request->id_perkara,
            'no_surat' => $request->noP16,
            'dikeluarkan' => 'KRAKSAAN',
            'tgl_dikeluarkan' => date('Y-m-d'),
            'id_penandatangan' => '197606081996031001',
            'created_by' => '199712072018012001',
            'created_time' => date('Y-m-d H:i:s'),
            'updated_time' => date('Y-m-d H:i:s'),
            'nama' => 'DAVID P. DUARSA, S.H., M.H.',
            'pangkat' => 'Jaksa Madya',
            'jabatan' => 'KEPALA KEJAKSAAN NEGERI KABUPATEN PROBOLINGGO',
            'id_kejati' => '13',
            'id_kejari' => '32',
            'id_cabjari' => '00'
        ];
        
        $p16->insert($dataP16);

        return $dataP16;
    }

    private function addDataTersangkaBerkas($id_pengantar, $id_berkas, $no_pengantar){
        $tskBerkas = new MsTersangkaBerkas();
        
        $jmlTersangka = rand(1, 2);

        $dataTersangka = array();

        for ($i = 1; $i <= $jmlTersangka; $i++) {
            $faker = \Faker\Factory::create('id_ID');

            $tersangka = [
                'id_tersangka' => $id_pengantar."|".$i,
                'id_berkas' => $id_berkas,
                'no_pengantar' => $no_pengantar,
                'tmpt_lahir' => $faker->city(),
                'alamat' => $faker->address(),
                'no_identitas' => 3513121607740002 + rand(1, 500) + rand(1, 1500) + rand(1, 1000500) + rand(1, 1000000500) + rand(1, 1000000000500),
                'warganegara' => '71',
                'pekerjaan' => '88',
                'suku' => '-',
                'nama' => $faker->name(),
                'id_jkl' => rand(1, 2),
                'id_identitas' => rand(1, 2),
                'id_agama' => rand(1, 5),
                'id_pendidikan' => rand(1, 5),
                'umur' => rand(18, 50),
                'no_urut' => $i,
                'id_pengantar' => $id_pengantar,
                'id_status' => '2',
                'id_kejati' => '13',
                'id_kejari' => '32',
                'id_cabjari' => '00'
            ];
            // dd($tersangka);

            array_push($dataTersangka, $tersangka);
            
            // Insert data ke database
            // $tskBerkas->insert($tersangka);
        }
        return $dataTersangka;
    }

    private function addDataPengantarTahap1($id_berkas){
        $pengantarThp1 = new PdmPengantarTahap1();
        $lastPengantarTahap1 = $pengantarThp1->orderBy('created_time', 'DESC')->first();

        // Menemukan angka di dalam string
        preg_match('/\d+/', $lastPengantarTahap1['no_pengantar'], $matches);
        // Menambahkan 1 ke angka yang ditemukan
        $angka_baru = $matches[0] + 1;
        // Mengganti angka lama dengan angka baru dalam string
        $no_PengantarTahap1Baru = preg_replace('/\d+/', $angka_baru, $lastPengantarTahap1['no_pengantar'], 1);

        $dataPengantarTahap1 = [
            'id_pengantar' => $id_berkas. '|' .$no_PengantarTahap1Baru,
            'id_berkas' => $id_berkas,
            'no_pengantar' => $no_PengantarTahap1Baru,
            'tgl_pengantar' => date('Y-m-d', strtotime('-1 days')),
            'tgl_terima' => date('Y-m-d'),
            'created_by' => '199712072018012001',
            'created_ip' => '10.16.32.16',
            'created_time' => date('Y-m-d H:i:s'),
            'updated_time' => date('Y-m-d H:i:s'),
            'id_kejati' => '13',
            'id_kejari' => '32',
            'id_cabjari' => '00'
        ];

        // $pengantarThp1->insert($dataPengantarTahap1);

        return $dataPengantarTahap1;
    }
    
    private function addDataBerkasThp1($id_perkara){
        $berkasThp1 = new PdmBerkasTahap1();
        $lastBerkasThp1 = $berkasThp1->orderBy('created_time', 'DESC')->first();

        // Pisahkan angka dari string
        preg_match('/(\d+)BP/', $lastBerkasThp1['id_berkas'], $matches);
        $angka = isset($matches[1]) ? $matches[1] : '';
        // Tambahkan 1 pada angka
        $angka_baru = $angka + 1;
        // Gabungkan angka baru dengan sisa string
        $IDBerkasBaru = preg_replace('/\d+BP/', $angka_baru . 'BP', $lastBerkasThp1['id_berkas'], 1);

        // Menemukan angka di dalam string
        preg_match('/\d+/', $lastBerkasThp1['no_berkas'], $matches);
        // Menambahkan 1 ke angka yang ditemukan
        $angka_baru = $matches[0] + 1;
        // Mengganti angka lama dengan angka baru dalam string
        $no_BerkasBaru = preg_replace('/\d+/', $angka_baru, $lastBerkasThp1['no_berkas'], 1);

        $dataBerkasThp1 = [
            'id_berkas' => $IDBerkasBaru,
            'id_perkara' => $id_perkara,
            'no_berkas' => $no_BerkasBaru,
            'tgl_berkas' => date('Y-m-d', strtotime('-2 days')),
            'created_by' => '199712072018012001',
            'created_ip' => '10.16.32.16',
            'created_time' => date('Y-m-d H:i:s'),
            'updated_time' => date('Y-m-d H:i:s'),
            'id_kejati' => '13',
            'id_kejari' => '32',
            'id_cabjari' => '00'
        ];

        // $berkasThp1->insert($dataBerkasThp1);

        return $dataBerkasThp1;
    }

    private function addDataSPDP(){
        $spdp = new PdmSpdp();
        $lastSPDP = $spdp->orderBy('created_time', 'DESC')->first();
        
        // Pisahkan angka dari string
        preg_match('/(\d+)SPDP/', $lastSPDP['id_perkara'], $matches);
        $angka = isset($matches[1]) ? $matches[1] : '';
        // Tambahkan 1 pada angka
        $angka_baru = $angka + 1;
        // Gabungkan angka baru dengan sisa string
        $IDPerkaraSPDPBaru = preg_replace('/\d+SPDP/', $angka_baru . 'SPDP', $lastSPDP['id_perkara'], 1);

        // Pisahkan angka dari string
        preg_match('/SPDP\/(\d+)\/(.+)/', $lastSPDP['no_surat'], $matches);
        $angka = isset($matches[1]) ? $matches[1] : '';
        $sisa = isset($matches[2]) ? $matches[2] : '';
        // Tambahkan 1 pada angka
        $angka_baru = $angka + 1;
        // Gabungkan angka baru dengan sisa string
        $no_suratSPSDPbaru = 'SPDP/' . $angka_baru . '/' . $sisa;

        // Menemukan angka di dalam string
        preg_match('/\d+/', $lastSPDP['no_sprindik'], $matches);
        // Menambahkan 1 ke angka yang ditemukan
        $angka_baru = $matches[0] + 1;
        // Mengganti angka lama dengan angka baru dalam string
        $no_sprindikSPDPbaru = preg_replace('/\d+/', $angka_baru, $lastSPDP['no_sprindik'], 1);
        
        $tgl_Surat = date('Y-m-d', strtotime('-7 days'));
        $tgl_Terima = date('Y-m-d', strtotime('-5 days'));

        $dataSPDP = [
            'id_perkara' => $IDPerkaraSPDPBaru,
            'id_asalsurat' => '01',
            'id_penyidik' => '160900',
            'no_surat' => $no_suratSPSDPbaru,
            'tgl_surat' => $tgl_Surat,
            'tgl_terima' => $tgl_Terima,
            'ket_kasus' => 'Tindak Pidana Ekonomi',
            'id_pk_ting_ref' => '165',
            'wilayah_kerja' => '13.32',
            'tgl_kejadian_perkara' => date('H-i-d-m-Y', strtotime(date('Y-m-d', strtotime('-10 days')))),
            'undang_pasal' => 'UU No 1 Tahun 1946 KUHP',
            'created_by' => '199712072018012001',
            'kode_pidana' => '2',
            'tgl_sprindik' => date('Y-m-d', strtotime('-7 days')),
            'no_sprindik' => $no_sprindikSPDPbaru,
            'ur_ip' => 'Kepolisian Republik Indonesia',
            'ur_ipp' => 'POLRES PROBOLINGGO',
            'id_kejati' => '13',
            'id_kejari' => '32',
            'id_cabjari' => '00'
        ];

        // $spdp->insert($dataSPDP);

        return $dataSPDP;
    }
}
