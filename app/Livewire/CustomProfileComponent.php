<?php

namespace App\Livewire;

use App\Filament\Shared\UseCases\UserPasswordUseCase;
use App\Helpers\NotificationHelper;
use App\Helpers\RolesHelper;
use App\Livewire\Components\AddressForm;
use App\Livewire\Components\PersonalInfoForm;
use App\Livewire\Components\RoleForm;
use App\Livewire\Components\SecurityForm;
use App\Livewire\Hooks\UserUpdate;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Livewire\Component;

class CustomProfileComponent extends Component implements HasForms
{
    use HasSort;

    use InteractsWithForms;

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    protected static int $sort = 0;

    protected ?Form $form = null;

    public function mount(): void
    {
        $this->form = $this->form($this->makeForm());
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                PersonalInfoForm::make(),
                RoleForm::make(),
                AddressForm::make(),
                SecurityForm::make(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if (! $this->form instanceof \Filament\Forms\Form) {
            $this->form = $this->form($this->makeForm());
        }

        /** @var array<string, mixed> */
        $data = $this->form->getState();

        if (! RolesHelper::isGuest() && ! UserPasswordUseCase::checkCurrentPassword($data)) {
            NotificationHelper::error('Kata Sandi Saat Ini Salah');

            return;
        }

        if (! isset($data['province_id']) && ! isset($data['city_id'])) {
            NotificationHelper::error('Provinsi dan Kota tidak boleh kosong');

            return;
        }

        if (isset($data['password'], $data['password_confirmation']) && ! UserPasswordUseCase::checkPasswordConfirmation($data)) {
            NotificationHelper::error('Kata Sandi Tidak Sama');

            return;
        }

        UserUpdate::updateFields($data);
        UserUpdate::updateRole($data);

        NotificationHelper::success('Data Berhasil Diubah');
    }

    public function render(): View
    {
        return view('livewire.custom-profile-component');
    }
}
