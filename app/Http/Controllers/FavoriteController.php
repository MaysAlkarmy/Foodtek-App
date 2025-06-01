<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function addToFavourites(Request $request)
    {
       $user = Auth::user();
      // dd($user);
       $fields= Validator::make($request->all(),[
            'item_id' => 'required|exists:items,id',
            ]);

        if($fields->fails()){
            return response()->json($fields->errors());
        }
        $item_id = $request->item_id;
        if(!$item_id){
            return response()->json('item not found');
        }
         
      try {
         if (!Favorite::where('user_id', $user->id)
             ->where('item_id', $item_id)->exists()) {
            Favorite::create(['user_id' => $user->id, 'item_id' => $item_id]);
            return response()->json(['message' => 'Item added to favorites']);
        }
        return response()->json(['message' => 'Item already in favorites']);


      } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
           }     
    }


   
    public function getUserFavourite()
    {
        $user = Auth::user();
        try {
            $favorites = Favorite::where('user_id', $user->id)
        ->with('item')->get();

        return response()->json(['favorites' => $favorites]);

        } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
           }
       
    }


    

    public function deleteUserFavourite(Request $request){
    
        $user = Auth::user();

         $fields= Validator::make($request->all(),[
            'item_id' => 'required|exists:items,id',
            ]);

        if($fields->fails()){
            return response()->json($fields->errors());
        }
        $item_id = $request->item_id;
        if(!$item_id){
            return response()->json('item not found');
        }

        try {
         DB::table('favourites')
        ->where('user_id', $user->id)
        ->where('item_id', $item_id)
        ->delete();
     return response()->json(['message' => 'delete from favourites']);

        } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()]);
           }
    
    }
}
