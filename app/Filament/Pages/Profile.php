<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class Profile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';

    use InteractsWithForms;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->attributesToArray()
        );
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('profile.avatar_column', 'profile_image');
        $path = $this->$avatarColumn;
        dd($path, Storage::exists($path), Storage::url($path));
        return $path ? Storage::url($path) : null;
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? Storage::url($this->profile_image) : null;
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('profile_image')
                    ->label('Poto Profile')
                    ->image()
                    ->imageEditor()
                    ->avatar()
                    ->afterStateUpdated(fn($state, $record) => $record->update(['profile_image' => $state]))
                    ->extraAttributes([
                        'class' => 'rounded-full w-24 h-24 object-cover mx-auto flex justify-center items-center'
                    ])
                    ->directory('profile')
                    ->saveRelationshipsWhenHidden(),
                TextInput::make('name')
                    ->label('Name')
                    ->autofocus()
                    ->required()
                    ->columnSpan(2),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->columnSpan(2),
            ])
            ->statePath('data')
            ->model(auth()->user());;
    }

    public function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        auth()->user()->update(
            $this->form->getState()
        );
    }
}
