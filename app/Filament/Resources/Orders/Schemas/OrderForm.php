<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('অর্ডার স্ট্যাটাস')
                    ->options(Arr::except(Order::STATUSES, ['cancelled']))
                    ->required()
                    ->helperText('ক্যানসেল অপশন পরের ধাপে আসবে (স্টক ফেরতের ব্যবস্থা সহ)'),
                Select::make('payment_status')
                    ->label('পেমেন্ট স্ট্যাটাস')
                    ->options(Order::PAYMENT_STATUSES)
                    ->required(),
                Textarea::make('note')
                    ->label('নোট')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
