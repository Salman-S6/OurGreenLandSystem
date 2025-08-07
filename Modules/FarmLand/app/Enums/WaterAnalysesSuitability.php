<?php

namespace Modules\FarmLand\Enums;

enum WaterAnalysesSuitability: string
{
    case Suitable = 'suitable';
    case Limited = 'limited';
    case Unsuitable = 'unsuitable';
}
