<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\PenjualanModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenjualanResource extends Resource
{
    protected static ?string $model = PenjualanModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $navigationGroup = 'Faktur';
    public static ?string $label = 'Laporan Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_faktur')
                    ->label('Tanggal')
                    ->sortable()
                    ->searchable()
                    ->date('d F Y'),
                TextColumn::make('kode_faktur')
                    ->sortable()
                    ->searchable()
                    ->label('Kode Faktur'),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.nama_customer')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('faktur_id')
                    ->label('Faktur ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'info',
                    })
                    ->formatStateUsing(fn(PenjualanModel $record): string => $record->status == 0 ? 'Belum Lunas' : 'Lunas')
                    ->searchable()
                    ->badge(),
            ])
            ->emptyStateHeading('Tidak Ada Data!')
            ->emptyStateDescription('Silahkan Tambahkan Data Terlebih Dahulu!')
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Tambahkan Data')
                    ->url(route('filament.admin.resources.fakturs.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
