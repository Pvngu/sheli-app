<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\Audit;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Examyou\RestAPI\ApiResponse;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Audit\IndexRequest;
use App\Http\Requests\Api\Audit\StoreRequest;
use App\Http\Requests\Api\Audit\DeleteRequest;
use App\Http\Requests\Api\Audit\UpdateRequest;

class AuditController extends ApiBaseController
{
    protected $model = Audit::class;

    protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;

    public function downloadAudit($auditXId) {
        $auditId = $this->getIdFromHash($auditXId);

        $audit = Audit::find($auditId);
        $company = Company::find(1);

        if($audit) {
            $data = [
                'audit_name' => $audit->audit_name,
                'audit_date' => $audit->audit_date,
                'auditor_name' => $audit->auditor->name,
                'area' => $audit->area->name,
                'findings' => $audit->findings,
                'corrective_actions' => $audit->corrective_actions,
                'status' => $audit->status,
                'current_year' => date('Y'),
                'company_name' => $company->name,
                'images' => []
            ];

            if($audit->images) {
                $images = [];
                foreach($audit->images as $image) {
                    $images[] = $image->file;
                }

                $data['images'] = $images;
            }

            $pdf = Pdf::loadView('pdf_templates.audit', $data)
                ->setPaper('letter', 'portrait');

            if ($pdf) {
                return $pdf->download('audit.pdf');
            } else {
                return response()->json(['error' => 'PDF generation failed'], 500);
            }
        }

        return response()->json(['error' => 'Audit not found'], 404);
    }

    public function updated(Audit $audit)
    {
        $request = request();
        error_log('updated');

        if($request->has('images')) {
            $images = [];
            $images = $request->images;

            foreach($images as $image) {
                $audit->images()->create([
                    'file' => $image
                ]);
            }
        }

        return $audit;
    }
}