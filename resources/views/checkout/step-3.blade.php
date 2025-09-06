@extends('layouts.app')

@section('title', 'Checkout - Step 3: Payment')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Select your payment method</p>
        </div>

        <!-- Checkout Stepper -->
        <x-checkout-stepper :current-step="3" />

        <form action="{{ route('checkout.process', 3) }}" method="POST" class="checkout-form">
            @csrf
            
            <div class="checkout-content">
                <!-- Payment Methods -->
                <div class="payment-section">
                    <div class="section-header">
                        <h2>Payment Method</h2>
                        <p>Choose your preferred payment option</p>
                    </div>

                    <div class="payment-options">
                        <!-- Card Payment Option -->
                        <div class="payment-option">
                            <input type="radio" name="payment_method" id="card_payment" value="card_payment" 
                                   {{ (old('payment_method', $checkoutData['payment_method'] ?? '') === 'card_payment') ? 'checked' : '' }}>
                            <label for="card_payment" class="payment-card">
                                <div class="option-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="option-content">
                                    <h3>Card Payment</h3>
                                    <p>Pay with your debit or credit card</p>
                                    <div class="card-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                        <i class="fab fa-cc-amex"></i>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Credit Card Option -->
                        <div class="payment-option">
                            <input type="radio" name="payment_method" id="credit_card" value="credit_card"
                                   {{ (old('payment_method', $checkoutData['payment_method'] ?? '') === 'credit_card') ? 'checked' : '' }}>
                            <label for="credit_card" class="payment-card">
                                <div class="option-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="option-content">
                                    <h3>Credit Card</h3>
                                    <p>Pay with your credit card</p>
                                    <span class="security-badge">
                                        <i class="fas fa-shield-alt"></i>
                                        Secure Payment
                                    </span>
                                </div>
                            </label>
                        </div>

                        <!-- Employee ID Option -->
                        <div class="payment-option">
                            <input type="radio" name="payment_method" id="employee_id" value="employee_id"
                                   {{ (old('payment_method', $checkoutData['payment_method'] ?? '') === 'employee_id') ? 'checked' : '' }}>
                            <label for="employee_id" class="payment-card">
                                <div class="option-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="option-content">
                                    <h3>Employee ID</h3>
                                    <p>Pay using your employee account</p>
                                    <span class="employee-badge">
                                        <i class="fas fa-building"></i>
                                        Employee Discount
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Details Section -->
                    <div class="payment-details">
                        <!-- Card Payment Details -->
                        <div id="card-payment-details" class="payment-form" style="display: none;">
                            <h3>Card Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="card_number">Card Number *</label>
                                    <input type="text" name="card_number" id="card_number" 
                                           placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="form-group">
                                    <label for="card_holder">Card Holder Name *</label>
                                    <input type="text" name="card_holder" id="card_holder" 
                                           placeholder="John Doe">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date *</label>
                                    <input type="text" name="expiry_date" id="expiry_date" 
                                           placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV *</label>
                                    <input type="text" name="cvv" id="cvv" 
                                           placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Details -->
                        <div id="credit-card-details" class="payment-form" style="display: none;">
                            <h3>Credit Card Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="credit_card_number">Credit Card Number *</label>
                                    <input type="text" name="credit_card_number" id="credit_card_number" 
                                           placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="form-group">
                                    <label for="credit_card_holder">Card Holder Name *</label>
                                    <input type="text" name="credit_card_holder" id="credit_card_holder" 
                                           placeholder="John Doe">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="credit_expiry_date">Expiry Date *</label>
                                    <input type="text" name="credit_expiry_date" id="credit_expiry_date" 
                                           placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label for="credit_cvv">CVV *</label>
                                    <input type="text" name="credit_cvv" id="credit_cvv" 
                                           placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <!-- Employee ID Details -->
                        <div id="employee-id-details" class="payment-form" style="display: none;">
                            <h3>Employee Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="employee_id_number">Employee ID *</label>
                                    <input type="text" name="employee_id_number" id="employee_id_number" 
                                           placeholder="EMP123456">
                                </div>
                                <div class="form-group">
                                    <label for="employee_department">Department *</label>
                                    <select name="employee_department" id="employee_department">
                                        <option value="">Select Department</option>
                                        <option value="IT">IT</option>
                                        <option value="HR">HR</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Operations">Operations</option>
                                        <option value="Marketing">Marketing</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="employee_email">Employee Email *</label>
                                <input type="email" name="employee_email" id="employee_email" 
                                       placeholder="john.doe@company.com">
                            </div>
                            
                            <div class="employee-benefits">
                                <h4>Employee Benefits</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> 10% Employee Discount</li>
                                    <li><i class="fas fa-check"></i> Free Delivery</li>
                                    <li><i class="fas fa-check"></i> Priority Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="security-notice">
                        <div class="security-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="security-content">
                            <h4>Secure Payment</h4>
                            <p>Your payment information is encrypted and secure. We never store your card details.</p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        
                        <div class="summary-items">
                            @foreach($processedCartItems as $item)
                            <div class="summary-item">
                                <div class="item-info">
                                    <span class="item-name">{{ $item['product']->name }}</span>
                                    <span class="item-quantity">x{{ $item['quantity'] }}</span>
                                </div>
                                <span class="item-price">${{ number_format($item['total'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-row total-row">
                            <span>Total:</span>
                            <span class="total-amount">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('checkout.step', 2) }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to Delivery
                            </a>
                            <button type="submit" class="proceed-btn">
                                <i class="fas fa-lock"></i>
                                Complete Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.checkout-page {
    padding: 2rem 0;
    background-color: #f8fafc;
    min-height: 100vh;
}

.checkout-header {
    text-align: center;
    margin-bottom: 2rem;
}

.checkout-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.checkout-header p {
    font-size: 1.1rem;
    color: #64748b;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.payment-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.section-header p {
    color: #64748b;
    margin: 0;
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.payment-option {
    position: relative;
}

.payment-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.payment-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.payment-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.payment-option input[type="radio"]:checked + .payment-card {
    border-color: #3b82f6;
    background: #f0f9ff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.option-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.payment-option input[type="radio"]:checked + .payment-card .option-icon {
    background: #3b82f6;
    color: white;
}

.option-icon i {
    font-size: 1.5rem;
    color: #64748b;
}

.payment-option input[type="radio"]:checked + .payment-card .option-icon i {
    color: white;
}

.option-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.option-content p {
    color: #64748b;
    margin-bottom: 0.5rem;
}

.card-icons {
    display: flex;
    gap: 0.5rem;
}

.card-icons i {
    font-size: 1.5rem;
    color: #64748b;
}

.security-badge, .employee-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
    color: #10b981;
    font-weight: 500;
}

.payment-details {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f1f5f9;
}

.payment-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-form h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 500;
    color: #374151;
}

.form-group input, .form-group select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.employee-benefits {
    background: #f0f9ff;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #bae6fd;
    margin-top: 1rem;
}

.employee-benefits h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.employee-benefits ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.employee-benefits li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    color: #1e293b;
}

.employee-benefits li i {
    color: #10b981;
}

.security-notice {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f0f9ff;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #bae6fd;
    margin-top: 2rem;
}

.security-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.security-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.security-content p {
    color: #64748b;
    margin: 0;
}

.order-summary {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.summary-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
}

.summary-items {
    margin-bottom: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 500;
    color: #1e293b;
}

.item-quantity {
    font-size: 0.875rem;
    color: #64748b;
}

.item-price {
    font-weight: 600;
    color: #1e293b;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    color: #64748b;
}

.summary-row.total-row {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
}

.summary-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 1rem 0;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.back-btn {
    flex: 1;
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.back-btn:hover {
    background: #e2e8f0;
    color: #374151;
}

.proceed-btn {
    flex: 2;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.proceed-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-summary {
        position: static;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardPayment = document.getElementById('card_payment');
    const creditCard = document.getElementById('credit_card');
    const employeeId = document.getElementById('employee_id');
    
    const cardDetails = document.getElementById('card-payment-details');
    const creditDetails = document.getElementById('credit-card-details');
    const employeeDetails = document.getElementById('employee-id-details');

    function togglePaymentDetails() {
        // Hide all details first
        cardDetails.style.display = 'none';
        creditDetails.style.display = 'none';
        employeeDetails.style.display = 'none';

        // Show selected details
        if (cardPayment.checked) {
            cardDetails.style.display = 'block';
        } else if (creditCard.checked) {
            creditDetails.style.display = 'block';
        } else if (employeeId.checked) {
            employeeDetails.style.display = 'block';
        }
    }

    cardPayment.addEventListener('change', togglePaymentDetails);
    creditCard.addEventListener('change', togglePaymentDetails);
    employeeId.addEventListener('change', togglePaymentDetails);

    // Initialize based on current selection
    togglePaymentDetails();

    // Format card number input
    const cardNumberInputs = ['card_number', 'credit_card_number'];
    cardNumberInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formattedValue;
            });
        }
    });

    // Format expiry date input
    const expiryInputs = ['expiry_date', 'credit_expiry_date'];
    expiryInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });
        }
    });

    // Format CVV input
    const cvvInputs = ['cvv', 'credit_cvv'];
    cvvInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
    });
});
</script>
@endsection
