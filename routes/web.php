<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/accounts', 'AccountController@fetch');
$router->post('/accounts/search', 'AccountController@fetch');
$router->get('/accounts/names', 'AccountController@selectAccountsNames');
$router->get('/accounts/{id}/related', 'AccountController@getRelatedDataByAccountId');
$router->get('/accounts/fields', 'AccountController@selectFields');
$router->get('/accounts/{id}', 'AccountController@fetch');
$router->get('/accounts/zones/{id}', 'AccountController@getZonesByAccountId');
$router->get('/accounts/managers/{id}', 'AccountController@getManagersByAccountId');
$router->get('/activities/{id}', 'AccountController@getActivitiesByAccountId');
$router->post('/accounts/add', 'AccountController@add');
$router->post('/accounts/{id}/update', 'AccountController@updateById');
$router->get('/accounts/{id}/delete', 'AccountController@deleteById');

$router->get('/contacts', 'ContactController@selectAll');
$router->post('/contacts/search', 'ContactController@selectAll');
$router->get('/contacts/accounts/{id}', 'ContactController@selectContactsToAccounts');
$router->get('/contacts/fields', 'ContactController@selectFields');
$router->get('/contacts/{id}', 'ContactController@selectById');
$router->post('/contacts/add', 'ContactController@add');
$router->post('/contacts/{id}/update', 'ContactController@updateById');
$router->get('/contacts/{id}/delete', 'ContactController@deleteById');

$router->get('/opportunities', 'OpportunityController@fetch');
$router->get('/deals', 'OpportunityController@dealsFetch');
$router->post('/opportunities/search', 'OpportunityController@fetch');
$router->get('/opportunities/fields', 'OpportunityController@selectFields');
$router->get('/opportunities/stages', 'OpportunityController@selectStages');
$router->get('/opportunities/azspace1_capacity', 'OpportunityController@azspace1Capacity');
$router->get('/opportunities/azspace2_capacity', 'OpportunityController@azspace2Capacity');
$router->get('/opportunities/c_band_chart', 'OpportunityController@cBandChart');
$router->get('/opportunities/ku_band_chart', 'OpportunityController@kuBandChart');
$router->get('/opportunities/{id}/stages', 'OpportunityController@getStagesData');
$router->get('/opportunities/{id}/history', 'OpportunityController@getHistory');
$router->get('/opportunities/{id}', 'OpportunityController@fetch');
$router->post('/opportunities/add', 'OpportunityController@add');
$router->post('/opportunities/{id}/update', 'OpportunityController@updateById');
$router->get('/opportunities/{id}/delete', 'OpportunityController@deleteById');
$router->post('/opportunities/{id}/terminate', 'OpportunityController@terminateById');

$router->get('/invoices', 'InvoiceController@fetchAll');

$router->get('/sales_targets/years', 'SalesTargetController@getUniqueYears');
$router->post('/sales_targets/filter', 'SalesTargetController@getFilteredResults');
$router->get('/sales_targets/{id}/delete', 'SalesTargetController@deleteById');
$router->post('/sales_targets/{id}/update', 'SalesTargetController@updateById');
$router->post('/sales_targets/add', 'SalesTargetController@add');

$router->get('/zones', 'ZoneController@selectAll');
$router->get('/zones/managers', 'ZoneController@selectZonesManagers');
$router->post('/zones/managers', 'ZoneController@updateZonesManagers');
$router->get('/types', 'TypeController@selectAll');
$router->get('/languages', 'LanguageController@selectAll');
$router->get('/softwares', 'SoftwareController@selectAll');

$router->get('/tasks', 'ActivityTaskController@selectAll');
$router->get('/tasks/{id}', 'ActivityTaskController@selectByAccountId');
$router->post('/tasks/{id}/update', 'ActivityTaskController@updateById');
$router->post('/tasks/add', 'ActivityTaskController@add');
$router->get('/tasks/{id}/delete', 'ActivityTaskController@deleteById');

$router->get('/events', 'ActivityEventController@selectAll');
$router->get('/events/{id}', 'ActivityEventController@selectByAccountId');
$router->post('/events/{id}/update', 'ActivityEventController@updateById');
$router->post('/events/add', 'ActivityEventController@add');
$router->get('/events/{id}/delete', 'ActivityEventController@deleteById');

$router->get('/calls/{id}', 'ActivityCallController@selectByAccountId');
$router->post('/calls/add', 'ActivityCallController@add');
$router->get('/calls/{id}/delete', 'ActivityCallController@deleteById');

$router->get('/emails/{id}', 'ActivityEmailController@selectByAccountId');
$router->post('/emails/add', 'ActivityEmailController@add');
$router->get('/emails/{id}/delete', 'ActivityEmailController@deleteById');

$router->get('/notes/{id}', 'NoteController@selectByAccountId');
$router->post('/notes/add', 'NoteController@add');
$router->get('/notes/{id}/delete', 'NoteController@deleteById');

$router->post('/negotiation/add', 'NegotiationController@add');
$router->get('/negotiation/{id}', 'NegotiationController@selectByOpportunityId');
$router->post('/negotiation/{id}/update', 'NegotiationController@updateById');

$router->get('/invoice/{id}', 'InvoiceController@selectByOpportunityId');
$router->post('/invoice/{id}/update', 'InvoiceController@updateById');
$router->post('/invoice/add', 'InvoiceController@add');

$router->get('/service_order/{id}', 'ServiceOrderController@selectByOpportunityId');
$router->post('/service_order/{id}/update', 'ServiceOrderController@updateById');
$router->post('/service_order/add', 'ServiceOrderController@add');

