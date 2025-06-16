<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Manajemen Karyawan';
    protected static ?string $label = 'Cuti';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('employee_id')
                ->relationship('employee', 'nama')
                ->required()
                ->searchable(),

            DatePicker::make('tanggal_mulai')->required(),
            DatePicker::make('tanggal_selesai')->required(),

            Textarea::make('alasan')->rows(3),

            Select::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ])
                ->default('menunggu')
                ->required()
                ->disabledOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama')->label('Karyawan')->searchable(),
                TextColumn::make('tanggal_mulai')->date(),
                TextColumn::make('tanggal_selesai')->date(),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Action::make('setujui')
                    ->label('Setujui')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->action(fn ($record) => $record->update(['status' => 'disetujui']))
                    ->requiresConfirmation(),

                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->action(fn ($record) => $record->update(['status' => 'ditolak']))
                    ->requiresConfirmation(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
