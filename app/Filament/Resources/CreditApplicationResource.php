<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditApplicationResource\Pages;
use App\Filament\Resources\CreditApplicationResource\RelationManagers;
use App\Models\CreditApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Closure;

class CreditApplicationResource extends Resource
{
    // Model Eloquent yang terhubung dengan resource ini
    protected static ?string $model = CreditApplication::class;

    // Icon yang akan ditampilkan di navigasi sidebar
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    // Grup navigasi untuk mengelompokkan item di sidebar
    protected static ?string $navigationGroup = 'Manajemen Kredit';

    // Label untuk model tunggal
    protected static ?string $modelLabel = 'Pengajuan Kredit';

    // Label untuk model jamak
    protected static ?string $pluralModelLabel = 'Daftar Pengajuan Kredit';

    /**
     * Mendefinisikan skema formulir untuk membuat dan mengedit data.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    // Kolom utama untuk data dan proses
                    Grid::make()->schema([
                        Card::make()
                            ->schema([
                                Forms\Components\Section::make('Data Pemohon')
                                    ->description('Semua data yang diinput oleh pemohon.')
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_lengkap')->label('Nama Lengkap')->disabled(),
                                        Forms\Components\TextInput::make('nik')->label('NIK')->disabled(),
                                        Forms\Components\TextInput::make('no_telepon')->label('Nomor Telepon')->disabled(),
                                        Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull()->disabled(),
                                        Forms\Components\TextInput::make('jumlah_pinjaman')->label('Jumlah Pinjaman')->prefix('Rp')->disabled(),
                                        Forms\Components\TextInput::make('tenor_bulan')->label('Tenor')->suffix(' bulan')->disabled(),
                                        Forms\Components\DatePicker::make('tanggal_pengajuan')->label('Tanggal Pengajuan')->disabled(),
                                        Forms\Components\FileUpload::make('dokumen_pendukung')->label('Dokumen Pendukung')->multiple()->directory('dokumen-pengajuan')->disabled(),
                                    ])->columns(2),
                            ]),

                        // Card untuk Verifikator
                        Card::make()
                            ->schema([
                                Forms\Components\Section::make('Proses Verifikasi')
                                    ->schema([
                                        Toggle::make('is_verified')->label('Data Terverifikasi?'),
                                        Forms\Components\Textarea::make('catatan_verifikator')->label('Catatan Verifikasi'),
                                    ])
                            ])
                            ->hidden(fn (Closure $get) => !auth()->user()->hasRole('verifikator')),

                        // Card untuk Operator
                        Card::make()
                            ->schema([
                                Forms\Components\Section::make('Tindakan Operator')
                                    ->schema([
                                        Forms\Components\Select::make('status_operator')
                                            ->label('Update Status')
                                            ->options([
                                                'Menunggu Persetujuan' => 'Lanjutkan ke Approver',
                                                'Ditolak' => 'Tolak Pengajuan',
                                            ]),
                                        Forms\Components\Textarea::make('catatan_operator')->label('Catatan Operator'),
                                    ])
                            ])
                            ->hidden(fn (Closure $get) => !(auth()->user()->hasRole('operator') && $get('status') === 'Sedang Direview')),

                        // Card untuk Approver
                        Card::make()
                            ->schema([
                                Forms\Components\Section::make('Keputusan Akhir (Approver)')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Keputusan')
                                            ->options([
                                                'Disetujui' => 'Setujui',
                                                'Ditolak' => 'Tolak',
                                            ])->required(),
                                        Forms\Components\Textarea::make('catatan_approver')->label('Catatan Persetujuan'),
                                    ])
                            ])
                            ->hidden(fn (Closure $get) => !(auth()->user()->hasRole('approver') && $get('status') === 'Menunggu Persetujuan')),

                    ])->columnSpan(2),

                    // Kolom samping untuk status
                    Grid::make()->schema([
                        Card::make()
                            ->schema([
                                Forms\Components\Section::make('Status')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'Baru' => 'Baru',
                                                'Diproses' => 'Diproses',
                                                'Sedang Direview' => 'Sedang Direview',
                                                'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                                                'Disetujui' => 'Disetujui',
                                                'Ditolak' => 'Ditolak',
                                            ])
                                            ->required()
                                            ->default('Baru'),
                                    ])
                            ])
                    ])->columnSpan(1),
                ]),
            ]);
    }

    /**
     * Mendefinisikan kolom-kolom yang akan ditampilkan pada tabel.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Pemohon')
                    ->searchable() // Membuat kolom ini dapat dicari
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_pinjaman')
                    ->label('Jumlah Pinjaman')
                    ->money('IDR') // Format sebagai mata uang Rupiah
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenor_bulan')
                    ->label('Tenor')
                    ->suffix(' bulan')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'Baru',
                        'secondary' => 'Diproses',
                        'info' => 'Sedang Direview',
                        'warning' => 'Menunggu Persetujuan',
                        'success' => 'Disetujui',
                        'danger' => 'Ditolak',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengajuan')
                    ->label('Tgl Pengajuan')
                    ->date('d M Y') // Format tanggal
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Kolom bisa disembunyikan
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Menambahkan filter berdasarkan status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Baru' => 'Baru',
                        'Diproses' => 'Diproses',
                        'Sedang Direview' => 'Sedang Direview',
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Mendefinisikan relasi yang akan ditampilkan.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            // RelationManagers bisa ditambahkan di sini jika ada
        ];
    }

    /**
     * Mendefinisikan halaman-halaman yang terkait dengan resource ini.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreditApplications::route('/'),
            'create' => Pages\CreateCreditApplication::route('/create'),
            'edit' => Pages\EditCreditApplication::route('/{record}/edit'),
        ];
    }
}
