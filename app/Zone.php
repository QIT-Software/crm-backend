<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Zone extends Model
{
    use SoftDeletes;

    protected $table = 'zones';

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function selectZonesManagers() {
        $result = DB::table($this->table)
            ->leftJoin('zones_managers', 'zones.id', '=', 'zones_managers.zone_id')
            ->join("{$this->erp}.PEOPLE", 'zones_managers.manager_id', 'PEOPLE.ID')
            ->where('PEOPLE.GROUP', 12)
            ->where('PEOPLE.DELETED', 0)
            ->select('PEOPLE.NAME as person', 'zones.id as zone_id', 'zones.zone', 'PEOPLE.ID as manager_id')
            ->get();

        return response()->json($result);
    }

    public function updateZonesManagers($data) {
        $result = ZoneManager::updateOrCreate(
            ['manager_id' => $data['manager_id']],
            ['zone_id' => $data['zone_id']]
        );

        return response()->json($result->id);
    }
}
