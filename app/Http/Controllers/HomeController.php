<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properties;
use App\Models\Customizeinfo;
use App\Models\RentalType;
use App\Models\User;
use App\Models\CancellationPolicy;
use App\Models\SliderPhoto;
use App\Models\Pages;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Reservations;
use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($subdomain=null)
    {
        $uid = 1;
        $props = Properties::where('show_on_main', "yes")->get();
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
            $uid = $subUser->id;
            $props = $subUser->properties()->where('show_on_main', "yes")->get();
        }
        $websiteHomeCityList = Customizeinfo::where([['name','website-home-city-list'],['user',$uid]])->get();
        $cityListName = Customizeinfo::where([['name','website-home-city-list-settings'],['user',$uid]])->first();
        $websiteHomeFeaturedProperties = Customizeinfo::where([['name','website-home-featured-properties'],['user',$uid]])->get();
        $featuredListingName = Customizeinfo::where([['name','website-home-featured-properties-settings'],['user',$uid]])->first();
        $currencyProperty = Customizeinfo::where([['name','currency-property'],['user',$uid]])->first();
        $currencyCurrent = Customizeinfo::where([['name','currency-current'],['user',$uid]])->first();
        $currency = Customizeinfo::where([['name','current'],['user',$uid]])->first();
        $properties = Properties::get(['city']);
        $slidephotos = SliderPhoto::where('user_id', $uid)->orderBy('order', 'ASC')->get();
        $sleeps_count = 0;
        $bed_count = 0;
        foreach ($props as $property){
            if($property->beds_count > $bed_count) {
                $bed_count = $property->beds_count;
            }
            if($property->sleeps_max > $sleeps_count) {
                $sleeps_count = $property->sleeps_max;
            }
        }
        $className = [];
        if (count($websiteHomeCityList) <= 3) {
            for ($i=0; $i < count($websiteHomeCityList); $i++) {
                $className []= 'col-12 col-sm-12 px-0 col-md-'.( 12/count($websiteHomeCityList) );
            }
        } else {
            for ($i=0; $i < count($websiteHomeCityList); $i++) {
                if ($i < 3) {
                    if ($i < 2) {
                        $className []= 'col-12 col-sm-12 px-0 col-md-4 pr-md-1';
                    } else {
                        $className []= 'col-12 col-sm-12 px-0 col-md-4';
                    }
                } elseif ($i == 3) {
                    $className []= 'col-12 col-sm-12 px-0 col-md-8 pr-md-1 pt-md-1';
                } elseif ($i%4 < 2) {
                    $className []= 'col-12 col-sm-12 px-0 col-md-4 pt-md-1';
                } else {
                    if ($i%4 == 2) {
                        $className []= 'col-12 col-sm-12 px-0 col-md-8 pt-md-1 pl-md-1';
                    } else {
                        $className []= 'col-12 col-sm-12 px-0 col-md-8 pt-md-1 pr-md-1';
                    }
                }
            }
        }

        return view('site.index', compact('websiteHomeCityList', 'cityListName', 'websiteHomeFeaturedProperties', 'featuredListingName', 'currencyProperty', 'currencyCurrent', 'currency',  'properties', 'className','slidephotos','sleeps_count','bed_count'));
    }

    public function changeCurrency(Request $request)
    {
        return back()->withCookie(cookie()->forever('currency', $request->currency));
    }

    public function change(Request $request)
    {
        \App::setLocale($request->lang);
        session()->put('locale', $request->lang);

        return redirect()->back();
    }

    public function locations($subdomain=null,Request $request)
    {
        $uid = 1;
        $props = Properties::where('show_on_main', "yes");
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
            $uid = $subUser->id;
            $props = $subUser->properties()->where('show_on_main', "yes");
        }

        if ($request->locationFiltersData) {
            $data = $request->locationFiltersData;
        } else {
            $data[0] = $request->city;
            $data[1] = $request->checkin;
            $data[2] = $request->checkout;
            $data[4] = $request->guests;
            $data[5] = $request->bedrooms;
        }

        $checkin = $data[1];
        $checkout = $data[2];
        $guest = $data[4];
        $bedroom = $data[5];
        $page = $request->page;

        $pagesize = 3;
        if ($request->page && $request->page == 1) {
            $pagesize = 5;
        }

        $properties = $props->where(function ($query) use ($data) {
            if (isset($data[0]) && $data[0] != null) {
                $place = $data[0]; $place1 = null;
                if (count(explode(',',$data[0])) > 0) {
                    $place = explode(',',$data[0])[0];
                }
                if (count(explode(', ',$data[0])) > 1) {
                    $place1 = explode(', ',$data[0])[1];
                }
                $query->where(function ($q) use($place,$place1) {
                    if ($place == 'Saint-Martin' || $place == 'St Martin' || $place == 'Saint Martin'
                        // || $place1 && ($place1 == 'Saint-Martin' || $place1 == 'St Martin' || $place1 == 'Saint Martin')
                    ) {
                        $q->where('city', 'like', 'Saint-Martin%')->orWhere('country', 'like', 'Saint-Martin%')
                            ->orWhere('city', 'like', 'St Martin%')->orWhere('country', 'like', 'St Martin%')
                            ->orWhere('city', 'like', 'Saint Martin%')->orWhere('country', 'like', 'Saint Martin%');
                    } elseif ($place == 'Orient Bay' || $place == 'La Baie Orientale' || $place == 'Baie Orientale'
                        // || $place1 && ($place1 == 'Orient Bay' || $place1 == 'La Baie Orientale' || $place1 == 'Baie Orientale')
                    ) {
                        $q->where('city', 'like', 'Orient Bay%')->orWhere('country', 'like', 'Orient Bay%')
                            ->orWhere('city', 'like', 'La Baie Orientale%')->orWhere('country', 'like', 'La Baie Orientale%')
                            ->orWhere('city', 'like', 'Baie Orientale%')->orWhere('country', 'like', 'Baie Orientale%');
                    } elseif ($place == 'Grand-Case' || $place == 'Grand Case'
                        // || $place1 && ($place1 == 'Grand-Case' || $place1 == 'Grand Case'
                    ) {
                        $q->where('city', 'like', 'Grand-Case%')->orWhere('country', 'like', 'Grand-Case%')
                            ->orWhere('city', 'like', 'Grand Case%')->orWhere('country', 'like', 'Grand Case%');
                    } elseif ($place == 'Terres Basses' || $place == 'Lowlands' || $place1 == 'Terres Basses' || $place1 == 'Lowlands') {
                        $q->where('city', 'like', 'Terres Basses%')->orWhere('country', 'like', 'Terres Basses%')
                            ->orWhere('city', 'like', 'Lowlands%')->orWhere('country', 'like', 'Lowlands%');
                    } elseif ($place == 'St Barts' || $place == 'St Barth' || $place == 'St Barthélemy' || $place == 'St Barths' || $place == 'Saint Barthelemy' || $place == 'Saint Barthélemy'
                        // || $place1 && ($place1 == 'St Barts' || $place1 == 'St Barth' || $place1 == 'St Barthélemy' || $place1 == 'St Barths' || $place1 == 'Saint Barthelemy' || $place1 == 'Saint Barthélemy')
                    ) {
                        $q->where('city', 'like', 'St Barts%')->orWhere('country', 'like', 'St Barts%')
                            ->orWhere('city', 'like', 'St Barth%')->orWhere('country', 'like', 'St Barth%')
                            ->orWhere('city', 'like', 'St Barthélemy%')->orWhere('country', 'like', 'St Barthélemy%')
                            ->orWhere('city', 'like', 'St Barths%')->orWhere('country', 'like', 'St Barths%')
                            ->orWhere('city', 'like', 'St Barths%')->orWhere('country', 'like', 'St Barths%')
                            ->orWhere('city', 'like', 'Saint Barthélemy%')->orWhere('country', 'like', 'Saint Barthélemy%')
                            ->orWhere('city', 'like', 'Saint Barthelemy%')->orWhere('country', 'like', 'Saint Barthelemy%');
                    } else {
                        // if ($place1) {
                            // $q->where('city', 'like', $place.'%')->orWhere('country', 'like', $place.'%')->orWhere('country', 'like', $place1.'%')->orWhere('country', 'like', $place1.'%');
                        // } else {
                            $q->where('city', 'like', $place.'%')->orWhere('country', 'like', $place.'%');
                        // }
                    }
                });
            }
            if (isset($data[3]) && $data[3] != null) {
                $query->where('rental_type', (int)$data[3]);
            }
            if (isset($data[4]) && $data[4] != null) {
                $query->where('sleeps_max', '>=', (int)$data[4]);
            }
        });

        if (isset($data[1]) || isset($data[2]) || isset($data[6])) {
            if (!isset($data[6])) {
                $data[6] = null;
            }
            $properties = $properties->filterByDate($data[1],$data[2],$data[6]);
            $properties = $this->custom_paginate($properties, $pagesize);
        } else {
            $properties = $properties->paginate($pagesize);
        }

        $propTypes = RentalType::pluck('title','id')->toArray();

        if ($request->ajax()) {
            return response()->json([
                'view' => view('site.partials.location-properties', compact('properties','checkin','checkout','guest','bedroom','page'))->render()
           ],200);
        }
        $sleeps_count = 0;
        $bed_count = 0;
        foreach ($properties as $property){
           if($property->beds_count > $bed_count) {
               $bed_count = $property->beds_count;
           }
           if($property->sleeps_max > $sleeps_count) {
               $sleeps_count = $property->sleeps_max;
           }
        }
        return view('site.locations', compact('properties', 'propTypes','sleeps_count','bed_count'));
    }

    function custom_paginate($items, $perPage)
    {
        $pageStart           = request('page', 1);
        $offSet              = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = $items->slice($offSet, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage, $items->count(), $perPage,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function property($subdomain=null,Request $request)
    {
        $property = Properties::findOrFail($request->id);

        if ($subdomain && !is_numeric($subdomain)) {
            $subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first();
            if (!$subUser || $subUser && !$property = $subUser->properties()->where('id',$request->id)->first()) {
                abort(404);
            }
        }

        return view('site.property', compact('property'));
    }

    public function contact($subdomain=null)
    {
        $uid = 1;
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
            $uid = $subUser->id;
        }
        $adminData = User::find($uid);

        return view('site.contact', compact('adminData'));
    }

    public function cancellationPolicy($subdomain=null)
    {
        $uid = 1;
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
            $uid = $subUser->id;
        }
        $content = CancellationPolicy::where('user_id', $uid)->first();

        return view('site.cancellation-policy', compact('content'));
    }

    public function viewPage($slug)
    {
        $content = Pages::where([['user_id', 1], ['slug',$slug]])->first();
        if (!$content) {
            abort(404);
        }

        return view('site.page', compact('content'));
    }

    public function viewPageAgency($subdomain=null, $slug)
    {
        $uid = 1;
        if ($subdomain) {
            if (!$subUser = User::where([['subdomain',$subdomain],['subdomain_status','active']])->first()) {
                abort(404);
            }
            $uid = $subUser->id;
        }
        $content = Pages::where([['user_id', $uid], ['slug',$slug]])->first();
        if (!$content) {
            abort(404);
        }

        return view('site.page', compact('content'));
    }

    public function sendContact(Request $request)
    {
        $toEmail = config('mail.to_email');
        $this->validate($request, [
             'name'     =>  'required',
             'email'  =>  'required|email',
             'message' =>  'required',
             'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);

        $data = [
            'name'      =>  $request->name,
            'email'      =>  $request->email,
            'message'   =>   $request->message
        ];

        Mail::to($toEmail)->send(new SendMail($data));
        return back()->with('success', 'We receive your message and we will contact with you up to 2 hours.');
    }

    public function webhookZohobookApi(Request $request)
    {
        //Log::info("Sync Invoice In PMS started.");
        $input = $request->all();
        $InvoiceData=$input['JSONString'];
        $jsonArr = json_decode($InvoiceData, true);

        $reservation_rental_price =0;
        $reservation_service_tax = 0;
        $reservation_city_tax = 0;
        $reservation_cleaning_fee = 0;
        if(isset($jsonArr['invoice']['invoice_id'])){

           $totalPrice=$jsonArr['invoice']['total'];
           $invoiceId=$jsonArr['invoice']['invoice_id'];
           $discountAmount=abs($jsonArr['invoice']['adjustment']);

           foreach ($jsonArr['invoice']['line_items'] as $lineItems){
                if($lineItems['name']=="Service charge"){
                    $reservation_service_tax = $lineItems['rate'];
                }elseif($lineItems['name']=="City Tax"){
                    $reservation_city_tax = $lineItems['rate'];
                }elseif($lineItems['name']=="Cleaning fee"){
                    $reservation_cleaning_fee = $lineItems['rate'];
                }else{
                    $reservation_rental_price = $lineItems['rate'];
                }
           }

          $updateReservationSql = Reservations::where('books_invoice_id',$invoiceId)->get();
          //Log::info($updateReservationSql[0]);
          if(isset($updateReservationSql[0]) && $invoiceId!=""){

              /*Log::info("totalPrice====>".$totalPrice);
              Log::info("invoiceId====>".$invoiceId);
              Log::info("reservation_rental_price====>".$reservation_rental_price);
              Log::info("reservation_service_tax====>".$reservation_service_tax);
              Log::info("reservation_city_tax====>".$reservation_city_tax);
              Log::info("reservation_cleaning_fee====>".$reservation_cleaning_fee);
              Log::info("discountAmount====>".$discountAmount);*/

              $updateReservationSql[0]->update([
                    'reservation_rental_price' => $reservation_rental_price,
                    'reservation_service_tax' => $reservation_service_tax,
                    'reservation_city_tax' => $reservation_city_tax,
                    'reservation_cleaning_fee' => $reservation_cleaning_fee,
                    'discount_amount' => $discountAmount,
                    'total_price' => $totalPrice,
              ]);
           }
        }
        //Log::info($invoiceArrData['books_invoice_id']);
        return response()->json([
            'status' => 200
        ],200);
    }
}
