<?php

namespace Database\Seeders;

use App\Models\AboutAndContact;
use App\Models\BatteryVoltage;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\CarSubType;
use App\Models\CarType;
use App\Models\Emergency;
use App\Models\EngineType;
use App\Models\Part;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TermsCondition;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        CarType::create(['name' => 'Toyota', 'image' => null]);
        CarType::create(['name' => 'BMW', 'image' => null]);
        CarType::create(['name' => 'Mercedes', 'image' => null]);
        CarType::create(['name' => 'Nissan', 'image' => null]);
        CarType::create(['name' => 'Hyundai', 'image' => null]);

        CarSubType::create(['car_type_id' => 1, 'name' => 'Camry']);
        CarSubType::create(['car_type_id' => 1, 'name' => 'Corolla']);
        CarSubType::create(['car_type_id' => 1, 'name' => 'Land Cruiser']);
        CarSubType::create(['car_type_id' => 2, 'name' => '3 Series']);
        CarSubType::create(['car_type_id' => 2, 'name' => '5 Series']);
        CarSubType::create(['car_type_id' => 2, 'name' => 'X5']);
        CarSubType::create(['car_type_id' => 3, 'name' => 'C-Class']);
        CarSubType::create(['car_type_id' => 3, 'name' => 'E-Class']);
        CarSubType::create(['car_type_id' => 3, 'name' => 'G-Class']);
        CarSubType::create(['car_type_id' => 4, 'name' => 'Altima']);
        CarSubType::create(['car_type_id' => 4, 'name' => 'Patrol']);
        CarSubType::create(['car_type_id' => 5, 'name' => 'Elantra']);
        CarSubType::create(['car_type_id' => 5, 'name' => 'Tucson']);

        EngineType::create(['name' => '1500 CC']);
        EngineType::create(['name' => '2000 CC']);
        EngineType::create(['name' => '3000 CC']);
        EngineType::create(['name' => '3500 CC']);
        EngineType::create(['name' => '4000 CC']);
        EngineType::create(['name' => '5000 CC']);

        BatteryVoltage::create(['name' => '12V']);
        BatteryVoltage::create(['name' => '24V']);

        $fields = ['selectCar' => 1, 'pickLocation' => 1, 'manufactory' => 0, 'batteryVoltage' => 0, 'withService' => 0, 'carLicense' => 1, 'withFilter' => 0, 'pickDate' => 1, 'startTime' => 1, 'endTime' => 1, 'note' => 1, 'phone' => 1, 'PaymentMethod' => 1, 'type' => 'Emergency', 'itemId' => 1];

        $emergency = Emergency::create(['name' => 'Towing', 'image' => null, 'price' => 50, 'fields' => $fields]);
        $emergency2 = Emergency::create(['name' => 'Flat Tire', 'image' => null, 'price' => 30, 'fields' => array_merge($fields, ['itemId' => 2])]);
        $emergency3 = Emergency::create(['name' => 'Fuel Delivery', 'image' => null, 'price' => 40, 'fields' => array_merge($fields, ['itemId' => 3])]);
        $emergency4 = Emergency::create(['name' => 'Jump Start', 'image' => null, 'price' => 25, 'fields' => array_merge($fields, ['itemId' => 4])]);
        $emergency5 = Emergency::create(['name' => 'Lockout', 'image' => null, 'price' => 35, 'fields' => array_merge($fields, ['itemId' => 5])]);

        $service = Service::create(['name' => 'Maintenance', 'image' => null]);
        $service2 = Service::create(['name' => 'Car Inspection', 'image' => null]);
        $service3 = Service::create(['name' => 'AC Service', 'image' => null]);

        $svcFields1 = ['selectCar' => 1, 'pickLocation' => 1, 'manufactory' => 0, 'batteryVoltage' => 0, 'withService' => 0, 'carLicense' => 1, 'withFilter' => 0, 'pickDate' => 1, 'startTime' => 1, 'endTime' => 1, 'note' => 1, 'phone' => 1, 'PaymentMethod' => 1, 'type' => 'Services', 'itemId' => 1];
        $svcFields2 = array_merge($svcFields1, ['itemId' => 2]);
        $svcFields3 = array_merge($svcFields1, ['itemId' => 3]);

        ServiceCategory::create(['service_id' => 1, 'name' => 'Oil Change', 'image' => null, 'price' => 30, 'fields' => $svcFields1]);
        ServiceCategory::create(['service_id' => 1, 'name' => 'Brake Pads Replacement', 'image' => null, 'price' => 80, 'fields' => $svcFields2]);
        ServiceCategory::create(['service_id' => 1, 'name' => 'Battery Replacement', 'image' => null, 'price' => 60, 'fields' => $svcFields3]);
        ServiceCategory::create(['service_id' => 2, 'name' => 'Full Inspection', 'image' => null, 'price' => 100, 'fields' => $svcFields1]);
        ServiceCategory::create(['service_id' => 2, 'name' => 'Pre-Purchase Inspection', 'image' => null, 'price' => 120, 'fields' => $svcFields2]);
        ServiceCategory::create(['service_id' => 3, 'name' => 'AC Gas Refill', 'image' => null, 'price' => 50, 'fields' => $svcFields1]);
        ServiceCategory::create(['service_id' => 3, 'name' => 'AC Compressor Repair', 'image' => null, 'price' => 200, 'fields' => $svcFields2]);

        $part = Part::create(['name' => 'Car Batteries', 'image' => null]);
        $part2 = Part::create(['name' => 'Brake Systems', 'image' => null]);
        $part3 = Part::create(['name' => 'Engine Parts', 'image' => null]);
        $part4 = Part::create(['name' => 'Lighting', 'image' => null]);

        $catFields1 = ['selectCar' => 1, 'pickLocation' => 0, 'manufactory' => 1, 'batteryVoltage' => 1, 'withService' => 1, 'carLicense' => 0, 'withFilter' => 1, 'pickDate' => 1, 'startTime' => 1, 'endTime' => 1, 'note' => 1, 'phone' => 1, 'PaymentMethod' => 1, 'type' => 'Parts', 'itemId' => 1];
        $cat1 = BrandCategory::create(['name' => 'Premium Batteries', 'image' => null, 'categorizable_type' => Part::class, 'categorizable_id' => 1]);
        Brand::create(['brand_category_id' => $cat1->id, 'name' => 'Bosch', 'image' => null, 'price' => 100, 'fields' => array_merge($catFields1, ['itemId' => 1])]);
        Brand::create(['brand_category_id' => $cat1->id, 'name' => 'Varta', 'image' => null, 'price' => 90, 'fields' => array_merge($catFields1, ['itemId' => 2])]);
        Brand::create(['brand_category_id' => $cat1->id, 'name' => 'ACDelco', 'image' => null, 'price' => 85, 'fields' => array_merge($catFields1, ['itemId' => 3])]);

        $cat2 = BrandCategory::create(['name' => 'Brake Pads', 'image' => null, 'categorizable_type' => Part::class, 'categorizable_id' => 2]);
        Brand::create(['brand_category_id' => $cat2->id, 'name' => 'Bosch Brake Pads', 'image' => null, 'price' => 50, 'fields' => array_merge($catFields1, ['itemId' => 4])]);
        Brand::create(['brand_category_id' => $cat2->id, 'name' => 'Brembo', 'image' => null, 'price' => 120, 'fields' => array_merge($catFields1, ['itemId' => 5])]);

        $cat3 = BrandCategory::create(['name' => 'Oil Filters', 'image' => null, 'categorizable_type' => Part::class, 'categorizable_id' => 3]);
        Brand::create(['brand_category_id' => $cat3->id, 'name' => 'Mobil 1', 'image' => null, 'price' => 15, 'fields' => array_merge($catFields1, ['itemId' => 6])]);
        Brand::create(['brand_category_id' => $cat3->id, 'name' => 'Castrol', 'image' => null, 'price' => 12, 'fields' => array_merge($catFields1, ['itemId' => 7])]);

        $cat4 = BrandCategory::create(['name' => 'Headlights', 'image' => null, 'categorizable_type' => Part::class, 'categorizable_id' => 4]);
        Brand::create(['brand_category_id' => $cat4->id, 'name' => 'Philips', 'image' => null, 'price' => 40, 'fields' => array_merge($catFields1, ['itemId' => 8])]);
        Brand::create(['brand_category_id' => $cat4->id, 'name' => 'Osram', 'image' => null, 'price' => 35, 'fields' => array_merge($catFields1, ['itemId' => 9])]);

        TermsCondition::create([
            'name_en' => 'Terms and Conditions',
            'name_ar' => 'الشروط والأحكام',
            'description_en' => 'These are the terms and conditions for using Click & Fix application.',
            'description_ar' => 'هذه هي الشروط والأحكام لاستخدام تطبيق كليك اند فيكس.',
        ]);

        AboutAndContact::create([
            'description_en' => 'Click & Fix is your trusted car service and repair platform.',
            'description_ar' => 'كليك اند فيكس منصتك الموثوقة لخدمات السيارات والإصلاح.',
            'email' => 'info@clickandfix.com',
        ]);
    }
}
