<?php

namespace App\Http\Controllers;

use App\Software;

class SoftwareController extends Controller
{
    protected $rules = [
        'software' => 'string',
        'language_id' => 'integer'
    ];
    protected $software;
    
    public function __construct(Software $software) {
        parent::__construct($software, $this->rules);
        $this->software = $software;
    }

    public function selectAll() {
        $result = Software::all();

        return response()->json($result);
    }
}