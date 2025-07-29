<?php

// app/Filament/Resources/CreditApplicationResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditApplicationResource\Pages;
use App\Models\CreditApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; // Gunakan BadgeColumn untuk status
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action; // Untuk aksi kustom

class CreditApplicationResource extends Resource
{
    protected static ?string $model = CreditApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        // Formulir yang dilihat admin saat mengklik "Edit" atau "View"
        return $form
            ->schema([
                Card::make()->schema([
                    Grid::make(2)->schema([
                        TextInput::make('user.name')->label('Nama Pengaju')->disabled(),
                        TextInput::make('user.email')->label('Email Pengaju')->disabled(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('full_name')->label('Nama Lengkap Sesuai KTP')->disabled(),
                        TextInput::make('nik')->label('NIK')->disabled(),
                    ]),
                    // ... tambahkan semua field dari pengajuan
                    // Pastikan semua field dibuat 'disabled()' agar admin tidak bisa mengubah data asli pengguna
                    TextInput::make('amount')->label('Jumlah Pengajuan')->disabled()->numeric(),
                    TextInput::make('tenor')->label('Jangka Waktu (Bulan)')->disabled(),
                    
                    // Kolom yang diisi oleh Tim Internal
                    Textarea::make('notes')->label('Catatan Internal'),
                    Select::make('status')
                        ->options([
                            'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                            'Sedang Direview' => 'Sedang Direview',
                            'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                            'Disetujui' => 'Disetujui',
                            'Ditolak' => 'Ditolak',
                        ])
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        // Tabel yang menampilkan semua daftar pengajuan
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->label('ID'),
                TextColumn::make('user.name')->sortable()->searchable()->label('Nama Pengaju'),
                TextColumn::make('amount')->sortable()->numeric()->money('IDR'),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable()->label('Tanggal Pengajuan'),
                BadgeColumn::make('status') // Menggunakan badge untuk visual yang lebih baik [cite: 40]
                    ->colors([
                        'primary',
                        'warning' => fn ($state): bool => in_array($state, ['Menunggu Verifikasi', 'Sedang Direview', 'Menunggu Persetujuan']),
                        'success' => fn ($state): bool => $state === 'Disetujui',
                        'danger' => fn ($state): bool => $state === 'Ditolak',
                    ])->searchable(),
            ])
            ->filters([
                // Filter sederhana di pojok kanan atas
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCreditApplications::route('/'),
            'create' => Pages\CreateCreditApplication::route('/create'),
            // 'view' => Pages\ViewCreditApplication::route('/{record}'),
            'edit' => Pages\EditCreditApplication::route('/{record}/edit'),
        ];
    }
}