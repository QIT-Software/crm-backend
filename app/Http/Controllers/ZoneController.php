<?php

namespace App\Http\Controllers;

use App\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    protected $rules = [
        'zone' => 'string',
    ];
    protected $zone;

    public function __construct(Zone $zone)
    {
        parent::__construct($zone, $this->rules);
        $this->zone = $zone;
    }

    public function selectAll()
    {
        $zones = Zone::all();

        return response()->json($zones);
    }

    public function selectZonesManagers()
    {
        return $this->zone->selectZonesManagers();
    }

    public function updateZonesManagers(Request $request)
    {
        return $this->zone->updateZonesManagers($request->all());
    }
}
