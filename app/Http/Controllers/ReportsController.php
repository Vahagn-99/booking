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
use Auth;

class ReportsController extends Controller
{
    public function index(){
        
        $user = Auth::user();
        
         return view('admin.report.index');
    }
    //here we have night booked calculation
    public function firstcall(Request $request){
    
        $user = Auth::user();
        
        $from=date("Y/m/d", strtotime($request->get('from')))." 00:00:00";
        $to=date("Y/m/d", strtotime($request->get('to')))." 00:00:00";
        
        $nightBookedPerChannels=Array();
       
        $bookingChannelServicesQuery=Reservations::join('properties', 'reservations.property', '=', 'properties.id')
         ->where('reservations.created_at', '>=',  $from)
         ->where('reservations.created_at', '<=',  $to)
         ->whereNotNull('reservations.service')
         ->groupBy('reservations.service')
         ->selectRaw('reservations.service')
         ->where(function($query) {
			$query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
         });  
        
        if ($user->account_type!='admin') {
          $bookingChannelServicesQuery->where('properties.owner','=',$user->id);
        }
        $bookingChannelServices=$bookingChannelServicesQuery->get();
        
       
        $totalNightBooked=0;
        $totalRevenue=0;
        foreach ($bookingChannelServices as $bookingChannelService) {
               $nightBookforChannel=0;
               $revenueforChannel=0; 
               
               $bookingChanyNameQuery=Reservations::join('properties', 'reservations.property', '=', 'properties.id')
                 ->where('reservations.created_at', '>=',  $from)
                 ->where('reservations.created_at', '<=',  $to)
                 ->where('service','=' ,$bookingChannelService->service)
                 ->selectRaw('reservations.reservation_check_in,reservations.reservation_check_out,reservations.total_price,reservations.service')
                 ->whereNotNull('reservations.service')
                 ->where(function($query) {
			         $query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
                  });
                 if ($user->account_type!='admin') {
                     $bookingChanyNameQuery->where('properties.owner','=',$user->id);
                 }
                 $bookingChanyName=$bookingChanyNameQuery->get();
               
               foreach ($bookingChanyName as $bookingChaNameValue) {
                   
                    $nightBookforChannel=round(abs(strtotime($bookingChaNameValue->reservation_check_in) - strtotime($bookingChaNameValue->reservation_check_out))/86400)+$nightBookforChannel; 
                    $revenueforChannel=$bookingChaNameValue->total_price+$revenueforChannel;
                   
                }
                $totalNightBooked=$nightBookforChannel+$totalNightBooked;
                $totalRevenue=$revenueforChannel+$totalRevenue;
        }
        
        $nightbookedArr=Array(
                    'nightbooked' => $totalNightBooked,
                    'totalrevenue' => number_format((float)$totalRevenue, 2, '.', ''),                   
                );    
       
        echo json_encode($nightbookedArr);
        exit;
    }
    //here we have datatable ajax call
    public function ajaxcall(Request $request)
    {
     $user = Auth::user();
     
     ## Read value
     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrderT = $order_arr[0]['dir']; // asc or desc
     if($columnSortOrderT=="asc"){
         $columnSortOrder="desc";
     }else{
         $columnSortOrder="asc";
     }
     
     $searchValue = $search_arr['value'];
     
     // Total records
     $totalRecordsQuery = Reservations::join('properties', 'reservations.property', '=', 'properties.id')
      ->where(function($query) {
       $query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
      });
      if ($user->account_type!='admin') {
                     $totalRecordsQuery->where('properties.owner','=',$user->id);
                 }
      $totalRecords=$totalRecordsQuery->count();
  
      $totalRecordswithFilterQuery = Reservations::join('properties', 'reservations.property', '=', 'properties.id')
         ->where(function($query) {
	         $query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
         })   
         ->where('reservations.created_at', 'like', '%' .$searchValue . '%')
       ->orwhere('properties.name', 'like', '%' .$searchValue . '%')
       ->orwhere('reservations.reservation_check_in', 'like', '%' .$searchValue . '%')
       ->orwhere('reservations.contact_first_name', 'like', '%' .$searchValue . '%');
         if ($user->account_type!='admin') {
                     $totalRecordswithFilterQuery->where('properties.owner','=',$user->id);
                 }
         $totalRecordswithFilter=$totalRecordswithFilterQuery->count();

     // Fetch records
      $recordsQuery = Reservations::join('properties', 'reservations.property', '=', 'properties.id')      
       ->orderBy($columnName,$columnSortOrder)
       ->where('reservations.created_at', 'like', '%' .$searchValue . '%')
       ->orwhere('properties.name', 'like', '%' .$searchValue . '%')
       ->orwhere('reservations.reservation_check_in', 'like', '%' .$searchValue . '%')
       ->orwhere('reservations.contact_first_name', 'like', '%' .$searchValue . '%')
       ->where(function($query) {
			$query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
        })   
       ->skip($start)
       ->take($rowperpage);
       if ($user->account_type!='admin') {
                     $recordsQuery->where('properties.owner','=',$user->id);
                 }
       $records=$recordsQuery->get(['reservations.*', 'properties.name']);
     
      $data_arr = array();
      
      foreach($records as $record){
      
        $data_arr[] = array(
          "id" => $record->id,
          "name"=> $record->name,
          "reservation_check_in" => $record->reservation_check_in,
          "reservation_check_out" => $record->reservation_check_out,
          "contact_first_name" => $record->contact_first_name,
          "service" =>$record->service,
          "total_price" => $record->total_price,
          "payment_status" => $record->payment_status,
          "created_at" => date("Y/m/d", strtotime($record->created_at)),
        );
     }
     
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
        
      
    }
    //here we have rental revenue calculation
    public function rentalrevenue(Request $request){
    
        $user = Auth::user();
        
        $from=date("Y/m/d", strtotime($request->get('from')))." 00:00:00";
        $to=date("Y/m/d", strtotime($request->get('to')))." 00:00:00";
        
        $nightBookedPerChannels=Array();
        
        $bookingChannelServicesQuery= Reservations::join('properties', 'reservations.property', '=', 'properties.id')
         ->where('reservations.created_at', '>=',  $from)
         ->where('reservations.created_at', '<=',  $to)
         ->whereNotNull('reservations.service')
         ->groupBy('reservations.service')
         ->selectRaw('reservations.service')
         ->where(function($query) {
			$query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
         });
         if ($user->account_type!='admin') {
                     $bookingChannelServicesQuery->where('properties.owner','=',$user->id);
                 }
         $bookingChannelServices=$bookingChannelServicesQuery->get();
        
       
   
        foreach ($bookingChannelServices as $bookingChannelService) {
               $nightBookforChannel=0;
               $serviceName=""; 
              
               $bookingChannelByNameQuery=Reservations::join('properties', 'reservations.property', '=', 'properties.id')
                 ->where('reservations.created_at', '>=',  $from)
                 ->where('reservations.created_at', '<=',  $to)
                 ->where('service','=' ,$bookingChannelService->service)
                 ->selectRaw('reservations.reservation_check_in,reservations.reservation_check_out,reservations.total_price,reservations.service')
                 ->whereNotNull('reservations.service')
                 ->where(function($query) {
			        $query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
                  });
                  if ($user->account_type!='admin') {
                     $bookingChannelByNameQuery->where('properties.owner','=',$user->id);
                  }
                  $bookingChannelByName=$bookingChannelByNameQuery->get();
              
                 $serviceName=$bookingChannelService->service;
                 $revenueforChannel=0;
                 foreach ($bookingChannelByName as $bookingChannelByNameValue) {
                    $nightBookforChannel=round(abs(strtotime($bookingChannelByNameValue->reservation_check_in) - strtotime($bookingChannelByNameValue->reservation_check_out))/86400)+$nightBookforChannel; 
                    $revenueforChannel=$bookingChannelByNameValue->total_price+$revenueforChannel;
                 }

                $colorCode="";   
                if($serviceName=="Airbnb"){
                   $colorCode="#ff585d";
                }else if($serviceName=="Hostaway Direct"){
                   $colorCode="#ff7c51";
                }else if($serviceName=="Booking.com"){
                   $colorCode="#00a2be";
                }else if($serviceName=="BookingEngine.com"){
                   $colorCode="#f7c31e";
                }else if($serviceName=="Vrbo"){
                   $colorCode="#116db3";
                }else if($serviceName=="Wordpress"){
                  $colorCode="#464342";
                }else{
                  $colorCode='#' .str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT); 
                }
                
                $nightbookedArr=Array(
                    'total' => number_format((float)$revenueforChannel, 2, '.', ''),
                    'service' => $serviceName,
                    'colour' => $colorCode,
                );
                array_push($nightBookedPerChannels, $nightbookedArr);
        }
            
