<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistServiceRequest;
use App\Models\BathServices;
use App\Models\Specialist;
use App\Models\SpecialistServices;
use Illuminate\Http\Request;

class SpecialistServicesController extends Controller
{
    /**
     * @OA\Put(
     *      path="/specialistservice/",
     *      tags={"SpecialistServices"},
     *      summary="Создать новое значение услуг специалиста",
     *      description="Создать новое значение услуг специалиста, возвращается объект услуг специалиста",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistServices")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistServices")
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
    public function create(SpecialistServiceRequest $request)
    {
        if(Specialist::where('id', $request->specialist_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->specialist_id.' not found'
            ], 400);
        }
        if(BathServices::where('id', $request->service_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'BathService with id '.$request->service_id.' not found'
            ], 400);
        }
        if(SpecialistServices::where('service_id', $request->service_id)->where('specialist_id', $request->specialist_id)->first() != null) {
            return response([
                'status' => 'error',
                'message' => 'SpecialistServices already exist'
            ], 400);
        }
        $specialist_services = SpecialistServices::create($request->validated());
        return $specialist_services;
    }


    /**
     * @OA\Get(
     *      path="/specialistservice/{service_id}",
     *      tags={"SpecialistServices"},
     *      summary="Список специалистов, подвязанных к услуге",
     *      description="Список специалистов, подвязанных к услуге, возвращается объект специалистов",
     *      @OA\Parameter(
     *          name="service_id",
     *          description="ID услуги",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Пагинация",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function list(SpecialistServiceRequest $request)
    {
        $specialist_service = SpecialistServices::where('service_id', $request->service_id)->get();
        $data = [];
        foreach($specialist_service as $value) {
            $data[] = $value->specialist_id;
        }
        return Specialist::whereIn('id', $data)->paginate(15);
    }




    /**
     * @OA\Delete(
     *      path="/specialistservice/{id}",
     *      tags={"SpecialistServices"},
     *      summary="Удалить значение услуг специалиста",
     *      description="Удалить атрибутa услуг специалиста",
     *      @OA\Parameter(
     *          name="specialist_id",
     *          description="id специалиста",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="service_id",
     *          description="id услуги",
     *          required=true,
     *          in="query",
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
            'status' => SpecialistServices::where('specialist_id', $request->specialist_id)->where('service_id', $request->service_id)->delete()
        ]);
    }
}
