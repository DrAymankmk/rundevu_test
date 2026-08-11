<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\invoices;
use App\Models\PatientService;
use App\Models\PaymentMethod;
use App\Models\Reservations;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\TakafoulDiscount;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
class InvoicesController extends Controller
{
    // invoices
    function invoices(Request $request,$patient_id = null)
    {

        $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
        $query = invoices::whereIn('reception_id', $reception_ids)->orderBy('id', 'desc');
        if ($patient_id) {
            $query->where('user_id', $patient_id);
        }
        $fromDate = DateTime::createFromFormat('d/m/Y', $request->date_from);
        $toDate = DateTime::createFromFormat('d/m/Y', $request->date_to);

        if (!$fromDate) {
            $fromDate = new DateTime();
        }

        if (!$toDate) {
            $toDate = $fromDate;
        }
        // Format the dates as 'Y-m-d' (e.g., 2023-12-04)
        $formattedFromDate = $fromDate->format('Y-m-d') ;
        $formattedToDate = $toDate->format('Y-m-d') ;

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $formattedFromDate)
                ->whereDate('created_at', '<=', $formattedToDate);
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $data['invoices'] = $query->orderBy('id','desc')->get();
        return view('reception.invoices', compact('data'));
    }

    function invoice_view($invoice_id)
    {
        $invoice = invoices::whereId($invoice_id)->first();
        $clinic = Clinic::whereId(auth()->user()->id)->select('id','image','name')->first();
        return view('reception.invoice-view', compact('invoice','clinic'));
    }

    // create invoice
    function create_invoice(Request $request, $patient_id = null)
    {
        $clinic_id = auth()->user()->parent_id;
        $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
        $data['patients'] = User::whereIn('reception_id', $reception_ids)->orderBy('id', 'desc')->get();
        $data['payment_method'] = PaymentMethod::where('clinic_id', auth()->user()->parent_id)->select('id', 'name_' . $this->lang() . ' as name')->get();

        $data['specializations'] = ClinicSpecialist::with('specialties')->where('clinic_id', $clinic_id)->where('type', 1)->where('status', 1)->get();
        $data['patient_id'] = $patient_id ?? null;
        $data['tax'] = Clinic::whereId(auth()->user()->id)->select('id', 'tax')->first();
        return view('reception.create_invoice', compact('data'));
    }

    // create invoice reservation
    function create_invoice_reservation($reservation_id)
    {
        $clinic_id = auth()->user()->parent_id;
        $reservation = Reservations::whereId($reservation_id)->first();
        $data['payment_method'] = PaymentMethod::where('clinic_id', auth()->user()->parent_id)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $data['specializations'] = ClinicSpecialist::with('specialties')->where('clinic_id', $reservation->doctor_id)->where('type', 1)->where('status', 1)->get();
        $data['patient_id'] = $patient_id ?? null;
        $data['tax'] = Clinic::whereId(auth()->user()->id)->select('id', 'tax')->first();
        if ($reservation->user->company_id) {
            $company_cost = $reservation->user->company->amount;
        } else {
            $company_cost = TakafoulDiscount::pluck('discount')->first();
        }
        return view('reception.create_invoice_reservation', compact('data','reservation','company_cost'));
    }

    // get services
    function getServices(Request $request)
    {
        $services = Service::where(['type' => $request->service_type, 'status' => 1])->select('id', 'name_' . $this->lang() . ' as name', 'price')->get();
        return response()->json($services);
    }

    // get service name
    function getServicesName(Request $request)
    {
        $service = Service::where(['id' => $request->service_name, 'status' => 1])->select('id', 'name_' . $this->lang() . ' as name', 'price')->first();
        return response()->json($service);
    }

    // create invoice
    function add_invoice(Request $request)
    {
        $reception = auth()->user()->id;
        $data = $request->all();
        $patient = User::where('id', $request->patient_id)->select('id', 'parent_id', 'company_id')->first();
        $data['reception_id'] = $reception;
        $data['doctor_id'] = $request->doctor_id;
        $data['total_price'] = $request->total ?? $request->reservation_total;
        $data['total_amount_paid'] = $request->amount_paid;
        $data['user_id'] = $request->patient_id;
        $data['company_id'] = $patient->company_id;
        $data['company_total_deductible'] = $patient->company->amount ?? 0;
        $create_invoice = invoices::create($data);
        $i = 0;
        if ($create_invoice) {
            $services = $request->all();
            if ($request->service_name) {
                foreach ($request->service_name as $service) {
                    $services['user_id'] = $request->patient_id;
                    $services['doctor_id'] = $request->doctor_id;
                    $services['reservation_id'] = $request->reservation_id ?? null;
                    $services['service_id'] = $service;
                    $services['price'] = $request->price[$i] ?? null;
                    $services['type'] = $request->service_type[$i] ?? null;
                    $services['qty'] = $request->qty[$i] ?? 1;
                    $services['notes'] = $request->notes[$i] ?? null;
                    $services['discount'] = $request->discount[$i] ?? 0;
                    $services['tax'] = $request->tax[$i] ?? null;
                    $services['invoice_id'] = $create_invoice->id ?? null;
                    PatientService::create($services);
                    $i++;
                }
            }
            $invoice = invoices::whereId($create_invoice->id)->first();
            $invoice->invoice_number = 'INV-0000' . $create_invoice->id;
            $invoice->save();
            if (!empty($create_invoice->reservation_id)) {
                $check_reservation = Reservations::whereId($create_invoice->reservation_id)->first();
                $check_reservation->payment_status = 1;
                $check_reservation->status_id = 2;
                $check_reservation->save();
            }

            invoices::generate_qrCode($invoice->invoice_number);
            session()->flash('success', __(trans('admin.add_invoice_success')));
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }
}
