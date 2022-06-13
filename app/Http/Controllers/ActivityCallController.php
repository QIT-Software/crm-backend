<?php

namespace App\Http\Controllers;

use App\ActivityCall;
use Illuminate\Http\Request;
use DB;

class ActivityCallController extends Controller
{
    protected $activity_call;

    protected $rules = [
        'subject' => 'required|string',
        'comments' => 'string',
        'account_id' => 'required|integer',
        'contact_id' => 'required|integer',
    ];

    public function __construct(ActivityCall $activity_call)
    {
        parent::__construct($activity_call, $this->rules);
        $this->activity_call = $activity_call;
    }

    public function selectByAccountId($id)
    {
        return $this->activity_call->selectByAccountId($id);
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        return DB::transaction(function () use ($request) {
            $new = $this->activity_call::create($request->all());
            if ($request->filled('related_to')) {
                $mass = $this->prepare($request['related_to'], $new->id);
                DB::table('related_activities')->insert($mass);
            }
            return response()->json($new->id);
        });
    }

    private function prepare($related, $activity_id)
    {
        $mass = [];
        foreach ($related as $manager) {
            $mass[] = [
                'related_to' => $manager['id'],
                'activity_id' => $activity_id,
                'type' => 'call'
            ];
        }
        return $mass;
    }
}
