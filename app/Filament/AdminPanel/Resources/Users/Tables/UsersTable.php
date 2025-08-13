<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')->label('UUID')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('first_name')->searchable()->sortable(),
                TextColumn::make('last_name')->searchable()->sortable(),
                TextColumn::make('role')->sortable(),
                BooleanColumn::make('enabled')->label('Enabled'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('enabled')
                    ->label('Enabled')
                    ->boolean(),
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin'    => 'Admin',
                        'merchant' => 'Merchant',
                        'sub_user' => 'Sub User',
                        'partner'  => 'Partner',
                        'support'  => 'Support',
                    ]),
            ])
            ->defaultSort('role', 'asc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
             ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]); 
    }
}
