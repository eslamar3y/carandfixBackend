<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutAndContact;
use App\Models\TermsCondition;
use Illuminate\Http\JsonResponse;

class StaticContentController extends Controller
{
    public function terms(): JsonResponse
    {
        $locale = request()->header('Accept-Language', 'en');
        $terms = TermsCondition::first();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $terms ? [
                'nameEn' => $locale === 'ar' ? ($terms->name_ar ?? $terms->name_en) : $terms->name_en,
                'nameAr' => $terms->name_ar,
                'descriptionEn' => $locale === 'ar' ? ($terms->description_ar ?? $terms->description_en) : $terms->description_en,
                'descriptionAr' => $terms->description_ar,
            ] : null,
        ]);
    }

    public function about(): JsonResponse
    {
        $about = AboutAndContact::first();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $about ? [
                'descriptionEn' => $about->description_en,
                'descriptionAr' => $about->description_ar,
                'email' => $about->email,
                'phone' => $about->phone,
            ] : null,
        ]);
    }
}
