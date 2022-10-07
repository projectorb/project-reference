<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlogsValidator;
use App\Models\Blogs;
use Illuminate\Support\Facades\Storage;
use Mail;
use \App\Mail\Mailer;

class BlogsController extends Controller
{
  public function store(BlogsValidator $request)
  {
    $data = $request->all();
    $user = \Auth::user();
    $data['user_id'] = $user->id;

    if($request->has('image'))
    {
      $data['image'] = $this->storeToOss($request->file('image'),'blogs/');
    }

    $blog = Blogs::create($data);
    $subject = 'New Blogs Post Created : ' . $blog->title;
    try {
      Mail::to($user->email)
      ->send( new Mailer($subject, $blog) );
    } catch (Exception $e)
    {
      \Log::info(['Failed to send email for : ', $blog ]);
    }
    return response()->json(['success' => true, 'data' =>  $blog], 200);
  }

  public function update(BlogsValidator $request, $id)
  {
    $blog = Blogs::findOrFail($id);

    $data = $request->all();

    if($request->has('image'))
    {
      if($blog->image)
      {
        $this->deleteOssFile($blog->image);
      }
      $data['image'] = $this->storeToOss($request->file('image'),'blogs/');
    }

    $blog->update($data);

    if($blog->title > 191)
    {
      // return response()->json(['success' => false, 'message' => 'Please only 191 characters'], 200);
    }

    return response()->json(['success' => true], 200);
  }

  public function destroy($id)
  {
    $blog = Blogs::findOrFail($id);

    if($blog->image)
    {
      $this->deleteOssFile($blog->image);
    }
    $blog->delete();

    return response()->json(['success' => true], 200);
  }
}
