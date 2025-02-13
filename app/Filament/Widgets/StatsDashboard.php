<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\CustomerModel;
use App\Models\FakturModel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countFaktur = FakturModel::count();
        $countBarang = Barang::count();
        $countCustomer = CustomerModel::count();
        return [
            Stat::make('Jumlah Faktur', $countFaktur . ' Faktur'),
            Stat::make('Jumlah Barang', $countBarang),
            Stat::make('Jumlah Customer', $countCustomer . ' Client'),
        ];
    }
}