$router->get('/demo_form/{id}', 'DemoFormController@selectByOpportunityId');
$router->post('/demo_form/{id}/update', 'DemoFormController@updateById');
$router->post('/demo_form/add', 'DemoFormController@add');

$router->post('/contract/{id}/update', 'ContractController@updateById');
$router->post('/contract/add', 'ContractController@add');

$router->get('/commercial_offer/{id}', 'CommercialOfferController@selectByOpportunityId');
$router->post('/commercial_offer/{id}/update', 'CommercialOfferController@updateById');
$router->post('/commercial_offer/add', 'CommercialOfferController@add');

$router->post('/customer_request/{id}/update', 'CustomerRequestController@updateById');
$router->post('/customer_request/add', 'CustomerRequestController@add');

$router->get('/technical_feasibility_report/{id}', 'TFController@selectByOpportunityId');
$router->post('/technical_feasibility_report/{id}/update', 'TFController@updateById');
$router->post('/technical_feasibility_report/add', 'TFController@add');

$router->get('/link_budget_analysis_report/{id}', 'LBARController@selectByOpportunityId');
$router->post('/link_budget_analysis_report/{id}/update', 'LBARController@updateById');
$router->post('/link_budget_analysis_report/add', 'LBARController@add');

$router->get('/accounts/{id}/attachments', 'AttachmentController@selectByAccountId');
$router->get('/accounts/{id}/reports', 'AttachmentController@selectReportByAccountId');
$router->get('/technical_feasibility_report/{id}/attachments', 'AttachmentController@selectByTechnicalFeasibilityId');
$router->get('/link_budget_analysis_report/{id}/attachments', 'AttachmentController@selectLBAAttachByOpportunityId');
$router->get('/demo_form/{id}/attachments', 'AttachmentController@selectByDemoFormId');
$router->get('/contract/{id}/attachments', 'AttachmentController@selectByContractId');
$router->get('/service_order/{id}/attachments', 'AttachmentController@selectByServiceOrderId');
$router->get('/invoice/{id}/attachments', 'AttachmentController@selectByInvoiceId');
$router->post('/attachments/upload', 'AttachmentController@upload');

$router->get('/people', 'PeopleController@selectAll');
$router->get('/people/managers', 'PeopleController@selectManagers');
$router->get('/people/account_managers', 'PeopleController@selectAccountManagers');
$router->get('/people/permissions', 'PeopleController@selectPermissions');
$router->post('/people/permissions', 'PeopleController@updatePermissions');
$router->get('/people/userPermissions', 'PeopleController@selectUserPermissions');

$router->get('/notifications', 'NotificationController@fetch');
$router->get('/notifications/count', 'NotificationController@count');
$router->get('/notifications/{limit}', 'NotificationController@fetch');
$router->post('/notifications', 'NotificationController@markAllAsRead');
$router->post('/notifications/{id}', 'NotificationController@markAsRead');

$router->get('/dashboard/opportunities', 'OpportunityController@totalCount');
$router->get('/dashboard/opportunities/chart', 'OpportunityController@totalCountChart');
$router->get('/dashboard/opportunities/regions', 'OpportunityController@countByRegions');
$router->get('/dashboard/opportunities/managers', 'OpportunityController@countByManagers');
$router->get('/dashboard/opportunities/segments', 'OpportunityController@countBySegments');
$router->get('/dashboard/profits/total', 'InvoiceController@totalProfit');
$router->get('/dashboard/profits/chart', 'InvoiceController@totalProfitChart');
$router->get('/dashboard/profits/regions', 'InvoiceController@profitByRegions');
$router->get('/dashboard/profits/managers', 'InvoiceController@profitByManagers');
$router->get('/dashboard/profits/segments', 'InvoiceController@profitBySegments');
$router->get('/dashboard/opportunities/stages', 'OpportunityController@getOpportunitiesCountByStages');
$router->get('/dashboard/opportunities/terminated', 'OpportunityController@getTerminatedOpportunitiesCount');
$router->get('/dashboard/deals/stages', 'OpportunityController@getDealsCountByStages');
$router->get('/dashboard/deals/terminated', 'OpportunityController@getTerminatedDealsCount');


// $router->get('/dashboard/accounts/leads/count', 'AccountController@leadsCount');
// $router->get('/dashboard/opportunities/managers/count', 'OpportunityController@countByManagers');
// $router->get('/dashboard/customer_request/managers/count', 'CustomerRequestController@countByManagers');
// $router->get('/dashboard/service_order/managers/count', 'ServiceOrderController@countByManagers');
// $router->get('/dashboard/link_budget_analysis_report/count', 'LBARController@countByManagers');
// $router->get('/dashboard/link_budget_analysis_report/losts/count', 'LBARController@lostCountByReasons');
// $router->get('/dashboard/commercial_offer/count', 'CommercialOfferController@countByManagers');
// $router->get('/dashboard/commercial_offer/losts/count', 'CommercialOfferController@lostCountByReasons');
// $router->get('/dashboard/invoices', 'InvoiceController@concludedInvoices');
// $router->get('/dashboard/invoices/zones', 'InvoiceController@concludedInvoicesGroupedByZones');
// $router->get('/dashboard/invoices/managers', 'InvoiceController@concludedInvoicesGroupedByManagers');
// $router->get('/dashboard/lead_to_quote_ratio', 'OpportunityController@leadToQuote');
// $router->get('/dashboard/quote_to_close_ratio', 'OpportunityController@quoteToClose');
// $router->get('/dashboard/sales_target/years/sum', 'SalesTargetController@sumByYears');

$router->get('/logs/get', 'LogController@selectAll');
$router->get('/logs/actions', 'LogController@getActions');
$router->get('/logs/modules', 'LogController@getModules');
$router->post('logs/filter', 'LogController@getFilteredLogs');
