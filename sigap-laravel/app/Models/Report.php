<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','category_id','title','description',
        'photo_path','location_address','latitude','longitude','status'
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function category()  { return $this->belongsTo(ReportCategory::class, 'category_id'); }
    public function statusLogs(){ return $this->hasMany(ReportStatusLog::class); }
}
