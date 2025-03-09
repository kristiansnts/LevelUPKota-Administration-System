<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Services;

use App\Enums\RomanMonthEnum;
use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents\MailCategoryRepositoryImpl;
use App\Helpers\LabelHelper;

class MailCategoryService
{
    public function __construct(
        private readonly MailCategoryRepositoryImpl $mailCategoryRepository
    ) {}

    private function generateMailNumber(int $id): string
    {
        $count = $this->mailCategoryRepository->getMailCategoryCountById($id);
        if ($count === null) {
            return 1;
        }
        $mailNumber = str_pad($count, 3, '0', STR_PAD_LEFT);
        dump($mailNumber);

        return $mailNumber;
    }

    public function generateMailCode(array $mailData): string
    {
        $date = strtotime($mailData['mail_date']);
        $month = RomanMonthEnum::fromNumber(date('n', $date))->getLabel();
        $year = date('y', $date);
        $categoryId = $mailData['mail_category_id'];
        $cityMailLabel = LabelHelper::cityMailLabel();
        $categoryName = $this->mailCategoryRepository->getMailCategoryNameById($categoryId);

        $mailNumber = $this->generateMailNumber($categoryId);

        return sprintf(
            '%s/%s/%s/%s-%s',
            $mailNumber,
            $cityMailLabel,
            $categoryName,
            $month,
            $year
        );
    }
}
