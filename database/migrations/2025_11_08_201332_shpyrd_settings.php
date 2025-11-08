<?php

use App\Models\Shipyard\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ([
            "app_adaptive_dark_mode" => false,
            "app_logo_path" => "/sz3_olive.svg",
            "app_name" => "Szpiewnik Szybkiego Szukania",
            "metadata_author" => "Wojciech Przybyła",
            "metadata_description" => "Zbiór pieśni kościelnych, melodii części stałych i psalmów oraz utworów na potrzeby mszy zwykłych i okolicznościowych. Pomoc dla organistów i muzyków kościelnych, tworzona przez organistę.",
            "metadata_keywords" => "śpiewnik, organy, muzyka kościelna, msza",
            "metadata_title" => "Szpiewnik Szybkiego Szukania",
            "users_self_register_enabled" => true,
        ] as $name => $value) {
            Setting::find($name)->update(["value" => $value]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
