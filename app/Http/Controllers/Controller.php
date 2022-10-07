<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  public function storeToOss($file, $path)
  {
    if($file)
    {
      $storage = Storage::disk("aliyun");
      $filename = time() . '_' . $file->getClientOriginalName();
      $storage->put($path . $filename , file_get_contents($file));
      return $path . $filename;
    }
  }

  public function deleteOssFile($path)
  {
    if($path)
    {
      $storage = Storage::disk("aliyun");
      if($storage->has($path))
      {
        $storage->delete($path);
      }
    }
  }
}

