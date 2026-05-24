<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="User", description="Профиль пользователя")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(path="/api/user", tags={"User"}, summary="Данные профиля",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Данные профиля")
     * )
     */
    public function profile(Request $request) { return response()->json($request->user()); }

    /**
     * @OA\Put(path="/api/user", tags={"User"}, summary="Обновить профиль",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="phone", type="string"),
     *     @OA\Property(property="address", type="string")
     *   )),
     *   @OA\Response(response=200, description="Профиль обновлён")
     * )
     */
    public function update(Request $request)
    {
        $request->validate(['name' => 'sometimes|string|max:255', 'phone' => 'sometimes|string|max:20', 'address' => 'sometimes|string|max:500']);
        $request->user()->update($request->only('name', 'phone', 'address'));
        return response()->json($request->user());
    }
}
