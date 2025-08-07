<?php

namespace Modules\Reporting\Services;

use Illuminate\Support\Facades\DB;
use Modules\CropManagement\Models\Crop;
use Modules\CropManagement\Models\ProductionEstimation;
use Modules\FarmLand\Models\Soil;

class ProductionEstimationReportsService
{
    public static function getProdEstByCrop()
    {
        $data = ProductionEstimation::join("crop_plans as cp", "production_estimations.crop_plan_id", "=", "cp.id")
            ->join("crops as c", "cp.crop_id", "=", "c.id")
            ->select(
                "c.id as crop_id",
                DB::raw("SUM(production_estimations.actual_quantity) as total_actual"),
                DB::raw("SUM(production_estimations.expected_quantity) as total_quantity"),
                DB::raw("ROUND((SUM(production_estimations.actual_quantity) / SUM(production_estimations.expected_quantity)) * 100, 2) as productivity_percentage")
                )
            ->groupBy("c.id")
            ->get()
            ->map(function ($item) {
                $crop = Crop::find($item->crop_id);
                $item->crop_name = $crop->getTranslations("name");
                return $item;
            });
            
        return $data;
    }

    public static function getProdEstBySoilType()
    {
        $data = ProductionEstimation::join("crop_plans as cp", "production_estimations.crop_plan_id", "=", "cp.id")
            ->join("lands as l", "cp.land_id", "=", "l.id")
            ->join("soils as s", "l.soil_type_id", "=", "s.id")
            ->select(
                "s.id as soil_id",
                DB::raw("SUM(production_estimations.actual_quantity) as total_actual"),
                DB::raw("SUM(production_estimations.expected_quantity) as total_expected"),
                DB::raw("ROUND((SUM(production_estimations.actual_quantity) / SUM(production_estimations.expected_quantity)) * 100, 2) as productivity_percentage")
            )
            ->groupBy("s.id")
            ->get()
            ->map(function ($item) {
                $soil = Soil::find($item->soil_id);
                $item->soil_type = $soil->getTranslations('name');
                return $item;
            });

        return $data;
    }

    public static function getProdEstByRegion()
    {
        $data = ProductionEstimation::join("crop_plans as cp", "production_estimations.crop_plan_id", "=", "cp.id")
            ->join("lands as l", "cp.land_id", "=", "l.id")
            ->select(
                "l.region as region",
                DB::raw("SUM(production_estimations.actual_quantity) as total_actual"),
                DB::raw("SUM(production_estimations.expected_quantity) as total_expected"),
                DB::raw("ROUND((SUM(production_estimations.actual_quantity) / SUM(production_estimations.expected_quantity)) * 100, 2) as productivity_percentage")
            )
            ->groupBy("l.region")
            ->get();

        return $data;
    }

    public static function getProdTrendsByYear()
    {
        $data = ProductionEstimation::join('crop_plans as cp', 'production_estimations.crop_plan_id', '=', 'cp.id')
            ->select(
                DB::raw('YEAR(cp.planned_harvest_date) as year'),
                DB::raw('SUM(production_estimations.expected_quantity) as total_expected'),
                DB::raw('SUM(production_estimations.actual_quantity) as total_actual'),
                DB::raw('ROUND((SUM(production_estimations.actual_quantity) / SUM(production_estimations.expected_quantity)) * 100, 2) as productivity_percentage')
            )
            ->groupBy(DB::raw('YEAR(cp.planned_harvest_date)'))
            ->orderBy('year', 'ASC')
            ->get();

        return $data;
    }

}
