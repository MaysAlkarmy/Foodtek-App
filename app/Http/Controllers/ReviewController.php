<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class ReviewController extends Controller
{
    public function createReview(Request $request){
        $user = Auth::user();
        // add validation 
         $fields= Validator::make($request->all(),[
          'item_id'=> 'required',
          'rating'=> 'required|numeric|between:1,5',
          'review'=> 'required|max:255',
            
        ]);
           if($fields->fails()){
            return response()->json($fields->errors());
       
        }
   try {
    DB::table('reviews')->insert([
       'user_id' =>  $user->id,
       'item_id' => $request->item_id,
       'rating' => $request->rating,
       'review' =>  $request->review
     ]) ; 

     return response()->json('review created');

   } catch (\Exception $exception) {

    return response()->json(['error'=>$exception->getMessage()]);
   }
      }  // done

    public function getReview()
    {
        return Review::with('user')->get();
    }  // get review with their user  

    public function getItemReviews($itemId)
{
    $item = Item::with('reviews')->find($itemId);
    $reviews = Review::find($itemId); 
  
    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }
     return new ReviewResource($reviews);
     
}  // must be for specific user  , done


    public function getItemRating($itemId)
{
    $item = Item::with('reviews')->findOrFail($itemId);
    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }
    $averageRating = $item->reviews()->avg('rating');

    return response()->json([
        'item_id' => $item->id,
        'average_rating' => round($averageRating, 2),
    ]);  // done

}

public function getTopRated()
{
   $items = Item::whereHas('reviews', 
   function ($query) {
   $query->where('rating', '>', 3.5);
      })->get();

    return response()->json($items);
}  // done, order by asc
}


