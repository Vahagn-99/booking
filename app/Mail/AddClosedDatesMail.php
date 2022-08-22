<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Reservations;

class AddClosedDatesMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;
    protected $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservations $reservation, $user)
    {
        $this->reservation = $reservation;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('vendor.mail.text.block-dates-mail')
            ->subject('New blocking dates')
            ->with([
                'user' => $this->user,
                'reservation' => $this->reservation
            ]);
    }
}

?>
