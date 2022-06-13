<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $rules = [
        'account_id' => 'required|integer',
        'name' => 'required|string',
        'surname' => 'required|string',
        'business_title' => 'string',
        'decision_maker' => 'boolean',
        'phone' => 'string',
        'email' => 'string',
        'can_directly_communicate' => 'boolean',
        'language_id' => 'required|integer',
        'description' => 'string',
    ];
    protected $contact;

    public function __construct(Contact $contact)
    {
        parent::__construct($contact, $this->rules);
        $this->contact = $contact;
    }

    public function selectAll(Request $conditions)
    {
        if ($conditions->method() !== 'POST') {
            $conditions = null;
        }

        return $this->contact->selectAll($conditions);
    }

    public function selectById($id)
    {
        return $this->contact->selectById($id);
    }

    public function selectContactsToAccounts($id)
    {
        return $this->contact->selectContactsToAccounts($id);
    }
}
