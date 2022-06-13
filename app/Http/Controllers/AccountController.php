<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use DB;

class AccountController extends Controller
{
    protected $account;
    protected $rules = [
        'form.company_name' => 'required|string',
        'form.email' => 'string',
        'form.short_name' => 'string',
        'form.company_location' => 'string',
        'form.country' => 'required|string',
        'form.city' => 'required|string',
        'form.address_line_1' => 'required|string',
        'form.address_line_2' => 'string',
        'form.postal_code' => 'string',
        'form.phone' => 'string',
        'form.web' => 'string',
        'form.language_id' => 'required|integer',
        'form.software_id' => 'required|integer',
        'form.customer_type' => 'string',
        'form.created_date' => 'required|date',
        'form.account_manager' => 'string'
    ];

    public function __construct(Account $account) {
        parent::__construct($account, $this->rules);
        $this->account = $account;
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);

        $form = $request->input('form');
        $managers = $request->input('managers');
        $zones = $request->input('zones');
        $types = $request->input('types');

        return $this->account->add($form, $managers, $zones, $types);
    }

    public function deleteById($id)
    {
        $account = Account::findOrFail($id);

        return $this->account->deleteById($account);
    }

    public function updateById(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $account = Account::findOrFail($id);
        $form = $request->input('form');
        $managers = $request->input('managers');
        $zones = $request->input('zones');
        $types = $request->input('types');

        return $this->account->updateById($form, $managers, $zones, $types, $account);
    }

    public function fetch(Request $conditions, $id = null)
    {
        if ($conditions->method() !== 'POST') {
            $conditions = null;
        }

        return $this->account->fetch($conditions, $id);
    }

    public function selectAccountsNames()
    {
        return $this->account->selectAccountsNames();
    }

    public function getZonesByAccountId($id)
    {
        return $this->account->getZonesByAccountId($id);
    }

    public function getManagersByAccountId($id)
    {
        return $this->account->getManagersByAccountId($id);
    }

    public function getActivitiesByAccountId($id)
    {
        return $this->account->getActivitiesByAccountId($id);
    }

    public function getRelatedDataByAccountId($id)
    {
        return $this->account->getRelatedDataByAccountId($id);
    }


    // public function totalCount()
    // {
    //     return $this->account->totalCount();
    // }

    // public function totalCountChart()
    // {
    //     return $this->account->totalCountChart();
    // }

    // public function countByRegions()
    // {
    //     return $this->account->countByRegions();
    // }

    // public function countByManagers()
    // {
    //     return $this->account->countByManagers();
    // }

    // public function countBySegments()
    // {
    //     return $this->account->countBySegments();
    // }
}
