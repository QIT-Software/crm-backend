<?php

namespace App\Http\Controllers;

use App\Log;
use App\LoggingAction;
use App\LoggingModule;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected $rules = [];
    protected $log;

    public function __construct(Log $log)
    {
        parent::__construct($this->log, $this->rules);
        $this->log = $log;
    }

    public function selectAll()
    {
        return $this->log->selectAll();
    }

    public function getActions()
    {
        return LoggingAction::getActions();
    }

    public function getModules()
    {
        return LoggingModule::getModules();
    }

    public function getFilteredLogs(Request $request)
    {
        return $this->log->getFilteredLogs($request);
    }
}
