<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_info';

    protected $fillable = [
        'company_name',
        'legal_name',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'tax_number',
        'registration_number',
        'logo_path',
        'bank_name',
        'bank_account',
        'iban',
    ];
}
