<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $recordTitleAttribute = 'subject';

    /** @return array<int, string> */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'subject'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Penerimaan Mahasiswa';

    protected static ?string $navigationLabel = 'Pesan Masuk';

    protected static ?string $modelLabel = 'Pesan';

    protected static ?string $pluralModelLabel = 'Pesan Masuk';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) (static::getModel()::where('is_read', false)->count() ?: '');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
                Forms\Components\TextInput::make('subject')->label('Subjek')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\Textarea::make('message')->label('Pesan')->required()->rows(5)->columnSpanFull(),
                Forms\Components\Toggle::make('is_read')->label('Sudah dibaca'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')->label('Dibaca')->boolean(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable()
                    ->weight(fn (ContactMessage $record) => $record->is_read ? null : 'bold'),
                Tables\Columns\TextColumn::make('subject')->label('Subjek')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->copyable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Diterima')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Status Baca')
                    ->trueLabel('Sudah dibaca')->falseLabel('Belum dibaca'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn () => \App\Support\CsvExporter::download(
                        ContactMessage::query()->latest()->get(),
                        ['name' => 'Nama', 'email' => 'Email', 'subject' => 'Subjek', 'message' => 'Pesan', 'created_at' => 'Diterima'],
                        'pesan-'.now()->format('Ymd-His').'.csv',
                    )),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleRead')
                    ->label(fn (ContactMessage $record): string => $record->is_read ? 'Tandai belum dibaca' : 'Tandai dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => ! $record->is_read])),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
