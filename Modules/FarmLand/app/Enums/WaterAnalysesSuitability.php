<?php

namespace Modules\FarmLand\Enums;

enum WaterAnalysesSuitability: string
{
    case suitable = 'suitable';
    case limited = 'limited';
    case unsuitable = 'unsuitable';
}
