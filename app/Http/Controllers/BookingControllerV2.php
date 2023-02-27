<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Auto;
use App\Emploee;
use App\Post;
use Carbon\Carbon;
use Carbon\Traits\Date;

class BookingControllerV2 extends Controller
{
    public function filterBooking(Request $request) 
    {
        $query = Post::query();
        $bookings = Booking::all();
        $autos = Auto::all();
        $emploees = Emploee::all();
        $posts = Post::all();

        if($request->ajax()){
            if (empty($request->post_id)){
                $post = $query->get();
            }
            else {
                $post = $query->where(['id'=>$request->post_id])->get();
            }           
            return response()->json(['posts'=>$post]);
        }

        return view('booking', compact('bookings', 'autos', 'emploees', 'posts'));
    }

}
