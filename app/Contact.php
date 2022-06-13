<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Carbon\Carbon;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'account_id', 'name', 'surname', 'position', 'decision_maker', 'phone',
        'email', 'can_directly_communicate', 'language_id', 'description'
    ];

    protected $dates = [
        'deleted_at'
    ];

    private $columns = [
        'contacts.id', 'contacts.name', 'contacts.surname', 'contacts.position', 'contacts.decision_maker',
        'contacts.email', 'contacts.can_directly_communicate', 'languages.language', 'contacts.description',
        'accounts.company_name as account', 'accounts.id as account_id', 'contacts.phone'
    ];

    public function selectAll($conditions)
    {
        $results = DB::table($this->table)
            ->join('languages', 'contacts.language_id', 'languages.id')
            ->join('accounts', 'accounts.id', 'contacts.account_id')
            ->orderBy('name', 'asc')
            ->whereNull('contacts.deleted_at')
            ->whereNull('accounts.deleted_at')
            ->where(function ($query) use ($conditions) {
                $this->compare($conditions, $query);
            })
            ->get($this->columns);

        return response()->json($this->convertToBoolean($results));
    }

    private function convertToBoolean($results)
    {
        foreach ($results as $result) {
            foreach ($result as $key => &$value) {
                if ($key == 'can_directly_communicate' || $key == 'decision_maker') {
                    $value == (string) 1 ? $value = 'Yes' : $value = 'No';
                }
            }
        }

        return $results;
    }

    public function selectById($id)
    {
        $result = DB::table($this->table)
            ->join('languages', 'contacts.language_id', 'languages.id')
            ->join('accounts', 'accounts.id', 'contacts.account_id')
            ->where('contacts.id', '=', $id)
            ->whereNull('contacts.deleted_at')
            ->whereNull('accounts.deleted_at')
            ->get($this->columns);

        return response()->json($result);
    }

    public function selectContactsToAccounts($id)
    {
        $result = DB::table($this->table)
            ->where('account_id', '=', $id)
            ->whereNull('contacts.deleted_at')
            ->get(['id', 'name']);

        return response()->json($result);
    }

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->surname}";
    }
}
