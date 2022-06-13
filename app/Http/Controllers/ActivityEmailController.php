<?php

namespace App\Http\Controllers;

use App\ActivityEmail;
use Illuminate\Http\Request;
use DB;

class ActivityEmailController extends Controller
{
    protected $activity_email;

    protected $rules = [
        'from' => 'required|string',
        'to' => 'required|string',
        'bcc' => 'string',
        'account_id' => 'required|integer',
        'subject' => 'required|string',
        'content' => 'string',
    ];

    public function __construct(ActivityEmail $activity_email)
    {
        parent::__construct($activity_email, $this->rules);
        $this->activity_email = $activity_email;
    }

    public function selectByAccountId($id)
    {
        return $this->activity_email->selectByAccountId($id);
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        return DB::transaction(function () use ($request) {
            $new = $this->activity_email::create($request->all());
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
                'type' => 'email'
            ];
        }
        return $mass;
    }
}
