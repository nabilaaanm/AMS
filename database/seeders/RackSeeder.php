<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat tabel hanya jika belum ada
        if (!Schema::hasTable('rack')) {
            Schema::create('rack', function (Blueprint $table) {
                $table->bigIncrements('id'); // ID sebagai primary key + auto increment
                $table->string('kode_region');
                $table->string('kode_site', 10);
                $table->integer('no_rack');
                $table->integer('u');
                $table->unsignedBigInteger('id_fasilitas')->nullable();
                $table->unsignedBigInteger('id_perangkat')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('kode_region')->references('kode_region')->on('region')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('kode_site')->references('kode_site')->on('site')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_fasilitas')->references('id_fasilitas')->on('listfasilitas')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_perangkat')->references('id_perangkat')->on('listperangkat')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        // Ambil data site
        $sites = DB::table('site')->get();

        foreach ($sites as $site) {
            $jumlahRack = $site->jml_rack ?? 1; // fallback biar gak null

            for ($no_rack = 1; $no_rack <= $jumlahRack; $no_rack++) {
                for ($u = 1; $u <= 42; $u++) {
                    $exists = DB::table('rack')
                        ->where('kode_region', $site->kode_region)
                        ->where('kode_site', $site->kode_site)
                        ->where('no_rack', $no_rack)
                        ->where('u', $u)
                        ->exists();

                    if (!$exists) {
                        DB::table('rack')->insert([
                            'kode_region' => $site->kode_region,
                            'kode_site' => $site->kode_site,
                            'no_rack' => $no_rack,
                            'u' => $u,
                            'id_fasilitas' => null,
                            'id_perangkat' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
