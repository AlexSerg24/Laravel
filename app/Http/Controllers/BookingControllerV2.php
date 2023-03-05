<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Auto;
use App\Emploee;
use App\Post;
use Carbon\Carbon;
use Carbon\Traits\Date;
use DB;

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
                $output = DB::table('bookings')
                ->join('autos', function ($join) {
                    $join->on('autos.id', '=', 'bookings.auto_id');
                })
                ->select('autos.id', 'autos.category', 'autos.model', 'bookings.booking_from', 'bookings.booking_to')
                ->get();
            }
            else {               
                $output = DB::table('posts')
                ->join('autos', function ($join) {
                    $join->on('posts.auto_category', '=', 'autos.category');
                })
                ->leftJoin('bookings', function ($join) {
                    $join->on('autos.id', '=', 'bookings.auto_id');
                })
                ->select('autos.id', 'autos.category', 'autos.model', 'bookings.booking_from', 'bookings.booking_to')
                ->where(['posts.id'=>$request->post_id])->get();
                //$post = $query->where(['id'=>$request->post_id])->get();
            }           
            return response()->json(['output'=>$output]);
        }

        return view('booking', compact('bookings', 'autos', 'emploees', 'posts'));
    }

}
