<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Properties;
use App\Models\Reservations;
use App\Models\RentalType;
use App\Models\Amenity;
use App\Models\PropertyAmenity;
use App\Models\PropertyPhoto;
use App\Models\PropertySeo;
use App\Models\Similar;
use App\Models\Pricebedrooms;
use App\Models\PropertyBedroom;
use App\Models\RoomType;
use App\Models\Periods;

class DBUpdateController extends Controller
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

    public function organize()
    {
        $properties = Properties::get();

        // foreach ($properties as $p) {
            // Step 1
            // if ($p->default_currency == 'EURO') {
            //     $p->update([
            //         'default_currency' => 'EUR'
            //     ]);
            // }

            // Step 2
            // if ($p->main_photo) {
            //     PropertyPhoto::create([
            //         'property_id' => $p->id,
            //         'photo' => $p->main_photo,
            //         'is_main' => 1
            //     ]);
            // }

            // Step 3
            // if ($p->photo_order) {
            //     $photos = explode(',',$p->photo_order);
            //     for ($i=1; $i <= count($photos); $i++) {
            //         if ($photos[$i-1] != "") {
            //             PropertyPhoto::create([
            //                 'property_id' => $p->id,
            //                 'photo' => $photos[$i-1],
            //                 'order' => $i
            //             ]);
            //         }
            //     }
            // }

            // Step 4
            // if ($p->seo_name || $p->seo_title || $p->seo_description || $p->seo_key) {
            //     PropertySeo::create([
            //         'property_id' => $p->id,
            //         'seo_name' => $p->seo_name,
            //         'seo_title' => $p->seo_title,
            //         'seo_description' => $p->seo_description,
            //         'seo_key' => $p->seo_key,
            //     ]);
            // }

            // Step 5
            // if ($p->rental_type) {
            //     $rt = RentalType::where('title', $p->rental_type)->first();
            //     if (!$rt) {
            //         $rt = RentalType::create([
            //             'title' => $p->rental_type
            //         ]);
            //     }
            //     $p->update([
            //         'rental_type' => $rt->id
            //     ]);
            // }

            // Step 6
            // if ($p->amenities) {
            //     foreach (explode(',',$p->amenities) as $pa) {
            //         if ($pa != "") {
            //             $am = Amenity::where('title', $pa)->first();
            //             if (!$am) {
            //                 $am = Amenity::create([
            //                     'category' => 'Recommended',
            //                     'title' => $pa
            //                 ]);
            //             }
            //             PropertyAmenity::create([
            //                 'property_id' => $p->id,
            //                 'amenity_id' => $am->id
            //             ]);
            //         }
            //     }
            // }

            // Step 7
            // if ($p->outside) {
            //     foreach (explode(',',$p->outside) as $pa) {
            //         if ($pa != '') {
            //             $am = Amenity::where('title', $pa)->first();
            //             if (!$am) {
            //                 $am = Amenity::create([
            //                     'category' => 'Outside',
            //                     'title' => $pa
            //                 ]);
            //             }
            //             PropertyAmenity::create([
            //                 'property_id' => $p->id,
            //                 'amenity_id' => $am->id
            //             ]);
            //         }
            //     }
            // }

            // Step 8
            // if ($p->pool_features) {
            //     foreach (explode(',',$p->pool_features) as $pa) {
            //         if ($pa != '') {
            //             $am = Amenity::where('title', $pa)->first();
            //             if (!$am) {
            //                 $am = Amenity::create([
            //                     'category' => 'Pool',
            //                     'title' => $pa
            //                 ]);
            //             }
            //             PropertyAmenity::create([
            //                 'property_id' => $p->id,
            //                 'amenity_id' => $am->id
            //             ]);
            //         }
            //     }
            // }
        // }

        // Step 9
        // $similars = Similar::whereNull('similar_id')->get();
        // foreach ($similars as $s) {
        //     if ($p = Properties::where('name',$s->name)->first()) {
        //         $s->update([
        //             'similar_id' => $p->id
        //         ]);
        //     } else {
        //         $s->delete();
        //     }
        // }

        // Step 10
        // foreach (Periods::get() as $p) {
        //     $p->update([
        //         'start_date' => \Carbon\Carbon::createFromFormat('d.m.Y',$p->start_date)->format('Y-m-d'),
        //         'end_date' => \Carbon\Carbon::createFromFormat('d.m.Y',$p->end_date)->format('Y-m-d')
        //     ]);
        // }
        // foreach (Pricebedrooms::get() as $p) {
        //     $p->update([
        //         'start' => \Carbon\Carbon::createFromFormat('d.m.Y',$p->start)->format('Y-m-d'),
        //         'end' => \Carbon\Carbon::createFromFormat('d.m.Y',$p->end)->format('Y-m-d')
        //     ]);
        // }

        // Step 11
        // $pricebedrooms = Pricebedrooms::get();
        // foreach ($pricebedrooms as $pb) {
        //     if ($b = RoomType::where('name',$pb->bed_name)->first()) {
        //         $pb->update([
        //             'bed_id' => $b->id
        //         ]);
        //     } elseif ($b = RoomType::where('name',strtolower($pb->bed_name))->first()) {
        //         $pb->update([
        //             'bed_id' => $b->id
        //         ]);
        //     }
        //     if ($b = PropertyBedroom::where([['property',$pb->property],['bed_id',$pb->bed_id]])->first()) {
        //         $pb->update([
        //             'bed_id' => $b->id
        //         ]);
        //     } else {
        //         $b = PropertyBedroom::create([
        //             'property' => $pb->property,
        //             'bed_id' => $pb->bed_id
        //         ]);
        //         $pb->update([
        //             'bed_id' => $b->id
        //         ]);
        //     }
        // }

        // Step 12
        foreach (Reservations::whereNotNull('reservation_room_type')->get() as $item) {
            if ($b = RoomType::where('name',$item->reservation_room_type)->first()) {
                $item->update([
                    'reservation_room_type' => $b->id
                ]);
            } elseif ($b = RoomType::where('name',strtolower($item->reservation_room_type))->first()) {
                $item->update([
                    'reservation_room_type' => $b->id
                ]);
            }
        }

        return 1;
    }
}
