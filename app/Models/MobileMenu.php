<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MobileMenu extends Model
{
     use HasFactory;
     protected $table = 'mobile_menu';
     protected $fillable = [
          'id',
          'title',
          'name',
          'icon',
          'sequence_number',
          'created_at',
          'updated_at',
          'parent_id',
     ];

     public function SubMenu()
     {
          return $this->hasMany(MobileMenu::class, 'parent_id')->select("*", "icon as icon_url")->orderBy("sequence_number", "ASC")
               ->with("SubMenu");
     }

     public function SubMenuCompany()
     {
          return $this->hasMany(MobileMenu::class, 'parent_id')->select("*", "icon as icon_url")
               ->whereHas('CompanyMobileMenu', function ($query)
               {
                    $query->where('company_id', Auth::user()->creatorId());
               })
               ->orderBy("sequence_number", "ASC")
               ->with("SubMenuCompany");
     }

     public function CompanyMobileMenu()
     {
          return $this->hasMany(CompanyMobileMenu::class, 'menu_id', 'id');
     }



     public function getIconUrlAttribute()
     {
          return Storage::url('/public/mobileIcon/' . $this->icon);
     }
}
