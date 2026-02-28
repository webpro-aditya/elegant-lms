<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class MediaManager extends Model
{
    protected $guarded = ['id'];

    public function used_media()
    {
        return $this->hasMany(UsedMedia::class, 'media_id', 'id');
    }

    public function getFileNameAttribute()
    {
        $file = $this->attributes['file_name'] ?? null;
        $type = $this->attributes['storage'] ?? null;
        if($type=='local'){
            return $file ? assetPath($file) : null;
        }else{
            return  $file;
        }
    }
}
