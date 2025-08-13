<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UsersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),

                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),

                Select::make('role')
                    ->options([
                        'admin'    => 'Admin',
                        'merchant' => 'Merchant',
                        'sub_user' => 'Sub User',
                        'partner'  => 'Partner',
                        'support'  => 'Support',
                    ])
                    ->default('merchant')
                    ->required(),

                Toggle::make('enabled')
                    ->label('Enabled')
                    ->default(true),

                Toggle::make('reset_password')
                    ->label('Reset Password')
                    ->default(true),

                TextInput::make('password')
                    ->password()
                    ->required(fn ($record) => $record === null) // only required when creating
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state)) // save only if entered
                    ->maxLength(255),
            ]);
    }
}
