<?php

namespace App\Models;

use CodeIgniter\Model;

class MsTersangkaBerkas extends Model
{
    protected $table            = 'pidum.ms_tersangka_berkas';
    protected $primaryKey       = 'id_tersangka';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tersangka','id_berkas','no_pengantar','tmpt_lahir','tgl_lahir','alamat','no_identitas',
    'warganegara','pekerjaan','suku','nama','id_jkl','id_identitas','id_agama','id_pendidikan','umur','no_urut','id_pengantar',
    'id_status','id_kejati','id_kejari','id_cabjari'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
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
