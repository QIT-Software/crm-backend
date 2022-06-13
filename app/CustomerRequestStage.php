<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Mailable;

class CustomerRequestStage extends Model implements Mailable
{
    use SoftDeletes;

    protected $table = 'customer_request_stage';

    protected $fillable = [
        'opportunity_id', 'service_region', 'frequency_band', 'mbit_mhz', 'details', 'title'
    ];
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];
    protected $dates = [
        'deleted_at'
    ];

    public function mail($id)
    {
        return new \App\Events\MailSenderEvent(
            CustomerRequestStage::find($id)
        );
    }

    public function subject()
    {
        return 'Customer Request was added.';
    }

    public function body()
    {
        $body = "Opportunity: <strong>{$this->opportunity->title}</strong>\n";
        $body .= "Company Name: <strong>{$this->relatedCompany()}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Service Region: <strong>{$this->service_region}</strong>\n";
        $body .= "\t2. Frequency Band: <strong>{$this->frequency_band}</strong>\n";
        $body .= "\t3. Mbit\Mhz: <strong>{$this->mbit_mhz}</strong>\n";
        $body .= "\t4. Details: <strong>{$this->details}</strong>";

        return $body;
    }

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }
}
