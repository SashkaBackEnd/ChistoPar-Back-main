<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::where('phone', $data['phone'])->first();
        if($user != null) {
            return response([
                'status' => 'User with phone '.$data['phone'].' already exist'
            ],400);
        }
        $code = rand(1000,9999);
        User::create([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            // 'password' => Hash::make($data['password']),
            'api_token' => Str::random(60),
            'code' => $code,
            'role_id' => 0
        ]);
        $phone = trim($data['phone'], '+');
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
    /**
     * @OA\Post(
     *      path="/registration/",
     *      tags={"User"},
     *      summary="Регистрация",
     *      description="Регистрация, возвращается объект пользователя",
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
    public function register(Request $request)
    {
        return $this->create($request->all());
    }
    /**
     * @OA\Post(
     *      path="/usercreate/",
     *      tags={"User"},
     *      summary="Создать нового пользователя",
     *      description="Создать нового пользователя, возвращается объект пользователя",
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
    public function createNew(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if($user != null) {
            return response([
                'status' => 'User with phone '.$request->phone.' already exist'
            ],400);
        }
        $user = User::where('email', $request->email)->first();
        if($user != null) {
            return response([
                'status' => 'User with email '.$request->email.' already exist'
            ],400);
        }
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'api_token' => Str::random(60),
            'code' => 0,
            'role_id' => $request->role_id
        ]);
        return $user;
    }
}
