@props(['currentStep' => 1])

<div class="checkout-stepper">
    <div class="stepper-container">
        <div class="stepper-step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
            <div class="step-circle">
                @if($currentStep > 1)
                    <i class="fas fa-check"></i>
                @else
                    <span>1</span>
                @endif
            </div>
            <div class="step-label">Review Cart</div>
        </div>
        
        <div class="stepper-line {{ $currentStep > 1 ? 'active' : '' }}"></div>
        
        <div class="stepper-step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
            <div class="step-circle">
                @if($currentStep > 2)
                    <i class="fas fa-check"></i>
                @else
                    <span>2</span>
                @endif
            </div>
            <div class="step-label">Delivery</div>
        </div>
        
        <div class="stepper-line {{ $currentStep > 2 ? 'active' : '' }}"></div>
        
        <div class="stepper-step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
            <div class="step-circle">
                @if($currentStep > 3)
                    <i class="fas fa-check"></i>
                @else
                    <span>3</span>
                @endif
            </div>
            <div class="step-label">Payment</div>
        </div>
        
        <div class="stepper-line {{ $currentStep > 3 ? 'active' : '' }}"></div>
        
        <div class="stepper-step {{ $currentStep >= 4 ? 'active' : '' }}">
            <div class="step-circle">
                <span>4</span>
            </div>
            <div class="step-label">Confirmation</div>
        </div>
    </div>
</div>

<style>
.checkout-stepper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    padding: 2rem;
    margin-bottom: 2rem;
}

.stepper-container {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 800px;
    margin: 0 auto;
}

.stepper-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.step-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 3px solid #e2e8f0;
    background: white;
    color: #64748b;
}

.stepper-step.active .step-circle {
    border-color: #3b82f6;
    background: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.stepper-step.completed .step-circle {
    border-color: #10b981;
    background: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.step-label {
    font-size: 0.9rem;
    font-weight: 500;
    color: #64748b;
    text-align: center;
    transition: color 0.3s ease;
}

.stepper-step.active .step-label {
    color: #3b82f6;
    font-weight: 600;
}

.stepper-step.completed .step-label {
    color: #10b981;
    font-weight: 600;
}

.stepper-line {
    height: 3px;
    background: #e2e8f0;
    flex: 1;
    margin: 0 1rem;
    margin-top: -24px;
    transition: background 0.3s ease;
}

.stepper-line.active {
    background: #10b981;
}

@media (max-width: 768px) {
    .checkout-stepper {
        padding: 1rem;
    }
    
    .stepper-container {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .stepper-step {
        flex: none;
        min-width: 80px;
    }
    
    .stepper-line {
        display: none;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .step-label {
        font-size: 0.8rem;
    }
}
</style>
