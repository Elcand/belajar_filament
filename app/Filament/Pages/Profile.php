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
        $avatarColumn = config('profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url("$this->$avatarColumn") : null;
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
                    ->extraAttributes([
                        'class' => 'rounded-full w-24 h-24 object-cover mx-auto flex justify-center items-center'
                    ])
                    ->directory('storage/profile'),
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
