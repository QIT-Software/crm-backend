<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Watchable;
use App\Contracts\Notifiable;

class DemoFormStage extends Model implements Watchable, Notifiable
{
    use SoftDeletes;

    protected $table = 'demo_form_stage';

    protected $fillable = [
        'opportunity_id', 'requested_period',
        'start', 'end', 'status', 'date_rate', 'title'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function informSubject()
    {
        return 'The end date of Demo Form has come.';
    }

    public function informBody()
    {
        $body = "Opportunity: <strong>{$this->opportunity->title}</strong>\n";
        $body .= "Company Name: <strong>{$this->relatedCompany()}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Title: <strong>{$this->title}</strong>\n";
        $body .= "\t2. Requested Period: <strong>{$this->requested_period}</strong>\n";
        $body .= "\t3. Date Rate: <strong>{$this->date_rate}</strong>\n";
        $body .= "\t4. Start Date: <strong>{$this->start}</strong>\n";
        $body .= "\t5. End Date: <strong>{$this->end}</strong>\n";
        $body .= "\t6. Status: <strong>{$this->status}</strong>";

        return $body;
    }

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }

    public function message()
    {
        $msg = "The <strong>{$this->opportunity->title}</strong> free usage end date ";
        $msg .= "(<strong>{$this->relatedCompany()}</strong>) has come.";

        return $msg;
    }

    public function owners()
    {
        return $this->opportunity->account->managers;
    }
}