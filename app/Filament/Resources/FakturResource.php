<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\FakturModel;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FakturResource extends Resource
{
    protected static ?string $model = FakturModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_faktur')
                    ->columnSpan([
                        'default' => '2', // column
                        'md' => '1', //span nya
                        'lg' => '1', //span nya
                        'xl' => '1', //span nya
                    ]),
                DatePicker::make('tanggal_faktur')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                TextInput::make('kode_customer')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                Select::make('customer_id')
                    ->relationship('customer', 'nama_customer'),
                Repeater::make('detail')
                    ->relationship()
                    ->columnSpan(2) // cari di from builder scroll kebawah di lay out ada grid
                    ->schema([
                        Select::make('barang_id')
                            ->relationship('barang', 'nama_barang')
                            ->columnSpan([
                                'default' => '1',
                                'md' => '3',
                                'lg' => '3',
                                'xl' => '3',
                            ]),
                        TextInput::make('nama_barang'),
                        TextInput::make('diskon')
                            ->numeric(),
                        TextInput::make('harga')
                            ->numeric(),
                        TextInput::make('subtotal')
                            ->numeric(),
                        TextInput::make('qty')
                            ->numeric(),
                        TextInput::make('qty_total')
                            ->numeric(),
                    ]),
                Textarea::make('ket_faktur')
                    ->columnSpan(2),
                TextInput::make('total')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                TextInput::make('nominal_charge')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                TextInput::make('charge')
                    ->columnSpan([
                        'default' => '2', // column
                        'md' => '1', //span nya
                        'lg' => '1', //span nya
                        'xl' => '1', //span nya
                    ]),
                TextInput::make('total_final')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_faktur'),
                TextColumn::make('tanggal_faktur'),
                TextColumn::make('kode_customer'),
                TextColumn::make('ket_faktur'),
                TextColumn::make('customer.nama_customer'),
                TextColumn::make('total'),
                TextColumn::make('nominal_charge'),
                TextColumn::make('charge'),
                TextColumn::make('total_final'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
