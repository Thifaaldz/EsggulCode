<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PositionResource\Pages;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Komponen Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

// Komponen Table
use Filament\Tables\Columns\TextColumn;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Manajemen Organisasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('division_id')
                    ->relationship('division', 'nama') // Pastikan field "nama" ada di tabel divisions
                    ->searchable()
                    ->required(),

                TextInput::make('nama')
                    ->label('Nama Jabatan')
                    ->required()
                    ->maxLength(100),

                TextInput::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('division.nama')->label('Divisi'),
                TextColumn::make('nama')->label('Nama Jabatan')->searchable(),
                TextColumn::make('basic_salary')->label('Gaji Pokok')->money('IDR'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
