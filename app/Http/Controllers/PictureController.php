<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;

class PictureController extends Controller
{
    public function store(Request $request)
    {
        $image = $request->file('file');
        $imageName = time() . '.' . $image->getClientOriginalName();
        // return $image->getClientOriginalName();
        $picture = new Picture();
        $picture->name= $imageName;
        $picture->album_id= $request->album_id;
        $picture->save();

        $image->move(public_path("images/"."$request->album_name"),$imageName);
        return response()->json(['success' => $imageName]);
    }
}
