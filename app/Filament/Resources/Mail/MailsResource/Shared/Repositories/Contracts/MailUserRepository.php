<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts;

use App\Models\MailUser;

interface MailUserRepository
{
    public function getMailUserByMailId(int $mailId): ?MailUser;

    public function getMailUserByAdddressIdAndMailId(?int $cityId, ?int $districtId, int $mailId): ?MailUser;

    public function getTotalMailCountByAddressId(?int $cityId, ?int $districtId): int;
}
