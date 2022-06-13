<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Traits\ArrayOperations;

class Model extends BaseModel
{
    use ArrayOperations;
    protected $erp;
    protected $crm;
    protected $cm;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->crm = app()->databases['connections']['mysql']['database'];
        $this->erp = app()->databases['connections']['mysql2']['database'];
        $this->cm = env('CM_DATABASE', 'admin_cm');
    }

    protected function checkIdIsEmpty($query, $id)
    {
        if (!is_null($id)) {
            return $query->where("{$this->table}.id", $id);
        }

        return $query;
    }

    protected function compare($conditions, $query)
    {
        if (is_null($conditions) || empty($conditions)) return $query;

        $conditions = $conditions->all();
        foreach ($conditions as $column => $neededToEqual) {
            if (is_array($neededToEqual)) {
                $query->whereIn($column, $neededToEqual);
                continue;
            }
            $query->where($column, 'like', "%{$neededToEqual}%");
        }
    }

    protected function relatedCompany()
    {
        return $this->opportunity->account->company_name;
    }
}
