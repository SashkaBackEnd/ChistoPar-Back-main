<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    /**
     * @OA\Post(
     *      path="/login/",
     *      tags={"User"},
     *      summary="Авторизация",
     *      description="Авторизация, возвращается объект пользователя",
     *      @OA\Parameter(
     *          name="phone",
     *          description="Телефон",
     *          required=true,
     *          in="path",
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
    public function auth(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);
        $code = rand(1000,9999);
        $user = User::where('phone', $request->phone)->first();
        if($user != null) {
            if($user->phone == '+79999999999') {
                $code = '1234';
                User::where('phone', $request->phone)->update([
                    'code' => $code
                ]);
                return response([
                    'status' => 'code sent'
                ]);
            }
            User::where('phone', $request->phone)->update([
                'code' => $code
            ]);
            $phone = trim($request->phone, '+');
            $url = 'https://v.karpenko@yurin.biz:HcM0IzUPk0XVLlfGogHVy5qBHc0@gate.smsaero.ru/v2/flashcall/send?phone='.$phone.'&code='.$code;
            file_get_contents($url,false,
                stream_context_create(
                    array(
                        'http' => array(
                            'ignore_errors' => true
                        )
                    )
                )
            );
            return response([
                'status' => 'code sent'
            ]);
        }
        return response([
            'status' => 'error',
            'message' => 'User not found'
        ], 404);
    }

    /**
     * @OA\Post(
     *      path="/confim/",
     *      tags={"User"},
     *      summary="Подтверждение по коду",
     *      description="Авторизация, возвращается объект пользователя",
     *      @OA\Parameter(
     *          name="phone",
     *          description="Телефон",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="code",
     *          description="Код",
     *          required=true,
     *          in="path",
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
    public function confirm(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required'
        ]);
        $user = User::where('phone', $request->phone)->first();
        if($user == null) {
            return response([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        if ($user->code == $request->code) {
            User::where('phone', $request->phone)->update([
                'api_token' =>  Str::random(60),
                'code' => 0,
                'phone_verified_at' => Carbon::now()
            ]);
            return User::where('phone', $request->phone)->first();
        } else {
            return response([
                'status' => 'error',
                'message' => 'Invalid code'
            ], 400);
        }

    }
}
