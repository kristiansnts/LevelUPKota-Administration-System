<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Services;

use App\Filament\Shared\Services\ModelQueryService;
use App\Models\Mail;
use App\Models\MailUser;
use App\Models\User;

class CreatePivotMailService
{
    private readonly User $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = ModelQueryService::getUserModel();
    }

    /**
     * Create a new instance of the service
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Create a mail user pivot
     */
    public function createMailUserPivot(Mail $mail)
    {
        MailUser::create([
            'mail_id' => $mail->id,
            'city_id' => $this->user->city_id,
            'district_id' => $this->user->district_id ?? null,
        ]);
    }
}
