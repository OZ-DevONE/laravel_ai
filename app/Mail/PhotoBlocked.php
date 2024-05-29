<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Photo;

class PhotoBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public $photo;

    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    public function build()
    {
        return $this->view('emails.photo_blocked')
                    ->subject('Ваше фото было заблокировано')
                    ->with([
                        'photo' => $this->photo,
                    ]);
    }
}
