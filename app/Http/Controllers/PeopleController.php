<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\People;
use DB;

class PeopleController extends Controller
{
    protected $people;

    public function __construct(People $people)
    {
        $this->people = $people;
    }

    public function selectAll()
    {
        return $this->people->selectAll();
    }

    public function selectManagers()
    {
        return $this->people->selectManagers();
    }

    public function selectAccountManagers()
    {
        return $this->people->selectAccountManagers();
    }

    public function selectPermissions()
    {
        return $this->people->selectPermissions();
    }

    public function updatePermissions(Request $request)
    {
        return $this->people->updatePermissions($request->all());
    }

    public function selectUserPermissions() {
        return $this->people->selectUserPermissions();
    }
}
