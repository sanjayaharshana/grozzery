@extends('layouts.app')

@section('title', 'My Addresses - Grozzoery')

@section('content')
<div class="addresses-page">
    <div class="container">
        <div class="page-header">
            <h1>My Addresses</h1>
            <p>Manage your delivery and billing addresses</p>
        </div>
        
        <div class="addresses-content">
            <!-- Add New Address Button -->
            <div class="add-address-section">
                <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Address
                </a>
            </div>
            
            <!-- Addresses List -->
            @if($addresses->count() > 0)
                <div class="addresses-grid">
                    @foreach($addresses as $address)
                        <div class="address-card">
                            <div class="address-header">
                                <div class="address-type">
                                    <span class="type-badge {{ $address->type }}">
                                        {{ ucfirst($address->type) }}
                                    </span>
                                    @if($address->label)
                                        <span class="address-label">{{ $address->label }}</span>
                                    @endif
                                </div>
                                <div class="address-actions">
                                    @if($address->is_default)
                                        <span class="default-badge">Default</span>
                                    @else
                                        <form action="{{ route('addresses.default', $address) }}" method="POST" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline">Set Default</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="address-details">
                                <p class="address-name"><strong>{{ $address->full_name }}</strong></p>
                                <p>{{ $address->address_line_1 }}</p>
                                @if($address->address_line_2)
                                    <p>{{ $address->address_line_2 }}</p>
                                @endif
                                <p>{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                <p>{{ $address->country }}</p>
                                <p class="address-phone">Phone: {{ $address->phone }}</p>
                                @if($address->latitude && $address->longitude)
                                    <p class="address-coordinates">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        GPS: {{ $address->latitude }}, {{ $address->longitude }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="address-footer">
                                <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="inline-form delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this address?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-addresses">
                    <div class="empty-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>No addresses yet</h3>
                        <p>You haven't added any addresses yet. Add your first address to get started with deliveries.</p>
                        <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Your First Address
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Confirm delete action
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to delete this address? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
