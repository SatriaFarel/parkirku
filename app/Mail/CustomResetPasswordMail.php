<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class CustomResetPasswordMail extends Mailable
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Reset Password Anda')
            ->markdown('email')
            ->with(['url' => $this->url]);
    }
}
