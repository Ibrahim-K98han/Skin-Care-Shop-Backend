<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('প্রোডাক্টের তথ্য')
                    ->schema([
                        Select::make('brand_id')
                            ->label('ব্র্যান্ড')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('category_id')
                            ->label('ক্যাটাগরি')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('প্রোডাক্টের নাম')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->helperText('ফাঁকা রাখলে নাম থেকে নিজে তৈরি হবে')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('short_description')
                            ->label('সংক্ষিপ্ত বিবরণ')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('বিস্তারিত বিবরণ')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('ingredients')
                            ->label('ইনগ্রেডিয়েন্ট')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('how_to_use')
                            ->label('ব্যবহারবিধি')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail')
                            ->label('মূল ছবি')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('সক্রিয়')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('ফিচার্ড (হোম পেজে দেখাবে)')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('ভ্যারিয়েন্ট (সাইজ ও দাম)')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->label('')
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('size')
                                    ->label('সাইজ (যেমন 50ml)'),
                                TextInput::make('price')
                                    ->label('দাম (৳)')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('sale_price')
                                    ->label('সেল প্রাইস (৳)')
                                    ->numeric(),
                                TextInput::make('stock')
                                    ->label('স্টক')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('সক্রিয়')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->addActionLabel('আরেকটা ভ্যারিয়েন্ট যোগ করুন')
                            ->defaultItems(1)
                            ->minItems(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
