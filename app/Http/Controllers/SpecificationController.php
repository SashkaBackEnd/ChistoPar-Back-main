<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecificationRequest;
use App\Models\Specification;
use App\Models\SpecificationBath;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * @OA\Get(
     *      path="/specification/",
     *      tags={"Specification"},
     *      summary="Характеристики банных комлексов",
     *      description="Возвращается объект характеристик банных комлексов",
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
        return Specification::all();
    }

    /**
     * @OA\Get(
     *      path="/specification/{bath_id}",
     *      tags={"Specification"},
     *      summary="Характеристики банного копмлекса",
     *      description="Возвращается объект характеристик банного комлекса",
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
    public function single(Request $request) {
        $spec_bath = SpecificationBath::where('bath_id', $request->bath_id)->get();
        $data = [];
        foreach($spec_bath as $value) {
            $data[] = $value->specification_id;
        }
        $spec = Specification::whereIn('id', $data)->get();
        $result = [];
        foreach($spec_bath as $spec_b) {
            foreach($spec as $s) {
                if($spec_b->specification_id == $s->id) {
                    $result[$s->name][] = $spec_b->descrition;
                }
            }
        }
        return response($result);
    }


    /**
     * @OA\Put(
     *      path="/specification/",
     *      tags={"Specification"},
     *      summary="Создать новую характеристику банных комлексов",
     *      description="Создать новый характеристику для БК, возвращается объект характеристики банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
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
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
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
    public function create(SpecificationRequest $request)
    {
        $format = Specification::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/specification/{id}",
     *      tags={"Specification"},
     *      summary="Обновить характеристику банных комлексов",
     *      description="Обновить характеристику, возращается объект характеристики банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id характеристики",
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
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
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
        $format = Specification::where('id', $request->id)->first();
        if($format != null) {
            $format->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return Specification::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/specification/{id}",
     *      tags={"Specification"},
     *      summary="Удалить характеристику банных комлексов",
     *      description="Удалить характеристику банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id характеристики",
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
            'status' => Specification::where('id', $request->id)->delete()
        ]);
    }
}
