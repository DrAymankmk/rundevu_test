<?php

namespace App\Http\Controllers\AdminPanel;

use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddTitle;
use App\Models\ClinicOffer;
use App\Models\ClinicSpecialist;
use App\Models\Notifications;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OffersController extends Controller
{
    protected function clinicSpecialtyIds($clinicId)
    {
        return ClinicSpecialist::where('clinic_id', $clinicId)
            ->where('type', 1)
            ->where('status', 1)
            ->pluck('specialty_id');
    }

    protected function normalizedOfferDates(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $startDate = $request->filled('start_date') ? $request->start_date : $today;

        if (Carbon::parse($startDate)->lt(Carbon::today())) {
            $startDate = $today;
        }

        $endDate = $request->filled('end_date') ? $request->end_date : null;
        if ($endDate && Carbon::parse($endDate)->lt(Carbon::parse($startDate))) {
            throw ValidationException::withMessages([
                'end_date' => trans('validation.after_or_equal', ['attribute' => trans('admin.To'), 'date' => trans('admin.From')]),
            ]);
        }

        return [$startDate, $endDate];
    }

    // show offers

    function index()
    {
        ClinicOffer::deleteExpired();

        $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

        $specialtyIds = $this->clinicSpecialtyIds($auth_user);

        $data['specialties'] = Specialty::whereIn('id', $specialtyIds)
            ->where('status', 1)
            ->orderBy('name_ar')
            ->get();

        $data['offers'] = ClinicOffer::with('specialty')
            ->where('clinic_id', $auth_user)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('offers.index', compact('data'));
    }

    // add offers
    public function add_offer(AddTitle $request)
    {
        $request->validate([
            'discount' => 'required|integer|min:0|max:100',
            'specialty_id' => 'required|exists:specialties,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        if (!$this->clinicSpecialtyIds($auth_user)->contains((int) $request->specialty_id)) {
            throw ValidationException::withMessages([
                'specialty_id' => trans('admin.specialties'),
            ]);
        }

        [$startDate, $endDate] = $this->normalizedOfferDates($request);

        $data = $request->all();
        $data['clinic_id'] = $auth_user;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $add_offer = ClinicOffer::create($data);
        if ($add_offer) {
            $this->notifyOfferCreated($add_offer);
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit city
    public function edit_offer($id, AddTitle $request)
    {
        $request->validate([
            'discount' => 'required|integer|min:0|max:100',
            'specialty_id' => 'required|exists:specialties,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        if (!$this->clinicSpecialtyIds($auth_user)->contains((int) $request->specialty_id)) {
            throw ValidationException::withMessages([
                'specialty_id' => trans('admin.specialties'),
            ]);
        }

        $edit_offer = ClinicOffer::where('id', $id)->first();
        [$startDate, $endDate] = $this->normalizedOfferDates($request);

        $data = $request->all();
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $edit_offer->update($data);
        $this->notifyOfferUpdated($edit_offer);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    public function update_status_offer($id, $status)
    {
        $status_ffer = ClinicOffer::where('id', $id)->first();
        if (!$status_ffer) {
            return response()->json(['message' => trans('messages.something_went_wrong')], 404);
        }

        $status_ffer->status = $status;
        $status_ffer->save();
        session()->flash('success', trans('messages.update_status'));

        return response()->json(['message' => trans('messages.update_status')]);
    }


    // delete offer
    function destroy_offer($id)
    {
        $offer = ClinicOffer::where('id', $id)->first();
        $offer->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }

    private function notifyOfferCreated(ClinicOffer $offer)
    {
        $notification = Notifications::create([
            'clinic_id' => $offer->clinic_id,
            'type' => 0,
            'app_type' => 1,
            'title_ar' => 'عرض جديد',
            'title_en' => 'New offer',
            'message_ar' => 'تم إضافة عرض جديد: ' . $offer->title_ar,
            'message_en' => 'A new offer has been added: ' . $offer->title_en,
        ]);

        broadcast(new UserNotificationEvent($notification))->toOthers();
    }

    private function notifyOfferUpdated(ClinicOffer $offer)
    {
        Notifications::create([
            'clinic_id' => $offer->clinic_id,
            'type' => 0,
            'app_type' => 1,
            'title_ar' => 'تحديث عرض',
            'title_en' => 'Offer updated',
            'message_ar' => 'تم تحديث بيانات العرض: ' . $offer->title_ar,
            'message_en' => 'Offer data has been updated: ' . $offer->title_en,
        ]);
    }
}