        Log::info($nightBookedPerChannels);
       
        echo json_encode($nightBookedPerChannels);
        exit;
    }
    //here we have night booked calculation
    public function nightbooked(Request $request){
    
        $user = Auth::user();
        
        $from=date("Y/m/d", strtotime($request->get('from')))." 00:00:00";
        $to=date("Y/m/d", strtotime($request->get('to')))." 00:00:00";
        
     
        $nightBookedPerChannels=Array();
        $bookingChannelServicesQuery= Reservations::join('properties', 'reservations.property', '=', 'properties.id')
         ->where('reservations.created_at', '>=',  $from)
         ->where('reservations.created_at', '<=',  $to)
         ->whereNotNull('reservations.service')
         ->groupBy('reservations.service')
         ->selectRaw('reservations.service')
         ->where(function($query) {
			$query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
         });
         if ($user->account_type!='admin') {
                     $bookingChannelServicesQuery->where('properties.owner','=',$user->id);
         }
         $bookingChannelServices=$bookingChannelServicesQuery->get();
   
        foreach ($bookingChannelServices as $bookingChannelService) {
               $nightBookforChannel=0;
               $serviceName=""; 
               
               $bookingChannelByNameQuery=Reservations::join('properties', 'reservations.property', '=', 'properties.id')
                 ->where('reservations.created_at', '>=',  $from)
                 ->where('reservations.created_at', '<=',  $to)
                 
                 ->where('service','=' ,$bookingChannelService->service)
                 ->selectRaw('reservations.reservation_check_in,reservations.reservation_check_out,reservations.total_price,reservations.service')
                 ->whereNotNull('reservations.service')
                 ->where(function($query) {
			        $query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
                  });
                  if ($user->account_type!='admin') {
                     $bookingChannelByNameQuery->where('properties.owner','=',$user->id);
                  }
                 $bookingChannelByName=$bookingChannelByNameQuery->get();
                 
                $serviceName=$bookingChannelService->service;
                $revenueforChannel=0;
                foreach ($bookingChannelByName as $bookingChannelByNameValue) {
                    $nightBookforChannel=round(abs(strtotime($bookingChannelByNameValue->reservation_check_in) - strtotime($bookingChannelByNameValue->reservation_check_out))/86400)+$nightBookforChannel; 
                    $revenueforChannel=$bookingChannelByNameValue->total_price+$revenueforChannel;
                }

                $colorCode="";   
                if($serviceName=="Airbnb"){
                   $colorCode="#ff585d";
                }else if($serviceName=="Hostaway Direct"){
                   $colorCode="#ff7c51";
                }else if($serviceName=="Booking.com"){
                   $colorCode="#00a2be";
                }else if($serviceName=="BookingEngine.com"){
                   $colorCode="#f7c31e";
                }else if($serviceName=="Vrbo"){
                   $colorCode="#116db3";
                }else if($serviceName=="Wordpress"){
                  $colorCode="#464342";
                }else{
                  $colorCode='#' .str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT); 
                }
                
                $nightbookedArr=Array(
                    'nightbooked' => $nightBookforChannel,
                    'servicename' => $serviceName,
                    'colourcode' => $colorCode,
                );
                array_push($nightBookedPerChannels, $nightbookedArr);
        }
            
        Log::info($nightBookedPerChannels);
       
        echo json_encode($nightBookedPerChannels);
        exit;
    }
    //here we have reservation per channel 
    public function reservationperchannel(Request $request){
    
        $user = Auth::user();
        
        $from=date("Y/m/d", strtotime($request->get('from')))." 00:00:00";
        $to=date("Y/m/d", strtotime($request->get('to')))." 00:00:00";
        
        $reservationPerChannels=Array();
 
        $reservationperChannelsQuery= Reservations::join('properties', 'reservations.property', '=', 'properties.id')
          ->where('reservations.created_at', '>=',  $from)
          ->where('reservations.created_at', '<=',  $to)
          
          ->where(function($query) {
			$query->where('reservations.reservation_type','!=','block')->orwhereNull('reservations.reservation_type');
           })   
          ->groupBy('reservations.service')
          ->selectRaw('count(reservations.service) as total, reservations.service')
          ->whereNotNull('reservations.service');
           if ($user->account_type!='admin') {
                     $reservationperChannelsQuery->where('properties.owner','=',$user->id);
                  }
          $reservationperChannels=$reservationperChannelsQuery->get();
   
        foreach ($reservationperChannels as $reservationperChannel) {

            $colorCode="";
            $serviceName=$reservationperChannel->service;
            if($serviceName=="Airbnb"){
                $colorCode="#ff585d";
            }else if($serviceName=="Hostaway Direct"){
                $colorCode="#ff7c51";
            }else if($serviceName=="Booking.com"){
                $colorCode="#00a2be";
            }else if($serviceName=="BookingEngine.com"){
                $colorCode="#f7c31e";
            }else if($serviceName=="Vrbo"){
                $colorCode="#116db3";
            }else if($serviceName=="Wordpress"){
                $colorCode="#464342";
            }else{
                $colorCode='#' .str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT); 
            }
                $reservationPerArr=Array(
                    'servicecount' => $reservationperChannel->total,
                    'servicename' => $reservationperChannel->service,
                    'colourcode' => $colorCode,
                );
                array_push($reservationPerChannels, $reservationPerArr);
        }
            
        Log::info($reservationPerChannels);
       
        echo json_encode($reservationPerChannels);
        exit;
    }
}