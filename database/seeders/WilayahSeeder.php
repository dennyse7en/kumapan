<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $url = 'https://raw.githubusercontent.com/kodewilayah/permendagri-72-2019/main/dist/base.csv';

        try {
            Schema::disableForeignKeyConstraints();
            DB::table('villages')->truncate();
            DB::table('districts')->truncate();
            DB::table('regencies')->truncate();
            DB::table('provinces')->truncate();

            $response = Http::get($url);
            if ($response->failed()) {
                $this->command->error('Gagal mengunduh file CSV.');
                return;
            }

            $csvData = $response->body();
            $rows = preg_split('/\\r\\n|\\r|\\n/', $csvData);

            $provinces = [];
            $regenciesData = [];
            $districtsData = [];
            $villagesData = [];

            foreach ($rows as $row) {
                if (empty(trim($row))) continue;
                $data = str_getcsv($row);
                $code = $data[0];
                $name = $data[1];
                $codeParts = explode('.', $code);
                
                if (count($codeParts) === 1) $provinces[] = ['code' => $code, 'name' => $name];
                elseif (count($codeParts) === 2) $regenciesData[] = ['code' => $code, 'province_code' => $codeParts[0], 'name' => $name];
                elseif (count($codeParts) === 3) $districtsData[] = ['code' => $code, 'regency_code' => "{$codeParts[0]}.{$codeParts[1]}", 'name' => $name];
                elseif (count($codeParts) === 4) $villagesData[] = ['code' => $code, 'district_code' => "{$codeParts[0]}.{$codeParts[1]}.{$codeParts[2]}", 'name' => $name];
            }

            DB::transaction(function () use ($provinces, $regenciesData, $districtsData, $villagesData) {
                DB::table('provinces')->insert($provinces);

                $provinceMap = DB::table('provinces')->pluck('id', 'code');
                $regenciesToInsert = array_map(function ($regency) use ($provinceMap) {
                    return [
                        'code' => $regency['code'],
                        'name' => $regency['name'],
                        'province_id' => $provinceMap[$regency['province_code']] ?? null,
                    ];
                }, $regenciesData);
                DB::table('regencies')->insert($regenciesToInsert);

                $regencyMap = DB::table('regencies')->pluck('id', 'code');
                $districtsToInsert = array_map(function ($district) use ($regencyMap) {
                    return [
                        'code' => $district['code'],
                        'name' => $district['name'],
                        'regency_id' => $regencyMap[$district['regency_code']] ?? null,
                    ];
                }, $districtsData);
                DB::table('districts')->insert($districtsToInsert);
                
                $districtMap = DB::table('districts')->pluck('id', 'code');
                $villagesToInsert = [];
                foreach($villagesData as $village) {
                    if (isset($districtMap[$village['district_code']])) {
                        $villagesToInsert[] = [
                            'code' => $village['code'],
                            'name' => $village['name'],
                            'district_id' => $districtMap[$village['district_code']],
                        ];
                    }
                }
                
                foreach (array_chunk($villagesToInsert, 1000) as $chunk) {
                    DB::table('villages')->insert($chunk);
                }
            });

            Schema::enableForeignKeyConstraints();
            $this->command->info('Seeder data wilayah Indonesia berhasil dijalankan.');

        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            $this->command->error('Terjadi kesalahan: ' . $e->getMessage() . ' pada baris ' . $e->getLine());
        }
    }
}