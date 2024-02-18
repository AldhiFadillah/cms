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

        $db = db_connect();
        $data['IDPerkaraSPDPBaru'] = $IDPerkaraSPDPBaru;
        $data['tgl_Surat'] = $tgl_Surat;
        $data['tgl_Terima'] = $tgl_Terima;
        $data['dataJaksa'] = $db->table('pidum.vw_dataJaksa')->select('nip_nrp, nama, golpang, jabatan, nip')->get()->getResultArray();

        return view('spdp/create', $data);
    }
}
