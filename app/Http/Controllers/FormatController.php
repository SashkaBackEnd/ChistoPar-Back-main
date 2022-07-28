<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatRequest;
use App\Models\Bath;
use App\Models\Format;
use Illuminate\Http\Request;

class FormatController extends Controller
{

    /**
     * @OA\Get(
     *      path="/format/{bath_id}",
     *      tags={"Format"},
     *      summary="Форматы посещения банных комлексов",
     *      description="Возвращается объект форматов банных комлексов",
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
    public function format(Request $request) {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id'.$request->bath_id.'not found'
            ]);
        }
        return Format::where('bath_id', $request->bath_id)->orderBy('created_at', 'asc')->get();
    }

    /**
     * @OA\Put(
     *      path="/format/{bath_id}",
     *      tags={"Format"},
     *      summary="Создать новый формат банных комлексов",
     *      description="Создать новый формат для БК, возвращается объект форматов банных комлексов",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id БК",
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
     *          @OA\JsonContent(ref="#/components/schemas/Format")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Format")
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
    public function create(FormatRequest $request)
    {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id'.$request->bath_id.'not found'
            ]);
        }

        $data = $request->validated();


        if(empty($data['periods'])) {
            $data['periods'] = null;
        }

        if($data['periods'] != null) {
            $data['periods'] = json_encode($request->periods);
        }
       
        if($request->validated()) {
            $format = Format::create($data);
        } else {
            return $request->errors();
        }
        $this->newBathPrice($format->bath_id);
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/format/{id}",
     *      tags={"Format"},
     *      summary="Обновить формат банных комлексов",
     *      description="Обновить формат, возращается объект форматов банных комлексов",
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
     *          @OA\JsonContent(ref="#/components/schemas/Format")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Format")
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


        $data = $request->except('api_token');
       
        if(empty($data['periods'])) {
            $data['periods'] = null;
        }

        if($data['periods'] != null) {
            $data['periods'] = json_encode($request->periods);
        }

        $format = Format::where('id', $request->id)->first();

        if($format != null) {
            $format->update($data);
        } else {
            return response([
                'status' => 'error'
            ]);
        }

        $this->newBathPrice($format->bath_id);

        return Format::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/format/{id}",
     *      tags={"Format"},
     *      summary="Удалить формат банных комлексов",
     *      description="Удалить формат банных комлексов",
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
        $format = Format::where('id', $request->id)->first();
        if($format == null) {
            return response([
                'status' => 0
            ], 404);
        }
        $bath_id = $format->bath_id; 
        
        Format::where('id', $request->id)->delete();
        
        $this->newBathPrice($bath_id);
      
        return response([
            'status' => true
        ]);
    }

    private function newBathPrice($bath_id) {
        $formats = Format::where('bath_id', $bath_id)->get();
        $prices = [];
        foreach($formats as $format) {
            $prices[] = $format->price_online;
            if($format->periods != null && $format->periods != 'null' && !empty(json_decode($format->periods))) {
            	$periods = json_decode($format->periods, true);
 				foreach ($periods as $key => $value) {
 					if (isset($value['price'])) {
 						$prices[] = $value['price'];
 					}
 				}
            }
        }
        Bath::where('id', $bath_id)->update([
            'price' => min($prices)
        ]);
    }
}
