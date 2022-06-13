<?php

namespace App\Http\Controllers;

use App\Notification;

class NotificationController extends Controller
{
    protected $rules = [
        'user_id' => 'integer',
        'message' => 'string',
        'read' => 'integer'
    ];

    protected $notification;

    public function __construct(Notification $notification)
    {
        parent::__construct($notification, $this->rules);
        $this->notification = $notification;
    }

    public function fetch($limit = null)
    {
        $result = Notification::limit($limit)
            ->where('user_id', app()->session_id)
            ->latest()
            ->get(['id', 'message', 'read', 'created_at']);

        return response()->json($result);
    }

    public function count()
    {
        $result = Notification::groupBy('user_id')
            ->where('user_id', app()->session_id)
            ->where('read', 0)
            ->count('user_id');

        return response()->json($result);
    }

    public function markAllAsRead()
    {
        $status = Notification::where('read', 0)->update([
            'read' => 1
        ]);

        return response()->json($status);
    }

    public function markAsRead($id)
    {
        $status = Notification::where('id', $id)->update([
            'read' => 1
        ]);

        return response()->json($status);
    }
}
