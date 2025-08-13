<?php

namespace App\Filament\AdminPanel\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use App\Models\User;
use App\Models\Users;
use Illuminate\Validation\Rules\Password;

class UsersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->options([
                        Users::ROLE_ADMIN    => 'Admin',
                        Users::ROLE_MERCHANT => 'Merchant',
                        Users::ROLE_SUB_USER => 'Sub User',
                        Users::ROLE_PARTNER  => 'Partner',
                        Users::ROLE_SUPPORT  => 'Support',
                    ])
                    ->default(Users::ROLE_MERCHANT)
                    ->required(),

                TextInput::make('merchant_id')
                    ->label('Merchant ID')
                    ->visible(fn ($get) => $get('role') === Users::ROLE_MERCHANT)
                    ->required(fn ($get) => $get('role') === Users::ROLE_MERCHANT),
                    // ->uuid()
                    // ->exists('merchants', 'uuid'),

                TextInput::make('partner_id')
                    ->label('Partner ID')
                    ->visible(fn ($get) => $get('role') === Users::ROLE_PARTNER)
                    ->required(fn ($get) => $get('role') === Users::ROLE_PARTNER)
                    ->uuid(),
                    // ->exists('partners', 'uuid'),

                TextInput::make('support_group_id')
                    ->label('Support Group ID')
                    ->visible(fn ($get) => $get('role') === Users::ROLE_SUPPORT)
                    ->required(fn ($get) => $get('role') === Users::ROLE_SUPPORT)
                    ->numeric(),
                    // ->exists('support_groups', 'id'),

                TextInput::make('first_name')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->minLength(5)
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Toggle::make('enabled')
                    ->label('Enabled')
                    ->required()
                    ->default(true),

                Toggle::make('reset_password')
                    ->label('Reset Password')
                    ->default(true),

                TextInput::make('password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->rule(Password::min(12)->letters()->mixedCase()->numbers()->symbols())
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ]);
    }
}
