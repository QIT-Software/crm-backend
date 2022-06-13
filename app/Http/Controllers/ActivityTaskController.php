<?php

namespace App\Http\Controllers;

use App\ActivityTask;
use Illuminate\Http\Request;
use DB;

class ActivityTaskController extends Controller
{
    protected $activity_task;

    protected $rules = [
        'subject' => 'required|string',
        'due_date' => 'required|date',
        'account_id' => 'required|integer',
        'contact_id' => 'required|integer',
        'assigned_to' => 'required|integer',
        'related_to' => 'required'
    ];

    public function __construct(ActivityTask $activity_task)
    {
        parent::__construct($activity_task, $this->rules);
        $this->activity_task = $activity_task;
    }

    public function selectByAccountId($id)
    {
        return $this->activity_task->selectByAccountId($id);
    }

    public function selectAll()
    {
        return $this->activity_task->selectAll();
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        return DB::transaction(function () use ($request) {
            $new = $this->activity_task::create($request->all());
            if ($request->filled('related_to')) {
                $mass = $this->prepare($request['related_to'], $new->id);
                DB::table('related_activities')->insert($mass);
            }
            return response()->json($new->id);
        });
    }

    public function deleteById($id)
    {
        $deleting = $this->activity_task::findOrFail($id);
        $status = DB::transaction(function () use ($id, $deleting) {
            $deleted = $deleting->delete();
            DB::table('related_activities')->where([
                ['activity_id', $id],
                ['type', 'task']
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
                'type' => 'task'
            ];
        }
        return $mass;
    }
}
