<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserApp\Invoices\InvoiceDetailsResource;
use App\Http\Resources\UserApp\Invoices\InvoicesResource;
use App\Http\Resources\UserApp\TestResult\TestResultDetailsResource;
use App\Http\Resources\UserApp\TestResult\TestResultResource;
use App\Models\invoices;
use App\Models\PatientService;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class InvoicesController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $user_id = $request->user_id ?? $check_authorization->id;
        $query = invoices::with('doctor')->where('user_id', $user_id)->orderBy('id','desc')->paginate(20);
        $invoices_list = InvoicesResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $invoices_list);
    }

    // test result
    function details(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = invoices::with('services')->whereId($request->invoice_id)->first();
        $invoice_details = new InvoiceDetailsResource($query);
        return $this->success(trans('user.data'), $invoice_details);
    }

    // pay invoice
    function pay(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $invoice = invoices::with('services')->whereId($request->invoice_id)->first();
        $invoice->payment_status = $request->payment_status;
        $invoice->total_amount_paid = $request->total_amount_paid;
        $invoice->save();
        return $this->respondWithMessage(trans('admin.add_invoice_success'));

    }

}
