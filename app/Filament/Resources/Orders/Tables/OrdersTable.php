<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('অর্ডার নম্বর')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('shipping_name')
                    ->label('কাস্টমার')
                    ->searchable(),
                TextColumn::make('shipping_phone')
                    ->label('ফোন')
                    ->searchable(),
                TextColumn::make('shipping_district')
                    ->label('জেলা'),
                TextColumn::make('total')
                    ->label('মোট')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('স্ট্যাটাস')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Order::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed', 'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->label('পেমেন্ট')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Order::PAYMENT_STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'paid' ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('সময়')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('স্ট্যাটাস')
                    ->options(Order::STATUSES),
                SelectFilter::make('payment_status')
                    ->label('পেমেন্ট')
                    ->options(Order::PAYMENT_STATUSES),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
