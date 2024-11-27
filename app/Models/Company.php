<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;

class Company extends BaseModel
{
    use Billable, Notifiable;

    protected $table = 'companies';


    protected $default = ['xid'];

    protected $guarded = ['id', 'is_global', 'package_type', 'stripe_id', 'trial_ends_at',  'created_at', 'updated_at'];

    protected $hidden = ['id', 'lang_id', 'admin_id', 'updated_at'];

    protected $appends = ['xid', 'x_lang_id', 'x_admin_id', 'login_image_url', 'light_logo_url', 'dark_logo_url', 'small_light_logo_url', 'small_dark_logo_url', 'beep_audio_url'];

    protected $hashableGetterFunctions = [
        'getXLangIdAttribute' => 'lang_id',
        'getXAdminIdAttribute' => 'admin_id'
    ];

    protected $casts = [
        'lang_id' => Hash::class . ':hash',
        'admin_id' => Hash::class . ':hash',
        'app_debug' => 'integer',
        'rtl' => 'integer',
        'auto_detect_timezone' => 'integer',
        'update_app_notification' => 'integer',
        'is_global' => 'integer',
        'verified' => 'integer',
        'white_label_completed' => 'integer',
    ];

    protected $filterable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            $builder->where('companies.is_global', 0);
        });
    }

    public function getLightLogoUrlAttribute()
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->light_logo == null ? asset('images/light.png') : Common::getFileUrl($companyLogoPath, $this->light_logo);
    }

    public function getDarkLogoUrlAttribute()
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->dark_logo == null ? asset('images/dark.png') : Common::getFileUrl($companyLogoPath, $this->dark_logo);
    }

    public function getSmallDarkLogoUrlAttribute()
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->small_dark_logo == null ? asset('images/small_dark.png') : Common::getFileUrl($companyLogoPath, $this->small_dark_logo);
    }

    public function getSmallLightLogoUrlAttribute()
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->small_light_logo == null ? asset('images/small_light.png') : Common::getFileUrl($companyLogoPath, $this->small_light_logo);
    }

    public function getLoginImageUrlAttribute()
    {
        $companyLogoPath = Common::getFolderPath('companyLogoPath');

        return $this->login_image == null ? asset('images/login_background.svg') : Common::getFileUrl($companyLogoPath, $this->login_image);
    }

    public function getBeepAudioUrlAttribute()
    {
        // $audioFilesPath = Common::getFolderPath('audioFilesPath');

        return asset('images/beep.wav');
    }

    public function admin()
    {
        return $this->belongsTo(StaffMember::class, 'admin_id', 'id');
    }
}
