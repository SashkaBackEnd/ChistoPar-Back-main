<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistAttrSpecialistRequest;
use App\Models\Specialist;
use App\Models\SpecialistAttrSpecialist;
use Illuminate\Http\Request;

class SpecialistAttrSpecialistController extends Controller
{
     /**
     * @OA\Get(
     *      path="/specialist/attr/specialist/{specialist_id}",
     *      tags={"SpecialistAttrSpecialist"},
     *      summary="Значение артибутов специалистов",
     *      description="Возвращается объект значений атрибутов специалистов",
     *      @OA\Parameter(
     *          name="specialist_id",
     *          description="id специалиста",
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
    public function specialistattr(Request $request) {
        if(Specialist::where('id', $request->specialist_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Specialist with id '.$request->specialist_id.' not found'
            ]);
        }
        return SpecialistAttrSpecialist::where('specialist_id', $request->specialist_id)->get();
    }


    /**
     * @OA\Put(
     *      path="/specialist/attr/specialist/",
     *      tags={"SpecialistAttrSpecialist"},
     *      summary="Создать новое значение атрибута специалистов",
     *      description="Создать новое значение атрибута специалистов, возвращается объект значний атрибутов специалистов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttrSpecialist")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttrSpecialist")
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
    public function create(SpecialistAttrSpecialistRequest $request)
    {
        $attr = SpecialistAttrSpecialist::create($request->validated());
        return $attr;
    }



    /**
     * @OA\Patch(
     *      path="/specialist/attr/specialist/{id}",
     *      tags={"SpecialistAttrSpecialist"},
     *      summary="Обновить атрибут",
     *      description="Обновить значение атрибут специалистов, возвращается объект значний атрибутов специалистов",
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttrSpecialist")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttrSpecialist")
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
        $bathattr = SpecialistAttrSpecialist::where('id', $request->id)->first();
        if($bathattr != null) {
            $bathattr->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return SpecialistAttrSpecialist::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/specialist/attr/specialist/{id}",
     *      tags={"SpecialistAttrSpecialist"},
     *      summary="Удалить значение атрибута специалистов",
     *      description="Удалить атрибутa специалистов",
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
            'status' => SpecialistAttrSpecialist::where('id', $request->id)->delete()
        ]);
    }
}
