<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavRequest;
use App\Models\Bath;
use Illuminate\Http\Request;
use App\Models\Fav;
use App\Models\Journal;
use App\Models\Specialist;
use App\Models\User;

class FavController extends Controller
{
    /**
     * @OA\Get(
     *      path="/fav/bath/{user_id}",
     *      tags={"Fav"},
     *      summary="Список избранных БК",
     *      description="Возращается объект избранных БК",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
     *          required=false,
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
    public function favBath(Request $request) {
        $fav =  Fav::where('user_id', $request->user_id)->where('type', 1)->get();
        $data = [];
        foreach($fav as $value) {
            $data[] = $value->entity_id;
        }
        return Bath::whereIn('id', $data)->with('format')->get();
    }
    /**
     * @OA\Get(
     *      path="/fav/specialist/{user_id}",
     *      tags={"Fav"},
     *      summary="Список избранных специалистов",
     *      description="Возращается объект избранных специалистов",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
     *          required=false,
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
    public function favSpecialist(Request $request) {
        $fav =  Fav::where('user_id', $request->user_id)->where('type', 2)->get();
        $data = [];
        foreach($fav as $value) {
            $data[] = $value->entity_id;
        }
        return Specialist::whereIn('id', $data)->get();
    }


    /**
     * @OA\Get(
     *      path="/fav/journal/{user_id}",
     *      tags={"Fav"},
     *      summary="Список избранных записей журнала",
     *      description="Возращается объект избранных записей журнала",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
     *          required=false,
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
    public function favJournal(Request $request) {
        $fav =  Fav::where('user_id', $request->user_id)->where('type', 3)->get();
        $data = [];
        foreach($fav as $value) {
            $data[] = $value->entity_id;
        }
        return Journal::whereIn('id', $data)->get();
    }

    /**
     * @OA\Put(
     *      path="/fav/bath",
     *      tags={"Fav"},
     *      summary="Добавить в избранное БК",
     *      description="Добавить в избранное БК",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
    public function createBath(Request $request)
    {
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Bath::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->entity_id.' not found'
                ]);
            }
            return Fav::create([
                'type' => 1,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
            ]);
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }


    /**
     * @OA\Put(
     *      path="/fav/specialist",
     *      tags={"Fav"},
     *      summary="Добавить в избранное специалиста",
     *      description="Добавить в избранное специалиста",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
    public function createSpecialist(Request $request)
    {
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Specialist::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->entity_id.' not found'
                ]);
            }
            return Fav::create([
                'type' => 2,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
            ]);
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }

    /**
     * @OA\Put(
     *      path="/fav/journal",
     *      tags={"Fav"},
     *      summary="Добавить в избранное запись журнала",
     *      description="Добавить в избранное запись журнала",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
     *          @OA\JsonContent(ref="#/components/schemas/Fav")
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
    public function createJournal(Request $request)
    {
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Journal::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->entity_id.' not found'
                ]);
            }
            return Fav::create([
                'type' => 3,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
            ]);
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }




    /**
     * @OA\Delete(
     *      path="/fav/bath/{bath_id}/{user_id}",
     *      tags={"Fav"},
     *      summary="Удалить избранное БК",
     *      description="Удалить избранное БК",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id бк",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
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
    public function deleteBathFav(Request $request)
    {
        return response([
            'status' => Fav::where('user_id', $request->user_id)->where('type', 1)->where('entity_id',$request->bath_id)->delete()
        ]);
    }
    /**
     * @OA\Delete(
     *      path="/fav/specialist/{specialist_id}/{user_id}",
     *      tags={"Fav"},
     *      summary="Удалить избранное специалиста",
     *      description="Удалить избранное специалиста",
     *      @OA\Parameter(
     *          name="specialist_id",
     *          description="id специалиста",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
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
    public function deleteSpecialistFav(Request $request)
    {
        return response([
            'status' => Fav::where('user_id', $request->user_id)->where('type', 2)->where('entity_id',$request->specialist_id)->delete()
        ]);
    }
    /**
     * @OA\Delete(
     *      path="/fav/journal/{journal_id}/{user_id}",
     *      tags={"Fav"},
     *      summary="Удалить избранное журнала",
     *      description="Удалить избранное журнала",
     *      @OA\Parameter(
     *          name="journal_id",
     *          description="id специалиста",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
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
    public function deleteJournalFav(Request $request)
    {
        return response([
            'status' => Fav::where('user_id', $request->user_id)->where('type', 3)->where('entity_id', $request->journal_id)->delete()
        ]);
    }
}
