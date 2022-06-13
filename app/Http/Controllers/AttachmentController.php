<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Request;
use DB;

class AttachmentController extends Controller
{
    protected $attachment;
    protected $rules = [
        'name' => 'string',
    ];

    private $file;
    private $id;
    private $pivotTable;
    private $idOwner;
    private $subDirectory;

    public function __construct(Attachment $attachment)
    {
        parent::__construct($attachment, $this->rules);
        $this->attachment = $attachment;
    }

    public function upload(Request $request)
    {
        $this->setUp($request);

        $destinationPath = storage_path("/files/{$this->subDirectory}/{$this->id}");
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if($this->file->isValid()) {
            $name = $this->saveAndGetFileName($destinationPath, $this->file);
            $this->attachment->name = $name;

            DB::transaction(function() {
                $this->attachment->save();
                DB::table($this->pivotTable)->insert(
                    ['attachment_id' => $this->attachment->id, $this->idOwner => $this->id]
                );
            });
        }
    }

    public function selectByAccountId($id)
    {
        return $this->attachment->selectByAccountId($id);
    }

    public function selectReportByAccountId($id)
    {
        return $this->attachment->selectReportByAccountId($id);
    }

    public function selectByTechnicalFeasibilityId($id)
    {
        return $this->attachment->selectByTechnicalFeasibilityId($id);
    }

    public function selectLBAAttachByOpportunityId($id)
    {
        return $this->attachment->selectLBAAttachByOpportunityId($id);
    }

    public function selectByDemoFormId($id)
    {
        return $this->attachment->selectByDemoFormId($id);
    }

    public function selectByContractId($id)
    {
        return $this->attachment->selectByContractId($id);
    }

    public function selectByServiceOrderId($id)
    {
        return $this->attachment->selectByServiceOrderId($id);
    }

    public function selectByInvoiceId($id)
    {
        return $this->attachment->selectByInvoiceId($id);
    }

    private function setUp($request){
        $this->file = $request->file('file');
        $this->id = $request->input('id');
        $this->pivotTable = $request->input('pivotTable');
        $this->idOwner = $request->input('idOwner');
        $this->subDirectory = $request->input('subDirectory');
    }

    private function saveAndGetFileName($destinationPath, $file)
    {
        $name = $file->getClientOriginalName();
        if (file_exists($destinationPath . '/' . $name)) {
            $filename = explode('.', $name)[0];
            $extension = explode('.', $name)[1];
            for ($i=1; ; $i++) {
                if(file_exists($destinationPath . '/' . $name)) {
                    $name = $filename . '-' . $i . '.' . $extension;
                } else {
                    break;
                }
            }
        }
        $file->move($destinationPath, $name);
        return $name;
    }
}
