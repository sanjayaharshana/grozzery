@props(['currentStep' => 1])

@php
$steps = [
    1 => [
        'icon' => 'fas fa-shopping-cart',
        'title' => 'Review Cart',
        'description' => 'Review your items'
    ],
    2 => [
        'icon' => 'fas fa-truck',
        'title' => 'Delivery',
        'description' => 'Choose delivery option'
    ],
    3 => [
        'icon' => 'fas fa-credit-card',
        'title' => 'Payment',
        'description' => 'Complete payment'
    ],
    4 => [
        'icon' => 'fas fa-check-circle',
        'title' => 'Confirmation',
        'description' => 'Order confirmed'
    ]
];
@endphp

<!-- Order Process Step Indicator -->
<div class="order-process-steps" style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; padding: 0.75rem; margin-bottom: 1rem;">
    <div class="step-indicator" style="display: flex; align-items: center; justify-content: space-between; max-width: 800px; margin: 0 auto; padding: 0 1rem;">
        @foreach($steps as $stepNumber => $step)
            @php
                $isActive = $stepNumber == $currentStep;
                $isCompleted = $stepNumber < $currentStep;
                
                if ($isActive) {
                    $circleClass = 'background: #ff4747; color: white; border: 2px solid #ff4747; box-shadow: 0 2px 8px rgba(255, 71, 71, 0.3);';
                    $titleClass = 'color: #ff4747;';
                } elseif ($isCompleted) {
                    $circleClass = 'background: #10b981; color: white; border: 2px solid #10b981; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);';
                    $titleClass = 'color: #10b981;';
                } else {
                    $circleClass = 'background: #e2e8f0; color: #64748b; border: 2px solid #e2e8f0;';
                    $titleClass = 'color: #2d3748;';
                }
            @endphp
            
            <div class="step {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}" 
                 data-step="{{ $stepNumber }}" 
                 style="display: flex; align-items: center; flex: 1; min-width: 0;">
                <div class="step-circle" 
                     style="width: 32px; height: 32px; border-radius: 50%; {{ $circleClass }} display: flex; align-items: center; justify-content: center; font-size: 0.875rem; margin-right: 0.5rem; transition: all 0.3s ease;">
                    <i class="{{ $step['icon'] }}"></i>
                </div>
                <div class="step-content" style="text-align: left;">
                    <h4 style="font-size: 0.8rem; font-weight: 600; {{ $titleClass }} margin: 0; line-height: 1.2;">{{ $step['title'] }}</h4>
                </div>
            </div>
        @endforeach
    </div>
</div>

