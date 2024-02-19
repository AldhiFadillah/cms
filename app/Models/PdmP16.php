<?php

namespace App\Models;

use CodeIgniter\Model;

class PdmP16 extends Model
{
    protected $table            = 'pidum.pdm_p16';
    protected $primaryKey       = 'id_p16';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_p16','id_perkara','no_surat','dikeluarkan','tgl_dikeluarkan','id_penandatangan',
    'created_by','created_time','updated_time','nama','pangkat','jabatan','id_kejati','id_kejari','id_cabjari'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_time';
    protected $updatedField  = 'updated_time';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getP16($limit, $offset)
    {
        if($limit == null){
            $limit = 0;
        }
        if($offset == null){
            $offset = 0;
        }
        
        $query = $this->db->table('pidum.vw_gridtahap1')
        ->select('*')
        ->limit($limit, $offset);

        $results = $query->get()->getResultArray(); // Ambil hasil join dalam bentuk array
        
        return $results;
    }

    public function getDataPerkaraJaksa($nip)
    {      
        $query = $this->db->table('pidum.pdm_p16')
        ->join('pidum.pdm_spdp', 'pdm_p16.id_perkara = pdm_spdp.id_perkara', 'left')
        ->join('pidum.pdm_berkas_tahap1', 'pdm_spdp.id_perkara = pdm_berkas_tahap1.id_perkara', 'left')
        ->join('pidum.ms_tersangka_berkas', 'pdm_berkas_tahap1.id_berkas = ms_tersangka_berkas.id_berkas', 'left')
        ->join('pidum.pdm_jaksa_p16', 'pdm_p16.id_perkara = pdm_jaksa_p16.id_perkara', 'left')
        ->where('pdm_jaksa_p16.nip', $nip)
        ->distinct()
        ->select('pdm_p16.no_surat, pdm_p16.tgl_dikeluarkan, pdm_spdp.ket_kasus, pdm_spdp.undang_pasal, ms_tersangka_berkas.nama');

        $results = $query->get()->getResultArray(); // Ambil hasil join dalam bentuk array
        
        return $results;
    }
}
