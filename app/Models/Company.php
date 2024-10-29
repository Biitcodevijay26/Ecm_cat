<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';


    public function companyOwner(){
        $company_admin = \Config::get('constants.roles.Company_Admin');
        // return $this->hasMany(User::class,'company_id','_id')->where(['role_id' => $company_admin,'status' => 1,'is_active' => 1]);
        return $this->hasMany(User::class,'company_id','_id');
    }

    public function cluster(){
        return $this->hasMany(Cluster::class,'company_id','_id');
    }



}
