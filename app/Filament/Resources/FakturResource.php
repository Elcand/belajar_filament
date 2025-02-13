<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\Barang;
use App\Models\FakturModel;
use App\Models\CustomerModel;
use Filament\Forms;
use App\Filament\intval;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
    protected static ?string $navigationLabel = 'Faktur';
    protected static ?string $navigationGroup = 'Faktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_faktur')
                    ->columnSpan(2),
                DatePicker::make('tanggal_faktur')
                    ->prefixIcon('heroicon-o-calendar')
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                Select::make('customer_id')
                    ->reactive()
                    ->relationship('customer', 'nama_customer')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $customer = CustomerModel::find($state);

                        if ($customer) {
                            $set('kode_customer', $customer->kode_customer);
                        }
                    }),
                TextInput::make('kode_customer')
                    ->reactive()
                    ->disabled() // kalo pake form trus ada disabled nya jan lupa tambah
                    ->dehydrated() // ini 👌
                    ->columnSpan(2),
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
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $barang = Barang::find($state);

                                if ($barang) {
                                    $set('harga', $barang->harga_barang);
                                    $set('nama_barang', $barang->nama_barang);
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set) {
                                $barang = Barang::find($state);

                                if ($barang) {
                                    $set('harga', $barang->harga_barang);
                                    $set('nama_barang', $barang->nama_barang);
                                }
                            }),
                        TextInput::make('nama_barang')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('harga')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->numeric(),
                        TextInput::make('qty')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $tampungHarga = $get('harga');
                                $set('qty_total', intval($state * $tampungHarga));
                            })
                            ->numeric(),
                        TextInput::make('qty_total')
                            ->disabled()
                            ->dehydrated()
                            ->label('Total Qty')
                            ->numeric(),
                        TextInput::make('diskon')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $hasilQTY = $get('qty_total');
                                $diskon = $hasilQTY * ($state / 100);
                                $hasil = $hasilQTY = $diskon;

                                $set('subtotal', intval($hasil));
                            })
                            ->numeric(),
                        TextInput::make('subtotal')
                            ->numeric(),
                    ])
                    ->live(),
                Textarea::make('ket_faktur')
                    ->columnSpan(2),
                TextInput::make('total')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder(function (Set $set, Get $get) {
                        $detail = collect($get('detail'))->pluck('subtotal')->sum();
                        if ($detail == null) {
                            $set('total', 0);
                        } else {
                            $set('total', $detail);
                        }
                    })
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                TextInput::make('nominal_charge')
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $total = $get('total');
                        $charge = $total * ($state / 100);
                        $hasil = $total + $charge;

                        $set('total_final', $hasil);
                        $set('charge', $charge);
                    })
                    ->columnSpan([
                        'default' => '2',
                        'md' => '1',
                        'lg' => '1',
                        'xl' => '1',
                    ]),
                TextInput::make('charge')
                    ->disabled()
                    ->dehydrated()
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
                TextColumn::make('tanggal_faktur')
                    ->date('d F Y'),
                TextColumn::make('kode_customer')
                    ->alignCenter(),
                TextColumn::make('ket_faktur'),
                TextColumn::make('customer.nama_customer'),
                TextColumn::make('total')
                    ->formatStateUsing(fn(FakturModel $record): string => 'Rp ' . number_format($record->total ?? 0, 2, ',', '.')),
                TextColumn::make('nominal_charge')
                    ->alignCenter(),
                TextColumn::make('charge'),
                TextColumn::make('total_final')
                    ->formatStateUsing(fn(FakturModel $record): string => 'Rp ' . number_format($record->total_final ?? 0, 2, ',', '.')),
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
