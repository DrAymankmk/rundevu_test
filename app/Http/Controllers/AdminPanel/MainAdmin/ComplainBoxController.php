<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintBox;
use App\Models\User;
use Illuminate\Http\Request;

class ComplainBoxController extends Controller
{
    // get complain box
    function index(Request $request)
    {
        $query = ComplaintBox::with(['users','clinics'])->latest();

        if ($request->date_from) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        if ($request->status != 1) {
            if ($request->status == 2) {
                $query->whereNotNull('reply');
            } else {
                $query->whereNull('reply');
            }
        }

        $usersCount = (clone $query)->whereNull('clinic_id')->count();
        $clinicsCount = (clone $query)->whereNotNull('clinic_id')->count();

        $complains_box = $query->paginate(50);

        return view('main_admin.complains_box', compact(
            'complains_box',
            'usersCount',
            'clinicsCount'
        ));
    }
}
