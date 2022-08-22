<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Repositories\ChannexRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Models\Properties;
use App\Models\Reservations;
use App\Models\Channexrooms;
use App\Models\User;
use App\Models\Icals;
use \Carbon\Carbon;
use App\Jobs\SendContract;
use Session;
use Stripe;
use Srmklive\PayPal\Services\ExpressCheckout;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(ChannexRepositoryInterface $channexRepository, BookingRepositoryInterface $bookingRepository, PaymentRepository $paymentRepository)
     {
         $this->channexRepository = $channexRepository;
         $this->bookingRepository = $bookingRepository;
         $this->paymentRepository = $paymentRepository;
     }

    public function payment($subdomain=null,Request $request)
    {
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
        }
        if ($request->property) {
            $request->session()->put('reservation-data', $request->except("_token"));
        }
        $data = $request->session()->get('reservation-data');
        $property = isset($data['property']) ? Properties::findOrFail($data['property']) : Properties::findOrFail($request->property);
        if (!isset($data['reservation_check_in']) && !isset($request->reservation_check_in) && !isset($data['reservation_check_out']) && !isset($request->reservation_check_out)) {
            abort(404);
        }
        return view('site.payment', compact('property','data'));
    }

    public function paymentSuccess($subdomain=null)
    {
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
        }
        return view('site.payment-success');
    }

    // public function paymentCancel()
    // {
    //     return view('site.payment-cancel');
    // }

    public function saveReservation($subdomain=null,Request $request)
    {
        $input = $request->except('_token');
        $property = Properties::findOrFail($request->property);

        if ($request->payment_method == 'Stripe') {
            $card = [
                "number" => $request->cardnumber,
                "exp_month" => $request->cardexpmonth,
                "exp_year" => $request->cardexpyear,
                "cvc" => $request->cardcvc
            ];
            try {
                $token = $this->paymentRepository->createToken($card,$property);

                try {
                    $customer = $this->paymentRepository->createCustomer($request->contact_email,$property);
                    try {
                        $this->paymentRepository->createSource($property,$customer->id,$token);
                        try {
                            $charge = $this->paymentRepository->payStripe($request->contact_email,$customer->id,$request->paid*100,$request->reservation_currency,$property,$request->reservation_check_in,$request->reservation_check_out);
                            if ($charge->status == "succeeded") {
                                $this->createReservation($input, $charge->id);
                            }
                        } catch (\Exception $e) {
                            return back()->withInput()->with(['error' => $e->getMessage()]);
                        }
                    } catch (\Exception $e) {
                        return back()->withInput()->with(['error' => $e->getMessage()]);
                    }
                } catch (\Exception $e) {
                    return back()->withInput()->with(['error' => $e->getMessage()]);
                }
            } catch (\Exception $e) {
                return back()->withInput()->with(['error' => $e->getMessage()]);
            }
        } else {
            //
        }

        return redirect('/payment-success');
    }

    public function createReservation($bookingdata,$transactionId)
    {
        $property = Properties::findOrFail($bookingdata['property']);
        $data['check_in'] = Carbon::createFromFormat('d.m.y',$bookingdata['reservation_check_in'])->format('d.m.Y');
        $data['check_out'] = Carbon::createFromFormat('d.m.y',$bookingdata['reservation_check_out'])->format('d.m.Y');
        $data['room_id'] = $bookingdata['reservation_room_type'];
        $data['currency'] = $bookingdata['reservation_currency'];
        $price = $this->bookingRepository->calcTotal($property, $data);

        $discount = $bookingdata['discount_amount'];
        if (is_numeric($discount)) {
            $price = $price - $discount;
        }

        $bookingdata['reservation_check_in'] = Carbon::createFromFormat('d.m.y',$bookingdata['reservation_check_in'])->format('d.m.Y');
        $bookingdata['reservation_check_out'] = Carbon::createFromFormat('d.m.y',$bookingdata['reservation_check_out'])->format('d.m.Y');
        $bookingdata['reservation_time'] = date('d/m/Y H:i:s P');
        $bookingdata['reservation_cleaning_fee'] = \App\Models\Currency::priceFormat($property->cleaning_fee);
        $bookingdata['reservation_city_tax'] = \App\Models\Currency::priceFormat($price * $property->city_tax/100);
        $bookingdata['reservation_service_tax'] = \App\Models\Currency::priceFormat($price * $property->service_charge/100);

        $bookingdata['payment_status'] = "Paid";
        $bookingdata['transaction_id'] = $transactionId;
        $bookingdata['paid'] = $bookingdata['paid'];
        $bookingdata['total_price'] = $bookingdata['total_price'];

        $reservation = Reservations::create($bookingdata);
        SendContract::dispatch($reservation, 'add');

        $chr = Channexrooms::where('property_iden', $reservation->property)->first();
        if ($chr !== null) {
            $this->channexRepository->createOrUpdateChannexReservations($chr,$bookingdata);
        }

        Session::flash('propertyName', $property->name);
        Session::forget('reservation-data');
    }
}
