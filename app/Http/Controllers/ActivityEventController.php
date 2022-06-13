<?php

namespace App\Http\Controllers;

use App\ActivityEvent;
use Illuminate\Http\Request;
use DB;

class ActivityEventController extends Controller
{
    protected $activity_event;

    protected $rules = [
        'subject' => 'required|string',
        'description' => 'string',
        'start' => 'required|date',
        'end' => 'required|date',
        'location' => 'string',
        'account_id' => 'required|integer',
        'contact_id' => 'required|integer',
        'related_to' => 'required'
    ];

    public function __construct(ActivityEvent $activity_event)
    {
        parent::__construct($activity_event, $this->rules);
        $this->activity_event = $activity_event;
    }

    public function selectByAccountId($id)
    {
        return $this->activity_event->selectByAccountId($id);
    }

    public function selectAll()
    {
        return $this->activity_event->selectAll();
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        return DB::transaction(function () use ($request) {
            $new = $this->activity_event::create($request->all());
            if ($request->filled('related_to')) {
                $mass = $this->prepare($request['related_to'], $new->id);
                DB::table('related_activities')->insert($mass);
            }
            return response()->json($new->id);
        });
    }

    public function deleteById($id)
    {
        $deleting = $this->activity_event::findOrFail($id);
        $status = DB::transaction(function () use ($id, $deleting) {
            $deleted = $deleting->delete();
            DB::table('related_activities')->where([
                ['activity_id', $id],
                ['type', 'event']
            ])->delete();
            return $deleted;
        });

        return response()->json($status);
    }

    private function prepare($related, $activity_id)
    {
        $mass = [];
        foreach ($related as $manager) {
            $mass[] = [
                'related_to' => $manager['id'],
                'activity_id' => $activity_id,
                'type' => 'event'
            ];
        }
        return $mass;
    }
}
