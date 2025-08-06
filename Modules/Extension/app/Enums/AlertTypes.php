<?php

namespace Modules\Extension\Enums;

enum AlertTypes: string 
{
    case Weather = 'weather';
    case General = 'general';
    case Fertilization = 'fertilization';
    case Pest = 'pest';
    case Irrigation = 'irrigation';
}
