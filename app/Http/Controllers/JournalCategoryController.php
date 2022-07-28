<?php

namespace App\Http\Controllers;

use App\Http\Requests\JournalCategoryRequest;
use App\Models\JournalCategory;
use Illuminate\Http\Request;

class JournalCategoryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/journalcategory",
     *      tags={"JournalCategory"},
     *      summary="Список категорий журнала",
     *      description="Возвращается список категорий журнала",
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
    
    public function category()
    {
        $categories = JournalCategory::all();
        return $categories;
    }

    /**
     * @OA\Put(
     *      path="/journalcategory",
     *      tags={"JournalCategory"},
     *      summary="Создать новую категорию журнала",
     *      description="Создать новую категорию, возвращается объект категории журнала",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/JournalCategory")
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
     *          @OA\JsonContent(ref="#/components/schemas/JournalCategory")
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
    public function create(JournalCategoryRequest $request)
    {    
        $category = JournalCategory::create($request->validated());
        return $category;
    }
    /**
     * @OA\Patch(
     *      path="/journalcategory/{id}",
     *      tags={"JournalCategory"},
     *      summary="Обновить категорию журнала",
     *      description="Обновить категорию, возвращается объект категории журнала",
     *      @OA\Parameter(
     *          name="id",
     *          description="id Категории",
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
     *          @OA\JsonContent(ref="#/components/schemas/JournalCategory")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/JournalCategory")
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
        return response([
            'status' =>  JournalCategory::where('id', $request->id)->update($request->except('api_token')),
            'category' => JournalCategory::where('id',$request->id)->first()
        ]);
    }
    /**
     * @OA\Delete(
     *      path="/journalcategory/{id}",
     *      tags={"JournalCategory"},
     *      summary="Удалить категорию журнала",
     *      description="Удалить категорию журнала",
     *      @OA\Parameter(
     *          name="id",
     *          description="id категории",
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
            'status' => JournalCategory::where('id', $request->id)->delete()
        ]);
    }
}
