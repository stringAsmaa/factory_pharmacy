<?php

namespace Database\Seeders;

use App\Models\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
          
            'categorie'=>'صدرية',
            'trade_name'=>'ايبراتروبيوم',    
         
        ]);
        DB::table('categories')->insert([
          
            'categorie'=>'صدرية',
            'trade_name'=>'الريفاينا',    
           
        ]); DB::table('categories')->insert([
          
            'categorie'=>'صدرية',
            'trade_name'=>'البيرازيمند',    
           
        ]); DB::table('categories')->insert([
          
            'categorie'=>'قلبية',
            'trade_name'=>'املودبين',    
           
        ]); DB::table('categories')->insert([
          
            'categorie'=>'قلبية',
            'trade_name'=>'تينورمين',    
          
        ]); DB::table('categories')->insert([
          
            'categorie'=>'قلبية',
            'trade_name'=>'كونكور',    
         
        ]); DB::table('categories')->insert([
          
            'categorie'=>'قلبية',
            'trade_name'=>'اندرال',    
      
        ]); DB::table('categories')->insert([
          
            'categorie'=>'جلدية',
            'trade_name'=>'يوريا',    
          
        ]); DB::table('categories')->insert([
          
            'categorie'=>'جلدية',
            'trade_name'=>'الاجزخانة',    
            
        ]);






    }
}
