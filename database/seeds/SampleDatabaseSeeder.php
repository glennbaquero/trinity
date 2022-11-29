<?php

use Illuminate\Database\Seeder;

use App\Seeders\Samples\SampleAdminsTableSeeder;
use App\Seeders\Samples\SampleUsersTableSeeder;
use App\Seeders\Samples\SamplePagesTableSeeder;
use App\Seeders\PermissionsTableSeeder;
use App\Seeders\RolesTableSeeder;
use App\Seeders\PagesTableSeeder;
use App\Seeders\ArticleTableSeeder;
use App\Seeders\CommentsTableSeeder;
// use App\Seeders\LocationTableSeeder;
use App\Seeders\RegionsTableSeeder;
use App\Seeders\ProvincesTableSeeder;
use App\Seeders\CitiesTableSeeder;
use App\Seeders\SpecializationsTableSeeder;
use App\Seeders\ProductsSeeder;
use App\Seeders\StatusTypesTableSeeder;
use App\Seeders\InvoicesTableSeeder;
use App\Seeders\DoctorsSeeder;
use App\Seeders\MedicalRepresentativesSeeder;
use App\Seeders\BankDetailsTableSeeder;
use App\Seeders\AddressTableSeeder;
use App\Seeders\CartsTableSeeder;
use App\Seeders\CartItemsTableSeeder;
use App\Seeders\RewardsTableSeeder;
use App\Seeders\PrescriptionsSeeder;
use App\Seeders\CallsSeeder;

class SampleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // $this->call(SampleItemsTableSeeder::class);
        // $this->call(SampleItemRelationshipsTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        
        $this->call(SampleAdminsTableSeeder::class);
        $this->call(SampleUsersTableSeeder::class);
        $this->call(SamplePagesTableSeeder::class);
        $this->call(ArticleTableSeeder::class);
        // $this->call(CommentsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        // $this->call(LocationTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(SpecializationsTableSeeder::class);
        // $this->call(ProductsSeeder::class);
        $this->call(StatusTypesTableSeeder::class);        
        // $this->call(InvoicesTableSeeder::class);
        $this->call(MedicalRepresentativesSeeder::class);
        $this->call(DoctorsSeeder::class);
        $this->call(BankDetailsTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        // $this->call(CartsTableSeeder::class);
        // $this->call(CartItemsTableSeeder::class);
        $this->call(RewardsTableSeeder::class);
        $this->call(PrescriptionsSeeder::class);
        $this->call(CallsSeeder::class);
        $this->call(CreditPackagesTableSeeder::class);
        $this->call(ConsultationsTableSeeder::class);
    }
}
