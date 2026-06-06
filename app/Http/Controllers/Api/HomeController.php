<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrandCategory;
use App\Models\Emergency;
use App\Models\Part;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $emergencies = Emergency::with(['brandCategories.brands', 'serviceCategories'])->get();
        $services = Service::with('serviceCategories')->get();
        $parts = Part::with('brandCategories.brands')->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => [
                'emergencies' => $emergencies->map(fn($e) => $this->formatEmergency($e)),
                'services' => $services->map(fn($s) => $this->formatService($s)),
                'parts' => $parts->map(fn($p) => $this->formatPart($p)),
            ],
        ]);
    }

    private function imageUrl(?string $path): string
    {
        return $path ? request()->getSchemeAndHttpHost() . '/storage/' . $path : 'https://ui-avatars.com/api/?name=Item&background=random&size=200';
    }

    private function defaultFields(): array
    {
        return [
            'id' => 0,
            'type' => '',
            'itemId' => 0,
            'selectCar' => 0,
            'pickLocation' => 0,
            'manufactory' => 0,
            'batteryVoltage' => 0,
            'withService' => 0,
            'carLicense' => 0,
            'withFilter' => 0,
            'pickDate' => 0,
            'startTime' => 0,
            'endTime' => 0,
            'note' => 0,
            'phone' => 0,
            'PaymentMethod' => 0,
        ];
    }

    private function makeFields(?array $dbFields, int $itemId, string $type): array
    {
        return array_merge($this->defaultFields(), $dbFields ?? [], [
            'id' => $itemId,
            'itemId' => $itemId,
            'type' => $type,
        ]);
    }

    private function formatEmergency(Emergency $emergency): array
    {
        return [
            'id' => $emergency->id,
            'name' => $emergency->name,
            'image' => $this->imageUrl($emergency->image),
            'price' => $emergency->price ?? '0',
            'fields' => $this->makeFields($emergency->fields, $emergency->id, 'Emergency'),
            'brandCategories' => $emergency->brandCategories->map(fn($bc) => $this->formatBrandCategory($bc)),
            'serviceCategories' => $emergency->serviceCategories->map(fn($child) => [
                'id' => $child->id,
                'image' => $this->imageUrl($child->image),
                'serviceCategoryId' => $child->service_category_id,
                'name' => $child->name,
                'price' => $child->price ?? '0',
                'fields' => $this->makeFields($child->fields, $child->id, 'Emergency'),
            ]),
            'serviceCategoryId' => $emergency->service_category_id,
        ];
    }

    private function formatService(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'image' => $this->imageUrl($service->image),
            'serviceCategories' => $service->serviceCategories->map(fn($sc) => [
                'id' => $sc->id,
                'image' => $this->imageUrl($sc->image),
                'serviceCategoryId' => $sc->service_id,
                'name' => $sc->name,
                'price' => $sc->price ?? '0',
                'fields' => $this->makeFields($sc->fields, $sc->id, 'Services'),
            ]),
        ];
    }

    private function formatPart(Part $part): array
    {
        return [
            'id' => $part->id,
            'name' => $part->name,
            'image' => $this->imageUrl($part->image),
            'brandCategories' => $part->brandCategories->map(fn($bc) => $this->formatBrandCategory($bc)),
        ];
    }

    private function formatBrandCategory(BrandCategory $brandCategory): array
    {
        return [
            'id' => $brandCategory->id,
            'image' => $this->imageUrl($brandCategory->image),
            'partId' => $brandCategory->categorizable_id,
            'name' => $brandCategory->name,
            'brands' => $brandCategory->brands->map(fn($b) => [
                'id' => $b->id,
                'image' => $this->imageUrl($b->image),
                'name' => $b->name,
                'price' => $b->price ?? '0',
                'fields' => $this->makeFields($b->fields, $b->id, 'Parts'),
            ]),
        ];
    }
}
