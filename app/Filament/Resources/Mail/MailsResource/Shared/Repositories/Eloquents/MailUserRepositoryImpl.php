<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents;

use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts\MailUserRepository;
use App\Models\MailUser;
use App\Enums\MailTypeEnum;

class MailUserRepositoryImpl implements MailUserRepository
{
    public function getMailUserByMailId(int $mailId): ?MailUser
    {
        return MailUser::where('mail_id', $mailId)->first();
    }

    public function getMailUserByAdddressIdAndMailId(?int $cityId, ?int $districtId, int $mailId): ?MailUser
    {
        return MailUser::where('city_id', $cityId)->where('district_id', $districtId)->where('mail_id', $mailId)->first();
    }

    public function getTotalMailCountByAddressId(?int $cityId, ?int $districtId): int
    {
        $totalMailCount = MailUser::where('city_id', $cityId)
            ->where('district_id', $districtId)
            ->whereHas('mail', function($query) {
                $query->where('type', MailTypeEnum::OUT->value);
            })
            ->count();

        return $totalMailCount + 1;
    }
}
