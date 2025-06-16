<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PayrollResource\Pages;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static ?string $navigationGroup = 'Manajemen Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('employee_id')
                ->relationship('employee', 'nama')
                ->searchable()
                ->required(),

            TextInput::make('tahun')->numeric()->required(),
            TextInput::make('bulan')->numeric()->required(),

            TextInput::make('gaji_pokok')->numeric()->readOnly(),
            TextInput::make('jumlah_kehadiran')->numeric()->readOnly(),
            TextInput::make('jumlah_cuti')->numeric()->readOnly(),
            TextInput::make('insentif_kehadiran')->numeric()->readOnly(),
            TextInput::make('bonus')->numeric()->readOnly(),
            TextInput::make('total_gaji')->numeric()->readOnly(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'dibayar' => 'Dibayar',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.nama')->label('Nama Karyawan'),
            TextColumn::make('bulan')->label('Bulan'),
            TextColumn::make('tahun')->label('Tahun'),
            TextColumn::make('gaji_pokok')->money('IDR'),
            TextColumn::make('insentif_kehadiran')->money('IDR'),
            TextColumn::make('bonus')->money('IDR'),
            TextColumn::make('total_gaji')->money('IDR'),
            TextColumn::make('status')->badge()->color(fn ($state) => $state === 'dibayar' ? 'success' : 'warning'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
