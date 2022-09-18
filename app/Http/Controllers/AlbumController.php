<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Picture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = DB::table('albums')->where('user_id',Auth::id())->get();
        return view('albums.index',['albums'=>$albums]);
    }


    public function store(Request $request)
    {
        $this->_validate($request);

        if(DB::table('albums')->insert(['name' => "$request->name",'user_id' =>Auth::id()])){
            return redirect()->back()->with('success','The album has been added successfully');
        }
    }

    public function update(Request $request)
    {
        $this->_validate($request);

        $album = Album::find($request->album_id);
        $images_dir = public_path('images'.DIRECTORY_SEPARATOR);

        $old_dir = $images_dir.$album->name;

        if(is_dir($old_dir)){
            rename($old_dir , $images_dir.$request->name);
        }

        if(Album::where('id',$album->id)->update(['name'=>$request->name])){
            return redirect()->back()->with('success','Album has been updated Successfully');
        }

    }

    private function _validate($request){

        return $request->validate([
            'name' => 'required|unique:albums,name,'.$request->id,
        ]);

    }

    public function checkEmpty($id)
    {
        $album = Album::find($id);

        if($album->pictures->count() > 0){
            return false;
        }
        return true;
    }

    public function delete($id)
    {
        $album = Album::find($id);

        if($album->pictures->count() === 0){
            return Album::destroy($id);
        }

        // delete album pics
        Picture::where('album_id', $id)->delete();

        // delete album
        Album::destroy($id);


        $dir = public_path('images\\'.$album->name);

        if (!file_exists($dir)) {
            return true;
        }

        // scan dir and delete each item in it then delete the empty folder
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            unlink($dir.DIRECTORY_SEPARATOR.$item);
        }
        return rmdir($dir);
    }

    public function move(Request $request)
    {
        if(!isset($request->new_id) || empty($request->new_id)){
            return redirect()->back()->with('error','You should select the backup album');
        }

        $old_id = $request->current_id;
        $new_id = $request->new_id;

        $old_album = Album::find($old_id);
        $new_album = Album::find($new_id);
        // return $request;
        $images_dir = public_path('images'.DIRECTORY_SEPARATOR);

        $oldDir = $images_dir . $old_album->name;
        $newDir = $images_dir . $new_album->name;

        if(!is_dir($newDir)){
            mkdir($newDir);
        }
        foreach (scandir($oldDir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            rename($oldDir.DIRECTORY_SEPARATOR.$item , $newDir.DIRECTORY_SEPARATOR.$item);
        }

        // update album pics ids
        Picture::where('album_id', $old_id)->update(['album_id'=>$new_id]);

        // delete old album db&folder
        Album::destroy($old_id);
        rmdir($oldDir);

        return redirect()->back()->with('success','Album pictures have been moved Successfully');

    }

    public function show($id)
    {
        $album = Album::find($id);
        $pictures = $album->pictures;
        return view ('albums.show',compact('album','pictures'));
    }
}
