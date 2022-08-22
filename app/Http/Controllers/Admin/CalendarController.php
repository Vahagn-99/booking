<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CalFileController;
use App\Repositories\ChannexRepository;
use App\Models\Channexrooms;
use App\Models\Icals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CancellationMail;

use App\Repositories\ChannexRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Models\Properties;
use App\Models\Reservations;
use App\Models\Bedroom;
use App\Models\Bathroom;
use App\Models\Currency;
use \Carbon\Carbon;
use App\Jobs\SendContract;

class CalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(ChannexRepositoryInterface $channexRepository, BookingRepositoryInterface $bookingRepository)
     {
         $this->channexRepository = $channexRepository;
         $this->bookingRepository = $bookingRepository;
     }

    /**protected $podcast;
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id=null)
    {
        $user = Auth::user();

        try {
            if ($id) {
                $chr = Channexrooms::where('property_iden', $id)->first();
                if ($chr) {
                    $this->channexRepository->processBookingRevisions($chr->channexProperty()->first()->channel_iden);
                }
            } else {
                $this->channexRepository->processBookingRevisions();
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

        $pageSize = 10;

        $weekDays = []; $dates = [];
        $currentWeekDay = Carbon::today()->dayOfWeek;
        $weekDayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        for ($x = 0; $x < 14; $x++) {
            if ($currentWeekDay >= count($weekDayNames)) {
                $currentWeekDay = 0;
            }
            $weekDays []= $weekDayNames[$currentWeekDay];
            $currentWeekDay++;
        }
        for ($d = 0; $d < 14; $d++) {
            $dates []= Carbon::today()->addDays($d);
        }

        $properties = $user->properties()->paginate($pageSize);
        if (\Request::has('query')) {
            $properties = $user->properties()->where('name','like','%'.\Request::get('query').'%')->paginate($pageSize);
        }
        if (\Request::has('properties')) {
            $properties = $user->properties()->whereIn('id',\Request::get('properties'))->paginate($pageSize);
            if (\Request::has('query')) {
                $properties = $user->properties()->where('name','like','%'.\Request::get('query').'%')->whereIn('id',\Request::get('properties'))->paginate($pageSize);
            }
        }

        if ($id) {
            $property = Properties::findOrFail($id);
            $properties = $user->properties()->get();

            $calFileController = new CalFileController();
            $calendars = Icals::where('property', $id)->get();
            foreach ($calendars as $cal) {
                $calFileController->import($id, $cal->name, $cal->link);
            }

            $currentMonth = Carbon::now();
            $monthsArray = [];
            for ($m = 0; $m < 9; $m++) {
                $monthsArray []= Carbon::now()->firstOfMonth()->addMonths($m);
            }

            if (!$user->is_admin() && $property->owner != $user->id && !$property->agencyproperties->where('agency',$user->id)->first()) {
                abort(404);
            }
            return view('admin.calendar.index', compact('user','property','properties','currentMonth','monthsArray'));
        }

        return view('admin.calendar.index-full', compact('user','properties','weekDays','dates'));
    }

    public function saveReservation(Request $request)
    {
        $property = Properties::findOrFail($request->property);
        $data['check_in'] = $request->reservation_check_in;
        $data['check_out'] = $request->reservation_check_out;
        $data['room_id'] = $request->reservation_room_type;
        $data['currency'] = $request->reservation_currency;
        $price = $this->bookingRepository->calcTotal($property, $data);

        $discount = $request->discount_amount;
        if (is_numeric($discount)) {
            $price = $price - $discount;
        }

        $input = $request->except('_token');
        $input['reservation_time'] = date('d/m/Y H:i:s P');
        $input['reservation_cleaning_fee'] = Currency::priceFormat($property->cleaning_fee);
        $input['reservation_city_tax'] = Currency::priceFormat($price * $property->city_tax/100);
        $input['reservation_service_tax'] = Currency::priceFormat($price * $property->service_charge/100);

        if ($request->id) {
            $input['reservation_updated'] = 1;
            $reservation = Reservations::findOrFail($request->id);
            $reservation->update($input);
            SendContract::dispatch($reservation, 'update');
        } else {
            $reservation = Reservations::create($input);
            SendContract::dispatch($reservation, 'add');
        }
        $chr = Channexrooms::where('property_iden', $reservation->property)->first();
        try {
            if ($chr !== null) {
                $this->channexRepository->createOrUpdateChannexReservations($chr, $request);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

        return back()->with('success', __('Saved successfully!') );
    }

    // Ajax methods
    public function calendarInfoFull(Request $request)
    {
        $user = Auth::user();
        $properties = Properties::whereIn('id', $request->propIds)->get();

        $weekDays = []; $dates = [];
        $currentWeekDay = Carbon::createFromFormat('d F Y',$request->startDate)->dayOfWeek;
        $weekDayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        for ($x = 0; $x < 14; $x++) {
            if ($currentWeekDay >= count($weekDayNames)) {
                $currentWeekDay = 0;
            }
            $weekDays []= $weekDayNames[$currentWeekDay];
            $currentWeekDay++;
        }
        for ($d = 0; $d < 14; $d++) {
            $dates []= Carbon::createFromFormat('d F Y',$request->startDate)->addDays($d);
        }

        return response()->json([
           'view' => view('admin.calendar.partials._calendar-full', compact('user', 'properties','weekDays','dates'))->render()
        ],200);
    }

    public function calendarInfo(Request $request)
    {
        $user = Auth::user();
        $property = Properties::findOrFail($request->id);

        $monthsArray = [];

        if ($request->dir == 'prev') {
            $currentMonth = Carbon::createFromFormat('F Y',$request->startDate)->firstOfMonth()->subMonths(9);
            for ($m = 0; $m < 9; $m++) {
                $monthsArray []= Carbon::createFromFormat('F Y',$request->startDate)->firstOfMonth()->subMonths(9)->addMonths($m);
            }
        } else {
            $currentMonth = Carbon::createFromFormat('F Y',$request->startDate)->firstOfMonth()->addMonths(9);
            for ($m = 9; $m < 18; $m++) {
                $monthsArray []= Carbon::createFromFormat('F Y',$request->startDate)->firstOfMonth()->addMonths($m);
            }
        }

        return response()->json([
           'view' => view('admin.calendar.partials._calendar', compact('user','property','currentMonth','monthsArray'))->render()
        ],200);
    }

    public function calcTotal(Request $request)
    {
        $property = Properties::findOrFail($request->p_id);
        $price = $price_sub = $this->bookingRepository->calcTotal($property, $request->except('_token'));
        $startCheckOut = $this->bookingRepository->getStartCheckOut($property, $request->check_in, $request->room_id);
        $paid = 0;

        $discount = $request->discount;
        if (is_numeric($discount)) {
            if ($discount <= $price_sub) {
                $price = $price_sub - $discount;
            } elseif ($discount) {
                $price = 0;
            }
        }

        if ($request->payment_status == 'Downpayment is paid') {
            $paid = $price * $property->downpayment/100;
        }

        $tax_fee = $price * (Currency::priceFormat($property->city_tax) + Currency::priceFormat($property->service_charge))/100 + Currency::priceFormat($property->cleaning_fee);
        $total = Currency::priceFormat($price + $tax_fee);

        if ($request->payment_status == 'Paid') {
            $paid = $total;
        }

        $currency = $request->currency ? $request->currency : 'EUR';

        return response()->json([
            'subtotal' => number_format($price_sub,2,".",""),
            'tax_fee' => number_format($tax_fee,2,".",""),
            'paid' => number_format($paid,2,".",""),
            'total' => number_format($total,2,".",""),
            'currency' => Currency::currenciesSign()[$currency],
            'startCheckOut' => $startCheckOut
        ],200);
    }

    public function getCheckOut(Request $request)
    {
        $property = Properties::findOrFail($request->p_id);
        $checkin = Carbon::createFromFormat('d.m.y',$request->check_in)->format('d.m.Y');
        $startCheckOut = $this->bookingRepository->getStartCheckOut($property, $checkin, $request->room_id);

        return response()->json([
            'startCheckOut' => $startCheckOut
        ],200);
    }

    public function openModal(Request $request)
    {
        $modal = $request->modal;
        $property = Properties::findOrFail($request->property);

        $data['added_by_agency'] = null;
        $data['check_in'] = $request->currentdate;

        if (Auth::user()->is_agency()) {
            $data['added_by_agency'] = Auth::user()->id;
        }

        $data['property'] = $property;

        if ($request->model_id) {
            $data['reservation'] = Reservations::findOrFail($request->model_id);
        }

        $data['startCheckOut'] = $this->bookingRepository->getStartCheckOut($property, $request->currentdate, null);

        return response()->json([
           'view' => view('admin.calendar.ajax-modals.'.$modal, $data)->render()
       ],200);
    }

    public function removeBlock(Request $request)
    {
        $from = Carbon::createFromFormat('d.m.Y',$request->date_from)->format('Y-m-d');
        $to = Carbon::createFromFormat('d.m.Y',$request->date_to)->format('Y-m-d');

        $blockDates = Reservations::where([['reservation_type','block'],['property',$request->property]])
            ->get()
            ->filter(function($item) use ($from, $to) {
                return Carbon::parse($item->check_in)->between(Carbon::parse($from),Carbon::parse($to)) && Carbon::parse($item->check_out)->between(Carbon::parse($from),Carbon::parse($to));
            });

        if (count($blockDates) == 0) {
            return back()->with('warning', __('No closed date found!') );
        }

        foreach ($blockDates as $reservation) {
            $propertyId = $reservation->property;
            $chr = Channexrooms::where('property_iden', $propertyId)->first();
            $user = Auth::user();
            $toEmail = config('mail.to_email_reservation');
            if ($chr) {
                $channexRepository = new ChannexRepository();
                $checkIn = str_replace('.', '-', $reservation->reservation_check_in);
                $checkOut = str_replace('.', '-', $reservation->reservation_check_out);
                $availabilityData = [
                    'property_id' => $chr->channexProperty()->first()->channel_iden,
                    'room_type_id' => $chr->channel_iden,
                    "date_from" => date('Y-m-d', strtotime(Carbon::parse($checkIn) < Carbon::today() ? Carbon::today()->toDateString() : $checkIn)),
                    'date_to' => date('Y-m-d', strtotime($checkOut)),
                    "availability" => 1
                ];
                $channexRepository->createAvailability([$availabilityData]);
            }

            Mail::to($toEmail)->send(new CancellationMail($reservation, $user));

            $reservation->delete();
        }

        return back()->with('success', __('Removed successfully!') );
    }
}
