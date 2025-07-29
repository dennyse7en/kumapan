<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WelcomeImageResource\Pages;
use App\Models\WelcomeImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class WelcomeImageResource extends Resource
{
    protected static ?string $model = WelcomeImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $label = 'Gambar Halaman Depan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('path')->label('File Gambar')->image()->disk('public')->directory('welcome-slider')->required(),
                TextInput::make('title')->label('Judul')->maxLength(255),
                TextInput::make('subtitle')->label('Sub-judul')->maxLength(255),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')->label('Gambar'),
                TextColumn::make('title')->searchable(),
                IconColumn::make('is_active')->label('Status')->boolean(),
            ])
            ->reorderable('order_column') // Aktifkan fitur drag-and-drop untuk urutan
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
            'index' => Pages\ListWelcomeImages::route('/'),
            'create' => Pages\CreateWelcomeImage::route('/create'),
            'edit' => Pages\EditWelcomeImage::route('/{record}/edit'),
        ];
    }

    // Hanya operator dan super admin yang bisa akses
    public static function canViewAny(): bool
    {
        return Auth::user()->hasAnyRole(['operator', 'Super Admin']);
    }
}