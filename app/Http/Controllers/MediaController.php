<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * @OA\Post(
     *      path="/media/upload",
     *      tags={"Media"},
     *      summary="Загрузка медиафайлов",
     *      description="Чтобы передать медиафалы, используем массив files[]. Возвращается json медиафайлов",
     *      @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="files[]",
     *                     type="file",
     *                ),
     *                required={"files[]"}
     *             )
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="api_token",
     *          description="Токен пользователя",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function create(Request $request)
    {
        $arr = [];
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $ext = $value->extension();
                $img = $value;
                $imageName = uniqid() . '.' . $ext;
                $value->move(public_path() . '/uploads', $imageName);
                if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg') {
                    $arr['images'][] = '/uploads/' . $imageName;
                } elseif($ext == 'mp4' || $ext == 'M4V' ||  $ext == 'AVI' || $ext == 'MPG') {
                    $arr['video'][] = '/uploads/' . $imageName;
                } else {
                    $arr['other'] = '/uploads/' . $imageName;
                }
            }
        }
        return response($arr);
    }
}
