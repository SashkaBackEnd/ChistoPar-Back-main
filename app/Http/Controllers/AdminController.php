<?php

namespace App\Http\Controllers;

use App\Models\Bath;
use App\Models\Specialist;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function bath(Request $request) {
        return view('admin.index', [
            'bath_id' => $request->bath_id
        ]);
    }
    public function specialist(Request $request) {
        return view('admin.specialist', [
            'specialist_id' => $request->specialist_id
        ]);
    }
    public function changePhotoBath(Request $request) {
        $path = '';
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $ext = $value->extension();
                $img = $value;
                $imageName = uniqid() . '.' . $ext;
                $value->move(public_path() . '/uploads', $imageName);

                $path = '/uploads/' . $imageName;
            }
        }
        $bath = Bath::where('id', $request->bath_id)->first();
        if($bath == null || $bath->media == '') {
            return "ошибка";
        }
        $media = json_decode($bath->media,true);
        $media['img'][0]['src'] = $path;
        $media_json = json_encode($media);
        Bath::where('id', $request->bath_id)->update([
            'media' => $media_json
        ]);
        return redirect()->back();
    }
    public function changePhotoSpecialist(Request $request) {
        $path = '';
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $ext = $value->extension();
                $img = $value;
                $imageName = uniqid() . '.' . $ext;
                $value->move(public_path() . '/uploads', $imageName);

                $path = '/uploads/' . $imageName;
            }
        }
        $bath = Specialist::where('id', $request->specialist_id)->first();
        if($bath == null || $bath->media == '') {
            return "ошибка";
        }
        $media = json_decode($bath->media,true);
        $media['img'][0]['src'] = $path;
        $media_json = json_encode($media);
        Specialist::where('id', $request->specialist_id)->update([
            'media' => $media_json
        ]);
        return redirect()->back();
    }
}
