<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Note extends Model
{
    use SoftDeletes;

    protected $table = 'notes';
    
    protected $fillable = [
        'account_id', 'note', 'created_by'
    ];
    
    protected $dates = [
        'deleted_at'
    ];

    public function selectByAccountId($id)
    {
        $result = DB::table($this->table)
                    ->join('accounts', 'account_id', 'accounts.id')
                    ->whereNull('notes.deleted_at')
                    ->where('notes.account_id', '=', $id)
                    ->select(
                        'account_id', 'note',
                        'created_by', 'created_at'
                    )
                    ->get();

        return response()->json($result);
    }
}
