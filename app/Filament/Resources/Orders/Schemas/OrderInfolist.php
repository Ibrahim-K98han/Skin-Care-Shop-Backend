<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('অর্ডার')
                    ->schema([
                        TextEntry::make('order_number')->label('অর্ডার নম্বর'),
                        TextEntry::make('status')
                            ->label('স্ট্যাটাস')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => Order::STATUSES[$state] ?? $state),
                        TextEntry::make('payment_status')
                            ->label('পেমেন্ট')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => Order::PAYMENT_STATUSES[$state] ?? $state),
                        TextEntry::make('created_at')
                            ->label('অর্ডারের সময়')
                            ->dateTime('d M Y, h:i A'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                Section::make('ডেলিভারির ঠিকানা')
                    ->schema([
                        TextEntry::make('shipping_name')->label('নাম'),
                        TextEntry::make('shipping_phone')->label('ফোন')->copyable(),
                        TextEntry::make('shipping_division')->label('বিভাগ'),
                        TextEntry::make('shipping_district')->label('জেলা'),
                        TextEntry::make('shipping_area')->label('এলাকা'),
                        TextEntry::make('shipping_address_line')->label('ঠিকানা')->columnSpanFull(),
                        TextEntry::make('note')->label('কাস্টমারের নোট')->placeholder('কোনো নোট নেই')->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('অর্ডারের আইটেম')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('product_name')->label('প্রোডাক্ট'),
                                TextEntry::make('variant_size')->label('সাইজ')->placeholder('-'),
                                TextEntry::make('sku')->label('SKU'),
                                TextEntry::make('unit_price')->label('একক দাম')->money('BDT'),
                                TextEntry::make('quantity')->label('পরিমাণ'),
                                TextEntry::make('line_total')->label('মোট')->money('BDT'),
                            ])
                            ->columns(6),
                    ])
                    ->columnSpanFull(),

                Section::make('হিসাব')
                    ->schema([
                        TextEntry::make('subtotal')->label('সাবটোটাল')->money('BDT'),
                        TextEntry::make('delivery_charge')->label('ডেলিভারি চার্জ')->money('BDT'),
                        TextEntry::make('total')->label('সর্বমোট (ক্যাশ অন ডেলিভারি)')->money('BDT')->weight('bold'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
