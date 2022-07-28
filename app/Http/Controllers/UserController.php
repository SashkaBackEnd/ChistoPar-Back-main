<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/user/{user_id}",
     *      tags={"User"},
     *      summary="Получение информации о пользователе",
     *      description="Возвращается объект пользователя",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
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
    public function index(Request $request) {
        return User::withCount('review')->withCount('order')->where('id', $request->user_id)->first();
    }
    /**
     * @OA\Get(
     *      path="/userlist",
     *      tags={"User"},
     *      summary="Получение списка всех пользователей",
     *      description="Возвращается объект пользователей",
     *      @OA\Parameter(
     *          name="page",
     *          description="Пагинация",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="Сортировка по имени (ищет совпадения)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="Сортировка по почте (ищет совпадения)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="Сортировка по телефону (ищет совпадения)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="role",
     *          description="Роль",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name_sort",
     *          description="Сортировка имени по алфавиту. Передавать asc/desc",
     *          required=false,
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
       
        $user = User::withCount('review')->withCount('order');
       
        if($request->has('name')) {
            $user->where('fullname', 'ilike', "%{$request->name}%");
        }
        if($request->has('email')) {
            $user->where('email', 'ilike', "%{$request->email}%");
        }
        if($request->has('phone')) {
            $user->where('phone', 'ilike', "%{$request->phone}%");
        }
        if($request->has('role')) {
            $user->where('role', $request->role);
        }
        if($request->has('name_sort')) {
            if($request->name_sort == 'asc') {
                $user->orderBy('name', 'ASC');
            } 
            if($request->name_sort == 'desc') {
                $user->orderBy('name', 'desc');
            }
        }
      
        return $user->paginate(15);
    }
    /**
     * @OA\Patch(
     *      path="/user/{id}",
     *      tags={"User"},
     *      summary="Обновить пользователя",
     *      description="Обновить пользователя, возвращается объект пользователя",
     *      @OA\Parameter(
     *          name="id",
     *          description="id пользователя",
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
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
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
    public function update(Request $request) {
        $user = User::where('id', $request->id)->first();
        if(User::where('id', '!=', $request->id)->where('email', $request->email) != null) {
            return response([
                'message' => "Email already use"
            ],400);
        }
        if($user == null) {
            return response([
                'message' => "User not found"
            ],400);
        }
        return User::where('id', $request->id)->update($request->except('api_token'));
    }


     /**
     * @OA\Post(
     *      path="/email-verify/{id}",
     *      tags={"User"},
     *      summary="Обновить пользователя",
     *      description="Обновить пользователя, возвращается объект пользователя",
     *      @OA\Parameter(
     *          name="id",
     *          description="id пользователя",
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
    public function emailCheck(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if($user == null) {
            return response([
            'message' => "User not found"
            ],400);
        }
        if($user->email_verified_at == null) {
            $code = rand(1000,9999);     
            $link = route('email_vefified', [
                'id' => $user->id,
                'code' => $code
            ]);
            $custom_message = "Для верификации почты перейдите по ссылке: ".$link;
            Mail::send(['text'=>'mail'],['custom_message'=>$custom_message], function ($message) use ($user) {
                $message->to($user->email,'Чистопар')->subject('Верификация почты');
                $message->from('nfs2025@mail.ru','Чистопар');
            });
            User::where('id', $request->id)->update([
                'code' => $code
            ]);
            return response([
                'status' => 'ok'
            ]);
        }
        return response([
            'status' => 'Email already verify'
        ], 400);
    }

    public function emailVefified(Request $request) {
        $user = User::where('id', $request->id)->first();
        if($user->code == $request->code) {
            User::where('id', $request->id)->update([
                'email_verified_at' => Carbon::now(),
                'code' => 0
            ]);
            return redirect(env('FRONT_HOST').'account/profile');
        } 
        return redirect(env('FRONT_HOST'));
    }


    /**
     * @OA\Delete(
     *      path="/user/{id}",
     *      tags={"User"},
     *      summary="Удалить пользователя",
     *      description="Удалить пользователя",
     *      @OA\Parameter(
     *          name="id",
     *          description="id пользователя",
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
            'status' => User::where('id', $request->id)->delete()
        ]);
    }
}
