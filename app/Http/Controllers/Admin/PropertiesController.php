<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Itstructure\GridView\DataProviders\EloquentDataProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\CancellationMail;
use App\Http\Controllers\CalFileController;
use App\Repositories\ChannexRepository;
use Carbon\Carbon;
use Auth;
use File;
use Image;

use App\Models\Properties;
use App\Models\RentalType;
use App\Models\Reservations;
use App\Models\Amenity;
use App\Models\PropertyAmenity;
use App\Models\PropertyPhoto;
use App\Models\PropertySeo;
use App\Models\Similar;
use App\Models\Agencyproperties;
use App\Models\Agencywish;
use App\Models\Agenciescommission;
use App\Models\Channexproperties;
use App\Models\Channexrooms;
use App\Models\Icals;

class PropertiesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $dataProvider = new EloquentDataProvider($user->properties());

        return view('admin.properties.index', compact('user', 'dataProvider'));
    }

    public function list()
    {
        $user = Auth::user();
        $properties = Properties::where('owner','!=',$user->id)->whereHas('agencywish')->paginate(10);

        return view('admin.properties.list', compact('user', 'properties'));
    }

    public function settings($id = null)
    {
        $user = Auth::user();
        $title = 'Add new property';
        $property = null;
        $icals = null;
        $rentalTypes = RentalType::pluck('id','title');
        $categories = Amenity::pluck('category')->toArray();
        $categories = array_unique($categories);
        $properties = $user->properties()->pluck('id','name');

        $subdomain = null;
        if (Auth::user()->subdomain && Auth::user()->subdomain_status == 'active') {
            $subdomain = Auth::user()->subdomain;
        }

        if ($id) {
            $property = $user->properties()->where('id',$id)->first();
            if (!$property || !$user->is_admin() && $property->owner != $user->id && !$property->agencyproperties->where('agency',$user->id)->first()) {
                abort(404);
            }
            $icals = Icals::where('property', $id)->get();
            $title = $property->name;

            if ($user->is_agency() && $user->id != $property->owner) {
                return view('admin.properties.settings-agency', compact('user', 'title', 'property', 'properties', 'rentalTypes', 'categories', 'icals', 'subdomain'));
            }
        }

        return view('admin.properties.settings', compact('user', 'title', 'property', 'properties', 'rentalTypes', 'categories', 'icals', 'subdomain'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'rental_type' => ['required', 'string', 'max:255'],
            'sleeps' => ['required', 'string', 'max:255'],
            'sleeps_max' => ['required', 'string', 'max:255'],
            'description_en' => ['required'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'zip' => ['required'],
        ]);
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $input = $request->all();

        if ($request->id) {
            $property = Properties::find($request->id);
            $property->update($input);
        } else {
            if (!Auth::user()->is_admin()) {
                $input['owner'] = Auth::user()->id;
            }
            $property = Properties::create($input);
        }

        if ($property->seo) {
            $property->seo->update([
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_key' => $request->seo_key,
            ]);
        } else {
            PropertySeo::create([
                'property_id' => $property->id,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_key' => $request->seo_key,
            ]);
        }

        foreach ($property->amenities as $pa) {
            if (!in_array($pa->amenity_id,$request->amenity)) {
                $pa->delete();
            }
        }

        if ($request->amenity) {
            foreach ($request->amenity as $pa) {
                if (!PropertyAmenity::where([['property_id',$property->id],['amenity_id',$pa]])->first()) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity_id' => $pa
                    ]);
                }
            }
        }


        for ($i=0; $i < 5; $i++) {
            $similar = 'similar_'.$i;
            $similar_id = $request->get($similar);
            $ps = Similar::where([['property',$property->id],['type',$i]])->first();
            if ($ps) {
                $ps->update([
                    'similar_id' => $similar_id
                ]);
            } else {
                Similar::create([
                    'property' => $property->id,
                    'type' => $i,
                    'similar_id' => $similar_id
                ]);
            }
        }

        if ($request->icalName && $request->icalLink) {
            $calFileController = new CalFileController();
            $calFileController->import($property->id, $request->icalName, $request->icalLink);
        }
        if (!empty($request->deleteIcal)) {
            foreach ($request->deleteIcal as $icalToDelete) {
                if (array_key_exists('delete', $icalToDelete) && $icalToDelete['delete'] == "") {
                    $calFileController = new CalFileController();
                    $calFileController->delete($property->id, $icalToDelete['name'], $icalToDelete['link']);
                }
            }
        }

        $chr = Channexrooms::where('property_iden', $property->id)->first();
        if ($chr) {
            $chp = Channexproperties::find($chr->channexproperty_iden);
            $channexRepository = new ChannexRepository();
            $channexRequest = [
                'id' => $chr->channexproperty_iden,
                'name' => $chp->name,
                'properties' => $chp->rooms()->pluck('property_iden')->toArray()
            ];
            $channexRepository->save((object) $channexRequest);
        }

        return redirect()->route('admin/property-settings', ['id' => $property->id])->withFragment($hash)->with('success', __('Property settings was saved successfully!') );
    }

    public function saveCommission(Request $request)
    {
        $this->validate($request,[
            'commission' => ['required', 'numeric', 'between:1,100']
        ]);
        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        $input = $request->all();

        $property = Properties::find($request->property);
        if ($commission = $property->commissions->where('agency',$request->agency)->first()) {
            $commission->update($input);
        } else {
            Agenciescommission::create($input);
        }

        return redirect()->route('admin/property-settings', ['id' => $property->id])->withFragment($hash)->with('success', __('Property settings was saved successfully!') );
    }

    public function saveAgencyproperty(Request $request)
    {
        $input = $request->all();
        $input['confirmed'] = Carbon::now();

        Agencyproperties::create($input);

        return redirect()->route('admin')->with('success', __('Property was added successfully!') );
    }

    public function show(Request $request)
    {
        $property = Properties::findOrFail($request->id);

        $property->update([
            'show_on_main' => $request->show_on_main
        ]);

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        return back()->withFragment($hash)->with('success', __('Property settings was saved successfully!') );
    }

    public function delete(Request $request)
    {
        $property = Properties::findOrFail($request->id);
        $property->delete();

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }

        $chr = Channexrooms::where('property_iden', $property->id)->first();
        if ($chr) {
            $chp = Channexproperties::find($chr->channexproperty_iden);
            $channexRepository = new ChannexRepository();
            $channexRequest = [
                'id' => $chr->channexproperty_iden,
                'name' => $chp->name,
                'properties' => $chp->rooms()->pluck('property_iden')->filter(function($propId) use ($property) {return $propId !== $property->id;})->toArray()
            ];
            $channexRepository->save((object) $channexRequest);
        }
        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }

    public function reservations($id, Request $request)
    {
        $property = Properties::findOrFail($id);
        $dataProvider = new EloquentDataProvider(Reservations::query()->where('property',$id)->whereNull('reservation_type'));

        if ($request->has('all')) {
            $dataProvider = new EloquentDataProvider(Reservations::query()->where('property',$id));
        }

        if (Auth::user()->is_admin()) {
            return view('admin.properties.reservations-admin', compact('property', 'dataProvider'));
        }

        return view('admin.properties.reservations', compact('property', 'dataProvider'));
    }

    public function deleteReservation(Request $request)
    {
        $reservation = Reservations::find($request->id);
        if ($reservation) {
            $propertyId = $reservation->property;
            $chr = Channexrooms::where('property_iden', $propertyId)->first();
            $user = Auth::user();
            $toEmail = config('mail.to_email_reservation');
            $contactEmail = $reservation->contact_email;
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
            if ($reservation->contact_email) {
                Mail::to($contactEmail)->send(new CancellationMail($reservation, $user));
            }
            $reservation->delete();
        }

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }

    public function openModal(Request $request)
    {
        $modal = $request->modal;

        $data['property'] = Properties::findOrFail($request->property);
        $data['title'] = $request->title;
        $data['model_name'] = $request->model_name;
        $data['model_id'] = $request->model_id;
        $data['bed_id'] = $request->bed_id;
        $data['hash'] = $request->hash;

        $data['model'] = null;
        $modelName = "App\Models\\".$request->model_name;
        if ($request->model_id) {
            $data['model'] = $modelName::findOrFail($request->model_id);
        }

        return response()->json([
           'view' => view('admin.properties.ajax-modals.'.$modal, $data)->render()
       ],200);
    }

    public function saveItem(Request $request)
    {
        $input = $request->all();
        $input['name'] = $request->item_name;
        $modelName = "App\Models\\".$request->model_name;

        if ($request->property_id) {
            $property = Properties::find($request->property_id);
        }

        if ($request->hasFile('photo') && $property) {
            $this->validate($request, [
               'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $path = 'uploads/photos'.$request->property_id;
            $file = $request->file('photo');
            $destinationPath = public_path($path);
            $filename = $property->photos->count() + 1 . time() . '.jpg';

            if (!File::exists($destinationPath)) {
                mkdir($destinationPath);
            }
            $img = Image::make($file->path())->fit(1200,700)->save($destinationPath.'/'.$filename,90);

            $input['photo'] =  '/' . $path . '/' . $filename;
        }

        if (($request->hasFile('photo') || $request->photo) && $property) {
            if (!isset($input['is_main']) || $input['is_main'] != 1) {
                $input['order'] = $property->photos->count() + 1;
            }
        }

        if (isset($request->start_date)) {
            $input['start_date'] = Carbon::createFromFormat('d.m.Y',$request->start_date)->format('Y-m-d');
        }
        if (isset($request->end_date)) {
            $input['end_date'] = Carbon::createFromFormat('d.m.Y',$request->end_date)->format('Y-m-d');
        }
        if (isset($request->start)) {
            $input['start'] = Carbon::createFromFormat('d.m.Y',$request->start)->format('Y-m-d');
        }
        if (isset($request->end)) {
            $input['end'] = Carbon::createFromFormat('d.m.Y',$request->end)->format('Y-m-d');
        }

        if ($request->id) {
            $item = $modelName::findOrFail($request->id);
            $item->update($input);
        } else {
            $modelName::create($input);
        }

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        if($request->property){
            $chr = Channexrooms::where('property_iden', $request->property)->first();
            if ($chr) {
                $chp = Channexproperties::find($chr->channexproperty_iden);
                $channexRepository = new ChannexRepository();
                $channexRequest = [
                    'id' => $chr->channexproperty_iden,
                    'name' => $chp->name,
                    'properties' => $chp->rooms()->pluck('property_iden')->toArray()
                ];
                $channexRepository->save((object) $channexRequest);
            }
        }
        return back()->withFragment($hash)->with('success', __('Saved successfully!') );
    }

    public function changeOrder(Request $request)
    {
        $item = PropertyPhoto::find($request->id);
        $is_main = 0;
        if ($request->order == 0) {
            $is_main = 1;
        }

        $item->update([
            'order' => $request->order,
            'is_main' => $is_main
        ]);

        return true;
    }

    public function deleteItem(Request $request)
    {
        $modelName = "App\Models\\".$request->model_name;
        $item = $modelName::findOrFail($request->id);

        if ($request->model_name == 'PropertyPhoto') {
            $source = public_path($item->photo);
            if (File::exists($source)) {
                File::delete($source);
            }
        }

        $item->delete();

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }

    public function suggestAgency(Request $request)
    {
        $property = Properties::findOrFail($request->property);

        Agencywish::create([
            'property' => $request->property,
            'agreed' => date('Y-m-d G:i:s')
        ]);

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        return back()->withFragment($hash)->with('success', __('Confirmed!') );
    }

    public function deleteAgencyProp(Request $request)
    {
        if ($request->property) {
            $aps = Agencyproperties::where('property',$request->property)->get();
            foreach ($aps as $ap) {
                $ap->delete();
            }
        } elseif ($request->id) {
            $ap = Agencyproperties::findOrFail($request->id);
            $ap->delete();
        }

        $hash = '';
        if ($request->hash) {
            $hash = $request->hash;
        }
        return back()->withFragment($hash)->with('success', __('Removed successfully!') );
    }
}
