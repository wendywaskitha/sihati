<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Pasar;
use App\Models\Pedagang;
use App\Models\Pengaturan;
use App\Models\Harga;
use Carbon\Carbon;

class AllDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@petugas.com'],
            [
                'name' => 'Budi Petugas Lapangan',
                'password' => Hash::make('password'),
                'role' => 'petugas'
            ]
        );

        // 2. Seed Pengaturan
        $settings = [
            ['key' => 'app_logo', 'value' => ''],
            ['key' => 'app_favicon', 'value' => ''],
            ['key' => 'instansi_nama', 'value' => 'Dinas Perdagangan dan Perindustrian'],
            ['key' => 'instansi_alamat', 'value' => 'Jl. Merdeka No. 123, Kota Makmur'],
            ['key' => 'instansi_email', 'value' => 'disperindag@sihati.go.id'],
            ['key' => 'pejabat_nama', 'value' => 'Drs. H. Ahmad Wijaya, M.Si'],
            ['key' => 'pejabat_pangkat', 'value' => 'Pembina Utama Muda (IV/c)'],
            ['key' => 'pejabat_nip', 'value' => '19750815 200003 1 002'],
            ['key' => 'app_footer', 'value' => 'Copyright © ' . date('Y') . ' Sihati. All rights reserved.'],
        ];

        foreach ($settings as $s) {
            Pengaturan::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }

        // 3. Seed Kategori
        $kategori_horti = Kategori::firstOrCreate(['nama' => 'Hortikultura']);
        $kategori_kebun = Kategori::firstOrCreate(['nama' => 'Perkebunan']);
        $kategori_pangan = Kategori::firstOrCreate(['nama' => 'Tanaman Pangan']);

        // 4. Seed Komoditas
        $komoditas = [
            ['kategori_id' => $kategori_pangan->id, 'nama' => 'Padi / Beras', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_pangan->id, 'nama' => 'Jagung Pipilan', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_pangan->id, 'nama' => 'Kedelai Lokal', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_horti->id, 'nama' => 'Cabai Merah Besar', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_horti->id, 'nama' => 'Cabai Rawit Merah', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_horti->id, 'nama' => 'Bawang Merah', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_horti->id, 'nama' => 'Tomat Sayur', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_kebun->id, 'nama' => 'Kopi Robusta', 'satuan' => 'Kg'],
            ['kategori_id' => $kategori_kebun->id, 'nama' => 'Kelapa Butiran', 'satuan' => 'Butir'],
            ['kategori_id' => $kategori_kebun->id, 'nama' => 'Cengkeh Kering', 'satuan' => 'Kg'],
        ];

        foreach ($komoditas as $k) {
            Komoditas::firstOrCreate(
                ['nama' => $k['nama']],
                ['kategori_id' => $k['kategori_id'], 'satuan' => $k['satuan']]
            );
        }

        // 5. Seed Kecamatan
        $kec_pusat = Kecamatan::firstOrCreate(['nama' => 'Kecamatan Pusat Kota']);
        $kec_selatan = Kecamatan::firstOrCreate(['nama' => 'Kecamatan Selatan']);

        // 6. Seed Pasar
        $pasar_pusat = Pasar::firstOrCreate(
            ['nama' => 'Pasar Sentral Makmur'],
            ['kecamatan_id' => $kec_pusat->id]
        );
        $pasar_selatan = Pasar::firstOrCreate(
            ['nama' => 'Pasar Tradisional Selatan'],
            ['kecamatan_id' => $kec_selatan->id]
        );

        // 7. Seed Pedagang
        $pedagang_data = [
            ['pasar_id' => $pasar_pusat->id, 'nama' => 'Lapak Tani Subur'],
            ['pasar_id' => $pasar_pusat->id, 'nama' => 'Kios Sayur Segar'],
            ['pasar_id' => $pasar_selatan->id, 'nama' => 'Gudang Hasil Bumi'],
        ];

        foreach ($pedagang_data as $p) {
            Pedagang::firstOrCreate(
                ['nama' => $p['nama'], 'pasar_id' => $p['pasar_id']]
            );
        }

        // 8. Seed Hargas (Mock dynamic price ranges for visual dashboard)
        $com_models = Komoditas::all();
        $pasar_models = Pasar::all();

        foreach ($pasar_models as $pasar) {
            foreach ($com_models as $com) {
                // Generate data for 30 consecutive days
                for ($i = 30; $i >= 0; $i--) {
                    $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
                    
                    // Generate pseudo random price
                    $base_price = match($com->kategori_id) {
                        $kategori_pangan->id => 14000,
                        $kategori_horti->id => 35000,
                        $kategori_kebun->id => match($com->nama) {
                            'Kopi Robusta' => 60000,
                            'Cengkeh Kering' => 110000,
                            default => 8000
                        },
                        default => 15000
                    };

                    $offset = rand(-3, 3) * 1000;
                    $harga_min = max(2000, $base_price + $offset - 1000);
                    $harga_max = $base_price + $offset + 2000;
                    $harga_avg = ($harga_min + $harga_max) / 2;

                    Harga::updateOrCreate(
                        [
                            'komoditas_id' => $com->id,
                            'pasar_id' => $pasar->id,
                            'tanggal' => $tanggal,
                        ],
                        [
                            'harga_min' => $harga_min,
                            'harga_max' => $harga_max,
                            'harga_avg' => $harga_avg,
                            'status' => 'approved',
                            'approved_by' => $admin->id
                        ]
                    );

                    $market_pedagangs = Pedagang::where('pasar_id', $pasar->id)->get();
                    foreach ($market_pedagangs as $idx => $ped) {
                        $p_price = ($idx == 0) ? $harga_min : $harga_max;
                        \App\Models\HargaDetail::updateOrCreate(
                            [
                                'komoditas_id' => $com->id,
                                'pedagang_id' => $ped->id,
                                'pasar_id' => $pasar->id,
                                'tanggal' => $tanggal,
                            ],
                            [
                                'harga' => $p_price,
                                'created_by' => $petugas->id,
                            ]
                        );
                    }
                }
            }
        }
    }
}
