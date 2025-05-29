<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ItemResource;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function getItemById($itemId)
    {
      $item = Item::find($itemId); 
      //$reviewCount = Review::where('item_id', $itemId)->count();
       if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }
      return new ItemResource($item );
   
      
    }  // done, 


    public function getMainCategory(Request $request){

        $category= Item::select('image', 'main_category')->distinct()->get();

        return response()->json($category);
      
  }  // done

    public function getItemsByCategory(Request $request){
      
        $item= DB::select('select image,name, main_category from items where main_category = ?',
         [$request->main_category]);

         if (!$item) {
        return response()->json(['message' => 'Category not found'], 404);
    }

        return response()->json($item);
    
    }  // done

    public function createItem (Request $request){
     // $user = auth('api')->user();
      try {
      DB::table('items')->insert(
          [
            'name' => $request->name,
           'category_id' => $request->category_id,
           'price' => $request->price,
           'description' => $request->description,
           'image' => $request->image,
           'main_category' => $request->main_category
            
             ]
          );
         
        return response()->json(['message' => 'Item created successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to create item',
            'error' => $e->getMessage()
        ], 500);
    }

    }  // authentication and validation 

    public function addToCart(Request $request)
    {
        $user = Auth::user();
       // dd($user);
       $fields= Validator::make($request->all(),[
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            
        ]);
       
           if($fields->fails()){
            return response()->json($fields->errors());
        }
       
        $item = Item::find($request->item_id);
        if(!$item){
            return response()->json('item not found');
        }
    
        // Check if product exists in user's cart
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('item_id', $item->id)
                        ->first();
      // $total= $request->quantity * $item->price;
       //dd($cartItem->quantity);
        if ($cartItem) {
             $cartItem->increment('quantity');
             $cartItem->total_price = $cartItem->quantity * $item->price;
             $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'quantity' => $request->quantity,
                'price' => $item->price,
                'total_price' => $request->quantity * $item->price,
            ]);
        }

        return response()->json(['message' => 'Item added to cart successfully']);
    }

    public function viewUserCart()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('item')->get();
        // return new CartResource($cartItems );
       return response()->json(['cart' => $cartItems]);
    }

   
    public function removeItemFromCart(Request $request){

        $request->validate([
            'item_id' => 'required|integer',
        ]);

        $user = auth()->user();

        $cartItem = Cart::where('user_id', $user->id)
                        ->where('item_id', $request->item_id)
                        ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart.'], 404);
        }

        // If quantity > 1, decrease by 1
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $cartItem->total_price = $cartItem->price * $cartItem->quantity;
            $cartItem->save();

            return response()->json(['message' => 'Item quantity decreased.']);
        }

        // If quantity is 1, remove the item
        $cartItem->delete();
        return response()->json(['message' => 'Item removed from cart.']);
    }

                
    }
 

