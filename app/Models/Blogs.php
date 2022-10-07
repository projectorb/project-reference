<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Blogs extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'image'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
      if($this->image)
      {
        $storage = Storage::disk("aliyun");
        if($storage->has($this->image))
        {
          return $storage->temporaryUrl($this->image, now()->addMinutes(60) );
        }
      }

      return '';
    }

}
