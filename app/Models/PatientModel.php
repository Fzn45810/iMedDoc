<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientModel extends Model
{
    use HasFactory;
    public $table = "patient";
    protected $fillable = [
        'id',
        'user_id',
        'title_type_id',
        'surname',
        'dname',
        'address1',
        'address2',
        'address3',
        'address4',
        'gp',
        'solicitor',
        'referingDr',
        'pharmacy',
        'dateOfAccident',
        'referralDate',
        'timeOfAccident',
        'primaryDiagnosisType',
        'side',
        'dateOfBirth',
        'age',
        'gender',
        'homePhone',
        'mobile',
        'occupation',
        'maritalStatus',
        'religion',
        'patientType',
        'caseRefNo',
        'notes',
        'primaryDiagnosis',
        'insurance_comp_id',
        'insurance_plane_id',
        'insurance_number'
    ];
}
