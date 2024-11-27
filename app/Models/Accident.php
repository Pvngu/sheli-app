<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Casts\Hash;
use App\Models\Area;
use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accident extends BaseModel
{

    use HasFactory;

    protected $table = 'accidents';
    
    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'x_injured_person', 'x_reporting_user', 'x_area_id'];

    protected $hidden = [ 'id', 'injured_person', 'reporting_user', 'area_id',  'created_at', 'updated_at'];

    protected $filterable = ['reporting_user', 'area_id', 'date_of_accident'];

    protected $hashableGetterFunctions = [
        'getXInjuredPersonAttribute' => 'injured_person',
        'getXReportingUserAttribute' => 'reporting_user',
        'getXAreaIdAttribute' => 'area_id',
    ];

    protected $casts = [
        'injured_person' => Hash::class . ':hash',
        'reporting_user' => Hash::class . ':hash',
        'area_id' => Hash::class . ':hash'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function injuredPerson() :BelongsTo
    {
        return $this->belongsTo(User::class, 'injured_person');
    }

    public function reportingUser() :BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_user');
    }

    public function area() :BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
