<?php

namespace App;

use DB;

class People extends Model
{
    protected $table = "PEOPLE";

    private $columns = ['PEOPLE.ID as id', 'PEOPLE.NAME as name', 'PEOPLE.PRIVILEGES as priv'];

    public function selectPermissions()
    {
        $result = DB::connection('mysql2')
            ->table($this->table)
            ->leftJoin('crm_priv', 'crm_priv.people_id', '=', 'PEOPLE.ID')
            ->where('PEOPLE.DELETED', '=', 0)
            ->where('PEOPLE.PRIVILEGES', 'like', '%E_CRM%')
            ->orderBy('PEOPLE.NAME')
            ->select(DB::raw('IFNULL( `crm_priv`.`edit` , 0 ) as `edit`'),
                DB::raw('IFNULL( `crm_priv`.`admin` , 0 ) as `admin`'),
                'PEOPLE.id', 'PEOPLE.NAME as name')
            ->get();

        return response()->json($result);
    }

    public function selectAll()
    {
        $result = DB::connection('mysql2')
            ->table($this->table)
            ->where('DELETED', '=', 0)
            ->where('PRIVILEGES', 'like', '%E_CRM%')
            ->get($this->columns);

        return response()->json($result);
    }

    public function selectManagers()
    {
        $result = DB::connection('mysql2')
            ->table($this->table)
            ->where('DELETED', '=', 0)
            ->where('PRIVILEGES', 'like', '%E_CRM%')
            ->where('GROUP', '=', 12)
            ->get($this->columns);

        return response()->json($result);
    }

    public function selectAccountManagers()
    {
        $result = DB::connection('mysql2')
            ->table($this->table)
            ->leftJoin("{$this->crm}.zones_managers", 'zones_managers.manager_id', '=', 'PEOPLE.id')
            ->whereNull('zones_managers.manager_id')
            ->where('DELETED', '=', 0)
            ->where('PRIVILEGES', 'like', '%E_CRM%')
            ->where('GROUP', '=', 12)
            ->get($this->columns);

        return response()->json($result);
    }

    public function selectById($id)
    {
        $result = DB::connection('mysql2')
            ->table($this->table)
            ->where('ID', '=', $id)
            ->where('DELETED', '=', 0)
            ->get($this->columns);

        return response()->json($result);
    }

    public function updatePermissions($data)
    {
        $result = Privileges::updateOrCreate(
            ['people_id' => $data['id']],
            ['edit' => $data['edit'], 'admin' => $data['admin']]
        );

        return response()->json($result->id);
    }

    static function selectUserPermissions()
    {
        $result = DB::connection('mysql2')
            ->table('crm_priv')
            ->where('people_id', '=', app()->session_id)
            ->get();

        return $result;
    }

    public function accounts()
    {
        return $this
            ->belongsToMany('App\Account', 'accounts_managers', 'manager_id', 'account_id')
            ->wherePivot('deleted_at', null);
    }
}
