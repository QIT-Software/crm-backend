<?php

namespace App\Http\Controllers;

use App\Language;

class LanguageController extends Controller
{
    protected $rules = [
        'language' => 'string',
    ];
    protected $language;
    
    public function __construct(Language $language)
    {
        parent::__construct($language, $this->rules);
        $this->language = $language;
    }

    public function selectAll()
    {
        $result = Language::all();

        return response()->json($result);
    }
}