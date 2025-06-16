<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmployeeResource\Pages;
use App\Filament\Admin\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Komponen Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;

// Komponen Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Organisasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            
            TextInput::make('nama')->label('Nama lengkap'),
                Select::make('user_id')
                ->relationship('user', 'name')
                ->label('User Login')
                ->searchable()
                ->preload()
                ->required(),
    
            Select::make('position_id')
                ->relationship('position', 'nama')
                ->label('Jabatan')
                ->required(),
    
            Select::make('branch_id')
                ->relationship('branch', 'nama')
                ->label('Cabang')
                ->required(),
                TextInput::make('email')->label('Email Karyawan'),
            TextInput::make('telepon')->label('No Telepon'),
            DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
            FileUpload::make('foto')->image()->directory('pegawai'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama Pegawai')->searchable(),
                TextColumn::make('position.nama')->label('Jabatan'),
                TextColumn::make('branch.nama')->label('Cabang'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
