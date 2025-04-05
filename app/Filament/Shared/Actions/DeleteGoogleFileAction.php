<?php

namespace App\Filament\Shared\Actions;

use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class DeleteGoogleFileAction extends Action
{
    protected string $fileColumn = '';
    protected string $additionalColumn = '';
    protected string $disk = 'google';

    public function fileColumn(string $column): static
    {
        $this->fileColumn = $column;
        return $this;
    }

    public function additionalColumn(string $column): static
    {
        $this->additionalColumn = $column;
        return $this;
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->label('Hapus File')
        ->icon('heroicon-o-trash')
        ->color('danger')
        ->requiresConfirmation('Apakah Anda yakin ingin menghapus file ini?')
        ->visible(function (?Model $record): bool {
            return $record && !empty($record->{$this->fileColumn});
        })
        ->action(function (Action $action, Model $record) {
            $fileName = $record->{$this->fileColumn};
            if ($fileName) {
                Storage::disk($this->disk)->delete($fileName);

                $record->update([
                    $this->fileColumn => null,
                ]);

                if ($this->additionalColumn) {
                    $record->update([
                        $this->additionalColumn => null,
                    ]);
                }
            }

            $action->getComponent()->state(null);
            $action->getComponent()->getLivewire()->js('window.location.reload()');
        });
    }
}
