<?php

namespace App\Http\Controllers;

use App\Http\Requests\BathValueAttrRequest;
use App\Models\BathAttrBath;
use App\Models\BathValueAttr;
use Illuminate\Http\Request;

class BathValueAttrController extends Controller
{
    /**
     * @OA\Get(
     *      path="/bathattrvalue/",
     *      tags={"Bathattrvalue"},
     *      summary="Список атрибуты БК",
     *      description="Возращается объект атрибутов банных комлексов",
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
    public function attr(Request $request) {
        return BathValueAttr::all();
    }

    /**
     * @OA\Put(
     *      path="/bathattrvalue/",
     *      tags={"Bathattrvalue"},
     *      summary="Создать новую атрибут БК",
     *      description="Создать новый атрибут банных комлексов,возращается объект атрибутов банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BathValueAttr")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathValueAttr")
     *       ),
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
    public function create(BathValueAttrRequest $request)
    {
        $attr = BathValueAttr::create($request->validated());
        return $attr;
    }



    /**
     * @OA\Patch(
     *      path="/bathattrvalue/{id}",
     *      tags={"Bathattrvalue"},
     *      summary="Обновить атрибут банных комлпексов",
     *      description="Обновить атрибут банных комлексов, возращается объект атрибутов банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрибута банных комлпексов",
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BathValueAttr")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathValueAttr")
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
    public function edit(Request $request)
    {
        $attr = BathValueAttr::where('id', $request->id)->first();
        if($attr != null) {
            BathValueAttr::where('id', $request->id)->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return BathValueAttr::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/bathattrvalue/{id}",
     *      tags={"Bathattrvalue"},
     *      summary="Удалить атрибут банных комлпексов",
     *      description="Удалить атрибут банных комлпексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрибута банных комлпексов",
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
        BathAttrBath::where('bath_value_id', $request->id)->delete();
        return response([
            'status' => BathValueAttr::where('id', $request->id)->delete()
        ]);
    }
}
