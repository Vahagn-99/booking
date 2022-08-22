<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservations;

class AddContractMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $user;
    protected $to_name;
    protected $type;
    protected $contract;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservations $reservation, $user, $contract, $to_name, $type)
    {
        $this->reservation = $reservation;
        $this->user = $user;
        $this->type = $type;
        $this->to_name = $to_name;
        $this->contract = $contract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type == 'owner') {
            return $this->markdown('vendor.mail.text.add-contract-email')
                ->subject('New reservation')
                ->with([
                    'user' => $this->user,
                    'to_name' => $this->to_name,
                    'reservation' => $this->reservation,
                    'type' => $this->type
                ]);
        }
        return $this->markdown('vendor.mail.text.add-contract-email')
            ->subject('New reservation')
            ->with([
                'user' => $this->user,
                'to_name' => $this->to_name,
                'reservation' => $this->reservation,
                'type' => $this->type
            ])
            ->attach($this->contract, [
                'mime' => 'application/pdf',
            ]);
    }
}

?>
