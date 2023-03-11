<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Auto;
use App\Emploee;
use App\Post;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Validator;
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
            }           
            return response()->json(['output'=>$output]);
        }

        return view('booking', compact('bookings', 'autos', 'emploees', 'posts'));
    }

    public function addBooking(Request $request){
        $data = Validator::make($request->all(), [
            'auto_id' => 'required',
            'emploee_id' => 'required',
            'booking_from' => 'required',
            'booking_to' => 'required',
            'booking_from' => 'before:booking_to',
        ]);

        if ($data->passes()){

            $checking = DB::table('bookings')
            ->select('bookings.booking_from', 'bookings.booking_to', 'bookings.auto_id')
            ->where(['bookings.auto_id'=>$request->auto_id])
            ->where(function ($query) use ($request){
                $query->whereBetween('bookings.booking_from', [$request->booking_from, $request->booking_to])
                ->orWhereBetween('bookings.booking_to', [$request->booking_from, $request->booking_to]);
            })
            ->get();
            if (count($checking) != 0) {
                return response()->json(['error'=>$checking]);
            }            
            else {
                $checking2 = DB::table('bookings')
                ->select('bookings.booking_from', 'bookings.booking_to', 'bookings.auto_id')
                ->where(['bookings.auto_id'=>$request->auto_id])
                ->get();
                $ok = true;
                if(count($checking2) !=0){
                    $bookdate1 = Carbon::parse($request->booking_from);
                    $bookdate2 = Carbon::parse($request->booking_to);
                    foreach ($checking2 as $check){
                        $date1 = Carbon::parse($check->booking_from);
                        $date2 = Carbon::parse($check->booking_to);
                        if ($date1->lessThanOrEqualTo($bookdate1) && $date2->greaterThanOrEqualTo($bookdate2)){
                            $ok = false;
                        }
                    }
                }
                if($ok) {
                    $booking = new Booking();
                    $booking->auto_id = $request->auto_id;
                    $booking->emploee_id = $request->emploee_id;
                    $booking->booking_from = $request->booking_from;
                    $booking->booking_to = $request->booking_to;

                    $booking->save();
                    return response()->json(['success'=>'added']);
                }
                else {
                    return response()->json(['error2'=>$checking2]);
                }
            }
        }

        return response()->json(['validation_error'=>$data]);
    }
}
