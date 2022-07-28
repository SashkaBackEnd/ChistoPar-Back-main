<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistValueAttrRequest;
use App\Models\Specialist;
use App\Models\SpecialistAttrSpecialist;
use App\Models\SpecialistValueAttr;
use Illuminate\Http\Request;

class SpecialistValueAttrController extends Controller
{
    /**
     * @OA\Get(
     *      path="/specialistattrvalue/",
     *      tags={"Specialistattrvalue"},
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
        return SpecialistValueAttr::all();
    }

    /**
     * @OA\Put(
     *      path="/specialistattrvalue/",
     *      tags={"Specialistattrvalue"},
     *      summary="Создать новую атрибут БК",
     *      description="Создать новый атрибут банных комлексов,возращается объект атрибутов банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistValueAttr")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistValueAttr")
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
    public function create(SpecialistValueAttrRequest $request)
    {
        $attr = SpecialistValueAttr::create($request->validated());
        return $attr;
    }



    /**
     * @OA\Patch(
     *      path="/specialistattrvalue/{id}",
     *      tags={"Specialistattrvalue"},
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistValueAttr")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistValueAttr")
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
        $attr = SpecialistValueAttr::where('id', $request->id)->first();
        if($attr != null) {
            $attr->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return SpecialistValueAttr::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/specialistattrvalue/{id}",
     *      tags={"Specialistattrvalue"},
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
        SpecialistAttrSpecialist::where('specialist_value_id', $request->id)->delete();
        return response([
            'status' => SpecialistValueAttr::where('id', $request->id)->delete()
        ]);
    }
}
