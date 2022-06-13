<?php

namespace App\Http\Middleware;

use App\Account;
use App\ActivityCall;
use App\ActivityEmail;
use App\ActivityEvent;
use App\ActivityTask;
use App\CommercialOfferStage;
use App\Contact;
use App\CustomerRequestStage;
use App\DemoFormStage;
use App\Invoice;
use App\LinkBudgetAnalysisStage;
use App\Log;
use App\NegotiationStage;
use App\Note;
use App\Opportunity;
use App\RequestLog;
use App\ServiceOrder;
use App\TechnicalFeasibilityStage;
use Closure;

class LoggingMiddleware
{
    private $logging_routes = [
        'accounts/add',
        'accounts/{id}/update',
        'accounts/{id}/delete',

        'contacts/add',
        'contacts/{id}/update',
        'contacts/{id}/delete',

        'opportunities/add',
        'opportunities/{id}/update',
        'opportunities/{id}/delete',

        'tasks/{id}/update',
        'tasks/add',
        'tasks/{id}/delete',

        'events/{id}/update',
        'events/add',
        'events/{id}/delete',

        'calls/add',
        'calls/{id}/delete',

        'emails/add',
        'emails/{id}/delete',

        'notes/add',
        'notes/{id}/delete',

        'negotiation/{id}/update',

        'invoice/{id}/update',
        'invoice/add',

        'service_order/{id}/update',
        'service_order/add',

        'demo_form/{id}/update',
        'demo_form/add',

        'contract/{id}/update',
        'contract/add',

        'commercial_offer/{id}/update',
        'commercial_offer/add',

        'customer_request/{id}/update',
        'customer_request/add',

        'technical_feasibility_report/{id}/update',
        'technical_feasibility_report/add',

        'link_budget_analysis_report/{id}/update',
        'link_budget_analysis_report/add',
    ];

    private $modules = [
        'accounts' => 1,
        'contacts' => 2,
        'opportunities' => 3,
        'tasks' => 4,
        'events' => 5,
        'calls' => 6,
        'emails' => 7,
        'notes' => 8,
        'negotiation' => 9,
        'invoice' => 10,
        'service_order' => 11,
        'demo_form' => 12,
        'commercial_offer' => 13,
        'customer_request' => 14,
        'technical_feasibility_report' => 15,
        'link_budget_analysis_report' => 16,
    ];

    private $actions = [
        'add' => 1,
        'update' => 2,
        'delete' => 3,
    ];

    private function getModel($route)
    {
        switch ($route) {
            case 'accounts':
                return new Account();
                break;
            case 'contacts':
                return new Contact();
                break;
            case 'opportunities':
                return new Opportunity();
                break;
            case 'tasks':
                return new ActivityTask();
                break;
            case 'events':
                return new ActivityEvent();
                break;
            case 'calls':
                return new ActivityCall();
                break;
            case 'emails':
                return new ActivityEmail();
                break;
            case 'notes':
                return new Note();
                break;
            case 'negotiation':
                return new NegotiationStage();
                break;
            case 'invoice':
                return new Invoice();
                break;
            case 'service_order':
                return new ServiceOrder();
                break;
            case 'demo_form':
                return new DemoFormStage();
                break;
            case 'commercial_offer':
                return new CommercialOfferStage();
                break;
            case 'customer_request':
                return new CustomerRequestStage();
                break;
            case 'technical_feasibility_report':
                return new TechnicalFeasibilityStage();
                break;
            case 'link_budget_analysis_report':
                return new LinkBudgetAnalysisStage();
                break;
            default:
                return false;
        }
    }

    private $log;
    private $request_response_log;

    public function handle($request, Closure $next)
    {
        $exploded_path = explode('/', $request->path());
        //getting objects before state
        if (in_array(preg_replace('/[0-9]+/', '{id}', $request->path()), $this->logging_routes))
        {
            if (count($exploded_path) === 3) {
                $before = json_encode($this->getModel($exploded_path[0])->find($exploded_path[1])->toArray());
            }
        }

        //sending request to controller
        $response = $next($request);

        //checking if the route is loggable
        if (in_array(preg_replace('/[0-9]+/', '{id}', $request->path()), $this->logging_routes)) {

            //creating main log data
            if (count($exploded_path) === 3) {
                $this->log = [
                    'user_id' => app()->session_id,
                    'action_id' => $this->actions[$exploded_path[2]],
                    'module_id' => $this->modules[$exploded_path[0]],
                    'object_id' => $exploded_path[1],
                ];
            } elseif (count($exploded_path) === 2) {
                $this->log = [
                    'user_id' => app()->session_id,
                    'action_id' => $this->actions[$exploded_path[1]],
                    'module_id' => $this->modules[$exploded_path[0]],
                    'object_id' => $response->getOriginalContent(),
                ];
            }

            //sending main log data to database
            $new_logs_id = Log::create($this->log)->id;

            //creating request log data
            if (in_array('add', $exploded_path)) {
                $this->request_response_log = [
                    'log_id' => $new_logs_id,
                    'after' => json_encode($request->all()),
                ];
            } elseif (in_array('update', $exploded_path)) {
                $this->request_response_log = [
                    'log_id' => $new_logs_id,
                    'after' => json_encode($request->all()),
                    'before' => $before,
                ];
            } elseif (in_array('delete', $exploded_path)) {
                $this->request_response_log = [
                    'log_id' => $new_logs_id,
                    'before' => $before,
                ];
            }

            //sending request log to database
            RequestLog::create($this->request_response_log);
        }

        //sending response to vue
        return $response;
    }
}
