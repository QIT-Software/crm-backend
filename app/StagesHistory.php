<?php

namespace App;
use DB;

class StagesHistory extends Model
{
    protected $table = 'stages_history';

    protected $fillable = [ 'opportunity_id', 'stage_id' ];

    protected $hidden = [ 'updated_at' ];

    public  function getHistory($id) {

         return DB::table($this->table)
                    ->join('stages_names', 'stages_history.stage_id', 'stages_names.stage_id')
                    ->where('opportunity_id', '=', $id)
                    ->whereNull('stages_names.deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get(['stages_names.stage_name', 'stages_history.created_at']);
    }

}
