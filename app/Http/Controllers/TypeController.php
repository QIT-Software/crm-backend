<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    protected $rules = [
        'type' => 'string',
    ];
    protected $type;

    public function __construct(Type $type)
    {
        parent::__construct($type, $this->rules);
        $this->type = $type;
    }

    public function selectAll()
    {
        $types = Type::all();

        return response()->json($types);
    }
}
