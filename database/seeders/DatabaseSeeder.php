<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuario = 1000;
        $cantidadCategories = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        User::factory()->count($cantidadUsuario)->create();
        Category::factory()->count($cantidadCategories)->create();
        
        Product::factory()->count($cantidadProductos)->create()->each(
            function($producto){
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );

        Transaction::factory()->count($cantidadTransacciones)->create();
        


        /*factory(Product::class, $cantidadUsuario)->create()->each(
            function($producto){
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );*/


    }
}
