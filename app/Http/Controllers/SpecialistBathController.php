<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistBathRequest;
use App\Models\Specialist;
use App\Models\SpecialistBath;
use Illuminate\Http\Request;

class SpecialistBathController extends Controller
{
    /**
     * @OA\Get(
     *      path="/specialistbath",
     *      tags={"SpecialistBath"},
     *      summary="Связь специалиста и БК",
     *      description="Возвращается объект связи специалиста и БК",
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
    public function specialistbath(Request $request) {
        if(Specialist::where('id', $request->specialist_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Specialist with id '.$request->specialist_id.' not found'
            ]);
        }
        return SpecialistBath::where('specialist_id', $request->specialist_id)->get();
    }


    /**
     * @OA\Put(
     *      path="/specialistbath",
     *      tags={"SpecialistBath"},
     *      summary="Создать новую связь специалиста и БК",
     *      description="Создать новое связь специалиста и БК, возвращается объект связи специалиста и БК",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistBath")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistBath")
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
    public function create(SpecialistBathRequest $request)
    {
        $specialistbath = SpecialistBath::create($request->validated());
        return $specialistbath;
    }

    /**
     * @OA\Delete(
     *      path="/specialistbath/{id}",
     *      tags={"SpecialistBath"},
     *      summary="Удалить связь специалиста и БК",
     *      description="Удалить связь специалиста и БК",
     *      @OA\Parameter(
     *          name="id",
     *          description="id связи",
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
            'status' => SpecialistBath::where('id', $request->id)->delete()
        ]);
    }
}
