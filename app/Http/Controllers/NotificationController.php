<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Models\Company;
use App\Models\CompanyAgent;
use App\Models\CompanyAgentDetail;
use App\Models\Notification;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    use UserTrait;

    // Notification List
    public function showNotification(Request $request){
        try{
            $data = [
                'heading'     => 'Notifications',
                'title'       => 'Home',
            ];
            Log::info('notification list page is retrived by user ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('notification.list',$data);
        }
        catch (\Exception $e) {
            Log::error('the error occured while accessing the notification page', $e->getMessage());
        }
        
    }

    public function getNotificationList(Request $request){
        $offset = $request->offset;
        $type   = $request->type;
        $limit  = 20;
        $user   = $request->user();

        $notifications = Notification::with('user')->select('*')
        ->where('type', 'NEW_REGISTER')
        ->take($limit)
        ->skip($offset)
        ->orderBy('created_at','desc')
        ->get();
        $unread_count   = Notification::where(['is_read' => 0, 'read_at' => NULL, 'type' => 'NEW_REGISTER'])->count();
        $notificationIds = $notifications->pluck('_id')->toArray();

        if($notifications && count($notifications) > 0){
            $is_data = true;
            $returnHTML = view('notification.tmp_notification')->with(compact('notifications'))->render();
            return response()->json(array('success' => true, 'is_data' => $is_data,  'html'=>$returnHTML, 'offset' => $offset + $limit,'unread_count' => $unread_count, 'notificationIds' => $notificationIds));
        } else {
            $returnHTML = view('notification.tmp_notification')->with(compact('notifications'))->render();
            $notificationIds = [];
            return response()->json(array('success' => true, 'is_data' => false,  'html'=>$returnHTML, 'offset' => $offset + $limit,'unread_count' => $unread_count,'notificationIds' => $notificationIds));
        }

    }

    public function markAsRead(Request $request)
    {
        $notificationIds = $request->notificationIds;
        if(gettype($notificationIds) == 'array'){
            $mytime   = Carbon::now();
            $now_time = $mytime->toDateTimeString();
            Notification::whereIn('_id', $notificationIds)->update(['is_read' => 1,'read_at' => $now_time]);
            return response()->json(['success' => true]);
        }
    }

    public function markAsReadSingle(Request $request)
    {
        $notificationIds = $request->notificationIds;
        $mytime   = Carbon::now();
        $now_time = $mytime->toDateTimeString();
        Notification::where('_id', $notificationIds)->update(['is_read' => 1,'read_at' => $now_time]);
        return response()->json(['success' => true]);
    }
}
