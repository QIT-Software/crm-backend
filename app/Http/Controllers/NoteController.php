<?php

namespace App\Http\Controllers;

use App\Note;

class NoteController extends Controller
{
    protected $rules = [
        'note' => 'required|string',
        'account_id' => 'required|integer',
    ];
    protected $note;

    public function __construct(Note $note)
    {
        parent::__construct($note, $this->rules);
        $this->note = $note;
    }
}