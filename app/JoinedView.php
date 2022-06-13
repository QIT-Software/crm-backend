<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class JoinedView extends Model
{
   protected $table = 'joined_users_accounts_opportunities_invoices_view';
}
