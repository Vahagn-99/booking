<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Reservations;
use Auth;
use PDF;
use Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddContractMail;
use App\Mail\UpdateContractMail;
use App\Mail\AddClosedDatesMail;

class SendContract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $r;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservations $r, $type)
    {
        $this->r = $r;
        $this->type = $type;

        Log::info("Send Contract started.");

        $user = Auth::user();
        $toEmail = config('mail.to_email_reservation');
        $propertyname = $this->r->propertyInfo->name;

        $filename = $propertyname.'-'.$this->r->id;
        $owner = User::find(1);
        if ($r->propertyInfo->ownerInfo) {
            $owner = $r->propertyInfo->ownerInfo;
        }
        try {
            $pdf = PDF::loadView('admin.pdf.contract-pdf',['reservation' => $this->r, 'owner' => $owner]);
            $pdf->save('contracts/'.$filename.'.pdf');
            $contract = 'contracts/'.$filename.'.pdf';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        if (env('APP_ENV') == 'prod') {
            if ($this->r->reservation_type == 'block' && $this->type == 'add') {
                try {
                    Mail::to($toEmail)->send(new AddClosedDatesMail($this->r,$user));
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            } else {
                try {
                    if ($this->type == 'add') {
                        Mail::to($toEmail)->send(new AddContractMail($this->r,$user,$contract,'Booking FWI','admin'));
                    } else {
                        Mail::to($toEmail)->send(new UpdateContractMail($this->r,$user,$contract,'Booking FWI','admin'));
                    }
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
            // To property owner
            if ($r->propertyInfo->ownerInfo) {
                $toEmail = $r->propertyInfo->ownerInfo->email;
                try {
                    if ($this->type == 'add') {
                        Mail::to($toEmail)->send(new AddContractMail($this->r,$user,$contract,$r->propertyInfo->ownerInfo->fullName(),'owner'));
                    } else {
                        Mail::to($toEmail)->send(new UpdateContractMail($this->r,$user,$contract,$r->propertyInfo->ownerInfo->fullName(),'owner'));
                    }
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
        }


        if ($r->contact_email && $r->contact_email != "") {
            $toEmail = $r->contact_email;
            try {
                if ($this->type == 'add') {
                    Mail::to($toEmail)->send(new AddContractMail($this->r,$user,$contract,$r->contact_name,'visitor'));
                } else {
                    Mail::to($toEmail)->send(new UpdateContractMail($this->r,$user,$contract,$r->contact_name,'visitor'));
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }

        Log::info("Send Contract ended.");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
