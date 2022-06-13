<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Mailable;

class CommercialOfferStage extends Model implements Mailable
{
    use SoftDeletes;

    protected $table = 'commercial_offer_stage';

    protected $fillable = [
        'opportunity_id', 'start', 'end', 'description', 'band', 'title', 'lost_reason',
        'segment', 'type_of_service', 'volume', 'unit', 'period_of_lease', 'price',
        'service_end', 'notice_period', 'free_trial_time', 'other_conditions', 'fail_note',
        'payment_condition', 'further_notice', 'service_start', 'availability'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function subject()
    {
        return 'New Commercial Offer was added.';
    }

    public function mail($id)
    {
        return new \App\Events\MailSenderEvent(
            CommercialOfferStage::find($id)
        );
    }

    public function body()
    {
        $body = "Opportunity: <strong>{$this->opportunity->title}</strong>\n";
        $body .= "Company Name: <strong>{$this->relatedCompany()}</strong>\n\n";
        $body .= "Parameters:\n";
        $body .= "\t1. Title: <strong>{$this->title}</strong>\n";
        $body .= "\t2. Start: <strong>{$this->start}</strong>\n";
        $body .= "\t3. End: <strong>{$this->end}</strong>\n";
        $body .= "\t4. Band: <strong>{$this->band}</strong>\n";
        $body .= "\t5. Segment: <strong>{$this->segment}</strong>\n";
        $body .= "\t6. Type of Service: <strong>{$this->type_of_service}</strong>\n";
        $body .= "\t7. Volume: <strong>{$this->volume}</strong>\n";
        $body .= "\t8. Unit: <strong>{$this->unit}</strong>\n";
        $body .= "\t9. Period of Lease: <strong>{$this->period_of_lease}</strong>\n";
        $body .= "\t10. Service Start Date: <strong>{$this->service_start}</strong>\n";
        $body .= "\t11. Service End Date: <strong>{$this->service_end}</strong>\n";
        $body .= "\t12. Notice Period: <strong>{$this->notice_period}</strong>\n";
        $body .= "\t13. Free Trial Time: <strong>{$this->free_trial_time}</strong>\n";
        $body .= "\t14. Other Conditions: <strong>{$this->other_conditions}</strong>\n";
        $body .= "\t15. Payment Conditions: <strong>{$this->payment_condition}</strong>\n";
        $body .= "\t16. Further Notice: <strong>{$this->further_notice}</strong>\n";
        $body .= "\t17. Price: <strong>{$this->price}</strong>\n";
        $body .= "\t18. Availability: <strong>{$this->availability}</strong>\n";
        $body .= "\t19. Failure Reason: <strong>{$this->lost_reason}</strong>\n";
        $body .= "\t20. Description: <strong>{$this->description}</strong>";

        return $body;
    }

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }
}