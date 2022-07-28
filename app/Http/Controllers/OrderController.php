<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Bath;
use App\Models\Order;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{   

    /**
     * @OA\Get(
     *      path="/orders/",
     *      tags={"Order"},
     *      summary="Заказы пользователя",
     *      description="Возвращается объект заказа",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="id пользователя",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id БК",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="specialist_id",
     *          description="id специалиста",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="time_from",
     *          description="С какого периода",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="time_to",
     *          description="По какой период",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="comment",
     *          description="Поиск по тексту",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
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
    public function list(Request $request) {
        $order = Order::with('service')->with('bath')->with('specialist')->with('users');
        if($request->has('user_id')) {
            $order->where('user_id', $request->user_id);
        }
        if($request->has('bath_id')) {
            $order->where('bath_id', $request->bath_id);
        }
        if($request->has('specialist_id')) {
            $order->where('specialist_id', $request->specialist_id);
        }
        if($request->has('time_to')) {
            $order->where('date_to', '>=', $request->time_to);
        }
        if($request->has('time_from')) {
            $order->where('date_from', '<=', $request->time_from);
        }
        if($request->has('bath_id')) {
            $order->where('comment', 'ilike', "%{$request->comment}%");
        }
        return $order->orderBy('date_from', 'desc')->paginate(15);
    }


    /**
     * @OA\Get(
     *      path="/order/{id}",
     *      tags={"Order"},
     *      summary="Карточка заказа",
     *      description="Возвращается объект заказа",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заказа",
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
    public function single(Request $request) {
        $order = Order::with('users')->with('bath')->with('specialist')->with('service')->where('id', $request->id)->first();
        if($order == null) {
            return response([]);
        }
        return response($order);
    }

    /**
     * @OA\Put(
     *      path="/order/",
     *      tags={"Order"},
     *      summary="Создать заказ",
     *      description="Создать заказ, возвращается объект заказа",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Order")
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
     *          @OA\JsonContent(ref="#/components/schemas/Order")
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
    **/
    public function create(OrderRequest $request) {
        if($request->has('bath_id')) {
            $bath = Bath::where('id', $request->bath_id)->first();
            if(is_null($bath)) {
                return response([
                    'error' => 'Bath with id '.$request->bath_id.' not found'
                ],400);
            }
            if($bath->email != '' && filter_var($bath->email, FILTER_VALIDATE_EMAIL)) {
                $custom_message = "Банный комплекс: ".$bath->name."\n";
                $custom_message .= "Комментарий: ".$request->comment."\n";
                $custom_message .= "С ".$request->date_from."\n".' по '.$request->date_to."\n";
                $custom_message .= "Цена: ".$request->price."\n";
                Mail::send(['text'=>'mail'],['custom_message'=>$custom_message], function ($message) use ($bath) {
                    $message->to($bath->email,'Чистопар')->subject('Бронирование');
                    $message->from('nfs2025@mail.ru','Чистопар');
                });
            }
        }
        if($request->has('specialist_id')) {
            $specialist = Specialist::where('id', $request->specialist_id)->first();
            if(is_null($specialist)) {
                return response([
                    'error' => 'Bath with id '.$request->specialist_id.' not found'
                ],400);
            }
            if($specialist->email != '' && filter_var($specialist->email, FILTER_VALIDATE_EMAIL)) {
                $custom_message = "Специалист: ".$specialist->name."\n";
                $custom_message .= "Комментарий: ".$request->comment."\n";
                $custom_message .= "С ".$request->date_from."\n".' по '.$request->date_to."\n";
                $custom_message .= "Цена: ".$request->price."\n";
                Mail::send(['text'=>'mail'],['custom_message'=>$custom_message], function ($message) use ($specialist) {
                    $message->to($specialist->email,'Чистопар')->subject('Бронирование');
                    $message->from('nfs2025@mail.ru','Чистопар');
                });
            }
        }
        $order = Order::create($request->validated());
        return $order;
    }


    /**
     * @OA\Patch(
     *      path="/order/{id}",
     *      tags={"Order"},
     *      summary="Обновить заказ",
     *      description="Обновить заказ, возвращается объект заказа",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заказа",
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
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order")
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
    public function update(Request $request)
    {   
        Order::where('id', $request->id)->update($request->except('api_token'));
        return Order::where('id',$request->id)->first();
    }


    /**
     * @OA\Delete(
     *      path="/order/{id}",
     *      tags={"Order"},
     *      summary="Удалить заказ",
     *      description="Удалить заказ",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заказа",
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
            'status' => Order::where('id', $request->id)->delete()
        ]);
    }
}
