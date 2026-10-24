<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('ব্র্যান্ডের নাম')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->helperText('ফাঁকা রাখলে নাম থেকে নিজে তৈরি হবে')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('brands'),
                TextInput::make('country')
                    ->label('দেশ'),
                Textarea::make('description')
                    ->label('বিবরণ')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('সক্রিয়')
                    ->default(true),
            ]);
    }
}
