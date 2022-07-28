<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecificationBathRequest;
use App\Models\SpecificationBath;
use App\Models\Bath;
use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationBathController extends Controller
{
    /**
     * @OA\Get(
     *      path="/specificationbath/{bath_id}",
     *      tags={"SpecificationBath"},
     *      summary="Значение характеристик банных комлексов",
     *      description="Возвращается объект значение характеристик банных комлексов",
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
    public function specification(Request $request) {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->bath_id.' not found'
            ]);
        }
        return SpecificationBath::where('bath_id', $request->bath_id)->get();
    }

    /**
     * @OA\Put(
     *      path="/specificationbath/{bath_id}",
     *      tags={"SpecificationBath"},
     *      summary="Создать новое начение характеристик банных комлексов",
     *      description="Создать новое значение характеристик для БК, возвращается объект значения характеристик банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecificationBath")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecificationBath")
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
    public function create(SpecificationBathRequest $request)
    {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id'.$request->bath_id.'not found'
            ]);
        }
        if(Specification::where('id', $request->specification_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Specification with id '.$request->specification_id.' not found'
            ]);
        }
        $format = SpecificationBath::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/specificationbath/{id}",
     *      tags={"SpecificationBath"},
     *      summary="Обновить значение характеристик банных комлексов",
     *      description="Обновить значение характеристик, возращается объект значения характеристик банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id формата",
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecificationBath")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SpecificationBath")
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
        $format = SpecificationBath::where('id', $request->id)->first();
        if($format != null) {
            $format->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return SpecificationBath::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/specificationbath/{id}",
     *      tags={"SpecificationBath"},
     *      summary="Удалить значения характеристики банных комлексов",
     *      description="Удалить значения характеристики банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id значения характеристики",
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
            'status' => SpecificationBath::where('id', $request->id)->delete()
        ]);
    }
}
