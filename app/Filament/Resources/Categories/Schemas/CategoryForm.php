<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('প্যারেন্ট ক্যাটাগরি')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('মূল ক্যাটাগরি হলে ফাঁকা রাখুন'),
                TextInput::make('name')
                    ->label('নাম')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->helperText('ফাঁকা রাখলে নাম থেকে নিজে তৈরি হবে')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('categories'),
                Textarea::make('description')
                    ->label('বিবরণ')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('ক্রম')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('সক্রিয়')
                    ->default(true),
            ]);
    }
}
