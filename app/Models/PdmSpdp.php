<?php

namespace App\Models;

use CodeIgniter\Model;

class PdmSpdp extends Model
{
    protected $table            = 'pidum.pdm_spdp';
    protected $primaryKey       = 'id_perkara';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_perkara','id_asalsurat','id_penyidik','no_surat','tgl_surat','tgl_terima','ket_kasus',
    'id_pk_ting_ref','wilayah_kerja','tgl_kejadian_perkara','undang_pasal','id_satker_tujuan','pkting','tempat_kejadian','proses_pelimpahan',
    'id_sita','kode_pidana','tgl_sprindik','no_sprindik'];

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
}
