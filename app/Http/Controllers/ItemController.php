<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function getItemById($id)
    {
      $item = Item::find($id); 
       if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }
      return response()->json($item);
      
    }  // done,  get number of reviews

    public function getMainCategory(Request $request){

        $category= Item::select('image', 'main_category')->distinct()->get();

        return response()->json($category);
      
  }  // done

    public function getItemsByCategory(Request $request){
      
        $item= DB::select('select image, main_category from items where main_category = ?',
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

    
}
