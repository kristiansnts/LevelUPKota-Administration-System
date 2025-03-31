<?php

namespace App\Filament\Resources\Mail\MailsResource\Form;

use App\Filament\Shared\Services\ModelQueryService;
use Filament\Forms;
use App\Filament\Resources\Mail\MailsCategoryResource\Form\MailCategoryCreateForm;
use App\Filament\Resources\Mail\MailsResource\Shared\UseCases\CreateOptionUseCase;

class MailInfoForm
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('mail_date')
                ->label('Tanggal Surat')
                ->native(false)
                ->required(),
            Forms\Components\Select::make('mail_category_id')
                ->label('Kategori Surat')
                ->searchable()
                ->preload()
                ->options(fn (): array => ModelQueryService::getMailCategoryOptions())
                ->createOptionForm(MailCategoryCreateForm::getFormSchema())
                ->createOptionUsing(function (array $data): int {
                    /** @var array<string, mixed> $data */
                    /** @var \App\Filament\Resources\Mail\MailsResource\Shared\UseCases\CreateOptionUseCase $useCase */
                    $useCase = app(CreateOptionUseCase::class);

                    return $useCase->createMailCategoryUser($data);
                })
                ->required(),
            Forms\Components\TextInput::make('sender_name')
                ->label('Pengirim')
                ->required(),
            Forms\Components\TagsInput::make('receiver_name')
                ->label('Penerima')
                ->separator(',')
                ->splitKeys(['Enter', 'Tab'])
                ->placeholder('Masukkan penerima')
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label('Keterangan')
                ->required(),
        ];
    }
}
