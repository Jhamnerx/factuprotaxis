<?php


use Illuminate\Database\Seeder;
use Modules\Payment\Models\Plan;
use App\Models\Tenant\Taxis\Marca;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Taxis\Modelo;
use App\Models\Tenant\Taxis\Condicion;

use function GuzzleHttp\json_encode;

class TenancyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $id = DB::table('items')->insertGetId(
        //     ['name' => 'Laptop Razer', 'second_name' => 'Laptop Razer', 'description' => 'Laptop Razer','item_type_id' => '01', 
        //     'unit_type_id' => 'NIU', 'currency_type_id' => 'PEN', 'sale_unit_price' => '5500.00', 'has_igv' => 1, 'purchase_unit_price' => '3000.00', 'has_isc' => 0,
        //     'amount_plastic_bag_taxes' => '0.10', 'percentage_isc' => '0.00', 'suggested_price' => '0.00', 'sale_affectation_igv_type_id' => '10' , 
        //     'purchase_affectation_igv_type_id' => '10', 'calculate_quantity' => '0', 'image' => 'demo1.jpg', 'image_medium' => 'demo1_medium.jpg', 'image_small' => 'demo1_small.jpg',  'stock' => '1',
        //     'stock_min' => '1',  'percentage_of_profit' => '20.0',  'has_perception' => '0',  'status' => 1, 'apply_store' => 1 ]
        // );

        // $id2 = DB::table('items')->insertGetId(

        //     ['name' => 'MacBook Pro', 'second_name' => 'LMacBook Pro', 'description' => 'MacBook Pro','item_type_id' => '01', 
        //     'unit_type_id' => 'NIU', 'currency_type_id' => 'PEN', 'sale_unit_price' => '5500.00', 'has_igv' => 1, 'purchase_unit_price' => '3000.00', 'has_isc' => 0,
        //     'amount_plastic_bag_taxes' => '0.10', 'percentage_isc' => '0.00', 'suggested_price' => '0.00', 'sale_affectation_igv_type_id' => '10' , 
        //     'purchase_affectation_igv_type_id' => '10', 'calculate_quantity' => '0', 'image' => 'demo2.jpg', 'image_medium' => 'demo2_medium.jpg', 'image_small' => 'demo2_small.jpg',  'stock' => '1',
        //     'stock_min' => '1',  'percentage_of_profit' => '20.0',  'has_perception' => '0',  'status' => 1, 'apply_store' => 1 ]
        // );

        // $id3 = DB::table('items')->insertGetId(

        //     ['name' => 'Laptop Asus', 'second_name' => 'Laptop Asus', 'description' => 'Laptop Asus','item_type_id' => '01', 
        //     'unit_type_id' => 'NIU', 'currency_type_id' => 'PEN', 'sale_unit_price' => '5500.00', 'has_igv' => 1, 'purchase_unit_price' => '3000.00', 'has_isc' => 0,
        //     'amount_plastic_bag_taxes' => '0.10', 'percentage_isc' => '0.00', 'suggested_price' => '0.00', 'sale_affectation_igv_type_id' => '10' , 
        //     'purchase_affectation_igv_type_id' => '10', 'calculate_quantity' => '0', 'image' => 'demo3.jpg', 'image_medium' => 'demo3_medium.jpg', 'image_small' => 'demo3_small.jpg',  'stock' => '1',
        //     'stock_min' => '1',  'percentage_of_profit' => '20.0',  'has_perception' => '0',  'status' => 1, 'apply_store' => 1 ]

        // );

        // DB::table('promotions')->insert([
        //     ['name' => 'Promocion 1', 'description' => 'Promocion 1', 'image' => 'promo1.jpg', 'item_id'=> $id, 'status'=> 1 ],
        //     ['name' => 'Promocion 2', 'description' => 'Promocion 2', 'image' => 'promo2.jpg', 'item_id'=> $id2, 'status'=> 1 ],
        //     ['name' => 'Promocion 3', 'description' => 'Promocion 3', 'image' => 'promo3.jpg', 'item_id'=> $id3, 'status'=> 1 ]
        // ]);

        // DB::table('module_user')->insert([
        //     ['module_id' => 10, 'user_id' => 1, ]
        // ]);
        if (DB::table('format_templates')->get()->count() == 0) {
            DB::table('format_templates')->insert([
                ['id' => 1, 'formats' => 'con_valor_unitario'],
                ['id' => 2, 'formats' => 'default'],
                ['id' => 3, 'formats' => 'default2'],
                ['id' => 4, 'formats' => 'font_sm'],
                ['id' => 5, 'formats' => 'font_sw2'],
                ['id' => 6, 'formats' => 'legend_amazonia'],
                ['id' => 7, 'formats' => 'model1'],
                ['id' => 8, 'formats' => 'model2'],
                ['id' => 9, 'formats' => 'model3'],
                ['id' => 10, 'formats' => 'model4'],
                ['id' => 11, 'formats' => 'modelw80'],
                ['id' => 12, 'formats' => 'santiago'],
                ['id' => 13, 'formats' => 'top_placa'],
                ['id' => 14, 'formats' => 'unit_types_desc']
            ]);
        }

        //! Rellenar los warehouse_id desde inventories hasta items

        $items_warehouse_null = DB::table('items')->where("warehouse_id", '=', null)->get();

        $items_warehouse_null->each(function ($item, $key) {
            $inventory_warehouse_id = DB::table('inventories')->where('item_id', $item->id)->first();
            if ($inventory_warehouse_id) {
                DB::table('items')->where('id', '=', $item->id)->update([
                    "warehouse_id" => $inventory_warehouse_id->warehouse_id
                ]);
            }
        });


        /**
         * ! Mandar los datos de DispatchAddress a PersonAddress
         */

        $dispatch_address_data = DB::table("dispatch_addresses")->get();

        if ($dispatch_address_data->count() > 0) {

            // Siempre va a validar cuando ya un tenant exista y además si tiene valores dentro de la tabla dispatch_addresses
            $configurations = DB::table("configurations")->first();
            if ($configurations->is_migrated_address) {
                return;
            }


            $data_insert_all = collect([]);

            // Se crea un nuevo array pero en la llave location_id, en vez de tener los valors en json, se tendra un array con todo los valores parseados
            $dispatch_address_data->transform(function ($item, $key) {

                //Convierto el objeto estandar en un array asociativo
                $objec_dispatch_addresses = collect((array) $item);

                $parse_item = $objec_dispatch_addresses->map(function ($item_object, $key_object) {
                    if ($key_object == "location_id") {
                        // |  country_id |  department_id(0)  | province_id)(1)  | district_id(2)  |
                        $location_clear = json_decode($item_object);

                        $ubigeo_deparment = DB::table('departments')->get();
                        $ubigeo_province = DB::table('provinces')->get();
                        $ubigeo_district = DB::table('districts')->get();

                        // Encontrar si tiene un id valido en la los datos ubigeo

                        $deparment_find = $ubigeo_deparment->where("id", $location_clear[0]);
                        $province_find = $ubigeo_province->where("id", $location_clear[1]);
                        $district_find = $ubigeo_district->where("id", $location_clear[2]);

                        // Si no es un caracter valido dentro de las base de datos, entonces se devolverá un null
                        $deparment = $deparment_find->count() == 0 ? null : $location_clear[0];
                        $province = $province_find->count() == 0 ? null : $location_clear[1];
                        $district = $district_find->count() == 0 ? null : $location_clear[2];

                        return ['PE', $deparment, $province, $district];
                    } else {
                        return $item_object;
                    }
                });

                return $parse_item->all();
            });

            // Tabla de person address 
            // | person_id | country_id (PE) | department_id | province_id | district_id | address | phone | email | main | establishment_code 

            $array_dispatch_data = $dispatch_address_data->all();
            foreach ($array_dispatch_data as $data_dispatch) {
                $array_data_insert_one = [];

                //person_id 
                $array_data_insert_one["person_id"] = $data_dispatch["person_id"];

                //country_id
                $array_data_insert_one["country_id"] = $data_dispatch["location_id"][0];

                //department_id
                $array_data_insert_one["department_id"] = $data_dispatch["location_id"][1];

                //province_id
                $array_data_insert_one["province_id"] = $data_dispatch["location_id"][2];

                //district_id
                $array_data_insert_one["district_id"] = $data_dispatch["location_id"][3];

                //address
                $array_data_insert_one["address"] = $data_dispatch["address"];

                //phone
                $array_data_insert_one["phone"] = null;

                // TODO  investigar que va en main 
                $array_data_insert_one["main"] = 0;

                //establishment_code 
                $array_data_insert_one["establishment_code"] = $data_dispatch["establishment_code"];

                $data_insert_all->push($array_data_insert_one);
            }

            DB::table('person_addresses')->insert($data_insert_all->all());

            DB::table('configurations')->update([
                'is_migrated_address' => true
            ]);
        }


        $marcas = [
            ['marca_id' => 'abarth',           'nombre' => 'Abarth',           'make_country' => 'Italy'],
            ['marca_id' => 'ac',               'nombre' => 'AC',               'make_country' => 'UK'],
            ['marca_id' => 'acura',            'nombre' => 'Acura',            'make_country' => 'USA'],
            ['marca_id' => 'alfa-romeo',       'nombre' => 'Alfa Romeo',       'make_country' => 'Italy'],
            ['marca_id' => 'allard',           'nombre' => 'Allard',           'make_country' => 'UK'],
            ['marca_id' => 'alpina',           'nombre' => 'Alpina',           'make_country' => 'UK'],
            ['marca_id' => 'alpine',           'nombre' => 'Alpine',           'make_country' => 'Germany'],
            ['marca_id' => 'alvis',            'nombre' => 'Alvis',            'make_country' => 'UK'],
            ['marca_id' => 'amc',              'nombre' => 'AMC',              'make_country' => 'USA'],
            ['marca_id' => 'ariel',            'nombre' => 'Ariel',            'make_country' => 'UK'],
            ['marca_id' => 'armstrong-siddeley', 'nombre' => 'Armstrong Siddeley', 'make_country' => 'UK'],
            ['marca_id' => 'ascari',           'nombre' => 'Ascari',           'make_country' => 'UK'],
            ['marca_id' => 'aston-martin',     'nombre' => 'Aston Martin',     'make_country' => 'UK'],
            ['marca_id' => 'audi',             'nombre' => 'Audi',             'make_country' => 'Germany'],
            ['marca_id' => 'austin',           'nombre' => 'Austin',           'make_country' => 'UK'],
            ['marca_id' => 'austin-healey',    'nombre' => 'Austin-Healey',    'make_country' => 'UK'],
            ['marca_id' => 'autobianchi',      'nombre' => 'Autobianchi',      'make_country' => 'Italy'],
            ['marca_id' => 'auverland',        'nombre' => 'Auverland',        'make_country' => 'France'],
            ['marca_id' => 'avanti',           'nombre' => 'Avanti',           'make_country' => 'USA'],
            ['marca_id' => 'beijing',          'nombre' => 'Beijing',          'make_country' => 'China'],
            ['marca_id' => 'bentley',          'nombre' => 'Bentley',          'make_country' => 'UK'],
            ['marca_id' => 'berkeley',         'nombre' => 'Berkeley',         'make_country' => 'UK'],
            ['marca_id' => 'bitter',           'nombre' => 'Bitter',           'make_country' => 'Germany'],
            ['marca_id' => 'bizzarrini',       'nombre' => 'Bizzarrini',       'make_country' => 'Italy'],
            ['marca_id' => 'bmw',              'nombre' => 'BMW',              'make_country' => 'Germany'],
            ['marca_id' => 'brilliance',       'nombre' => 'Brilliance',       'make_country' => 'China'],
            ['marca_id' => 'bristol',          'nombre' => 'Bristol',          'make_country' => 'UK'],
            ['marca_id' => 'bugatti',          'nombre' => 'Bugatti',          'make_country' => 'Italy'],
            ['marca_id' => 'buick',            'nombre' => 'Buick',            'make_country' => 'USA'],
            ['marca_id' => 'cadillac',         'nombre' => 'Cadillac',         'make_country' => 'USA'],
            ['marca_id' => 'caterham',         'nombre' => 'Caterham',         'make_country' => 'UK'],
            ['marca_id' => 'checker',          'nombre' => 'Checker',          'make_country' => 'USA'],
            ['marca_id' => 'chevrolet',        'nombre' => 'Chevrolet',        'make_country' => 'USA'],
            ['marca_id' => 'chrysler',         'nombre' => 'Chrysler',         'make_country' => 'USA'],
            ['marca_id' => 'citroen',          'nombre' => 'Citroen',          'make_country' => 'France'],
            ['marca_id' => 'dacia',            'nombre' => 'Dacia',            'make_country' => 'Romania'],
            ['marca_id' => 'daewoo',           'nombre' => 'Daewoo',           'make_country' => 'South Korea'],
            ['marca_id' => 'daf',              'nombre' => 'DAF',              'make_country' => 'Netherlands'],
            ['marca_id' => 'daihatsu',         'nombre' => 'Daihatsu',         'make_country' => 'Japan'],
            ['marca_id' => 'daimler',          'nombre' => 'Daimler',          'make_country' => 'UK'],
            ['marca_id' => 'datsun',           'nombre' => 'Datsun',           'make_country' => 'Japan'],
            ['marca_id' => 'de-tomaso',        'nombre' => 'De Tomaso',        'make_country' => 'Italy'],
            ['marca_id' => 'dkw',              'nombre' => 'DKW',              'make_country' => 'Germany'],
            ['marca_id' => 'dodge',            'nombre' => 'Dodge',            'make_country' => 'USA'],
            ['marca_id' => 'donkervoort',      'nombre' => 'Donkervoort',      'make_country' => 'Netherlands'],
            ['marca_id' => 'eagle',            'nombre' => 'Eagle',            'make_country' => 'USA'],
            ['marca_id' => 'fairthorpe',       'nombre' => 'Fairthorpe',       'make_country' => 'UK'],
            ['marca_id' => 'ferrari',          'nombre' => 'Ferrari',          'make_country' => 'Italy'],
            ['marca_id' => 'fiat',             'nombre' => 'Fiat',             'make_country' => 'Italy'],
            ['marca_id' => 'fisker',           'nombre' => 'Fisker',           'make_country' => 'USA'],
            ['marca_id' => 'ford',             'nombre' => 'Ford',             'make_country' => 'USA'],
            ['marca_id' => 'gaz',              'nombre' => 'GAZ',              'make_country' => 'Russia'],
            ['marca_id' => 'geely',            'nombre' => 'Geely',            'make_country' => 'China'],
            ['marca_id' => 'ginetta',          'nombre' => 'Ginetta',          'make_country' => 'UK'],
            ['marca_id' => 'gmc',              'nombre' => 'GMC',              'make_country' => 'USA'],
            ['marca_id' => 'holden',           'nombre' => 'Holden',           'make_country' => 'Australia'],
            ['marca_id' => 'honda',            'nombre' => 'Honda',            'make_country' => 'Japan'],
            ['marca_id' => 'hudson',           'nombre' => 'Hudson',           'make_country' => 'USA'],
            ['marca_id' => 'humber',           'nombre' => 'Humber',           'make_country' => 'UK'],
            ['marca_id' => 'hummer',           'nombre' => 'Hummer',           'make_country' => 'USA'],
            ['marca_id' => 'hyundai',          'nombre' => 'Hyundai',          'make_country' => 'South Korea'],
            ['marca_id' => 'infiniti',         'nombre' => 'Infiniti',         'make_country' => 'Japan'],
            ['marca_id' => 'innocenti',        'nombre' => 'Innocenti',        'make_country' => 'Italy'],
            ['marca_id' => 'isuzu',            'nombre' => 'Isuzu',            'make_country' => 'Japan'],
            ['marca_id' => 'italdesign',       'nombre' => 'Italdesign',       'make_country' => 'Italy'],
            ['marca_id' => 'jaguar',           'nombre' => 'Jaguar',           'make_country' => 'UK'],
            ['marca_id' => 'jeep',             'nombre' => 'Jeep',             'make_country' => 'USA'],
            ['marca_id' => 'jensen',           'nombre' => 'Jensen',           'make_country' => 'UK'],
            ['marca_id' => 'kia',              'nombre' => 'Kia',              'make_country' => 'South Korea'],
            ['marca_id' => 'koenigsegg',       'nombre' => 'Koenigsegg',       'make_country' => 'Sweden'],
            ['marca_id' => 'lada',             'nombre' => 'Lada',             'make_country' => 'Russia'],
            ['marca_id' => 'lamborghini',      'nombre' => 'Lamborghini',      'make_country' => 'Italy'],
            ['marca_id' => 'lancia',           'nombre' => 'Lancia',           'make_country' => 'Italy'],
            ['marca_id' => 'land-rover',       'nombre' => 'Land Rover',       'make_country' => 'UK'],
            ['marca_id' => 'lexus',            'nombre' => 'Lexus',            'make_country' => 'Japan'],
            ['marca_id' => 'lincoln',          'nombre' => 'Lincoln',          'make_country' => 'USA'],
            ['marca_id' => 'lotec',            'nombre' => 'Lotec',            'make_country' => 'Germany'],
            ['marca_id' => 'lotus',            'nombre' => 'Lotus',            'make_country' => 'UK'],
            ['marca_id' => 'luxgen',           'nombre' => 'Luxgen',           'make_country' => 'Taiwan'],
            ['marca_id' => 'mahindra',         'nombre' => 'Mahindra',         'make_country' => 'India'],
            ['marca_id' => 'marcos',           'nombre' => 'Marcos',           'make_country' => 'UK'],
            ['marca_id' => 'maserati',         'nombre' => 'Maserati',         'make_country' => 'Italy'],
            ['marca_id' => 'matra-simca',      'nombre' => 'Matra-Simca',      'make_country' => 'France'],
            ['marca_id' => 'maybach',          'nombre' => 'Maybach',          'make_country' => 'Germany'],
            ['marca_id' => 'mazda',            'nombre' => 'Mazda',            'make_country' => 'Japan'],
            ['marca_id' => 'mcc',              'nombre' => 'MCC',              'make_country' => 'Germany'],
            ['marca_id' => 'mclaren',          'nombre' => 'McLaren',          'make_country' => 'UK'],
            ['marca_id' => 'mercedes-benz',    'nombre' => 'Mercedes-Benz',    'make_country' => 'Germany'],
            ['marca_id' => 'mercury',          'nombre' => 'Mercury',          'make_country' => 'USA'],
            ['marca_id' => 'mg',               'nombre' => 'MG',               'make_country' => 'UK'],
            ['marca_id' => 'mini',             'nombre' => 'Mini',             'make_country' => 'UK'],
            ['marca_id' => 'mitsubishi',       'nombre' => 'Mitsubishi',       'make_country' => 'Japan'],
            ['marca_id' => 'monteverdi',       'nombre' => 'Monteverdi',       'make_country' => 'Switzerland'],
            ['marca_id' => 'moretti',          'nombre' => 'Moretti',          'make_country' => 'Italy'],
            ['marca_id' => 'morgan',           'nombre' => 'Morgan',           'make_country' => 'UK'],
            ['marca_id' => 'morris',           'nombre' => 'Morris',           'make_country' => 'UK'],
            ['marca_id' => 'nissan',           'nombre' => 'Nissan',           'make_country' => 'Japan'],
            ['marca_id' => 'noble',            'nombre' => 'Noble',            'make_country' => 'UK'],
            ['marca_id' => 'nsu',              'nombre' => 'NSU',              'make_country' => 'Germany'],
            ['marca_id' => 'oldsmobile',       'nombre' => 'Oldsmobile',       'make_country' => 'USA'],
            ['marca_id' => 'opel',             'nombre' => 'Opel',             'make_country' => 'Germany'],
            ['marca_id' => 'packard',          'nombre' => 'Packard',          'make_country' => 'USA'],
            ['marca_id' => 'pagani',           'nombre' => 'Pagani',           'make_country' => 'Italy'],
            ['marca_id' => 'panoz',            'nombre' => 'Panoz',            'make_country' => 'USA'],
            ['marca_id' => 'peugeot',          'nombre' => 'Peugeot',          'make_country' => 'France'],
            ['marca_id' => 'pininfarina',      'nombre' => 'Pininfarina',      'make_country' => 'Italy'],
            ['marca_id' => 'plymouth',         'nombre' => 'Plymouth',         'make_country' => 'USA'],
            ['marca_id' => 'pontiac',          'nombre' => 'Pontiac',          'make_country' => 'USA'],
            ['marca_id' => 'porsche',          'nombre' => 'Porsche',          'make_country' => 'Germany'],
            ['marca_id' => 'proton',           'nombre' => 'Proton',           'make_country' => 'Malaysia'],
            ['marca_id' => 'reliant',          'nombre' => 'Reliant',          'make_country' => 'UK'],
            ['marca_id' => 'renault',          'nombre' => 'Renault',          'make_country' => 'France'],
            ['marca_id' => 'riley',            'nombre' => 'Riley',            'make_country' => 'UK'],
            ['marca_id' => 'rolls-royce',      'nombre' => 'Rolls-Royce',      'make_country' => 'UK'],
            ['marca_id' => 'rover',            'nombre' => 'Rover',            'make_country' => 'UK'],
            ['marca_id' => 'saab',             'nombre' => 'Saab',             'make_country' => 'Sweden'],
            ['marca_id' => 'saleen',           'nombre' => 'Saleen',           'make_country' => 'USA'],
            ['marca_id' => 'samsung',          'nombre' => 'Samsung',          'make_country' => 'South Korea'],
            ['marca_id' => 'saturn',           'nombre' => 'Saturn',           'make_country' => 'USA'],
            ['marca_id' => 'scion',            'nombre' => 'Scion',            'make_country' => 'Japan'],
            ['marca_id' => 'seat',             'nombre' => 'Seat',             'make_country' => 'Spain'],
            ['marca_id' => 'simca',            'nombre' => 'Simca',            'make_country' => 'France'],
            ['marca_id' => 'singer',           'nombre' => 'Singer',           'make_country' => 'UK'],
            ['marca_id' => 'skoda',            'nombre' => 'Skoda',            'make_country' => 'Czech Republic'],
            ['marca_id' => 'smart',            'nombre' => 'Smart',            'make_country' => 'France'],
            ['marca_id' => 'spyker',           'nombre' => 'Spyker',           'make_country' => 'Netherlands'],
            ['marca_id' => 'ssangyong',        'nombre' => 'SsangYong',        'make_country' => 'South Korea'],
            ['marca_id' => 'ssc',              'nombre' => 'SSC',              'make_country' => 'USA'],
            ['marca_id' => 'steyr',            'nombre' => 'Steyr',            'make_country' => 'Austria'],
            ['marca_id' => 'studebaker',       'nombre' => 'Studebaker',       'make_country' => 'USA'],
            ['marca_id' => 'subaru',           'nombre' => 'Subaru',           'make_country' => 'Japan'],
            ['marca_id' => 'sunbeam',          'nombre' => 'Sunbeam',          'make_country' => 'UK'],
            ['marca_id' => 'suzuki',           'nombre' => 'Suzuki',           'make_country' => 'Japan'],
            ['marca_id' => 'talbot',           'nombre' => 'Talbot',           'make_country' => 'UK'],
            ['marca_id' => 'tata',             'nombre' => 'Tata',             'make_country' => 'India'],
            ['marca_id' => 'tatra',            'nombre' => 'Tatra',            'make_country' => 'Czech Republic'],
            ['marca_id' => 'tesla',            'nombre' => 'Tesla',            'make_country' => 'USA'],
            ['marca_id' => 'toyota',           'nombre' => 'Toyota',           'make_country' => 'Japan'],
            ['marca_id' => 'trabant',          'nombre' => 'Trabant',          'make_country' => 'Germany'],
            ['marca_id' => 'triumph',          'nombre' => 'Triumph',          'make_country' => 'UK'],
            ['marca_id' => 'tvr',              'nombre' => 'TVR',              'make_country' => 'UK'],
            ['marca_id' => 'vauxhall',         'nombre' => 'Vauxhall',         'make_country' => 'Germany'],
            ['marca_id' => 'vector',           'nombre' => 'Vector',           'make_country' => 'Japan'],
            ['marca_id' => 'venturi',          'nombre' => 'Venturi',          'make_country' => 'France'],
            ['marca_id' => 'volkswagen',       'nombre' => 'Volkswagen',       'make_country' => 'Germany'],
            ['marca_id' => 'volvo',            'nombre' => 'Volvo',            'make_country' => 'Sweden'],
            ['marca_id' => 'wartburg',         'nombre' => 'Wartburg',         'make_country' => 'Germany'],
            ['marca_id' => 'westfield',        'nombre' => 'Westfield',        'make_country' => 'UK'],
            ['marca_id' => 'willys-overland',  'nombre' => 'Willys-Overland',  'make_country' => 'USA'],
            ['marca_id' => 'xedos',            'nombre' => 'Xedos',            'make_country' => 'Japan'],
            ['marca_id' => 'zagato',           'nombre' => 'Zagato',           'make_country' => 'Italy'],
            ['marca_id' => 'zastava',          'nombre' => 'Zastava',          'make_country' => 'Serbia'],
            ['marca_id' => 'zaz',              'nombre' => 'ZAZ',              'make_country' => 'Ukraine'],
            ['marca_id' => 'zenvo',            'nombre' => 'Zenvo',            'make_country' => 'Denmark'],
            ['marca_id' => 'zil',              'nombre' => 'ZIL',              'make_country' => 'Russia'],
        ];

        DB::table('marcas')->insert($marcas);

        $this->obtenerYGuardarModelos();
    }

    /**
     * Obtener y guardar modelos de una marca específica.
     *
     * @param string $makeId
     * @param int $marcaId
     */
    private function obtenerYGuardarModelos()
    {
        $modelos = [
            [
                "model_name" => "1000",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1000 Bialbero ",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1000 GT",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1000 TC Corsa",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "103 GT",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "124",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "205",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "207",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "208",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "209",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "210",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "2200",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "2400",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "595",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "700",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "800 Scorpione Coupe Allemano",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "A 112",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Bialbero",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Grande Punto",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Lancia 037",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Mono 1000",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Monomille",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Monotipo",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "OT",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Renault",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Simca",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Spider Riviera",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "Stola",
                "model_make_id" => "abarth",
                "marca_id" => "abarth"
            ],
            [
                "model_name" => "2-Litre",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "427",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "428",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "Ace",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "Aceca",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "Aceca-Bristol",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "Cobra",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "Greyhound",
                "model_make_id" => "ac",
                "marca_id" => "ac"
            ],
            [
                "model_name" => "CL",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "CL-X",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "CSX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "EL",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "ILX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "ILX Hybrid",
                "model_make_id" => "Acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "Integra",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "Legend",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "MDX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "NSX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "RDX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "RL",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "RLX",
                "model_make_id" => "Acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "RSX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "SLX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "TL",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "TLX",
                "model_make_id" => "Acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "TSX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "Vigor",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "ZDX",
                "model_make_id" => "acura",
                "marca_id" => "acura"
            ],
            [
                "model_name" => "145",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "146",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "147",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "155",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "156",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "159",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "164",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "166",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "175",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "1750",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "179",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "1900",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "2600",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "33",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "33 Race",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "33 Stradale",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "33 Tt",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "6C",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "75",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "8C Competizione",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "90",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Alfa 6",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Alfasud",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Alfetta",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "AR 51",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Arna",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Bella",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Berlina",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Brera",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Caimano",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Carabo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Centauri",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Crosswagon",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Cuneo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Dardo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Disco Volante",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Eagle",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Giulia",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Giulietta",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GP 158",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GP 159",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Graduate",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GTA",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GTV",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GTV6",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Junior",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Kamal",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "MiTo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Navajo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Nuvola",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Proteo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "RZ",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Scarabeo",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Scighera",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Spider",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Sportiva",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Sportut",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Sportwagon",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "Sprint",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "SZ",
                "model_make_id" => "alfa-romeo",
                "marca_id" => "alfa-romeo"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "J",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "J1",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "J2",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "J2R",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "J2X",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "K1",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "K2",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "K3",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "M1",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "P1",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "Palm Beach",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "Safari",
                "model_make_id" => "allard",
                "marca_id" => "allard"
            ],
            [
                "model_name" => "2002",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "3",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B 10",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B 12",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B3",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B5",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B6",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "B7",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "D3",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "alpina",
                "marca_id" => "alpina"
            ],
            [
                "model_name" => "A",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 106",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 108",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 110",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 310",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 442",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "A 610",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "V6",
                "model_make_id" => "alpine",
                "marca_id" => "alpine"
            ],
            [
                "model_name" => "TA 14",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TA 21",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TB 14",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TB 21",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TC",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TC 108 G",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TC 21",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TD",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "TF",
                "model_make_id" => "alvis",
                "marca_id" => "alvis"
            ],
            [
                "model_name" => "Ambassador",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "AMX",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "AMX III",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Gremlin",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Hornet",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Javelin",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Matador",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Pacer",
                "model_make_id" => "amc",
                "marca_id" => "amc"
            ],
            [
                "model_name" => "Atom",
                "model_make_id" => "ariel",
                "marca_id" => "ariel"
            ],
            [
                "model_name" => "16",
                "model_make_id" => "armstrong-siddeley",
                "marca_id" => "armstrong-siddeley"
            ],
            [
                "model_name" => "Sapphire",
                "model_make_id" => "armstrong-siddeley",
                "marca_id" => "armstrong-siddeley"
            ],
            [
                "model_name" => "Star Sapphire",
                "model_make_id" => "armstrong-siddeley",
                "marca_id" => "armstrong-siddeley"
            ],
            [
                "model_name" => "Whitley",
                "model_make_id" => "armstrong-siddeley",
                "marca_id" => "armstrong-siddeley"
            ],
            [
                "model_name" => "A10",
                "model_make_id" => "ascari",
                "marca_id" => "ascari"
            ],
            [
                "model_name" => "Ecosse",
                "model_make_id" => "ascari",
                "marca_id" => "ascari"
            ],
            [
                "model_name" => "FG-T",
                "model_make_id" => "ascari",
                "marca_id" => "ascari"
            ],
            [
                "model_name" => "KZ1",
                "model_make_id" => "ascari",
                "marca_id" => "ascari"
            ],
            [
                "model_name" => "15",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "2-Litre",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "AM Vantage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Atom",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Cygnet",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB2",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB3",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB4",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB5",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB6",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB7",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DB9",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DBR2",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "DBS",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Lagonda",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "One-77",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Project Vantage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Rapide",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V12",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V12 Vantage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V8",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V8 Saloon",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V8 Vantage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V8 Volante",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "V8 Zagato",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Vanquish",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Vantage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "Virage",
                "model_make_id" => "aston-martin",
                "marca_id" => "aston-martin"
            ],
            [
                "model_name" => "100",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "200",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "4000",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "50",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "5000",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "80",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "90",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A1",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A2",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A3",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A3 e-tron",
                "model_make_id" => "Audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A4",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A5",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A6",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A7",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "A8",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "AD",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "AL2",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Allroad",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Asso",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Avantissimo",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Avus",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Fox",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "LeMans",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Nuvolari Quattro",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Pikes Peak Quattro",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Q3",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Q5",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Q7",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Quattro",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "R8",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "R8R",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Rosemeyer",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS 5",
                "model_make_id" => "Audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS 7",
                "model_make_id" => "Audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS2",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS3",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS4",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS5",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "RS6",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S2",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S3",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S4",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S5",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S6",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S7",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "S8",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Sport",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "SQ5",
                "model_make_id" => "Audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Steppenwolf",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Super 90",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "TT",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "TTS",
                "model_make_id" => "Audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "UR",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "V8",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "Variant",
                "model_make_id" => "audi",
                "marca_id" => "audi"
            ],
            [
                "model_name" => "10 HP",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "16",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "2200",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "3",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "3-Litre",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "7",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "8",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 110",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 125",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 135",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 30",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 35",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 40",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 55",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 60",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 70",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 90",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 95",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A 99",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "A135",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Allegro",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Ambassador",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Maestro",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Marina",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Maxi",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Mini Clubman",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Mini Cooper",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Mini Metro",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Mini Sky",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Montego",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "Princess",
                "model_make_id" => "austin",
                "marca_id" => "austin"
            ],
            [
                "model_name" => "100",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "3000",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "3000 Mk II",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "3000 Mk III",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "Sports Convertible",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "Sprite",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "Tickford",
                "model_make_id" => "austin-healey",
                "marca_id" => "austin-healey"
            ],
            [
                "model_name" => "A 112",
                "model_make_id" => "autobianchi",
                "marca_id" => "autobianchi"
            ],
            [
                "model_name" => "Bianchina",
                "model_make_id" => "autobianchi",
                "marca_id" => "autobianchi"
            ],
            [
                "model_name" => "Primula",
                "model_make_id" => "autobianchi",
                "marca_id" => "autobianchi"
            ],
            [
                "model_name" => "Stellina",
                "model_make_id" => "autobianchi",
                "marca_id" => "autobianchi"
            ],
            [
                "model_name" => "A3",
                "model_make_id" => "auverland",
                "marca_id" => "auverland"
            ],
            [
                "model_name" => "A4",
                "model_make_id" => "auverland",
                "marca_id" => "auverland"
            ],
            [
                "model_name" => "Avanti",
                "model_make_id" => "avanti",
                "marca_id" => "avanti"
            ],
            [
                "model_name" => "Sport Convertible",
                "model_make_id" => "avanti",
                "marca_id" => "avanti"
            ],
            [
                "model_name" => "Studebaker",
                "model_make_id" => "avanti",
                "marca_id" => "avanti"
            ],
            [
                "model_name" => "BJ 2020",
                "model_make_id" => "beijing",
                "marca_id" => "beijing"
            ],
            [
                "model_name" => "BJ 2021",
                "model_make_id" => "beijing",
                "marca_id" => "beijing"
            ],
            [
                "model_name" => "BJ 212",
                "model_make_id" => "beijing",
                "marca_id" => "beijing"
            ],
            [
                "model_name" => "Arnage",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Azure",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Brooklands",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental Flying Spur",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental GT",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental GTC",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental Supersports",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Continental T",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Exp Speed 8",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Flying Spur",
                "model_make_id" => "Bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Hunaudieres",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Java",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Mark VI",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "MK V",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Mulsane",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Mulsanne",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "R Type Continental",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "S1",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "S2",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "State Limousine",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "T",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "T2",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "Turbo",
                "model_make_id" => "bentley",
                "marca_id" => "bentley"
            ],
            [
                "model_name" => "B",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "B 95",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "Foursome",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "QB",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "Sports",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "Twosome",
                "model_make_id" => "berkeley",
                "marca_id" => "berkeley"
            ],
            [
                "model_name" => "CD",
                "model_make_id" => "bitter",
                "marca_id" => "bitter"
            ],
            [
                "model_name" => "Diplomat",
                "model_make_id" => "bitter",
                "marca_id" => "bitter"
            ],
            [
                "model_name" => "SC",
                "model_make_id" => "bitter",
                "marca_id" => "bitter"
            ],
            [
                "model_name" => "Type III",
                "model_make_id" => "bitter",
                "marca_id" => "bitter"
            ],
            [
                "model_name" => "A3C",
                "model_make_id" => "bizzarrini",
                "marca_id" => "bizzarrini"
            ],
            [
                "model_name" => "BZ-2001",
                "model_make_id" => "bizzarrini",
                "marca_id" => "bizzarrini"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "bizzarrini",
                "marca_id" => "bizzarrini"
            ],
            [
                "model_name" => "GTS",
                "model_make_id" => "bizzarrini",
                "marca_id" => "bizzarrini"
            ],
            [
                "model_name" => "Manta",
                "model_make_id" => "bizzarrini",
                "marca_id" => "bizzarrini"
            ],
            [
                "model_name" => "1 Series",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "116",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "118",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "120",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "123",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "125",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "128",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "130",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "135",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "1502",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "1602",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2 Series",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2.8",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2002",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2004",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "2800",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "3 Series",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "3 Series Gran Turismo",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "3.3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "315",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "316",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "317",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "318",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "320",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "3200 CS",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "323",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "324",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "325",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "328",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "330",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "332",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "333",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "335",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "340",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "4 Series",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "4 Series Gran Coupe",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "5 Series",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "5 Series Gran Turismo",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "5.8",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "501",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "502",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "503",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "507",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "518",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "520",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "523",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "524",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "525",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "528",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "529",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "530",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "535",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "538",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "540",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "545",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "550",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "6 series",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "6 Series Gran Coupe",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "628",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "630",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "633",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "635",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "640",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "645",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "650",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "7 Series",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "700",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "725",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "728",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "729",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "730",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "732",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "735",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "740",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "745",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "748",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "760",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "840",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "845",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "854",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "856",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "ActiveHybrid 3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "ActiveHybrid 5",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "ActiveHybrid 7",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Alpina",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "ALPINA B6 Gran Coupe",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "ALPINA B7",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "B7",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Breyton",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "CLS",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Compact",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Convertible",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Dinan",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Formula FB02",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Formula One",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "FW 27",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Isetta",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Just 4",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Karmann Asso di Quadri",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "L7",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "LMR",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M1",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M12",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M28i",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M4",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M5",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M6",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "M6 Gran Coupe",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Mini ACV 30",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "MM Roadster",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Pickster",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "S3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Touring",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Turbo",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "V12",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Veritas Rennsportwagen",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X Activity",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X Coupe",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X1",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X4",
                "model_make_id" => "BMW",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X5",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X5 M",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X6",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "X6 M",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z1",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z18",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z22",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z3",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z4",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z8",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z9",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "Z9 Gran Turismo Convertible",
                "model_make_id" => "bmw",
                "marca_id" => "bmw"
            ],
            [
                "model_name" => "BS4",
                "model_make_id" => "brilliance",
                "marca_id" => "brilliance"
            ],
            [
                "model_name" => "BS6",
                "model_make_id" => "brilliance",
                "marca_id" => "brilliance"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "401",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "402",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "403",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "404",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "405",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "406",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "407",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "408",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "409",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "410",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "411",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "412",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "450",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "603",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Beaufighter",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Blenheim",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Brigand",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Britannia",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Fighter",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "Project Fighter",
                "model_make_id" => "bristol",
                "marca_id" => "bristol"
            ],
            [
                "model_name" => "251",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "Chiron",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 110",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 112",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 118",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 18-3 Chiron",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 18-4 Veyron",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "EB 218",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "ID 90",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "Type 101",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "Type 68",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "Type 73",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "Veyron",
                "model_make_id" => "bugatti",
                "marca_id" => "bugatti"
            ],
            [
                "model_name" => "40",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "70",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Blackhawk",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Centieme",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Centurion",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Century",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Cielo",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Electra",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Enclave",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Encore",
                "model_make_id" => "Buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Estate",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "GS",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Invicta",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "LaCrosse",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "LeSabre",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Lucerne",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Park Avenue",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Rainier",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Reatta",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Regal",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Rendezvous",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Riviera",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Roadmaster",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Signia",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Skyhawk",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Skylark",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Terraza",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Verano",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "Wildcat",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "XP2000",
                "model_make_id" => "buick",
                "marca_id" => "buick"
            ],
            [
                "model_name" => "60",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "61",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "62",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "6239D",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Allante",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "ATS",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "ATS Coupe",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Aurora",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Biarritz",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "BLS",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Brougham",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Calais",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Catera",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Cimarron",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS Coupe",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS Wagon",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS-V",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS-V Coupe",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "CTS-V Wagon",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "DeVille",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "DTS",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Eldorado",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "ELR",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Escalade",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Escalade ESV",
                "model_make_id" => "Cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Fleetwood",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Imaj",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Le Mans",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "LMP",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Park Avenue",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Seville",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Sixty",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Solitaire",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "SRX",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "STS",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "STS-V",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "V Sixteen",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "Vizon",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "XLR",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "XTS ",
                "model_make_id" => "cadillac",
                "marca_id" => "cadillac"
            ],
            [
                "model_name" => "1700",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "21",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "7",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "Beaulieu",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "C21",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "Seven",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "Super 7",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "Superlight R",
                "model_make_id" => "caterham",
                "marca_id" => "caterham"
            ],
            [
                "model_name" => "Aerobus",
                "model_make_id" => "checker",
                "marca_id" => "checker"
            ],
            [
                "model_name" => "Town",
                "model_make_id" => "checker",
                "marca_id" => "checker"
            ],
            [
                "model_name" => "2103",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Adventure",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Aerovette",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Alero",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "APV",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Astro",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Astrovette",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Avalanche",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Aveo",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Bel Air",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Beretta",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Biscayne",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Blazer",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "C-10",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Camaro",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Caprice",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Captiva",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Cavalier",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Celebrity",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Celta",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Chevelle",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Chevette",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Cheyenne",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Citation",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Citation II",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "City Express",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Cobalt",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Colorado",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Comodoro",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Corsica",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Corvair",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Corvette",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Corvette Stingray",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Cruze",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "DeLuxe",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "DeVille",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "El Camino",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Epica",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Equinox",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Evanda",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Express",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Express Cargo",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Grand Blazer",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Half-Ton",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "HHR",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Impala",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Intimidator",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Journey",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "K-20",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Kalos",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Kodiak",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Lacetti",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Lumina",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Malibu",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Matiz",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Metro",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Monte Carlo",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Monza",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Nomad",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Nova",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Nubira",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Optra",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Orlando",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Pickup",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Prizm",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Relsamo",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Rezzo",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "S-10",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Sabia",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Silverado",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Silverado 1500",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Silverado 2500HD",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Silverado 3500HD",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Sonic",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Spark",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Spark EV",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Sprint",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "SS",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "SSR",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Suburban",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Tahoe",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Tandem 2000",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Tracker",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Trailblazer",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Trans Sport",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Traverse",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Trax",
                "model_make_id" => "Chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Triax",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Uplander",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Vega",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Venture",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Vivant",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "Volt",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "XP 882",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "XP 897 GT",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "XP 898",
                "model_make_id" => "chevrolet",
                "marca_id" => "chevrolet"
            ],
            [
                "model_name" => "160",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "1609",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "1610",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "200",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "200 CONVERTIBLE",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "300",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Airflite",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Aspen",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Atlantic",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Avenger",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Aviat",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "C",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "CCV",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Centura",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Charger",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Chronos",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Cirrus",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Concorde",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Conquest",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Cordoba",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Crossfire",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Dart",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Daytona",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Delta",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Dodge 3700",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Dodge Phoenix",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "E",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "ES",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "ESX 3",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Executive",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Expresso",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Grand Voyager",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "GS",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Howler",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Imperial",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Java",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Laser",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Le Baron",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "LHS",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Limousine",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Millenium",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Neon",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "New Yorker",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Newport",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Pacifica",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Panel Cruiser",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Phaeton",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "PT Cruiser",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "PT Dream Cruiser",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Sebring",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Sedan",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "TC",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Thunderbolt",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Town & Country",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Town and Country",
                "model_make_id" => "Chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Valiant",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Viper",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Voyager",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Windsor",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "Ypsilon",
                "model_make_id" => "chrysler",
                "marca_id" => "chrysler"
            ],
            [
                "model_name" => "1.6",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "11",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "15",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "2CV",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "7",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "7A",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Activa",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Ak 350",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Ami",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Aventure",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "AX",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Axel",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Berlingo",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Bijou",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "BX",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C 15",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C-Airdream",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C-Crosser",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C-Zero",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C1",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C2",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C3",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C4",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C5",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C6",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "C8",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "CX",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "DS",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Dyane",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Dyane 4",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Dyane 6",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Eco 2000",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Eole",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "GS",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "GSA",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "ID 19",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Karin",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "LN",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "LNA",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Mehari",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Mini-Zup",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Multispace",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Osee",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Picasso",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Pluriel",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Rally Raid",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Saxo",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "SM",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Traction",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Visa",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Xanae",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Xantia",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "XM",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Xsara",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "ZX",
                "model_make_id" => "citroen",
                "marca_id" => "citroen"
            ],
            [
                "model_name" => "Duster",
                "model_make_id" => "dacia",
                "marca_id" => "dacia"
            ],
            [
                "model_name" => "Logan",
                "model_make_id" => "dacia",
                "marca_id" => "dacia"
            ],
            [
                "model_name" => "Sandero",
                "model_make_id" => "dacia",
                "marca_id" => "dacia"
            ],
            [
                "model_name" => "Supernova",
                "model_make_id" => "dacia",
                "marca_id" => "dacia"
            ],
            [
                "model_name" => "Arcadia",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Brougham",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Chairman",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Espero",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Evanda",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Joyster",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Kalos",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Korando",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Lacetti",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Lanos",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Leganza",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Maepsy-Na",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Magnus",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Matiz",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Musiro",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Musson",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Nexia",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "No 1",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Nubira",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Prince",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Rexton",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Rezzo",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Tacuma",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Tocsa",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "Vada",
                "model_make_id" => "daewoo",
                "marca_id" => "daewoo"
            ],
            [
                "model_name" => "44",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "46",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "66",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "Daffodil",
                "model_make_id" => "daf",
                "marca_id" => "daf"
            ],
            [
                "model_name" => "Altis",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Applause",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Atrai",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Bee",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Charade",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Charmant",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Compagno",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Consorte",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Copen",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Cuore",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Delta",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Domino",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Fellow",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Feroza",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Fourtrak TDX",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Freeclimber",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Gran Move",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Leeza",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Materia",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Micros 3l",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Mira",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Move",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Muse",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Naked",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Opti",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Rocky",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Sirion",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "SP-4",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Taft",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Terios",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Terios II",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "Trevis",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "U4 B",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "YRV",
                "model_make_id" => "daihatsu",
                "marca_id" => "daihatsu"
            ],
            [
                "model_name" => "4.2",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "Conquest",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "DE 27",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "DE 36",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "DK",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "One-O-Four",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "Super Eight",
                "model_make_id" => "daimler",
                "marca_id" => "daimler"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "100A Cherry",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "240Z",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "260Z",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "280",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "280Z",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "Laurel",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "Violet",
                "model_make_id" => "datsun",
                "marca_id" => "datsun"
            ],
            [
                "model_name" => "Bigua",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Deauville",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Guara",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Longchamp",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Mangusta",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Pantera",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "Vallelunga",
                "model_make_id" => "de-tomaso",
                "marca_id" => "de-tomaso"
            ],
            [
                "model_name" => "3-6 Monza",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "F 102",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "F 11",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "F 89",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "F 91",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "F 93",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "Junior",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "Munga",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "Reichklasse F8",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "Universal",
                "model_make_id" => "dkw",
                "marca_id" => "dkw"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Aspen",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Avenger",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Caliber",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Caravan",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Challenger",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Charger",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Charger RT Concept",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Colt",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Conquest",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Copperhead",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Coronet",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Custom",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Dakota",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Dart",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Daytona",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Durango",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Dynasty",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Grand Caravan",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Hemi Super 8",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Intrepid",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Journey",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Kahuna",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Lancer",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "M80",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Magnum",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Maxx Cab",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Mirada",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Monaco",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Neon",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Nitro",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Omni",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Polara",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Power Box",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Prowler",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Ram",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Razor",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Shadow",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Sidewinder",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Spirit",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Sprinter",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "SRT Viper",
                "model_make_id" => "Dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Stealth",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Stratus",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "T-Rex 6x6",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Venom",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "Viper",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "WC 51",
                "model_make_id" => "dodge",
                "marca_id" => "dodge"
            ],
            [
                "model_name" => "D8",
                "model_make_id" => "donkervoort",
                "marca_id" => "donkervoort"
            ],
            [
                "model_name" => "S7",
                "model_make_id" => "donkervoort",
                "marca_id" => "donkervoort"
            ],
            [
                "model_name" => "S8",
                "model_make_id" => "donkervoort",
                "marca_id" => "donkervoort"
            ],
            [
                "model_name" => "Summit",
                "model_make_id" => "eagle",
                "marca_id" => "eagle"
            ],
            [
                "model_name" => "Talon",
                "model_make_id" => "eagle",
                "marca_id" => "eagle"
            ],
            [
                "model_name" => "Vision",
                "model_make_id" => "eagle",
                "marca_id" => "eagle"
            ],
            [
                "model_name" => "Atom",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "Atomata",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "Electrina",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "Electron",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "Rockette",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "TX-GT",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "TX-S",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "Zeta",
                "model_make_id" => "fairthorpe",
                "marca_id" => "fairthorpe"
            ],
            [
                "model_name" => "125",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "125 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "125S",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "126",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "156",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "158",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "159S",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "166",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "195",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "196",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "206",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "208",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "212",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "225",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "246",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 GT",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 GTE",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 GTO",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 LM",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 Mille Miglia",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "250 Testarossa",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "255 S",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "256 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "275",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "288 GTO",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "306",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "308",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "312",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "328 GTB",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "328 GTS",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "330",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "330GT",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "340 America",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "340 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "340 Mexico",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "340 MM",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "340 Spider",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "342 America",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "348",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "348 TS Targa",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "355 Spider",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "355 TS",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "360",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 BB",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 California",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GT",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GT4",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GTB",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GTC",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GTC 4",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "365 GTS",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "375 America",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "375 Indy",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "375 Mille Miglia",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "3Z",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "400 Superamerica",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "410 Superamerica",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "412 GT",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "412 MI",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "412 T2",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "456",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "456M",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "458",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "458 Italia",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "500 F2",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "500 Mondial",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "500 Superfast",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "500 Testarossa",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 BB",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 BBi",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 BBi Turbo",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 M",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 S",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "512 TR",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "550",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "553 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "553 F2",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "575 Superamerica",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "575M Maranello",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "599",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "599 GTB Fiorano",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "612",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "612 Scaglietti",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "625 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "735",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "801 F1",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "C62",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "California",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "D 50",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Dino",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Enzo",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F 2005",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 156",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 2000",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 86",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 88",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 89",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 90",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F1 93",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F310",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F333",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F355",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F399",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F40",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F430",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F50",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F512 M",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F55",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "F550",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "FF",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "FF HELE",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "GTO",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "KS 360 Modena",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Maranello",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Mondial",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Mythos",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "P2",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "P5",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Pinin",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Rossa",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Superamerica",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "Testarossa",
                "model_make_id" => "ferrari",
                "marca_id" => "ferrari"
            ],
            [
                "model_name" => "1100",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "124",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "125",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "126",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "127",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "128",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "130",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "131",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "132",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "133",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1400",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "1900",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "2100",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "2300",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "500e",
                "model_make_id" => "FIAT",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "500L",
                "model_make_id" => "FIAT",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "8V",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Abarth",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Albea",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Argenta",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Armadillo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Barchetta",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Berline",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Brava",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Bravo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Cabriolet",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Campagnola",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Cinquecento",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Croma",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Dino",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Doblo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Ducato",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Duna",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Ecobasic",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Ecobasis",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "ESV",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Fiorino",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Freemont",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Grand Break",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Grande Punto",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Idea",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Legram",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Linea",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Marea",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Marea Weekend",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Mirafiori",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Multipla",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "OT",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Palio",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Palio II",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Panda",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Punto",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Regata",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Ritmo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Scudo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Sedici",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Seicento",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Siena",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Stilo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Strada",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Tempra",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Tipo",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Ulysse",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Uno",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "V8",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Vivace",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "X1-9",
                "model_make_id" => "fiat",
                "marca_id" => "fiat"
            ],
            [
                "model_name" => "Karma",
                "model_make_id" => "fisker",
                "marca_id" => "fisker"
            ],
            [
                "model_name" => "021 C",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "12 M",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "17",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "17M",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "24.7",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "427",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "49",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Aerostar",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Anglia",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Artic",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Aspire",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Bantam",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Bronco",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Bronco II",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "C 100",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "C-MAX",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "C-Max Energi",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "C-Max Hybrid",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Capri",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Coin",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Consul",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Contour",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Corsair",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Cortina",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Cougar",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Courier",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Crestline",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Crown Victoria",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Custom",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Desert Excursion",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-150",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-250",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-350",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "e-Ka",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-Series",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-Series Van",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "E-Series Wagon",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Econoline",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Econovan",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Ecosport",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Edge",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Equator",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Escape",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Escort",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "EX",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Excursion",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Expedition",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Explorer",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Extreme EX",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-150",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-250",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-250 Super Duty",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-350",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-350 Super Duty",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-450",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-450 Super Duty",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "F-650",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Fairlane",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Falcon",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Festiva",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Fiesta",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Five Hundred",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Flex",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Focus",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Focus ST",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "FPV BF GT",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Freestar",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Freestyle",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Fusion",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Fusion Energi",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Fusion Hybrid",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Galaxie",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Galaxy",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Gran Torino",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Granada",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "GT 40",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "GT 500",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "GT 70",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Husky",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Ikon",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Indigo",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Ka",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Kuga",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Laser",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Libre",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Limited",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Lotus Cortina",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "LTD",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Lynx",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Maverick",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Megastar II",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Meteor",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Model U",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Monarch",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Mondeo",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Mustang",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "O21 C",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Orion",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Pilot",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Popular",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Prefect",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Probe",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Puma",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Ranchero",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Ranger",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Royale",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "RS 200",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "S-Max",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Saetta",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Scorpio",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Shelby GR-1 Concept",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Shelby GT 500",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Shelby GT500",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Sierra",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Skyliner",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "ST 460",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Station Wagon",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Synergy 2010",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Taunus",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Taurus",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Taurus X",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "TE-50",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Telstar",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Tempo",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Territory",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Think",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Think Neighbor",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Thunderbird",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "TL-50",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Tonka",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Torino",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Tracer",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Transit Connect",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Transit Van",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Transit Wagon",
                "model_make_id" => "Ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Triton",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "TS-50",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Urban Explorer",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Vedette",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Versailles",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Windstar",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "XR 8 Xplod",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Zephyr",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "Zodiac",
                "model_make_id" => "ford",
                "marca_id" => "ford"
            ],
            [
                "model_name" => "22",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "24",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "24-10",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "3110",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "3111",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "61",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "M-20",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "M1",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "M20",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "Oct-73",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "Volga",
                "model_make_id" => "gaz",
                "marca_id" => "gaz"
            ],
            [
                "model_name" => "CK",
                "model_make_id" => "geely",
                "marca_id" => "geely"
            ],
            [
                "model_name" => "Ck1",
                "model_make_id" => "geely",
                "marca_id" => "geely"
            ],
            [
                "model_name" => "G",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G10",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G11",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G12",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G15",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G20",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G21",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G27",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G3",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G32",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G33",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G34",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G4",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "G40",
                "model_make_id" => "ginetta",
                "marca_id" => "ginetta"
            ],
            [
                "model_name" => "Acadia",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Autonomy",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Canyon",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Envoy",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "EV1",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Firebird",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Jimmy",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Safari",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Savana",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Sierra",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Sierra 1500",
                "model_make_id" => "GMC",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Sierra 2500HD",
                "model_make_id" => "GMC",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Sierra 3500HD",
                "model_make_id" => "GMC",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Sonoma",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Suburban",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Terra 4",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Terracross",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Terradyne",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Terrain",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Typhoon",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Yukon",
                "model_make_id" => "gmc",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Yukon XL",
                "model_make_id" => "GMC",
                "marca_id" => "gmc"
            ],
            [
                "model_name" => "Apollo",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Astra",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Barina",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Belmont",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Berlina",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Brougham",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Calais",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Camira",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Caprice",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Captiva",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Clubsport",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Colorado",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Combo",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Commodore",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Cruze",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Drover",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "EH",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "EJ",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "EK",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Epica",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "FB Special",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "FC",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "FE",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "FJ",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Frontera",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "FX",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Gemini",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "GTS",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "GTS-R",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "HB",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "HD",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "HR",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "HRT",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "HSV",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Jackaroo",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Kingswood",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Maloo",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Monaro",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Nova",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Rodeo",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Senator",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Statesman",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Sunbird",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Torana",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "UTE",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Vectra",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "XU 6",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "Zafira",
                "model_make_id" => "holden",
                "marca_id" => "holden"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "145",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Accord",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Accord Crosstour",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Accord Hybrid",
                "model_make_id" => "Honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Accord Plug-In Hybrid",
                "model_make_id" => "Honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Acty",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Argento Viva",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Avancier",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Ballade",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Capa",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "City",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Civic",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Civic Del Sol",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Concerto",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "CR-V",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "CR-Z",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Crosstour",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "CRX",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Element",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "EV",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "F1",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "FCX",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Fit",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "FR-V",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Fuya Jo",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "HP-X",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "HR-V",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Insight",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Inspire",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Integra",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "J-VX",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Jazz",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Lagreat",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Legend",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Life",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Logo",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Mobilio",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Model X",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "N III",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "NSX",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Odyssey",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Passport",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Pilot",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Prelude",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Quintet",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Ridgeline",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "S-MX",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "S2000",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "S500",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "S600",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "S800",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Saber",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Shuttle",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "SSM",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Step Wagon",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Stream",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "That",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Today",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Torneo",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Vamos",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Z",
                "model_make_id" => "honda",
                "marca_id" => "honda"
            ],
            [
                "model_name" => "Commodore",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Hornet",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Italia Coupe",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Metropolitan",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Super Jet",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Super Wasp",
                "model_make_id" => "hudson",
                "marca_id" => "hudson"
            ],
            [
                "model_name" => "Hawk",
                "model_make_id" => "humber",
                "marca_id" => "humber"
            ],
            [
                "model_name" => "Pullman",
                "model_make_id" => "humber",
                "marca_id" => "humber"
            ],
            [
                "model_name" => "Sceptre",
                "model_make_id" => "humber",
                "marca_id" => "humber"
            ],
            [
                "model_name" => "Super Snipe",
                "model_make_id" => "humber",
                "marca_id" => "humber"
            ],
            [
                "model_name" => "H1",
                "model_make_id" => "hummer",
                "marca_id" => "hummer"
            ],
            [
                "model_name" => "H2",
                "model_make_id" => "hummer",
                "marca_id" => "hummer"
            ],
            [
                "model_name" => "H3",
                "model_make_id" => "hummer",
                "marca_id" => "hummer"
            ],
            [
                "model_name" => "Accent",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Atos",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Azera",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Clix",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Dynasty",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Elantra",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Elantra GT",
                "model_make_id" => "Hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Entourage",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Equus",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Euro 1",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Excel",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Galloper",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Genesis",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Genesis Coupe",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Getz",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Grandeur",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "H1",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "H100",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "HCD-7",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "HCD-III",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "HED-5",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "HED-6",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "i10",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "i20",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "i30",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "i40",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "ix20",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "ix35",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Lantra",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Marcia",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Matrix",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Neos",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Panel Van",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Pony",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Portico",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Santa Fe",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Santa Fe Sport",
                "model_make_id" => "Hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Satellite",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Scoupe",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Sonata",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Sonata Hybrid",
                "model_make_id" => "Hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Stellar",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Terracan",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Tiburon",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Tipper",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Trajet",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Tucson",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Veloster",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "Veracruz",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "XG",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "XG 300",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "XG 350",
                "model_make_id" => "hyundai",
                "marca_id" => "hyundai"
            ],
            [
                "model_name" => "EX",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "FX",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "G",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "G20",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "G25",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "G35",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "G37",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "I",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "I30",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "I35",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "IPL",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "J30",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "JX",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q40",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q45",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q50",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q60 Convertible",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q60 Coupe",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "Q70",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX4",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX50",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX56",
                "model_make_id" => "infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX60",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX70",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "QX80",
                "model_make_id" => "Infiniti",
                "marca_id" => "infiniti"
            ],
            [
                "model_name" => "950",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "990",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "A 40",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "A40S",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "C",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "Elba",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "IM 3",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "J4",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "Koral",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "Mille",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "Mini",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "Turbo De Tomaso",
                "model_make_id" => "innocenti",
                "marca_id" => "innocenti"
            ],
            [
                "model_name" => "117",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "3.1",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "4200",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Amigo",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Ascender",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Axiom",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Bellel",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Bellett",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "D-MAX",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Florian",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Frontera",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Gemini",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Hombre",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "I-280",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "I-290",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "I-350",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Impulse",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Kai",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "KB",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Minx",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "New Bellel",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Piazza",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Rodeo",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Stylus",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Trooper",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "VehiCross",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "VX-02",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "VX4",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Wizard",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "ZXS",
                "model_make_id" => "isuzu",
                "marca_id" => "isuzu"
            ],
            [
                "model_name" => "Aspid",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Buron",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Columbus",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Formula 4",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Legram",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "M12",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Machimoto",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Medusa",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Nazca",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Tapiro",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Twenty Twenty",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Visconti",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "Volta",
                "model_make_id" => "italdesign",
                "marca_id" => "italdesign"
            ],
            [
                "model_name" => "220",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "240",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "3.8",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "420G",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "C-Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Concept Eight",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "E Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "E-Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "F-Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Kensington",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Mark IV",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK 10",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK II",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK IV",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK IX",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK V",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK VII",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK VIII",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "MK X",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "R Coupe",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "R-D6",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "S-Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Sovereign",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "SS",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Type E",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Type S",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Type-C",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Vanden Plas",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "X-300",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "X-Type",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "X400",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XF",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJ",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJ-13",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJ220",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJ6",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJ8",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJR",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJR-11",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJR-15",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XJS",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XK",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XK8",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XKA",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XKR",
                "model_make_id" => "jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "XS",
                "model_make_id" => "Jaguar",
                "marca_id" => "jaguar"
            ],
            [
                "model_name" => "Cherokee",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ2",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ2A",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ3A",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ5",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "CJ7",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Commander",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Compass",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Dakar",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Grand Cherokee",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Grand Cherokee SRT",
                "model_make_id" => "Jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Grand Wagoneer",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Icon",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Jeepster",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Liberty",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "MB",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Patriot",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Renegade",
                "model_make_id" => "Jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Station Wagon",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Varsity",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Willys",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Willys 2",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "Wrangler",
                "model_make_id" => "jeep",
                "marca_id" => "jeep"
            ],
            [
                "model_name" => "4-Litre",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "541",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "CV-8",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "FF",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "Healey",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "Interceptor",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "Jensen-Healey",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "SP",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "Straight 8",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "SV-8",
                "model_make_id" => "jensen",
                "marca_id" => "jensen"
            ],
            [
                "model_name" => "Amanti",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Avella",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Borrego",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Brisa",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Cadenza",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Capital",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Carens",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Carnival",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Cee'd",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Cerato",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Clarus",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Elan",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Forte",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Joice",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "K2700",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "K900",
                "model_make_id" => "Kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "KCV-4",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Magentis",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Opirus",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Optima",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Optima Hybrid",
                "model_make_id" => "Kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Picanto",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Potentia",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Pregio",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Pride Wagon",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Pro-ceed",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Retona",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Rio",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Rondo",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Sedona",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Sephia",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Sephia II",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Shuma",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Shuma II",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Sorento",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Soul",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Soul EV",
                "model_make_id" => "Kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Spectra",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Spectra5",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Sportage",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Towner",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Venga",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Visto",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Xedos",
                "model_make_id" => "kia",
                "marca_id" => "kia"
            ],
            [
                "model_name" => "Agera",
                "model_make_id" => "koenigsegg",
                "marca_id" => "koenigsegg"
            ],
            [
                "model_name" => "CC8S",
                "model_make_id" => "koenigsegg",
                "marca_id" => "koenigsegg"
            ],
            [
                "model_name" => "CCR",
                "model_make_id" => "koenigsegg",
                "marca_id" => "koenigsegg"
            ],
            [
                "model_name" => "CCX",
                "model_make_id" => "koenigsegg",
                "marca_id" => "koenigsegg"
            ],
            [
                "model_name" => "CCXR",
                "model_make_id" => "koenigsegg",
                "marca_id" => "koenigsegg"
            ],
            [
                "model_name" => "110",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "111",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "112",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "117",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "119",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "21",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2104",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2105",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2107",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2110",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2111",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "2123",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Calina 1118",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Granta",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Kalina",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Natacha",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Niva",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Oka",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Riva",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "S",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Samara",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Samara II",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Tarzan",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "Vaz",
                "model_make_id" => "lada",
                "marca_id" => "lada"
            ],
            [
                "model_name" => "350 GT",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Aventador",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Bravo",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Cala",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Concept S",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Countach",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Diablo",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Espada",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Flying Star",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Gallardo",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Huracan",
                "model_make_id" => "Lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Islero",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Jalpa",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Jarama",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "LM",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "LM002",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Marco Polo",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Marzal",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Miura",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Murcielago",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "P140",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Portofino",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Project P140",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "Urraco",
                "model_make_id" => "lamborghini",
                "marca_id" => "lamborghini"
            ],
            [
                "model_name" => "037 Rallye",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "A 112",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Appia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Aprilia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Ardea 3",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Aurelia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Beta",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "D 50",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Dedra",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Delta",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Dialogos",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "ECV",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Flaminia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Flavia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Fulvia",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Gamma",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Hit",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Hyena",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Ionos",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "K",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Kappa",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Lybra",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Megagamma",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Musa",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Nea",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Phedra",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Prisma",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Scorpion",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Stratos",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Thema",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Thesis",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Trevi",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Voyager",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Y10",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Ypsilon",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "Zeta",
                "model_make_id" => "lancia",
                "marca_id" => "lancia"
            ],
            [
                "model_name" => "109",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "88",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "A 109",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "ALL-NEW Range Rover",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Defender",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Discovery",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Discovery 3",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Freelander",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "I",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "LR2",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "LR3",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "LR4",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Range Rover",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Range Rover Evoque",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Range Rover Sport",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Serie I",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Serie II",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "Serie III",
                "model_make_id" => "land-rover",
                "marca_id" => "land-rover"
            ],
            [
                "model_name" => "CT",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "CT 200h",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "Daytona",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "ES",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "ES 300h",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "ES 350",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "FLV",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "GS",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "GS 350",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "GS 450h",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "GX",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "GX 460",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "HS",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS 250",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS 250 C",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS 350",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS 350 C",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "IS F",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LF-C",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LFA",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LS",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LS 460",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LS 600h L",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LX",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "LX 570",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "Minority Report",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "NX 200t",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "NX 300h",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "RC 350",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "RC F",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "RX",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "RX 350",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "RX 450h",
                "model_make_id" => "Lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "SC",
                "model_make_id" => "lexus",
                "marca_id" => "lexus"
            ],
            [
                "model_name" => "Aviator",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Blackwood",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Capri",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Continental",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "LS",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark 7",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark LT",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark VI",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark VII",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark VIII",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Mark X",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MK 9",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MKC",
                "model_make_id" => "Lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MKS",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MKT",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MKX",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "MKZ",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Navigator",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Premiere",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Sentinel",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Town Car",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "Zephyr",
                "model_make_id" => "lincoln",
                "marca_id" => "lincoln"
            ],
            [
                "model_name" => "C 1000",
                "model_make_id" => "lotec",
                "marca_id" => "lotec"
            ],
            [
                "model_name" => "Sirius",
                "model_make_id" => "lotec",
                "marca_id" => "lotec"
            ],
            [
                "model_name" => "Testa de Oro",
                "model_make_id" => "lotec",
                "marca_id" => "lotec"
            ],
            [
                "model_name" => "11",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "16",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "25",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "33",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "340 R",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "49",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "72",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "79",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Carlton",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Eclat",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Elan",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Elise",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Elite",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Emme",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Esprit",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Etna",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Europa",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Evora",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Excel",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Exige",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Extreme",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "GT 1",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "M250",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Seven",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "XI Le Mans",
                "model_make_id" => "lotus",
                "marca_id" => "lotus"
            ],
            [
                "model_name" => "Luxgen7",
                "model_make_id" => "luxgen",
                "marca_id" => "luxgen"
            ],
            [
                "model_name" => "Armada",
                "model_make_id" => "mahindra",
                "marca_id" => "mahindra"
            ],
            [
                "model_name" => "Bolero",
                "model_make_id" => "mahindra",
                "marca_id" => "mahindra"
            ],
            [
                "model_name" => "CL",
                "model_make_id" => "mahindra",
                "marca_id" => "mahindra"
            ],
            [
                "model_name" => "Commander",
                "model_make_id" => "mahindra",
                "marca_id" => "mahindra"
            ],
            [
                "model_name" => "Scorpio",
                "model_make_id" => "mahindra",
                "marca_id" => "mahindra"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "2 Litres",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "GTS",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Le Mans 500",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Mantara",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Mantis",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Mantula",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Mini Marcos",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "TS",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "TS500",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "TSO",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "Ugly Duckling",
                "model_make_id" => "marcos",
                "marca_id" => "marcos"
            ],
            [
                "model_name" => "124",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "150S",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "151",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "228",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "250",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "300",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "3200 GT",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "3500",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "350S",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "4",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "420",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "420M",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "430",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "450",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "4CL",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "4CLT",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "5000 GT",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "6C",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "8",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "8CL",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "8CLT",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "A6",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "A6G",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "A6GCM",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "A6GCS",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Auge",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Barchetta Straale",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Bi Turbo",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Boomerang",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Bora",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Ghibli",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Gran Turismo",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Gran Turismo 3500",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "GranSport",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "GranTurismo",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "GranTurismo Convertible",
                "model_make_id" => "Maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "GT 3500",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Indy",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Khamsin",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Kubang",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Kyalami",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "MC12",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Merak",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Mexico",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Mistral",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Quattroporte",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Royale",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Shamal",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Simun",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Spider 3500",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Spyder",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Tipo 60",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Tipo 61",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Tipo 64",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Tipo 65",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "V8 GranSport",
                "model_make_id" => "maserati",
                "marca_id" => "maserati"
            ],
            [
                "model_name" => "Bagheera",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "D Jet",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M25",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M530",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M630",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M650",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M660",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M670",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "M72",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "Murena",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "Rancho",
                "model_make_id" => "matra-simca",
                "marca_id" => "matra-simca"
            ],
            [
                "model_name" => "57",
                "model_make_id" => "maybach",
                "marca_id" => "maybach"
            ],
            [
                "model_name" => "62",
                "model_make_id" => "maybach",
                "marca_id" => "maybach"
            ],
            [
                "model_name" => "Landaulet",
                "model_make_id" => "maybach",
                "marca_id" => "maybach"
            ],
            [
                "model_name" => "SW",
                "model_make_id" => "maybach",
                "marca_id" => "maybach"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "110 S",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "121",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "2",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "3",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "323",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "5",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "6",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "616",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "626",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "787",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "818",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "929",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Activehicle",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Atenza",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "AZ",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "AZ-1",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "B2300",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "B2500",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "B3000",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "B4000",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Brown Collection Verisa",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "BT-50",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Carol",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Chante",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Cosmo",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CU-X",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CX-05",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CX-09",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CX-5",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CX-7",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "CX-9",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Demio",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Drifter",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "DrifterX",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Etude",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Eunos",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Familia",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Kusabi",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Lantis",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Laputa",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Levante",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Luce",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Marathon",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MAZDA2",
                "model_make_id" => "Mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MAZDA3",
                "model_make_id" => "Mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Mazda5",
                "model_make_id" => "Mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MAZDA6",
                "model_make_id" => "Mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MazdaSpeed3",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MazdaSpeed6",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Midge",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Millenia",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Montrose",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MPV",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MS-6",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MS-8",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MS-9",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MX",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MX-3",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MX-5 Miata",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MX-6",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "MX-Flexa",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Persona",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Premacy",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Protege",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "R 360",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "R-100",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Rustler",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX 4 Coupe",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX Evolv",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-01",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-2",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-3",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-4",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-7",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-8",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "RX-Evolv",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Sentia",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "SPEED3",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Spiano",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Sport Wagon",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "SW-X",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Tribute",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Washu",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Xedos 9",
                "model_make_id" => "mazda",
                "marca_id" => "mazda"
            ],
            [
                "model_name" => "Crossblade",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "ForFour",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "ForTwo",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "Silverpulse",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "Smart",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "Smart & Pulse City Coupe",
                "model_make_id" => "mcc",
                "marca_id" => "mcc"
            ],
            [
                "model_name" => "650S Coupe",
                "model_make_id" => "McLaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "650S Spider",
                "model_make_id" => "McLaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "F1",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "F1 GTR",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "F1 LM",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M10",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M14",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M19",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M1C",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M21",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M23",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "M8E",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "MP4",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "MP4-12C",
                "model_make_id" => "mclaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "P1",
                "model_make_id" => "McLaren",
                "marca_id" => "mclaren"
            ],
            [
                "model_name" => "170 V",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "170S",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "180",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "190",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "200",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "219",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "220",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "230",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "230.4",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "240",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "250",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "260",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "280",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "300",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "300B",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "300D",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "300S",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "320",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "350",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "380",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "420",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "450",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "560",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "770",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "A",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "B",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Bionic Car Concept",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "C",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "C 111",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "C 112",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "C-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "C30",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CK",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CL",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CL-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLA-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLC",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLK",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLK GTR",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLK LM",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLS",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CLS-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "CW",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "E",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "E-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "E420",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "E500",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "F200",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "F300 Life Jet",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "G",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "G-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "GL",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "GL-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "GLA-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "GLK",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "GLK-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "M-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "MCC",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "ML",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Ponton",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "R",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "S",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "S-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SC",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SE",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SL",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SL-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLK",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLK-Class",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLR McLaren",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLS",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLS AMG GT",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "SLS AMG GT Final Edition",
                "model_make_id" => "Mercedes-Benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Smart",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Sprinter",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Studie",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Swatch",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "T",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "T V-12",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "V",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Vaneo",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Version Longue",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Viano",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Vision",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Vito",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "W 110",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "W 111",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "W 123 Coupe",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "W 136",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "W 196",
                "model_make_id" => "mercedes-benz",
                "marca_id" => "mercedes-benz"
            ],
            [
                "model_name" => "Antser",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Brougham",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Capri",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Comet",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Cougar",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Cyclone",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "El Gato",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Grand Marquis",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "LN7",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Lynx",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Marauder",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Mariner",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "MC4",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Milan",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Montclair",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Montego",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Monterey",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Mountaineer",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Mystique",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Park Lane",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Sable",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Tracer",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "Villager",
                "model_make_id" => "mercury",
                "marca_id" => "mercury"
            ],
            [
                "model_name" => "1100",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "EX-E",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "Magnett",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "MGA",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "MGB",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "MGC",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "Midget",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "Rover",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "RV8",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "TD",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "TF",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "X10",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "X20",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "X80",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "XPower",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "YB",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "ZR",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "ZS",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "ZT",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "ZT-T",
                "model_make_id" => "mg",
                "marca_id" => "mg"
            ],
            [
                "model_name" => "Clubman",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper Clubman",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper Countryman",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper Coupe",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper Paceman",
                "model_make_id" => "MINI",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Cooper Roadster",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Countryman",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK I",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK II",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK III",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK IV",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK V",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK VI",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "MK VII",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "One",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "mini",
                "marca_id" => "mini"
            ],
            [
                "model_name" => "3000GT",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "A10",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "ASX",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Carisma",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Celeste",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Challenger",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Colt",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Debonair",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Delica Space Gear",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Diamante",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Dingo",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Dion",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Eclipse",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "eK",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Endeavor",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Field Guard",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "FTO",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Galant",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Gaus",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Grandis",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "GTO",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "HSR-V",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "i-MiEV",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Jeep",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "L 200",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Lancer",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Lancer Evolution",
                "model_make_id" => "Mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Lancer Sportback",
                "model_make_id" => "Mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Magna",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Minica",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Mirage",
                "model_make_id" => "Mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Montero",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Mum 500",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Nessie",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Outlander",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Outlander Sport",
                "model_make_id" => "Mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Pajero",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Proudia",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Raider",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "RPM 7000",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "RVR",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Sapporo",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Shogun",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Shogun Pinin",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Sigma",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Space Liner",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Space Runner",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Space Star",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Space Wagon",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "SSS",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "SST",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "SSU",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Starion",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "SUP",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "SUW",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Valley",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "Verada",
                "model_make_id" => "mitsubishi",
                "marca_id" => "mitsubishi"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "375",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Berlinetta",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Hai 375",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Hai 450",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Hai 650",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "High Speed 375",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Military 250 Z Zivil",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Safari",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Sahara",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Sierra",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "Tiara",
                "model_make_id" => "monteverdi",
                "marca_id" => "monteverdi"
            ],
            [
                "model_name" => "1100",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "127",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "2500",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "750S Berlina",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "850S",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "Golden",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "La Cita",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "Panoramica",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "S Coupe",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "Spider Turismo",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "SS",
                "model_make_id" => "moretti",
                "marca_id" => "moretti"
            ],
            [
                "model_name" => "4",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "4 Plus 2100",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "4 Seater",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "44",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Aero",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Aero 8",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Aeromax",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "F Super",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Plus 4",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Plus 4 Plus",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Plus 8",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "morgan",
                "marca_id" => "morgan"
            ],
            [
                "model_name" => "1100",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Cowley",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Isis",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Marina",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Mini Moke",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Minor",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Oxford",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Six",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Ten Four",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "Traveller",
                "model_make_id" => "morris",
                "marca_id" => "morris"
            ],
            [
                "model_name" => "100 NX",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "110",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "1400",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "200 SX Silvia",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "211",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "240 C",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "260 ZX",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "270 R",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "300 ZX",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "350Z",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "370Z",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "400 R",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "AA-X",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Almera",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Alpha",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Altima",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Armada",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Avenir",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "AXY",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "AZ-1",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Bluebird",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "C 52",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Cedric",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Cefiro",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Chappo",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Cherry",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Cima",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Commercial",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "CQ-X",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Crew",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Cube",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "DS-2",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "E20",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "El Grand",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Fairlady",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Frontier",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Fusion",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Gloria",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Grand Livina",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "GT-R",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Hardbody",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "HyperMini",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Ideo",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Interstar",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Juke",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Lafesta",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Laurel",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Leaf",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Leopard",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Livina",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Maxima",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Micra",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Mid4",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "MM",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Moco",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Murano",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Murano CrossCabriolet",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Navara",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Note",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "NP300",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "NV",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "NV200",
                "model_make_id" => "Nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Pathfinder",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Patrol",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Pickup",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Pintara",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Pixo",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Platina",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Prairie",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Presea",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "President",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Primera",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Pulsar",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Qashqai",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Quest",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "R 390 GT1",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "R 391",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Rasheen",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Rogue",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Santana",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Sedan",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Sentra",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Serena",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Silvia",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Skyline",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Sport",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Sports",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Stagea",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Stanza",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Sunny",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "SUT",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Terrano",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Terrano II",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Tiida",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Titan",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Trailrunner",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Vanette",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Versa",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Versa Note",
                "model_make_id" => "Nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Violet",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "X-Trail",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Xterra",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "XVL",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Z",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "Zaroot",
                "model_make_id" => "nissan",
                "marca_id" => "nissan"
            ],
            [
                "model_name" => "M10",
                "model_make_id" => "noble",
                "marca_id" => "noble"
            ],
            [
                "model_name" => "M12",
                "model_make_id" => "noble",
                "marca_id" => "noble"
            ],
            [
                "model_name" => "M14",
                "model_make_id" => "noble",
                "marca_id" => "noble"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "1000 C",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "1000 LS",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Autonova",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Prinz",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Prinz I",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Prinz IV",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Ro 80",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Sport",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "Wankel Spider",
                "model_make_id" => "nsu",
                "marca_id" => "nsu"
            ],
            [
                "model_name" => "442",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "442 Indy Pace Car",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "66",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "88",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "98",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Achieva",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Aerotech",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Alero",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Anthem",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Aurora",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Bravada",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Custom Cruiser",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Cutlass",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Delta 88",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Eighty-Eight",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Incas",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Intrigue",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Jetfire",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "LSS",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Ninety-Eight",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "O4",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Omega",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Profile",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Recon",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Silhouette",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "SS",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Starfire",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Toronado",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Trofeo",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Vista Cruiser",
                "model_make_id" => "oldsmobile",
                "marca_id" => "oldsmobile"
            ],
            [
                "model_name" => "Admiral",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Agila",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Ampera",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Antara",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Ascona",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Astra",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Calibra",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Combo",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Commodore",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Concept M",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Corsa",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Diplomat",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Filo",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Frogster",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Frontera",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "GT",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Insignia",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Kadett",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Kapitan",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Kraftfahrzeug",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Lotus Omega",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Manta",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Meriva",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Monterey",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Monza",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Movano",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Olympia",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Omega",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "P 1200",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Record",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Rekord",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Senator",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Signum",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Speedster",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Tigra",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Trixx",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Vectra",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Vita",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Vivaro",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "Zafira",
                "model_make_id" => "opel",
                "marca_id" => "opel"
            ],
            [
                "model_name" => "12",
                "model_make_id" => "packard",
                "marca_id" => "packard"
            ],
            [
                "model_name" => "Carribean",
                "model_make_id" => "packard",
                "marca_id" => "packard"
            ],
            [
                "model_name" => "Patrician",
                "model_make_id" => "packard",
                "marca_id" => "packard"
            ],
            [
                "model_name" => "Huayra",
                "model_make_id" => "pagani",
                "marca_id" => "pagani"
            ],
            [
                "model_name" => "Zonda",
                "model_make_id" => "pagani",
                "marca_id" => "pagani"
            ],
            [
                "model_name" => "AIV",
                "model_make_id" => "panoz",
                "marca_id" => "panoz"
            ],
            [
                "model_name" => "Esperante",
                "model_make_id" => "panoz",
                "marca_id" => "panoz"
            ],
            [
                "model_name" => "Q9",
                "model_make_id" => "panoz",
                "marca_id" => "panoz"
            ],
            [
                "model_name" => "1007",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "104",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "106",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "107",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "202",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "203",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "204",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "205",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "206",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "207",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "3008",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "304",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "305",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "306",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "307",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "308",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "309",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "4007",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "4008",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "403",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "404",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "405",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "406",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "407",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "408",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "5008",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "504",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "504D",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "505",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "508",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "604",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "605",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "607",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "806",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "807",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "907",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Boxer",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Escapade",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Expert",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "H2O",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "iOn",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Kart Up",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Nautilus",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Oxia",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Partner",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Peugette",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Promethee",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Proxima",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Quasar",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "RC",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "RCZ",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Sesame",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Touareg",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Tulip",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Type 202",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "Vroomster",
                "model_make_id" => "peugeot",
                "marca_id" => "peugeot"
            ],
            [
                "model_name" => "33",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Argento",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Birdcage",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Enjoy",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Eta",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Hit",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Metrocubo",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Rossa",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Start",
                "model_make_id" => "pininfarina",
                "marca_id" => "pininfarina"
            ],
            [
                "model_name" => "Acclaim",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Barracuda",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Belvedere",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Breeze",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Cambridge",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Caravelle",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Colt",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Fury",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Gran Fury",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "GTX",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Horizon",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Laser",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Neon",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Pronto Cruiser",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Pronto Spyder",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Prowler",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Reliant",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Road Runner",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Sundance",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Superbird",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Trail Duster",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Turismo",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Valiant",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "VIP",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Volare",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "Voyager",
                "model_make_id" => "plymouth",
                "marca_id" => "plymouth"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "6000",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Aztek",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Banshee",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Bonneville",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Catalina",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Fiero",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Firebird",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Firehawk",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "G3",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "G5",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "G6",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "G8",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "GPX",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Grand Am",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Grand Prix",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Grand Safari",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Grande Parisienne",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "GTO",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Lemans",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Montana",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Phoenix",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Piranha",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Rageous",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Salsa",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Solstice",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Star Chief",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Stinger",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Sunbird",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Sunfire",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "SV 6",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Tempest",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Torrent",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Trans Am",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Trans Sport",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Ventura",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Ventura II",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "Vibe",
                "model_make_id" => "pontiac",
                "marca_id" => "pontiac"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "3400",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "356",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "550 A",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "718",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "804",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "904",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "906",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "907",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "908",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "910",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "911",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "912",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "914",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "917",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "918 Spyder",
                "model_make_id" => "Porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "924",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "928",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "930",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "935",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "936",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "944",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "959",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "962",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "964",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "965",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "968",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "993",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "996",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Boxster",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Boxter",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Carrera GT",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Cayenne",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Cayman",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "DP",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "GT2",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "GT3",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Karisma",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Kremer 935 K3",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Macan",
                "model_make_id" => "Porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Mega Cabriolet Biturbo",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Pan Americana",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Panamera",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "RGT",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "Tapiro",
                "model_make_id" => "porsche",
                "marca_id" => "porsche"
            ],
            [
                "model_name" => "300",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Arena",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Gen",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Gen-2",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Impian",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Juara",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Perdana",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Persona",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Saloon",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Satria",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Savvy",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Tiara",
                "model_make_id" => "proton",
                "marca_id" => "proton"
            ],
            [
                "model_name" => "Bug",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "GTC",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Kitten",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Rebel",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Regal",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Sabre 4",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Sabre Six",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "Scimitar",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "SE6",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "SE7",
                "model_make_id" => "reliant",
                "marca_id" => "reliant"
            ],
            [
                "model_name" => "10",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "11",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "12",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "14",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "15",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "17",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "18",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "19",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "20",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "21",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "25",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "30",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "4",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "5",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "6",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "8",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "9",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Alpine",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Argos",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Avantime",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Be Bop",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Caravelle",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Clio",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Colorale",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Dauphine",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Dauphine Gordini",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Duster",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Ellypse",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Espace",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Espider",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Etoile",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Express",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Fifties",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Floride",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Fluence",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Fregate",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Fuego",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Grand Espace",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Grand Scenic",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Helem",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Juva",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Kangoo",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Koleos",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Laguna",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Latitude",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Logan",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Ludo",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Megan",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Megane",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Modus",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "P55",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "R5",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Racoon",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "RE",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Rodeo",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "RS 11",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Safrane",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Sandero",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Scenic",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Scenic II",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Siete",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Spider",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Sport Spider",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Super 5",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Symbol",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Talisman",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Thalia",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Trafic",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Twingo",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Twizy",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "TXE",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Vel Satis",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Wind",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Zo",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Zoe",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "Zoom",
                "model_make_id" => "renault",
                "marca_id" => "renault"
            ],
            [
                "model_name" => "1.5",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "2.6",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "4",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "Apr-68",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "Elf",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "Kestrel",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "MR II",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "One-Point-Five",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "Pathfinder",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "RM A",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "Two Point Six",
                "model_make_id" => "riley",
                "marca_id" => "riley"
            ],
            [
                "model_name" => "100",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Camargue",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Corniche",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Ghost",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Ghost Series II",
                "model_make_id" => "Rolls-Royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Park Ward",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Phantom",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Phantom Drophead Coupe",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Cloud",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Cloud II",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Dawn",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Seraph",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Shadow",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Spirit",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Spirit II",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Spur",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Silver Wraith",
                "model_make_id" => "rolls-royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "Wraith",
                "model_make_id" => "Rolls-Royce",
                "marca_id" => "rolls-royce"
            ],
            [
                "model_name" => "100",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "100 1.4 D",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "200",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "214",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "216",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "220",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "25",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "2600",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "3.5",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "3.5 Litre",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "3500",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "414i",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "416i",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "420",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "45",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "620i",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "75",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "800",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "820",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "825",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "825i",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "City",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Land Rover Discovery",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Metro",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "MGF",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Mini",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Montego",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "P2",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "P4",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "P5",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "P5B",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "P6",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Range Rover",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Streetwise",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "Vitesse",
                "model_make_id" => "rover",
                "marca_id" => "rover"
            ],
            [
                "model_name" => "3-Sep",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "5-Sep",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "9-2x",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "9-4X",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "9-7X",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "9-X",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "90",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "900",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "9000",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "900i",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "92",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "92B",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "93",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "94",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "95",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "96",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "99",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "MC",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "Sonett",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "Sonett II",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "Sonett III",
                "model_make_id" => "saab",
                "marca_id" => "saab"
            ],
            [
                "model_name" => "S7",
                "model_make_id" => "saleen",
                "marca_id" => "saleen"
            ],
            [
                "model_name" => "SM",
                "model_make_id" => "samsung",
                "marca_id" => "samsung"
            ],
            [
                "model_name" => "SM3",
                "model_make_id" => "samsung",
                "marca_id" => "samsung"
            ],
            [
                "model_name" => "SM5",
                "model_make_id" => "samsung",
                "marca_id" => "samsung"
            ],
            [
                "model_name" => "Astra",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Aura",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Curve",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "ION",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "L",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "LS",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "LW",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Outlook",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Relay",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "SC",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Sedan",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Sky",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "SL",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Sports Coupe",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "Vue",
                "model_make_id" => "saturn",
                "marca_id" => "saturn"
            ],
            [
                "model_name" => "FR-S",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "FR-S Convertible",
                "model_make_id" => "Scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "iQ",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "T2B",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "tC",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "xA",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "xB",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "xD",
                "model_make_id" => "scion",
                "marca_id" => "scion"
            ],
            [
                "model_name" => "124",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "127",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "131",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "133",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "1400",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "1430",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "600",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Alhambra",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Altea",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Arosa",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Bolero",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Cordoba",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Exeo",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Formula",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Ibiza",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Inca Kombi",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Leon",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Malaga",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Marbella",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Ritmo",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Ronda",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Salsa",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Tango",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "Toledo",
                "model_make_id" => "seat",
                "marca_id" => "seat"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "11",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1100",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1200S",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1301",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1307",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1308",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1309",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "1501",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "2 Litres",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "5",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "6",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "8",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Ariane",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Aronde",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Horizon",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Oceane",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Plein Ceil",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Type 11 Monoplace",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Vedette",
                "model_make_id" => "simca",
                "marca_id" => "simca"
            ],
            [
                "model_name" => "Gazelle",
                "model_make_id" => "singer",
                "marca_id" => "singer"
            ],
            [
                "model_name" => "Hunter",
                "model_make_id" => "singer",
                "marca_id" => "singer"
            ],
            [
                "model_name" => "SM 1500",
                "model_make_id" => "singer",
                "marca_id" => "singer"
            ],
            [
                "model_name" => "Vogue II",
                "model_make_id" => "singer",
                "marca_id" => "singer"
            ],
            [
                "model_name" => "Vogue III",
                "model_make_id" => "singer",
                "marca_id" => "singer"
            ],
            [
                "model_name" => "1000 MB",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "110 L",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "1200",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "1202",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "440",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Fabia",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Favorit",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Felicia",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "L",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Montreux",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Octavia",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Polular 995",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Popular",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Praktik",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Rapid R",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Roomster",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "S 110 R",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Superb",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Yeti",
                "model_make_id" => "skoda",
                "marca_id" => "skoda"
            ],
            [
                "model_name" => "Brabus",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "Cabrio Passion",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "ForFour",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "ForTwo",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "Ultimate 112",
                "model_make_id" => "smart",
                "marca_id" => "smart"
            ],
            [
                "model_name" => "C8",
                "model_make_id" => "spyker",
                "marca_id" => "spyker"
            ],
            [
                "model_name" => "Actyon",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Chairman H",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Chairman W",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Korando",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Kyron",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Musso",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Rexton",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Rodius",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Stavic",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "W Coupe",
                "model_make_id" => "ssangyong",
                "marca_id" => "ssangyong"
            ],
            [
                "model_name" => "Aero",
                "model_make_id" => "ssc",
                "marca_id" => "ssc"
            ],
            [
                "model_name" => "Tuatara",
                "model_make_id" => "ssc",
                "marca_id" => "ssc"
            ],
            [
                "model_name" => "Ultimate Aero",
                "model_make_id" => "ssc",
                "marca_id" => "ssc"
            ],
            [
                "model_name" => "126",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "220",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "500",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "500D",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "650T",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "700C",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "G-series",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "Haflinger",
                "model_make_id" => "steyr",
                "marca_id" => "steyr"
            ],
            [
                "model_name" => "Avanti",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "Champion",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "Commander",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "President State",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "Silver Hawk",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "Sky Hawk",
                "model_make_id" => "studebaker",
                "marca_id" => "studebaker"
            ],
            [
                "model_name" => "1.8",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "1400",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "360",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Alfa",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "B",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "B9 Tribeca",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Baja",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "BRZ",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "DL",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "FF-1",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Forester",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "G3X",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "HM-01",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Impreza",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Impreza WRX",
                "model_make_id" => "Subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Justy",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "K 111",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Legacy",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Leone",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Mini Jumbo",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Outback",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Outback Sport",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Pleo",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "R-2",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "R2",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Rex",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "STX",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "SVX",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Traviq",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Tribeca",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "Vivio",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "WRX",
                "model_make_id" => "Subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "WX 01",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "XT",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "XV",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "XV Crosstrek",
                "model_make_id" => "subaru",
                "marca_id" => "subaru"
            ],
            [
                "model_name" => "10",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "2 Litres",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "2-Litre",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Alpine",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Chamois",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Imp",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Rapier",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Tiger",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Venezia",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Vogue",
                "model_make_id" => "sunbeam",
                "marca_id" => "sunbeam"
            ],
            [
                "model_name" => "Aerio",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Alto",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Baleno",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "C2",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Cappuccino",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Cervo",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Covie",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Equator",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Escudo",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Esteem",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "EV Sport",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "F1",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Forenza",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Fronte",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Grand Vitara",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "GSX R4",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Ignis",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Jimny",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Kizashi",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Lapin",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Liana",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Light",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "LJ 20",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "LJ 50",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Reno",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Samurai",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "SC 100 GX",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Sea Forenza Wagon",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Sidekick",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "SJ 410",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "SJ 413",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Splash",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Swift",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "SX",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "SX4",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Twin",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Verona",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Vitara",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "Wagon R+",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "X2",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "X90",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "XL6",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "XL7",
                "model_make_id" => "suzuki",
                "marca_id" => "suzuki"
            ],
            [
                "model_name" => "1510",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "2500",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Baby",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Horizon",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Lago America",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Samba",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Saoutchik",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Solara",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Sport",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Sunbeam-Lotus",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "T 26",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Tagora",
                "model_make_id" => "talbot",
                "marca_id" => "talbot"
            ],
            [
                "model_name" => "Aria",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "B-Line",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "E",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "E220",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Estate",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Indica",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Indigo",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Mint",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Nano",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Safari",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "Sumo",
                "model_make_id" => "tata",
                "marca_id" => "tata"
            ],
            [
                "model_name" => "MTX",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T107",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T600",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T603",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T613",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T700",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "T87",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "Tatraplan",
                "model_make_id" => "tatra",
                "marca_id" => "tatra"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "tesla",
                "marca_id" => "tesla"
            ],
            [
                "model_name" => "1000",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "105",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "2000GT",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "4Runner",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "AA",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Allion",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Altezza",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Aristo",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Ateva",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Auris",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Avalon",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Avalon Hybrid",
                "model_make_id" => "Toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Avanza",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Avensis",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Aygo",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Bandeirante",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "BB",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Blizzard",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Brevis",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Caldina",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Camry",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Camry Hybrid",
                "model_make_id" => "Toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Carina",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Caserta",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Celica",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Celsior",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Century",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Chaser",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Coaster",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Condor",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Conquest",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Corolla",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Corona",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Corsa",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Cressida",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Cresta",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Crown",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Dyna",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Echo",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "ES 3",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "F-1",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "FCHV 5",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "FJ Cruiser",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Fortuner",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "FXS",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "GT1",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Hi-Ace",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Highlander",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Highlander Hybrid",
                "model_make_id" => "Toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Hilux",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "HMV",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Ipsum",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "iQ",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Land Cruiser",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Lexcen",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Lite Ace",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Mark II",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Master RR",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Matrix",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Mega Cruiser",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Model F",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "MR-S",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "MR2",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "MRJ",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Opa",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Paseo",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Picnic",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Pod",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Previa",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Prius",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Prius C",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Prius Plug-in",
                "model_make_id" => "Toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Prius V",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Progress NC 300",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Publica",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Quantum",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Ractis",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "RAV4",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "RAV4 EV",
                "model_make_id" => "Toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Retro Cruiser",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "RSC",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "RunX",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "SA",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sequoia",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sera",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sienna",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Soarer",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Solara",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sparky",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sport 800",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Sprinter",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Stallion",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Starlet",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Super",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Supra",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Tacoma",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Tazz",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Tercel",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Tundra",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Venture",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Venza",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Verossa",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Vitz",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Will",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Windom",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "XYR",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "Yaris",
                "model_make_id" => "toyota",
                "marca_id" => "toyota"
            ],
            [
                "model_name" => "P 50",
                "model_make_id" => "trabant",
                "marca_id" => "trabant"
            ],
            [
                "model_name" => "P 601",
                "model_make_id" => "trabant",
                "marca_id" => "trabant"
            ],
            [
                "model_name" => "P 70",
                "model_make_id" => "trabant",
                "marca_id" => "trabant"
            ],
            [
                "model_name" => "Universal",
                "model_make_id" => "trabant",
                "marca_id" => "trabant"
            ],
            [
                "model_name" => "10 Break",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "2.5",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "2000",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Acclaim",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Dolomite",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Dove GTR4",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "GT6",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Herald",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Mayflower",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Renown",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Roadster",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Spitfire",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Stag",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Toledo",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR2",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR3",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR4",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR5",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR6",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR7",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "TR8",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "Vitesse",
                "model_make_id" => "triumph",
                "marca_id" => "triumph"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "2500",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "3000",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "350i",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "390",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "420",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "7",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Cerbera",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Cerbera Speed 12",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Chimaera",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Grantura",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Griffith",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "M",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "S",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "S2",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Sagaris",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "SE",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Speed 12",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Speed Eight",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "T 350",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "T 440 R",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Taimar",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Tamora",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Tasmin",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Trident",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Tuscan",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Tuscan Speed Six",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Type S",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "V8 S",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Vixen",
                "model_make_id" => "tvr",
                "marca_id" => "tvr"
            ],
            [
                "model_name" => "Agila",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Astra",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Belmont",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Calibra",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Carlton",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Cavalier",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Chevette",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Corsa",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Cresta",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Firenza",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Frontera",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Magnum",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Maxx",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Meriva",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Monaro",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Nova",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Omega",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Royale",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Signum",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Sintra",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Tigra",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Vectra",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Velox",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Ventora",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Victor",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Viscount",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Viva",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "VX Lightning",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "VX220",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "VX4",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Wyvern",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "Zafira",
                "model_make_id" => "vauxhall",
                "marca_id" => "vauxhall"
            ],
            [
                "model_name" => "M12",
                "model_make_id" => "vector",
                "marca_id" => "vector"
            ],
            [
                "model_name" => "RD 180",
                "model_make_id" => "vector",
                "marca_id" => "vector"
            ],
            [
                "model_name" => "Wiegert",
                "model_make_id" => "vector",
                "marca_id" => "vector"
            ],
            [
                "model_name" => "400",
                "model_make_id" => "venturi",
                "marca_id" => "venturi"
            ],
            [
                "model_name" => "Atlantique",
                "model_make_id" => "venturi",
                "marca_id" => "venturi"
            ],
            [
                "model_name" => "Cabriolet",
                "model_make_id" => "venturi",
                "marca_id" => "venturi"
            ],
            [
                "model_name" => "Coupe",
                "model_make_id" => "venturi",
                "marca_id" => "venturi"
            ],
            [
                "model_name" => "1 Litre",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "1302",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "1303",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "1600",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "181",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "411",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "AAC",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Beetle",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Beetle Convertible",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Bora",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Cabriolet",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Caddy",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Caravelle",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "CC",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Citi",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Commercial",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Concept C",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Concept T",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Corrado",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Derby",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "e-Golf",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Eos",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Eurovan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Fox",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Fusca",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "GLI",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Gol",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Golf",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Golf GTI",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Golf R",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Golf SportWagen",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "GTD",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "GTI",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Hac",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Iltis",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Jetta",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Jetta GLI",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Jetta Hybrid",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Jetta SportWagen",
                "model_make_id" => "Volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "K 70",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Karmann-Ghia",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Kombi",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "LT 35 HR Panel Van",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Lupo",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Magellan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Microbus",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Multivan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "New Beetle",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Parati",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Passat",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Phaeton",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Pickup",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Polo",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Quantum",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Rabbit",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Routan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Santana",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Schwimmwagen",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Scirocco",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Sedan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Sharan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "SP",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "SP2",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "T5",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Tiguan",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Touareg",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Touran",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Transporter",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Type 3 Squareback",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "up",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "Vento",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "W12",
                "model_make_id" => "volkswagen",
                "marca_id" => "volkswagen"
            ],
            [
                "model_name" => "120",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "122",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "140",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "144",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "145",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "164",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "1800",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "220",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "240",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "242",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "244",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "245",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "260",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "264",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "265",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "343",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "360",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "440",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "460",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "480",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "66",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "740",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "760",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "780",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "850",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "940",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "960",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "C30",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "C70",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "Duett",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "Hatric",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "P 1800",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "P 1900",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "P 210 Duett",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "PV",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "PV 544 1.8",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "PV 60",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "PV 801-10",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "S40",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "S60",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "S70",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "S80",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "S90",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "SCC",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V40",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V50",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V60",
                "model_make_id" => "Volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V60 Cross Country",
                "model_make_id" => "Volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V70",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "V90",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "X670",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "XC60",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "XC70",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "XC90",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "YCC",
                "model_make_id" => "volvo",
                "marca_id" => "volvo"
            ],
            [
                "model_name" => "1.3 l Tourist",
                "model_make_id" => "wartburg",
                "marca_id" => "wartburg"
            ],
            [
                "model_name" => "311",
                "model_make_id" => "wartburg",
                "marca_id" => "wartburg"
            ],
            [
                "model_name" => "312",
                "model_make_id" => "wartburg",
                "marca_id" => "wartburg"
            ],
            [
                "model_name" => "353",
                "model_make_id" => "wartburg",
                "marca_id" => "wartburg"
            ],
            [
                "model_name" => "Sports Convertible",
                "model_make_id" => "wartburg",
                "marca_id" => "wartburg"
            ],
            [
                "model_name" => "XTR 2",
                "model_make_id" => "westfield",
                "marca_id" => "westfield"
            ],
            [
                "model_name" => "ZEI",
                "model_make_id" => "westfield",
                "marca_id" => "westfield"
            ],
            [
                "model_name" => "Aero",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "Aero-Willys 2600",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "Dauphine",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "Interlagos",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "Jeep",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "Rural",
                "model_make_id" => "willys-overland",
                "marca_id" => "willys-overland"
            ],
            [
                "model_name" => "6",
                "model_make_id" => "xedos",
                "marca_id" => "xedos"
            ],
            [
                "model_name" => "9",
                "model_make_id" => "xedos",
                "marca_id" => "xedos"
            ],
            [
                "model_name" => "Bravo",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "Ferrari 3Z",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "Hyena",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "Raptor",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "Zeta",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "Zuma",
                "model_make_id" => "zagato",
                "marca_id" => "zagato"
            ],
            [
                "model_name" => "102",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "103",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "1300",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "132",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "1500",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "2101",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "65",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "750",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "850 K",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "Yugo",
                "model_make_id" => "zastava",
                "marca_id" => "zastava"
            ],
            [
                "model_name" => "966",
                "model_make_id" => "zaz",
                "marca_id" => "zaz"
            ],
            [
                "model_name" => "Jalta",
                "model_make_id" => "zaz",
                "marca_id" => "zaz"
            ],
            [
                "model_name" => "Slavuta",
                "model_make_id" => "zaz",
                "marca_id" => "zaz"
            ],
            [
                "model_name" => "Tavrija",
                "model_make_id" => "zaz",
                "marca_id" => "zaz"
            ],
            [
                "model_name" => "Wagon",
                "model_make_id" => "zaz",
                "marca_id" => "zaz"
            ],
            [
                "model_name" => "ST1",
                "model_make_id" => "zenvo",
                "marca_id" => "zenvo"
            ],
            [
                "model_name" => "114",
                "model_make_id" => "zil",
                "marca_id" => "zil"
            ],
            [
                "model_name" => "117",
                "model_make_id" => "zil",
                "marca_id" => "zil"
            ],
            [
                "model_name" => "4104",
                "model_make_id" => "zil",
                "marca_id" => "zil"
            ]
        ];
        foreach ($modelos as $data) {
            // Verificar si la marca existe usando la columna 'marca_id' de la tabla marcas.
            $marca = Marca::where('marca_id', $data['marca_id'])->first();

            if (!$marca) {
                // Si la marca no existe, se crea.
                // Se usa el mismo valor para 'marca_id' y se asigna un nombre por defecto (puedes personalizarlo).
                $marca = Marca::create([
                    'marca_id'     => $data['marca_id'],
                    'nombre'       => ucfirst($data['marca_id']),
                    'make_country' => null, // O asigna un valor por defecto si lo tienes
                ]);
            }

            // Insertar el modelo. Recordar que en la tabla modelos el campo 'marca_id'
            // es una foreign key que hace referencia al id (entero) de la tabla marcas.
            Modelo::create([
                'nombre'         => $data['model_name'],
                'model_make_id'  => $data['model_make_id'],
                'marca_id'       => $marca->id, // Relaciona usando el id de la marca
            ]);
        }

        $condiciones = [
            'TUC',
            'RECIBO',
            'TRAMITE BAJA',
            'PAGO LOGO',
            'NO REGISTRADO',
            'DE BAJA',
            'LIBRE'
        ];


        foreach ($condiciones as $condicion) {
            Condicion::create([
                'descripcion' => $condicion,
            ]);
        }

        $plans = [
            [
                'id' => 4,
                'name' => 'CLASICO',
                'slug' => 'clasico',
                'description' => '',
                'is_active' => 1,
                'price' => 30.00,
                'signup_fee' => 0.00,
                'currency' => 'PEN',
                'trial_period' => 0,
                'trial_interval' => 'day',
                'invoice_period' => 1,
                'invoice_interval' => 'month',
                'grace_period' => 0,
                'grace_interval' => 'day',
                'prorate_day' => null,
                'prorate_period' => null,
                'prorate_extend_due' => null,
                'active_subscribers_limit' => null,
                'sort_order' => 1,
                'is_socio' => 0,
                'discounts' => [
                    [
                        "name" => "Descuento por pago anual",
                        "slug" => "descuento-anual",
                        "value" => 160,
                        "months" => 12
                    ],
                    [
                        "name" => "Descuento por pago semestral",
                        "slug" => "descuento-semestral",
                        "value" => 0,
                        "months" => 6
                    ],
                    [
                        "name" => "Descuento por pago trimestral",
                        "slug" => "descuento-trimestral",
                        "value" => 30,
                        "months" => 3
                    ],
                    [
                        "name" => "Descuento por inicio de mes",
                        "slug" => "descuento-inicio-mes",
                        "value" => 5,
                        "months" => 1
                    ]
                ],
                'created_at' => '2025-04-15 00:43:30',
                'updated_at' => '2025-04-15 00:43:30',
                'deleted_at' => null,
                'type' => 'recurring',
            ],
            [
                'id' => 5,
                'name' => 'SOCIO',
                'slug' => 'socio',
                'description' => '',
                'is_active' => 1,
                'price' => 1000.00,
                'signup_fee' => 0.00,
                'currency' => 'USD',
                'trial_period' => 0,
                'trial_interval' => 'year',
                'invoice_period' => 6,
                'invoice_interval' => 'month',
                'grace_period' => 0,
                'grace_interval' => 'day',
                'prorate_day' => null,
                'prorate_period' => null,
                'prorate_extend_due' => null,
                'active_subscribers_limit' => null,
                'sort_order' => 2,
                'is_socio' => 1,
                'discounts' => [
                    [
                        "name" => "Descuento por pago anual",
                        "slug" => "descuento-anual",
                        "value" => 160,
                        "months" => 12
                    ],
                    [
                        "name" => "Descuento por pago semestral",
                        "slug" => "descuento-semestral",
                        "value" => 0,
                        "months" => 6
                    ],
                    [
                        "name" => "Descuento por pago trimestral",
                        "slug" => "descuento-trimestral",
                        "value" => 30,
                        "months" => 3
                    ],
                    [
                        "name" => "Descuento por inicio de mes",
                        "slug" => "descuento-inicio-mes",
                        "value" => 5,
                        "months" => 1
                    ]
                ],
                'created_at' => '2025-04-15 03:52:40',
                'updated_at' => '2025-04-15 03:52:40',
                'deleted_at' => null,
                'type' => 'recurring',
            ],
            [
                'id' => 6,
                'name' => 'SOCIO 5 AÑOS',
                'slug' => 'socio-5-anos',
                'description' => '',
                'is_active' => 1,
                'price' => 2000.00,
                'signup_fee' => 0.00,
                'currency' => 'PEN',
                'trial_period' => 0,
                'trial_interval' => 'day',
                'invoice_period' => 6,
                'invoice_interval' => 'year',
                'grace_period' => 0,
                'grace_interval' => 'day',
                'prorate_day' => null,
                'prorate_period' => null,
                'prorate_extend_due' => null,
                'active_subscribers_limit' => null,
                'sort_order' => 3,
                'is_socio' => 1,
                'discounts' => [
                    [
                        "name" => "Descuento por pago anual",
                        "slug" => "descuento-anual",
                        "value" => 0,
                        "months" => 12
                    ],
                    [
                        "name" => "Descuento por pago semestral",
                        "slug" => "descuento-semestral",
                        "value" => 0,
                        "months" => 6
                    ],
                    [
                        "name" => "Descuento por pago trimestral",
                        "slug" => "descuento-trimestral",
                        "value" => 0,
                        "months" => 3
                    ],
                    [
                        "name" => "Descuento por inicio de mes",
                        "slug" => "descuento-inicio-mes",
                        "value" => 0,
                        "months" => 1
                    ]
                ],
                'created_at' => '2025-04-15 04:36:20',
                'updated_at' => '2025-04-15 04:36:20',
                'deleted_at' => null,
                'type' => 'recurring',
            ],
            [
                'id' => 7,
                'name' => 'INDETERMINADO',
                'slug' => 'indeterminado',
                'description' => null,
                'is_active' => 1,
                'price' => 5000.00,
                'signup_fee' => 0.00,
                'currency' => 'PEN',
                'trial_period' => 0,
                'trial_interval' => 'day',
                'invoice_period' => 999,
                'invoice_interval' => 'year',
                'grace_period' => 0,
                'grace_interval' => 'day',
                'prorate_day' => null,
                'prorate_period' => null,
                'prorate_extend_due' => null,
                'active_subscribers_limit' => null,
                'sort_order' => 4,
                'is_socio' => 1,
                'discounts' => [
                    [
                        "name" => "Descuento por pago anual",
                        "slug" => "descuento-anual",
                        "value" => 0,
                        "months" => 12
                    ],
                    [
                        "name" => "Descuento por pago semestral",
                        "slug" => "descuento-semestral",
                        "value" => 0,
                        "months" => 6
                    ],
                    [
                        "name" => "Descuento por pago trimestral",
                        "slug" => "descuento-trimestral",
                        "value" => 0,
                        "months" => 3
                    ],
                    [
                        "name" => "Descuento por inicio de mes",
                        "slug" => "descuento-inicio-mes",
                        "value" => 0,
                        "months" => 1
                    ]
                ],
                'created_at' => '2025-04-17 03:25:58',
                'updated_at' => '2025-04-17 03:25:58',
                'deleted_at' => null,
                'type' => 'indeterminate',
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
