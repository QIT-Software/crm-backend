<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'company_name', 'email', 'short_name', 'country', 'city', 'software_id',
        'address_line_2', 'postal_code', 'province', 'phone', 'web', 'customer_type',
        'created_date', 'account_manager', 'language_id', 'address_line_1'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function add($form, $managers, $zones, $types)
    {
        $contacts = $form['contacts'];
        unset($form['contacts']);
        return DB::transaction(function () use ($form, $managers, $zones, $types, $contacts) {
            $account = Account::create($form);
            DB::table('accounts_managers')->insert($this->batchInsert(
                $account->id, $managers, 'manager_id'
            ));

            DB::table('accounts_zones')->insert($this->batchInsert(
                $account->id, $zones, 'zone_id'
            ));

            DB::table('accounts_types')->insert($this->batchInsert(
                $account->id, $types, 'type_id'
            ));

            foreach ($contacts as $contact) {
                DB::table('contacts')->insert([
                    'account_id' => $account->id,
                    'name' => $contact['name'],
                    'surname' => $contact['surname'],
                    'position' => $contact['position'],
                    'phone' => $contact['phone'],
                    'email' => $contact['email'],
                    'language_id' => $account->language_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            return $account->id;
        });
    }

    private function batchInsert($account_id, $data, $column)
    {
        $pairs = [];

        for ($index = 0; $index < count($data); $index++) {
            $pairs[] = [
                'account_id' => $account_id,
                $column => $data[$index]['id']
            ];
        }

        return $pairs;
    }


    public function updateById($form, $managers, $zones, $types, $account)
    {
        DB::transaction(function () use ($form, $managers, $zones, $types, $account) {
            $account->fill($form)->save();

            DB::table('accounts_managers')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);

            DB::table('accounts_managers')->insert($this->batchInsert(
                $account->id, $managers, 'manager_id'
            ));

            DB::table('accounts_zones')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);

            DB::table('accounts_zones')->insert($this->batchInsert(
                $account->id, $zones, 'zone_id'
            ));

            DB::table('accounts_types')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);

            DB::table('accounts_types')->insert($this->batchInsert(
                $account->id, $types, 'type_id'
            ));
        });

        return response()->json(true);
    }

    public function deleteById($account)
    {
        DB::transaction(function () use ($account) {
            $account->delete();

            DB::table('accounts_managers')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);

            DB::table('accounts_zones')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);

            DB::table('accounts_types')
                ->where('account_id', $account->id)
                ->update(['deleted_at' => Carbon::now()]);
        });

        return response()->json(true);
    }

    public function fetch($conditions, $id)
    {
        $zones = DB::table($this->table)
            ->leftJoin('accounts_zones', 'accounts.id', 'accounts_zones.account_id')
            ->leftJoin('zones', 'accounts_zones.zone_id', 'zones.id')
            ->whereNull('accounts_zones.deleted_at')
            ->where(function ($query) use ($id, $conditions) {
                $this->checkIdIsEmpty($query, $id);
                $this->compare($conditions, $query);
            })
            ->groupBy('accounts.id')
            ->select(
                DB::raw('group_concat(zones.zone separator ", ") as zones'),
                'accounts.id');

        $types = DB::table($this->table)
            ->leftJoin('accounts_types', 'accounts.id', 'accounts_types.account_id')
            ->leftJoin('types', 'accounts_types.type_id', 'types.id')
            ->whereNull('accounts_types.deleted_at')
            ->where(function ($query) use ($id, $conditions) {
                $this->checkIdIsEmpty($query, $id);
                $this->compare($conditions, $query);
            })
            ->groupBy('accounts.id')
            ->select(
                DB::raw('group_concat(types.type separator ", ") as type'),
                'accounts.id');

        $opps = DB::table($this->table)
            ->leftJoin('opportunities', 'opportunities.account_id', 'accounts.id')
            ->leftJoin('invoice', 'invoice.opportunity_id', 'opportunities.id')
            ->whereNull('opportunities.deleted_at')
            ->whereNull('invoice.deleted_at')
            ->where(function ($query) use ($id, $conditions) {
                $this->checkIdIsEmpty($query, $id);
                $this->compare($conditions, $query);
            })
            ->groupBy('accounts.id')
            ->select(
                DB::raw('count(opportunities.id) as opp_count'),
                DB::raw('count(invoice.id) as invoice_count'),
                DB::raw('group_concat(opportunities.title separator ", ") as opportunities'),
                DB::raw("
                        case
                            when sum(case when  opportunities.current_stage_id >= 7 then 1 else 0 end) = count(opportunities.id)
                                then 'Active'
                            when count(opportunities.id) > 0 and count(opportunities.id) = count(opportunities.termination_reason)
                                then 'Passive'
                            else 'Lead' end as status
                    "),
                'accounts.id'
            );

        $result = DB::table($this->table)
            ->groupBy('accounts.id')
            ->join('languages', 'accounts.language_id', 'languages.id')
            ->join('softwares', 'accounts.software_id', 'softwares.id')
            ->leftJoin('accounts_managers', 'accounts.id', 'accounts_managers.account_id')
            ->join("{$this->erp}.PEOPLE", 'PEOPLE.ID', "accounts_managers.manager_id")
            ->orderBy('accounts.id', 'desc')
            ->where(function ($query) use ($id, $conditions) {
                $this->checkIdIsEmpty($query, $id);
                $this->compare($conditions, $query);
            })
            ->whereNull('accounts.deleted_at')
            ->whereNull('accounts_managers.deleted_at')
            ->joinSub($zones, 'zones', function ($join) {
                $join->on('accounts.id', '=', 'zones.id');
            })
            ->joinSub($types, 'types', function ($join) {
                $join->on('accounts.id', '=', 'types.id');
            })
            ->joinSub($opps, 'opps', function ($join) {
                $join->on('accounts.id', '=', 'opps.id');
            })
            ->select(
                DB::raw('group_concat(PEOPLE.NAME separator ", ") as regional_sales_managers'),
                'company_name', 'country', 'city', 'address_line_1', 'address_line_2', 'postal_code', 'province',
                'accounts.email', 'languages.language', 'softwares.software', 'web',
                'short_name', 'opps.opportunities', 'opps.status as customer_status',
                'accounts.id', 'phone', 'customer_type', 'created_date', 'account_manager', 'zones.zones', 'types.type as customer_industry',
                'accounts.created_at'
            )->get();

        return response()->json($result);
    }

    public function selectAccountsNames()
    {
        $result = DB::table($this->table)
            ->orderBy('company_name', 'asc')
            ->whereNull('accounts.deleted_at')
            ->select('id', 'company_name as account')
            ->get();

        return response()->json($result);
    }

    public function getZonesByAccountId($id)
    {
        $result = DB::table($this->table)
            ->leftJoin('accounts_zones', 'accounts.id', 'accounts_zones.account_id')
            ->join('zones', 'zones.id', 'accounts_zones.zone_id')
            ->where('accounts.id', '=', $id)
            ->whereNull('accounts_zones.deleted_at')
            ->select('accounts.id', 'zones.zone', 'accounts_zones.zone_id')
            ->get();

        return response()->json($result);
    }

    public function getManagersByAccountId($id)
    {
        $result = DB::table($this->table)
            ->leftJoin('accounts_managers', 'accounts.id', 'accounts_managers.account_id')
            ->join("{$this->erp}.PEOPLE", 'PEOPLE.ID', "accounts_managers.manager_id")
            ->where('accounts.id', '=', $id)
            ->whereNull('accounts_managers.deleted_at')
            ->select('PEOPLE.ID as id', 'PEOPLE.NAME as name')
            ->get();

        return response()->json($result);
    }

    public function getActivitiesByAccountId($id)
    {
        $activity_calls = $this->fetchActivityCallByAccountId($id);

        $activity_emails = $this->fetchActivityEmailByAccountId($id);

        $activity_events = $this->fetchActivityEventByAccountId($id);

        $activity_tasks = $this->fetchActivityTaskByAccountId($id);

        return response()->json([
            'activity_calls' => $activity_calls,
            'activity_emails' => $activity_emails,
            'activity_events' => $activity_events,
            'activity_tasks' => $activity_tasks
        ]);
    }

    private function fetchActivityCallByAccountId($id)
    {
        return DB::table("activity_calls")
            ->groupBy("activity_calls.id")
            ->leftJoin("related_activities", function ($query) {
                $query->on("activity_calls.id", "related_activities.activity_id")
                    ->where('related_activities.type', 'call');
            })
            ->join("{$this->erp}.PEOPLE", "PEOPLE.ID", "related_activities.related_to")
            ->whereNull("deleted_at")
            ->where("account_id", $id)
            ->select(
                "activity_calls.id", "activity_calls.subject", "activity_calls.comments",
                "activity_calls.contact_id", "PEOPLE.NAME as related_to", "activity_calls.created_at",
                DB::raw("group_concat(PEOPLE.NAME separator ', ') as related_to")
            )
            ->get();
    }

    private function fetchActivityEmailByAccountId($id)
    {
        return DB::table("activity_emails")
            ->groupBy("activity_emails.id")
            ->leftJoin("related_activities", function ($query) {
                $query->on("activity_emails.id", "related_activities.activity_id")
                    ->where('related_activities.type', 'email');
            })
            ->join("{$this->erp}.PEOPLE", "PEOPLE.ID", "related_activities.related_to")
            ->whereNull("deleted_at")
            ->where("account_id", $id)
            ->select(
                "activity_emails.id", "activity_emails.from", "activity_emails.to",
                "activity_emails.bcc", "activity_emails.subject", "activity_emails.content",
                "activity_emails.created_at", DB::raw("group_concat(PEOPLE.NAME separator ', ') as related_to")
            )
            ->get();
    }

    private function fetchActivityEventByAccountId($id)
    {
        return DB::table("activity_events")
            ->groupBy("activity_events.id")
            ->leftJoin("related_activities", function ($query) {
                $query->on("activity_events.id", "related_activities.activity_id")
                    ->where('related_activities.type', 'event');
            })
            ->join("{$this->erp}.PEOPLE", "PEOPLE.ID", "related_activities.related_to")
            ->whereNull("deleted_at")
            ->where("account_id", $id)
            ->select(
                "activity_events.id", "activity_events.subject", "activity_events.description",
                "activity_events.start", "activity_events.end", "activity_events.location",
                "activity_events.contact_id", "activity_events.created_at", DB::raw("
                    group_concat(PEOPLE.NAME separator ', ') as related_to
                ")
            )
            ->get();
    }

    private function fetchActivityTaskByAccountId($id)
    {
        return DB::table("activity_tasks")
            ->groupBy("activity_tasks.id")
            ->leftJoin("related_activities", function ($query) {
                $query->on("activity_tasks.id", "related_activities.activity_id")
                    ->where('related_activities.type', 'task');
            })
            ->leftJoin("{$this->erp}.PEOPLE as r", "r.ID", "related_activities.related_to")
            ->leftJoin("{$this->erp}.PEOPLE as a", "a.ID", "activity_tasks.assigned_to")
            ->whereNull("deleted_at")
            ->where("account_id", $id)
            ->select(
                "activity_tasks.id", "activity_tasks.subject",
                "activity_tasks.due_date", "activity_tasks.contact_id",
                "a.NAME as assigned_to", "activity_tasks.created_at",
                DB::raw("group_concat(r.NAME separator ', ') as related_to")
            )
            ->get();
    }

    public function getRelatedDataByAccountId($id)
    {
        $contacts = $this->fetchContactsByAccountId($id);

        $opportunities = $this->fetchOpportunitiesByAccountId($id);

        $notes = $this->fetchNotesByAccountId($id);

        return response()->json([
            'contacts' => $contacts,
            'opportunities' => $opportunities,
            'notes' => $notes
        ]);
    }

    private function fetchContactsByAccountId($id)
    {
        return DB::table('contacts')
            ->join('accounts', 'contacts.account_id', 'accounts.id')
            ->whereNull('contacts.deleted_at')
            ->whereNull('accounts.deleted_at')
            ->where('accounts.id', '=', $id)
            ->select(
                'contacts.name', 'contacts.position',
                'contacts.email', 'contacts.phone'
            )
            ->get();
    }

    private function fetchOpportunitiesByAccountId($id)
    {
        return DB::table('opportunities')
            ->join('accounts', 'opportunities.account_id', 'accounts.id')
            ->join('stages_names', 'opportunities.current_stage_id', 'stages_names.stage_id')
            ->whereNull('opportunities.deleted_at')
            ->whereNull('accounts.deleted_at')
            ->where('accounts.id', '=', $id)
            ->select(
                'stages_names.stage_name as stage',
                'opportunities.title'
            )
            ->get();
    }

    private function fetchNotesByAccountId($id)
    {
        return DB::table('notes')
            ->join('accounts', 'notes.account_id', 'accounts.id')
            ->whereNull('notes.deleted_at')
            ->whereNull('accounts.deleted_at')
            ->where('accounts.id', '=', $id)
            ->select(
                'notes.note',
                'notes.created_by',
                'notes.created_at'
            )
            ->get();
    }

    // public function totalCount()
    // {
    //     $result = Account::whereNull('deleted_at')->count();

    //     return response()->json($result);
    // }

    // public function totalCountChart()
    // {
    //     $result = DB::table("accounts")
    //             ->whereNull("deleted_at")
    //             ->groupBy( DB::raw("DATE_FORMAT(created_date, '%Y')") )
    //             ->select( DB::raw("DATE_FORMAT(created_date, '%Y') as year"), DB::raw("count(accounts.id) as count") )
    //             ->get();

    //     return response()->json($result);
    // }

    // public function countByRegions()
    // {
    //     $result =  DB::table("zones")
    //         ->leftJoin('accounts_zones', function($join){
    //             $join->on('accounts_zones.zone_id', '=', 'zones.id')
    //             ->whereNull('accounts_zones.deleted_at');
    //         })
    //         ->leftJoin('accounts', function($join){
    //             $join->on('accounts_zones.account_id', '=', 'accounts.id')
    //             ->whereNull('accounts.deleted_at');
    //         })
    //         ->groupBy("zones.id")
    //         ->whereNull("zones.deleted_at")
    //         ->select("zones.zone as region", "zones.id",
    //                 DB::raw("count(accounts.id) as data"))
    //         ->get();

    //     return response()->json($result);
    // }

    // public function countByManagers()
    // {
    //     $result =  DB::table("{$this->erp}.PEOPLE as people")
    //         ->leftJoin('accounts_managers', function($join){
    //             $join->on('accounts_managers.manager_id', '=', 'people.ID')
    //             ->whereNull('accounts_managers.deleted_at');
    //         })
    //         ->leftJoin('accounts', function($join){
    //             $join->on('accounts_managers.account_id', '=', 'accounts.id')
    //             ->whereNull('accounts.deleted_at');
    //         })
    //         ->groupBy("people.ID")
    //         ->where('people.DELETED', '=', 0)
    //         ->where('people.GROUP', '=', 12)
    //         ->where('people.PRIVILEGES', 'like', '%E_CRM%')
    //         ->select("people.NAME as manager", "people.ID as id",
    //                 DB::raw("count(accounts.id) as data"))
    //         ->get();


    //     return response()->json($result);
    // }

    // public function countBySegments()
    // {
    //     $result =  DB::table($this->table)
    //         ->groupBy("customer_industry")
    //         ->whereNull($this->table.".deleted_at")
    //         ->select("customer_industry as segment", 'id',
    //                 DB::raw("count(*) as data"))
    //         ->get();


    //     return response()->json($result);
    // }

    public function managers()
    {
        return $this
            ->belongsToMany('App\People', 'accounts_managers', 'account_id', 'manager_id')
            ->wherePivot('deleted_at', null);
    }
}
