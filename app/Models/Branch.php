<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
     protected $fillable = [
          'name', 'country_name', 'state_name', 'district_name', 'zip_code', 'address', 'city_name', 'created_by',
          "geofencing_status", "geofencing_radius", 'remote_geofencing_status'
     ];

     public function states()
     {
          return $this->hasOne(State::class, 'state_id', 'state_name');
     }

     public function districts()
     {
          return $this->hasOne(District::class, 'districtid', 'district_name');
     }
}
