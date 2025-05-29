<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\GeneralNotification;
use DB;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
     public function sendNotification(Request $request)  {

        $users = User::query()->get();
       // dd($users);
       if(!$users){
        return response()->json('no useres found');
       }
        $newGeneralNotification = new GeneralNotification($request->get('title'), $request->get('content'), ['database']);
         Notification::send($users, $newGeneralNotification);

        return response()->json(['message' => 'Notification sended successfully'], 201);
    }  


    public function getUserNotification($id)
    {
        $user = User::query()->find($id);

        if (!isset($user)) {
        return response()->json(['message' => 'user not found'], 404);
        }
        $ns = $user->notifications;
        //dd($user->notifications);
             

        return $this->api_response(true, 'Fetched successfully', [
           'notifications' => NotificationResource::collection($ns)
        ]);
    }  // make resources for response,  done
 
     public function markNotificationAsRead($id, $nid)
    {
        $user = User::query()->find($id);

        if (!isset($user)) {
            return $this->api_response(false, 'User Not Found', [], 404);
        }

        $notification = $user->notifications()->where('id', $nid)->first();
       // dd($notification);
        $notification->markAsRead();
        
       return response()->json('Notification mark as read');
    }  // done

}
