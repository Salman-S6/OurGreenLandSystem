<?php

namespace Modules\Resources\Enums;

enum SupplierType: string{

    case SEEDS       = 'seeds';
    case FERTILIZERS = 'fertilizers';
    case PESTICIDES  = 'pesticides';
    case EQUIPMENT   = 'equipment';

  public function translations(): array
    {
        return match($this) {
            self::SEEDS       => ['en' => 'Seeds',       'ar' => 'بذور'],
            self::FERTILIZERS => ['en' => 'Fertilizers', 'ar' => 'أسمدة'],
            self::PESTICIDES  => ['en' => 'Pesticides',  'ar' => 'مبيدات'],
            self::EQUIPMENT   => ['en' => 'Equipment',   'ar' => 'معدات'],
        };
    }

    public static function allTranslations(): array
    {
        return collect(self::cases())->mapWithKeys(fn($type) => [
            $type->value => $type->translations()
        ])->toArray();
    }
        public static function fromTranslations(array $translations): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->translations() === $translations) {
                return $case;
            }
        }

        return null;
    }
}