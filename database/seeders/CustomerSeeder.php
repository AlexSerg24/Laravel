<?php

namespace Database\Seeders;

use App\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * 
     * @return void
     */
    public function run()
    {
        Customer::factory()
         ->count(25)
         ->hasInvoices(10)
         ->create();

         Customer::factory()
         ->count(100)
         ->hasInvoices(5)
         ->create();

         Customer::factory()
         ->count(100)
         ->hasInvoices(3)
         ->create();

         Customer::factory()
         ->count(5)
         ->create();
    }
}
