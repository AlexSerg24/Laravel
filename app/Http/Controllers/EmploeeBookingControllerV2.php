<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;

class EmploeeBookingControllerV2 extends Controller
{
    public function filterEmploeeBooking(Request $request) 
    {
        $query = Booking::query();

        if($request->ajax()){
            $bookings = $query->where(['auto_id'=>$request->auto_id])->get();
            return response()->json(['bookings'=>$bookings]);
        }
    }
}
