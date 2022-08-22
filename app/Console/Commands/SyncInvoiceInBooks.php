<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservations;
use App\Models\ZohoAccess;
use App\Models\User;
use App\Models\Properties;
use Log;

class SyncInvoiceInBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync-invoices-zoho';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Sync Invoice In Books started.");

        set_time_limit(0);
        $token = $this->GenrateZohoAccessToken();
        //update Invoice details in zoho books
        $this->UpdateInvoiceInBooks($token);
        $this->DeleteInvoiceInBooks($token);

        $invoice_url = "https://books.zoho.com/api/v3/invoices?organization_id=" . env('ZOHO_ORG_ID');

        //Log::info($invoice_url);

        $selectReservationSql = Reservations::where('sync_status',0)->whereNotNull('contact_first_name')->whereNotNull('contact_last_name')->get();



        foreach ($selectReservationSql as $reservationRow) {

			//Log::info($reservationRow);
			//here we will call for vendor and product api call to send into zoho crm.
			//CreateProperty,CreateVendor update by AT

			$this->CreateProperty($token);
			$this->CreateVendor($token);
            //here we have to search customer or add customer logic
            // first customer search if not found then create customer.
            $contact_id = "";
            $customerRes = "";
            $customerName='';
            $customerName=$reservationRow->contact_first_name.' '.$reservationRow->contact_last_name;
			//Log::info($customerName);
			if($customerName==''){
				Log::info("Customer name is empty");
				//continue;
			}

            $customerRes = $this->SearchCustomerInBooks($token,$customerName);
            //Log::info($customerRes);
            //here we have check customer exist or not. if not exist then it will got else part`Create customer`
            if (isset($customerRes['contacts'][0]['contact_id'])) {
                $contact_id = $customerRes['contacts'][0]['contact_id'];
            } else {
                $customerRes="";
                $customerRes=$this->CreateCustomer($token,$reservationRow);
                if (isset($customerRes['contact']['contact_id'])) {
                    $contact_id = $customerRes['contact']['contact_id'];
                }
            }
            $ProductName="";
			$description_fr="";
			$description_en="";
			$damage_deposit=0;
			$propertyId="";
            //here we will get products information from properties table.
            $productRow = $reservationRow->propertyInfo;
            
			//Log::info($productRow);
            
            $ProductName = $productRow->name;
            if ($productRow->description_fr != "") {
                $description_fr = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$productRow->description_fr);
            }
            if ($productRow->description_en != "") {
                $description_en = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$productRow->description_en);
            }
			if ($productRow->damage_deposit != "") {
                $damage_deposit = $productRow->damage_deposit;
            }
            if ($productRow->books_item_id != "") {
                $propertyId = $productRow->books_item_id;
            }
            
            //Search property In zoho books item modules
			//$PropertyRes=$this->SearchPropertyInBooks($token,$productRow->name);
			//Log::info($PropertyRes);
            //if(isset($PropertyRes['items']['item_id'])){
            //    $propertyId=$PropertyRes['items']['item_id'];
            //}

            $total_price = "0";
            if ($reservationRow->total_price != "") {
                $total_price = $reservationRow->total_price;
            }
            $beforeInvoiceDate ='';
			if ($reservationRow->reservation_time != '') {
				$beforeInvoiceDate = str_replace('/','-',current(explode(" ",$reservationRow->reservation_time)));
			}else{
				$beforeInvoiceDate = date('Y-m-d');
			}

			$reservation_adults=0;
			$reservation_children=0;
			$totalPerson=0;
			if($reservationRow->reservation_adults!=''){
				$reservation_adults=$reservationRow->reservation_adults;
			}
			if($reservationRow->reservation_children!=''){
				$reservation_children=$reservationRow->reservation_children;
			}
			$totalPerson=$reservation_adults+$reservation_children;

			$reservation_rental_price=0;
			if($reservationRow->reservation_rental_price !=''){
				 $reservation_rental_price=$reservationRow->reservation_rental_price;
			}
			
            
			/*/if($reservationRow->reservation_city_tax !=''){
				 $reservation_city_tax_Amount=$reservationRow->reservation_city_tax;
			}*/
			
			/*if($reservationRow->reservation_cleaning_fee !=''){
				 $reservation_cleaning_Amount=$reservationRow->reservation_cleaning_fee;
			}*/
			
			/*if($reservationRow->reservation_service_tax!=''){
				 $reservation_service_Amount=$reservationRow->reservation_service_tax;
			}*/
            
			$discount=0;
			if($reservationRow->discount_amount !=''){
				 $discount=$reservationRow->discount_amount;
			}
            
			$total_price=0;
			if($reservationRow->total_price !=''){
				 $total_price=$reservationRow->total_price;
			}
            
            //here we are calculating reservation service price,cleaning fee & city tax
            $tempCityTax=0;
            if ($productRow->city_tax != "") {
                $tempCityTax = $productRow->city_tax;
            }
            $tempServiceTax=0;
            if ($productRow->service_charge != "") {
                $tempServiceTax = $productRow->service_charge;
            }
            $tempCleaningFee=0;
            if ($productRow->cleaning_fee != "") {
                $tempCleaningFee = $productRow->cleaning_fee;
            }
            
            $reservation_city_tax_Amount=0;
            $reservation_cleaning_Amount=0;
            $reservation_service_Amount=0;
            $reservation_rentalprice_Amount=0;
            $reservation_rentalprice_Amount=
            
            $reservation_rentalprice_Amount=($total_price-$tempCleaningFee)/(1+$tempServiceTax/100+$tempCityTax/100);
            $reservation_city_tax_Amount=$reservation_rentalprice_Amount*($tempCityTax/100);
            $reservation_service_Amount=$reservation_rentalprice_Amount*($tempServiceTax/100);
            $reservation_cleaning_Amount=$tempCleaningFee;
            
            $totalReservationRentalPrice=$reservation_rentalprice_Amount+$discount;
            $totalService=$reservation_city_tax_Amount+$reservation_cleaning_Amount+$reservation_service_Amount;
            
            //Log::info("=====totalService=========");
            //Log::info($totalService);
            //Log::info("====reservation_city_tax==========");
            //Log::info($reservation_city_tax_Amount);
            //Log::info("=====reservation_service_tax=========");
           // Log::info($reservation_service_Amount);
            //Log::info("=====reservation_cleaning_fee=========");
            //Log::info($reservation_cleaning_Amount);
            

			

			$selectVendorNameSql = User::where('id',$productRow->owner)->limit(1)->get();
			//Log::info($selectVendorNameSql);
			$vendorName="";
			foreach ($selectVendorNameSql as $vendorRowData) {
				$vendorName=$vendorRowData->first_name.' '.$vendorRowData->last_name;
			}


             //Add Invoice customer in books
        $booksInvoiceBody='';
        $booksInvoiceExtraBody='';
        $booksInvoiceBody.='{
            "customer_id": "'.$contact_id.'",
            "invoice_number": "Reservation #'.$reservationRow->id.' from -'.$reservationRow->service.'",
            "payment_terms": 15,
            "payment_terms_label": "Net 15",
            "due_date": "'.date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days')) .'",
            "salesperson_name": "'.$vendorName.'",
			"adjustment": '. -1*$discount.',
			"adjustment_description": "Discount Amount",
            "custom_fields": [
                {
                    "customfield_id": "2991628000000132174",
                    "value": "'.date('D', strtotime($reservationRow->reservation_check_in)).', '.date("d F Y",strtotime($reservationRow->reservation_check_in)).', 3:00 PM'.'"
                },{
                    "customfield_id": "2991628000000132178",
                    "value": "'.date('D', strtotime($reservationRow->reservation_check_out)).', '.date("d F Y",strtotime($reservationRow->reservation_check_out)).', 3:00 PM'.'"
                },{
                    "customfield_id": "2991628000000132182",
                    "value": "'.date_diff(date_create($reservationRow->reservation_check_in),date_create($reservationRow->reservation_check_out))->format("%a").'"
                },{
                    "customfield_id": "2991628000000132186",
                    "value": "'.$totalPerson.'"
                },{
                    "customfield_id": "2991628000000132196",
                    "value": "'.$totalReservationRentalPrice.'"
                },{
                    "customfield_id": "2991628000000132200",
                    "value": "'.$totalService.'"
                },{
                    "customfield_id": "2991628000000132204",
                    "value": "'.$damage_deposit.'"
                },{
                    "customfield_id": "2991628000000132208",
                    "value": "'.$total_price.'"
                },{
                    "customfield_id": "2991628000000441001",
                    "value": "'.$reservation_service_Amount.'"
                },{
                    "customfield_id": "2991628000000441005",
                    "value": "'.$reservation_city_tax_Amount.'"
                },{
                    "customfield_id": "2991628000000441009",
                    "value": "'.$reservation_cleaning_Amount.'"
                }
            ],
            "line_items": [';
            $booksInvoiceExtraBody.='{';
            
              if($propertyId!=""){
                $booksInvoiceExtraBody.='"item_id": "'.$propertyId.'",';
              }
                $booksInvoiceExtraBody.='"name": "'.trim(preg_replace('/\s\s+/', ' ',str_replace('"',"'",$ProductName))).'",
                "description": "1 Bedroom, 1 Bathroom, Sleeps 1 - 4, 60 m",
                "rate": '.$totalReservationRentalPrice.',
                "quantity": 1
            },';
            if($reservation_service_Amount !=''){
               $booksInvoiceExtraBody.=' {
                "item_id": "2991628000000088100",
                "rate": '.$reservation_service_Amount.',
                "quantity": 1
                },';
            }
            if($reservation_city_tax_Amount !=''){
               $booksInvoiceExtraBody.=' {
                "item_id": "2991628000000088051",
                "rate": '.$reservation_city_tax_Amount.',
                "quantity": 1
                },';
            }
            if($reservation_cleaning_Amount !=''){
               $booksInvoiceExtraBody.=' {
                "item_id": "2991628000000088109",
                "rate": '.$reservation_cleaning_Amount.',
                "quantity": 1
                },';
            }
            $booksInvoiceBody.=rtrim($booksInvoiceExtraBody, ',');
            $booksInvoiceBody.='],
            "allow_partial_payments": true,
            "notes": "'.$description_en.'",
            "terms": "Terms & Conditions apply",
            "shipping_charge": 0
        }';

            // echo "<pre>";
            // print_r($booksInvoiceBody);
            Log::info($booksInvoiceBody);
			//dd(1);

            $InvoiceCreateResponse = json_decode($this->CreateInvoiceInBooks($token,$booksInvoiceBody),true);

            Log::info($InvoiceCreateResponse);

            if (isset($InvoiceCreateResponse['invoice']['invoice_id'])) {
                Log::info("Invoice Created Successfully");
                //Log::info($InvoiceCreateResponse['invoice']['invoice_id']);
                $reservationRow->update([
                    'sync_status' => 2,
                    'books_invoice_id' => $InvoiceCreateResponse['invoice']['invoice_id']
                ]);
            }
        }
        Log::info("Sync Invoice In Books ended.");
    }
    //here we have update Invoice functionality
    //here we have update Invoice functionality
    function UpdateInvoiceInBooks($token)
    {
    
        Log::info("Update Invoice In Books started.");
        // here we have to send updated record id. we need to update invoice in zoho books.

        $updateReservationSql = Reservations::where('reservation_updated',"1")->get();
        foreach ($updateReservationSql as $reservationRow) {

            //Log::info($reservationRow);
            //here we have to search customer or add customer logic
            // first customer search if not found then create customer.
            $contact_id = "";
            $customerRes = "";
            $customerName='';
            $customerName=$reservationRow->contact_first_name.' '.$reservationRow->contact_last_name;
            $customerRes = $this->SearchCustomerInBooks($token,$customerName);
            //Log::info($customerRes);
            //here we have check customer exist or not. if not exist then it will got else part`Create customer`
            if (isset($customerRes['contacts'][0]['contact_id'])) {
                $contact_id = $customerRes['contacts'][0]['contact_id'];
            } else {
                $customerRes="";
                $customerRes=$this->CreateCustomer($token,$reservationRow);
                if (isset($customerRes['contact']['contact_id'])) {
                    $contact_id = $customerRes['contact']['contact_id'];
                }
            }
            $ProductName="";
            $description_fr="";
            $description_en="";
            $damage_deposit=0;
            $propertyId="";
            //here we will get products information from properties table.
            $productRow = $reservationRow->propertyInfo;
            $ProductName = $productRow->name;
            if ($productRow->description_fr != "") {
                $description_fr = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$productRow->description_fr);
            }
            if ($productRow->description_en != "") {
                $description_en = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$productRow->description_en);
            }
            if ($productRow->books_item_id != "") {
                $propertyId = $productRow->books_item_id;
            }
            
            //$PropertyRes=$this->SearchPropertyInBooks($token,$productRow->name);
            //Log::info($PropertyRes);
            //if(isset($PropertyRes['items']['item_id'])){
            //    $propertyId=$PropertyRes['items']['item_id'];
            //}

            $discount=0;
            if($reservationRow->discount_amount !=''){
                 $discount=$reservationRow->discount_amount;
            }
           
			$total_price=0;
			if($reservationRow->total_price !=''){
				 $total_price=$reservationRow->total_price;
			}
            
            $beforeInvoiceDate ='';
            if ($reservationRow->reservation_time != '') {
                $beforeInvoiceDate = str_replace('/','-',current(explode(" ",$reservationRow->reservation_time)));
            }else{
                $beforeInvoiceDate = date('Y-m-d');
            }

            $reservation_adults=0;
            $reservation_children=0;
            $totalPerson=0;
            if($reservationRow->reservation_adults!=''){
                $reservation_adults=$reservationRow->reservation_adults;
            }
            if($reservationRow->reservation_children!=''){
                $reservation_children=$reservationRow->reservation_children;
            }
            $totalPerson=$reservation_adults+$reservation_children;

            $total_price=0;
            if($reservationRow->total_price !=''){
                 $total_price=$reservationRow->total_price;
            }

            
            
            //here we are calculating reservation service price,cleaning fee & city tax
            $tempCityTax=0;
            if ($productRow->city_tax != "") {
                $tempCityTax = $productRow->city_tax;
            }
            $tempServiceTax=0;
            if ($productRow->service_charge != "") {
                $tempServiceTax = $productRow->service_charge;
            }
            $tempCleaningFee=0;
            if ($productRow->cleaning_fee != "") {
                $tempCleaningFee = $productRow->cleaning_fee;
            }
            
            $reservation_city_tax_Amount=0;
            $reservation_cleaning_Amount=0;
            $reservation_service_Amount=0;
            $reservation_rentalprice_Amount=0;
            $reservation_rentalprice_Amount=
            
            $reservation_rentalprice_Amount=($total_price-$tempCleaningFee)/(1+$tempServiceTax/100+$tempCityTax/100);
            $reservation_city_tax_Amount=$reservation_rentalprice_Amount*($tempCityTax/100);
            $reservation_service_Amount=$reservation_rentalprice_Amount*($tempServiceTax/100);
            $reservation_cleaning_Amount=$tempCleaningFee;
            $totalService=$reservation_city_tax_Amount+$reservation_cleaning_Amount+$reservation_service_Amount;
            
            $totalReservationRentalPrice=$reservation_rentalprice_Amount+$discount;
            $selectVendorNameSql = User::where('id',$productRow->owner)->limit(1)->get();
            //Log::info($selectVendorNameSql);
            $vendorName="";
            foreach ($selectVendorNameSql as $vendorRowData) {
                $vendorName=$vendorRowData->first_name.' '.$vendorRowData->last_name;
            }

             //Add Invoice customer in books
        $UpdateInvoiceBody='';
        $UpdateInvoiceExactBody='';
        $UpdateInvoiceBody.='{
            "customer_id": "'.$contact_id.'",
            "invoice_number": "Reservation #'.$reservationRow->id.' from -'.$reservationRow->service.'",
            "payment_terms": 15,
            "payment_terms_label": "Net 15",
            "due_date": "'.date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days')) .'",
            "salesperson_name": "'.$vendorName.'",
            "adjustment": '. -1*$discount.',
            "adjustment_description": "Discount Amount",
            "custom_fields": [
                {
                    "customfield_id": "2991628000000132174",
                    "value": "'.date('D', strtotime($reservationRow->reservation_check_in)).', '.date("d F Y",strtotime($reservationRow->reservation_check_in)).', 3:00 PM'.'"
                },{
                    "customfield_id": "2991628000000132178",
                    "value": "'.date('D', strtotime($reservationRow->reservation_check_out)).', '.date("d F Y",strtotime($reservationRow->reservation_check_out)).', 3:00 PM'.'"
                },{
                    "customfield_id": "2991628000000132182",
                    "value": "'.date_diff(date_create($reservationRow->reservation_check_in),date_create($reservationRow->reservation_check_out))->format("%a").'"
                },{
                    "customfield_id": "2991628000000132186",
                    "value": "'.$totalPerson.'"
                },{
                    "customfield_id": "2991628000000132196",
                    "value": "'.$totalReservationRentalPrice.'"
                },{
                    "customfield_id": "2991628000000132200",
                    "value": "'.$totalService.'"
                },{
                    "customfield_id": "2991628000000132204",
                    "value": "'.$damage_deposit.'"
                },{
                    "customfield_id": "2991628000000132208",
                    "value": "'.$total_price.'"
                },{
                    "customfield_id": "2991628000000441001",
                    "value": "'.$reservation_service_Amount.'"
                },{
                    "customfield_id": "2991628000000441005",
                    "value": "'.$reservation_city_tax_Amount.'"
                },{
                    "customfield_id": "2991628000000441009",
                    "value": "'.$reservation_cleaning_Amount.'"
                }
            ],
            "line_items": [';
            $UpdateInvoiceExactBody.='{';
              if($propertyId!=""){
                $UpdateInvoiceExactBody.='"item_id": "'.$propertyId.'",';
              }
                $UpdateInvoiceExactBody.='"name": "'.$ProductName.'",
                "description": "1 Bedroom, 1 Bathroom, Sleeps 1 - 4, 60 m",
                "rate": '.$totalReservationRentalPrice.',
                "quantity": 1
            },';
            if($reservation_service_Amount !=''){
               $UpdateInvoiceExactBody.=' {
                "item_id": "2991628000000088100",
                "rate": '.$reservation_service_Amount.',
                "quantity": 1
                },';
            }
            if($reservation_city_tax_Amount !=''){
               $UpdateInvoiceExactBody.=' {
                "item_id": "2991628000000088051",
                "rate": '.$reservation_city_tax_Amount.',
                "quantity": 1
                },';
            }
            if($reservation_cleaning_Amount !=''){
               $UpdateInvoiceExactBody.=' {
                "item_id": "2991628000000088109",
                "rate": '.$reservation_cleaning_Amount.',
                "quantity": 1
                },';
            }
            $UpdateInvoiceBody.=rtrim($UpdateInvoiceExactBody, ',');
            $UpdateInvoiceBody.='],
            "allow_partial_payments": true,
            "notes": "'.$description_en.'",
            "terms": "Terms & Conditions apply",
            "shipping_charge": 0
        }';


            Log::info($UpdateInvoiceBody);
            //here we are sending put request to update invoice in zoho books
            //Log::info($reservationRow->books_invoice_id);

        $InvoiceId=$reservationRow->books_invoice_id;
        if($InvoiceId!=""){
            $InvoiceUpdateResponse = json_decode($this->UpdateInvoiceInBooksZoho($token,$UpdateInvoiceBody,$InvoiceId),true);
            //Log::info($UpdateInvoiceBody);
            if (isset($InvoiceUpdateResponse['invoice']['invoice_id'])) {
                Log::info("Invoice Updated Successfully");
                $reservationRow->update([
                    'reservation_updated' => 0,
                    'books_invoice_id' => $InvoiceUpdateResponse['invoice']['invoice_id']
                ]);
            }
        }
        }
        Log::info("Update Invoice In Books ended.");
    }

     //here we are deleting invoice from zoho books.
     function DeleteInvoiceInBooks($token)
    {
        Log::info("Delete Invoice In Books started.");
        // here we have to send updated record id. we need to update invoice in zoho books.

        $deleteResSql = Reservations::onlyTrashed()->get();
        //Log::info($deleteResSql);

        foreach ($deleteResSql as $resRow) {

            //Log::info($resRow);

            if($resRow->books_invoice_id && $resRow->books_invoice_id != ""){

                $DeleteInvoiceUrl="https://books.zoho.com/api/v3/invoices/".$resRow->books_invoice_id."?organization_id=".env('ZOHO_ORG_ID');
                //Log::info($DeleteInvoiceUrl);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $DeleteInvoiceUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,//123145727942734
                    CURLOPT_SSL_VERIFYPEER=> FALSE,
                    CURLOPT_SSL_VERIFYHOST=> FALSE,
                    CURLOPT_TIMEOUT => 500,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: ".$token,
                )));
                $deleteInvoiceRes=curl_exec($curl);
                //Log::info($deleteInvoiceRes);
                if (isset($deleteInvoiceRes['message'])) {
                    Log::info("Invoice Deleted Successfully");
                    $resRow->update([
                        'delete_message' => 'The invoice has been deleted.'
                    ]);
                }
            }
        }
        Log::info("Delete Invoice In Books ended.");
    }

    function GenrateZohoAccessToken()
    {
        $current_time = date("Y-m-d H:i:s");
		$getTokenSqlResData = ZohoAccess::find(1);

        //     print_r($current_time);
        //    echo "</br>";
        //    print_r($getTokenSqlResData['expiry_time']);

        if (strtotime($current_time) < strtotime($getTokenSqlResData['expiry_time'])) {
            // print_r($getTokenSqlResData);
            return $token = "Zoho-oauthtoken".' '.$getTokenSqlResData['access_token'];
        } else {
            return $this->callForAccessToken();
        }
	}

	// this funtion will genrate new acces token and store into database
	function callForAccessToken()
    {
  	    $current_time = date("Y-m-d H:i:s");
        $getTokenData = ZohoAccess::find(1);

        $url="https://accounts.zoho.com/oauth/v2/token?refresh_token=".$getTokenData['refresh_token']."&grant_type=".$getTokenData['grant_type']."&client_id=".$getTokenData['client_id']."&client_secret=".$getTokenData['client_secret'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $curlCallResponse = curl_exec($ch);
        $oauth2RefreshTokenResponse = json_decode($curlCallResponse,true);
        
        if (isset($oauth2RefreshTokenResponse['access_token'])) {
            $getTokenData->update([
                'access_token' => $oauth2RefreshTokenResponse['access_token'],
                'created_time' => $current_time,
                'expiry_time' => date("Y-m-d H:i:s",strtotime('+1 hour',strtotime($current_time)))
            ]);
            return "Zoho-oauthtoken".' '.$oauth2RefreshTokenResponse['access_token'];
        }else{
            return "Zoho-oauthtoken";
        }
    }

    //if customer id not found create customer in zoho book
    function CreateCustomer($token,$contactData)
    {
        $FirstName = '';
        $LastName = '';
        $FirstName = $contactData['contact_first_name'];
        $LastName = $contactData['contact_last_name'];
        $contact_nameF=$FirstName.' '.$LastName;
        //if las name not availbale assign first name
        if ($LastName == '') {
            $LastName = $contactData['contact_first_name'];
            $FirstName = '';
            $contact_nameF=$LastName;
        }
        
        if($contactData['contact_first_name'] !="" || $contactData['contact_last_name']!=""){
        //json data for post on zoho book
        $customer_body = '{
            "contact_name":"'.$contact_nameF.'",
            "contact_type":"customer",
            "contact_persons": [
                {
                    "salutation": "Mr",
                    "first_name":"'.$FirstName.'",
                    "last_name":"'.$LastName.'",
                    "email":"'.$contactData['contact_email'].'",
                    "phone": "'.$contactData['contact_phone'].'"
                }
            ],
            "billing_address":{
                "attention":"'.$FirstName.'",
                "address":"'.$contactData['contact_address'].'",
                "city":"'.$contactData['contact_city'].'",
                "state":"'.$contactData['contact_state'].'",
                "zip":"'.$contactData['contact_zip'].'",
                "country":"'.$contactData['contact_country'].'",
                "phone":"'.$contactData['contact_phone'].'"
            },
            "shipping_address":{
                "attention":"'.$FirstName.'",
                "address":"'.$contactData['contact_address'].'",
                "city":"'.$contactData['contact_city'].'",
                "state":"'.$contactData['contact_state'].'",
                "zip":"'.$contactData['contact_zip'].'",
                "country":"'.$contactData['contact_country'].'",
                "phone":"'.$contactData['contact_phone'].'"
            }
        }';

        //Log::info($customer_body);
        //zoho book api call
        $customerResponce = json_decode($this->AddCustomerInBooks($token,$customer_body),true);
        //Log::info($customerResponce);
        return $customerResponce;
        }else{
          return "";
        }
    }


	//if customer id not found create customer in zoho book
    function CreateVendor($token)
    {
		$selectVendorSql = User::whereNull('vendor_id')->limit(20)->get();
         //Log::info($selectVendorSql);
		foreach ($selectVendorSql as $vendorRow) {

            // $vendorPropertyName = $vendorRow->userInfo;  // Please check this line code. We need property name using user Id
            // $PropertyName = $vendorPropertyName->name;
            $PropertyName ='';
            $selectPropertySql = Properties::where('owner',$vendorRow->id)->get();
			foreach ($selectPropertySql as $propertyRow) {
				$PropertyName.=$propertyRow->name.',';
			}
        $vendorFirstName = '';
        $vendorLastName = '';
        $vendorFirstName = $vendorRow->first_name;
        $vendorLastName = preg_replace('/[^A-Za-z0-9\- ]/', '',$vendorRow->last_name);

        //if las name not availbale assign first name
        if ($vendorLastName == '') {
            $vendorLastName = preg_replace('/[^A-Za-z0-9\- ]/', '',$vendorRow->first_name);
            $vendorFirstName = '';
        }
        //json data for post on zoho book
        $vendor_body = '{
            "contact_name":"'.$vendorFirstName.' '.$vendorLastName.'",
            "contact_type":"vendor",
            "custom_fields": [
				{
					"customfield_id": "2991628000000185023",
					"index": 1,
					"value": "'.rtrim(str_replace('"',"'",$PropertyName),",").'"
				}
			],
            "contact_persons": [
                {
                    "salutation": "Mr",
                    "first_name":"'.$vendorFirstName.'",
                    "last_name":"'.$vendorLastName.'",
                    "email":"'.$vendorRow->email.'",
                    "phone": "'.$vendorRow->phone.'"
                }
            ],
            "billing_address":{
                "attention":"'.$vendorFirstName.'",
                "address":"'.$vendorRow->zip.'",
                "city":"'.$vendorRow->city.'",
                "state":"'.$vendorRow->state.'",
                "zip":"'.$vendorRow->zip.'",
                "country":"'.$vendorRow->country.'",
                "phone":"'.$vendorRow->contact_phone.'"
            },
            "shipping_address":{
                "attention":"'.$vendorFirstName.'",
                "address":"'.$vendorRow->zip.'",
                "city":"'.$vendorRow->city.'",
                "state":"'.$vendorRow->state.'",
                "zip":"'.$vendorRow->zip.'",
                "country":"'.$vendorRow->country.'",
                "phone":"'.$vendorRow->contact_phone.'"
            }
        }';

        //Log::info($vendor_body);

        $vendorResponce = json_decode($this->AddCustomerInBooks($token,$vendor_body),true);
        //Log::info($vendorResponce);

        if (isset($vendorResponce['contact']['contact_id'])) {
                // Log::info($vendorResponce['contact']['contact_id']);
                $vendorRow->update([
                    'vendor_id' => $vendorResponce['contact']['contact_id']
                ]);
            }else{
				$vendorRow->update([
                    'vendor_id' => '1'
                ]);
			}
        }
    }

	//if customer id not found create customer in zoho book
    function CreateProperty($token)
    {
		//$selectPropertySql = Properties::whereNull('books_item_id')->get();
		$selectPropertySql = Properties::whereNull('books_item_id')->limit(40)->get();
		//Log::info($selectPropertySql);
		foreach ($selectPropertySql as $propertyRow) {

		$minimumRate=0;
		if($propertyRow->minimum_rate !=''){
			$minimumRate=$propertyRow->minimum_rate;
		}
        //json data for property post on zoho book
        $property_body ='{
			"name": "'.str_replace('"','',$propertyRow->name).'",
			"rate": '.$propertyRow->base_rate.',
			"description": "'.preg_replace('/[^A-Za-z0-9\- ]/', '',$propertyRow->description_en).'",
			"product_type": "service",
			"account_id": "2991628000000000388",
			"item_type": "sales_and_purchases",
			"purchase_rate": '.$minimumRate.',
			"purchase_account_id": "2991628000000034003",

			"custom_fields": [{
					"customfield_id": "2991628000000132260",
					"index": 1,
					"label": "Rental Type",
					"value": "'.$propertyRow->rental_type.'"
				}, {
					"customfield_id": "2991628000000132092",
					"index": 2,
					"label": "Residency Category",
					"value": "'.$propertyRow->residency_category.'"
				}, {
					"customfield_id": "2991628000000132098",
					"index": 3,
					"label": "Sleeps",
					"value": "'.$propertyRow->sleeps.'"
				}, {
					"customfield_id": "2991628000000132104",
					"index": 4,
					"label": "Sleep Max",
					"value": "'.$propertyRow->sleeps_max.'"
				}, {
					"customfield_id": "2991628000000132110",
					"index": 5,
					"label": "Area",
					"value": "'.$propertyRow->area.'"
				}, {
					"customfield_id": "2991628000000132122",
					"index": 6,
					"label": "Headline",
					"value": "'.preg_replace('/[^A-Za-z0-9\- ]/', '',$propertyRow->headline_en).'"
				}, {
					"customfield_id": "2991628000000132128",
					"index": 7,
					"label": "Summary",
					"value": "'.preg_replace('/[^A-Za-z0-9\- ]/', '',substr($propertyRow->summary_en,0,240)).'"
				}, {
					"customfield_id": "2991628000000132116",
					"index": 9,
					"label": "Floor Number",
					"value": "'.$propertyRow->floor_number.'"
				}, {
					"customfield_id": "2991628000000132292",
					"index": 10,
					"label": "Country",
					"value": "'.$propertyRow->country.'"
				}, {
					"customfield_id": "2991628000000132298",
					"index": 11,
					"label": "State",
					"value": "'.$propertyRow->state.'"
				}, {
					"customfield_id": "2991628000000132304",
					"index": 12,
					"label": "City",
					"value": "'.$propertyRow->city.'"
				}, {
					"customfield_id": "2991628000000132310",
					"index": 13,
					"label": "Zip",
					"value": "'.$propertyRow->zip.'"
				}, {
					"customfield_id": "2991628000000132316",
					"index": 14,
					"label": "Address",
					"value": "'.$propertyRow->address.'"
				}, {
					"customfield_id": "2991628000000132328",
					"index": 16,
					"label": "Base Rate Kind",
					"value": "'.$propertyRow->base_rate_kind.'"
				}, {
					"customfield_id": "2991628000000132334",
					"index": 17,
					"label": "Minimum stay",
					"value": "'.$propertyRow->min_stay_base.'"
				}, {
					"customfield_id": "2991628000000132340",
					"index": 18,
					"label": "Minimum bedrooms",
					"value": "'.$propertyRow->min_bed.'"
				},{
					"customfield_id": "2991628000000132352",
					"index": 20,
					"label": "Downpayment (%)",
					"value": "'.$propertyRow->downpayment.'"
				}, {
					"customfield_id": "2991628000000132358",
					"index": 21,
					"label": "Damage Deposit",
					"value": "'.$propertyRow->damage_deposit.'"
				}, {
					"customfield_id": "2991628000000132370",
					"index": 22,
					"label": "Service Charge (%)",
					"value": "'.$propertyRow->service_charge.'"
				}, {
					"customfield_id": "2991628000000132380",
					"index": 23,
					"label": "City Tax (%)",
					"value": "'.$propertyRow->city_tax.'"
				}, {
					"customfield_id": "2991628000000132386",
					"index": 24,
					"label": "Cleaning fee",
					"value": "'.$propertyRow->cleaning_fee.'"
				}]
		}';

        //Log::info($property_body);
        $PrpertyResponce = json_decode($this->AddPropertyInBooks($token,$property_body),true);
        //Log::info($PrpertyResponce);
		//Properties::where("id", '5')->update(["books_item_id" => 'NULL']);
        if (isset($PrpertyResponce['item']['item_id'])) {
                Log::info($PrpertyResponce['item']['item_id']);
                $propertyRow->update([
                    'books_item_id' => $PrpertyResponce['item']['item_id']
                ]);
            }
        }
    }

    function SearchCustomerInBooks($token,$contactFirstName)
    {
        $contactUrlZoho ="https://books.zoho.com/api/v3/contacts?organization_id=".env('ZOHO_ORG_ID')."&contact_name_contains=".urlencode($contactFirstName);
        // Log::info($contactUrlZoho);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$contactUrlZoho);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array(
            "Authorization: ".$token,
            "Accept: application/json",
            "Content-Type: application/json"
        ));
        return json_decode(curl_exec($ch),true);
    }
	function SearchPropertyInBooks($token,$propertyName){

		$propertyUrl ="https://books.zoho.com/api/v3/items?organization_id=".env('ZOHO_ORG_ID')."&name_contains=".urlencode(str_replace('"','',$propertyName));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$propertyUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch,CURLOPT_HTTPHEADER, array(
			"Authorization: ".$token,
			"Accept: application/json",
			"Content-Type: application/json"
		));
		return json_decode(curl_exec($ch),true);

	}

    function AddCustomerInBooks($token,$contactBody)
    {
        $requestType='POST';
        $contactUrlZoho ="https://books.zoho.com/api/v3/contacts?organization_id=".env('ZOHO_ORG_ID');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$contactUrlZoho);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contactBody);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array(
            "Authorization: ".$token,
            "Accept: application/json",
            "Content-Type: application/json"
        ));
        return curl_exec($ch);
    }
	function AddPropertyInBooks($token,$propertyBody)
    {
        $requestType='POST';
        $propertyUrlZoho ="https://books.zoho.com/api/v3/items?organization_id=".env('ZOHO_ORG_ID');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$propertyUrlZoho);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $propertyBody);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array(
            "Authorization: ".$token,
            "Accept: application/json",
            "Content-Type: application/json"
        ));
        return curl_exec($ch);
    }

    function CreateInvoiceInBooks($token,$booksInvoiceBody)
    {
        $invoice_url="https://books.zoho.com/api/v3/invoices?organization_id=".env('ZOHO_ORG_ID');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $invoice_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,//123145727942734
            CURLOPT_SSL_VERIFYPEER=> FALSE,
            CURLOPT_SSL_VERIFYHOST=> FALSE,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $booksInvoiceBody,
            CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: ".$token,
        )));

        return curl_exec($curl);
    }

    function UpdateInvoiceInBooksZoho($token,$UpdateInvoiceBody,$InvoiceId)
    {
        $updateInvoiceUrl="https://books.zoho.com/api/v3/invoices/".$InvoiceId."?organization_id=".env('ZOHO_ORG_ID');
        //Log::info($updateInvoiceUrl);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $updateInvoiceUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,//123145727942734
            CURLOPT_SSL_VERIFYPEER=> FALSE,
            CURLOPT_SSL_VERIFYHOST=> FALSE,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $UpdateInvoiceBody,
            CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: ".$token,
        )));

        return curl_exec($curl);
    }
}
