<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Mailable;

class LinkBudgetAnalysisStage extends Model implements Mailable
{
    use SoftDeletes;

    protected $table = 'link_budget_analysis_stage';

    protected $fillable = [
        'opportunity_id', 'allocated_bandwidth', 'data_rate', 'downlink_location', 'uplink_location',
        'modem_model', 'satellite_name', 'recommended_hpa_size', 'title', 'lost_reason', 'fail_note'
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
            LinkBudgetAnalysisStage::find($id)
        );
    }

    public function subject()
    {
        return 'New Link Budget Analysis Report was added.';
    }

    public function body()
    {
        $body = "Opportunity: <strong>{$this->opportunity->title}</strong>\n";
        $body .= "Company Name: <strong>{$this->relatedCompany()}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Title: <strong>{$this->title}</strong>\n";
        $body .= "\t2. Allocated Bandwidth: <strong>{$this->allocated_bandwidth}</strong>\n";
        $body .= "\t3. Data Rate: <strong>{$this->data_rate}</strong>\n";
        $body .= "\t4. Downlink Location: <strong>{$this->downlink_location}</strong>\n";
        $body .= "\t5. Uplink Location: <strong>{$this->uplink_location}</strong>\n";
        $body .= "\t6. Modem Model: <strong>{$this->modem_model}</strong>\n";
        $body .= "\t7. Satellite Name: <strong>{$this->satellite_name}</strong>\n";
        $body .= "\t8. Recommended HPA Size: <strong>{$this->recommended_hpa_size}</strong>";

        return $body;
    }

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }
}
