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
        return view('spdp/create');
    }
}
