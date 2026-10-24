<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Validation\ValidationException;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('cancel')
                ->label('অর্ডার বাতিল করুন')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('অর্ডার বাতিল করবেন?')
                ->modalDescription('বাতিল করলে সব আইটেমের স্টক ফেরত যাবে। এটা আর ফেরানো যাবে না।')
                ->visible(fn (Order $record) => ! in_array($record->status, ['cancelled', 'shipped', 'delivered'], true))
                ->action(function (Order $record) {
                    try {
                        app(OrderService::class)->cancel($record);
                    } catch (ValidationException $e) {
                        Notification::make()
                            ->title(collect($e->errors())->flatten()->first())
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('অর্ডার বাতিল হয়েছে ও স্টক ফেরত গেছে')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
        ];
    }
}
