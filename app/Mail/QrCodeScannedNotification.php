<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Volunteer;

class QrCodeScannedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $volunteer;

    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    public function build()
    {
        return $this->subject('QR Code Scanned Again')
                    ->view('emails.qr_code_scanned_notification')
                    ->with([
                        'volunteerName' => $this->volunteer->name,
                        'volunteerId' => $this->volunteer->id,
                    ]);
    }
}
