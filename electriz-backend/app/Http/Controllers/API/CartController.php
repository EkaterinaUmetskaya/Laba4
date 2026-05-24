<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Cart", description="Корзина")
 */
class CartController extends Controller
{
    /**
     * @OA\Get(path="/api/cart", tags={"Cart"}, summary="Корзина пользователя",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Содержимое корзины")
     * )
     */
    public function index(Request $request)
    {
        $items = CartItem::with('product')->where('user_id', $request->user()->id)->get();
        return CartItemResource::collection($items);
    }

    /**
     * @OA\Post(path="/api/cart", tags={"Cart"}, summary="Добавить в корзину",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"product_id","quantity"},
     *     @OA\Property(property="product_id", type="integer"),
     *     @OA\Property(property="quantity", type="integer")
     *   )),
     *   @OA\Response(response=200, description="Добавлено")
     * )
     */
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);

        $existing = CartItem::where('user_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $request->quantity]);
            return new CartItemResource($existing->load('product'));
        }

        $item = CartItem::create(['user_id' => $request->user()->id, 'product_id' => $request->product_id, 'quantity' => $request->quantity]);
        return new CartItemResource($item->load('product'));
    }

    /**
     * @OA\Put(path="/api/cart/{id}", tags={"Cart"}, summary="Обновить количество",
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"quantity"}, @OA\Property(property="quantity", type="integer"))),
     *   @OA\Response(response=200, description="Обновлено")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item = CartItem::where('user_id', $request->user()->id)->findOrFail($id);
        $item->update(['quantity' => $request->quantity]);
        return new CartItemResource($item->load('product'));
    }

    /**
     * @OA\Delete(path="/api/cart/{id}", tags={"Cart"}, summary="Удалить позицию",
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Удалено")
     * )
     */
    public function destroy(Request $request, $id)
    {
        CartItem::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['message' => 'Удалено из корзины']);
    }

    /**
     * @OA\Delete(path="/api/cart", tags={"Cart"}, summary="Очистить корзину",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Корзина очищена")
     * )
     */
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'Корзина очищена']);
    }
}
