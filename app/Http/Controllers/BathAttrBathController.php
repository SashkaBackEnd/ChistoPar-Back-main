<?php

namespace App\Http\Controllers;

use App\Http\Requests\BathAttrBathRequest;
use App\Models\Bath;
use App\Models\BathAttrBath;
use Illuminate\Http\Request;

class BathAttrBathController extends Controller
{
    /**
     * @OA\Get(
     *      path="/bathattrbath/{bath_id}",
     *      tags={"BathAttrBath"},
     *      summary="Значение артибутов банных комлексов",
     *      description="Возвращается объект значений атрибутов банных комлексов",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id БК",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function bathattr(Request $request) {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->bath_id.' not found'
            ]);
        }
        return BathAttrBath::where('bath_id', $request->bath_id)->get();
    }


    /**
     * @OA\Put(
     *      path="/bathattrbath/",
     *      tags={"BathAttrBath"},
     *      summary="Создать новое значение атрибута банных комлексов",
     *      description="Создать новое значение атрибута банных комлексов, возвращается объект значний атрибутов банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BathAttrBath")
     *      ),
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
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathAttrBath")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function create(BathAttrBathRequest $request)
    {
        $format = BathAttrBath::create($request->validated());
        return $format;
    }

    /**
     * @OA\Delete(
     *      path="/bathattrbath/{id}",
     *      tags={"BathAttrBath"},
     *      summary="Удалить значение атрибута банных комлексов",
     *      description="Удалить атрибутa банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрибута",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function delete(Request $request)
    {
        return response([
            'status' => BathAttrBath::where('id', $request->id)->delete()
        ]);
    }
}
