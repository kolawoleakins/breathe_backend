<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function notifications(){
        
        $notifications = Notifications::where('userid', Auth::id())->get();

        if($notifications):
            return response()->json(["notifications"=>$notifications], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;        
    
    }

    public function viewnotification($id){
        $notification = Notifications::find($id);
        if($notification):
            return response()->json(["notification"=>$notification], 200);
        else:
            return response()->json(["message"=>"No Record Found"], 404);
        endif;        
    }
    
}
