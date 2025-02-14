<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\PenjualanModel;
use Carbon\Carbon;

class PenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        // $trendMinggu = Trend::model(PenjualanModel::class)
        //     ->between(
        //         start: now()->startOfDay(),
        //         end: now()->endOfDay(),
        //     )
        //     ->perDay()
        //     ->count();
        // $trendBulan = Trend::model(PenjualanModel::class)
        //     ->between(
        //         start: now()->startOfDay(),
        //         end: now()->endOfDay(),
        //     )
        //     ->perDay()
        //     ->count();
        $trendTahun = Trend::model(PenjualanModel::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                // [
                //     'label' => 'Penjualan per Hari',
                //     'data' => $trendMinggu->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                // ],
                // [
                //     'label' => 'Penjualan per Bulan',
                //     'data' => $trendBulan->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                // ],
                [
                    'label' => 'Penjualan per Tahun',
                    'data' => $trendTahun->map(fn(TrendValue $value) => $value->aggregate)->toArray(),
                ],
            ],
            // 'labels' => $trendMinggu->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat('D'))->toArray(),
            // 'labels' => $trendBulan->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat('W'))->toArray(),
            'labels' => $trendTahun->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat('M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
