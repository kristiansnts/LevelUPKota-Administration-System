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

    /**
     * @param  array<string, mixed>  $mailData
     */
    public function generateMailCode(array $mailData): string
    {
        /**
         * @var string $date
         */
        $date = $mailData['mail_date'];

        /**
         * @var int|false $timestamp
         */
        $timestamp = strtotime($date);

        if ($timestamp === false) {
            throw new \InvalidArgumentException('Invalid mail date provided');
        }

        /**
         * @var int $month
         */
        $month = (int) date('n', $timestamp);

        /**
         * @var string $romanMonth
         */
        $romanMonth = RomanMonthEnum::fromNumber($month)->getLabel();

        /**
         * @var string $year
         */
        $year = date('y', $timestamp);

        /**
         * @var int $categoryId
         */
        $categoryId = $mailData['mail_category_id'];

        /**
         * @var string $cityMailLabel
         */
        $cityMailLabel = LabelHelper::cityMailLabel();

        /**
         * @var string $categoryName
         */
        $categoryName = $this->mailCategoryRepository->getMailCategoryNameById($categoryId);

        /**
         * @var string $mailNumber
         */
        $mailNumber = $this->generateMailNumber($categoryId);

        return sprintf(
            '%s/%s/%s/%s-%s',
            $mailNumber,
            $cityMailLabel,
            $categoryName,
            $romanMonth,
            $year
        );
    }

    private function generateMailNumber(int $id): string
    {
        /**
         * @var int|null $count
         */
        $count = $this->mailCategoryRepository->getMailCategoryCountByCategoryId($id);

        if (is_null($count)) {
            return '001';
        }

        /**
         * @var string $countToStr
         */
        $countToStr = (string) $count;

        /**
         * @var string $mailNumber
         */
        $mailNumber = str_pad($countToStr, 3, '0', STR_PAD_LEFT);

        $this->mailCategoryRepository->setMailCategoryCountByCategoryId($id);

        return $mailNumber;
    }
}
