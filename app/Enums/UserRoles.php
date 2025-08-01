<?php

namespace App\Enums;

enum UserRoles: string
{
    case SuperAdmin = 'SuperAdmin';
    case ProgramManager = 'ProgramManager';
    case AgriculturalAlert = 'AgriculturalEngineer';
    case Farmer = 'Farmer';
    case SoilWaterSpecialist = 'SoilWaterSpecialist';
    case Supplier = 'Supplier';
    case FundingAgency = 'FundingAgency';
    case DataAnalyst = 'DataAnalyst';
}
