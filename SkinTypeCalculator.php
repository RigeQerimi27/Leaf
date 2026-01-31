<?php
declare(strict_types=1);

class SkinTypeCalculator
{
    
    private array $allowed = ['dry', 'normal', 'combo', 'oily'];

    
    public function calculate(string $q1, string $q2, string $q3): string
    {
      
        $counts = [
            'dry' => 0,
            'normal' => 0,
            'combo' => 0,
            'oily' => 0
        ];

       
        foreach ([$q1, $q2, $q3] as $ans) {
          
            if (in_array($ans, $this->allowed, true)) {
                $counts[$ans]++;
            }
        }

        
        arsort($counts);

        
        return array_key_first($counts);
    }

    
    public function label(string $type): string
    {
        return match ($type) {
            'dry' => 'Dry Skin',
            'normal' => 'Normal Skin',
            'combo' => 'Combination Skin',
            'oily' => 'Oily Skin',
            default => 'Your Skin Type',
        };
    }

    
    public function description(string $type): string
    {
        return match ($type) {
            'dry' => 'Your skin tends to feel tight and may look dull. Focus on hydration and barrier repair.',
            'normal' => 'Your skin feels balanced. Maintain it with gentle cleansing, moisturizing and SPF.',
            'combo' => 'You have both oily and dry areas (often T-zone). Use balancing products and light hydration.',
            'oily' => 'Your skin gets shiny easily. Focus on gentle cleansing, oil-control, and non-comedogenic hydration.',
            default => '',
        };
    }
}
