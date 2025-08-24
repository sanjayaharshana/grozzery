$(document).ready(function() {
    // CSRF token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Cart functionality
$('.add-to-cart, .add-to-cart-btn').on('click', function(e) {
    e.preventDefault();

    var productId = $(this).data('product-id');
    var quantity = $(this).closest('form').find('input[name="quantity"]').val() || 1;

    // Show loading state
    var $btn = $(this);
    var originalText = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
    $btn.prop('disabled', true);

    $.post('/cart/add', {
        product_id: productId,
        quantity: quantity
    })
    .done(function(response) {
        showAlert('Product added to cart successfully!', 'success');
        updateCartCount();

        // Reset button state
        $btn.html(originalText);
        $btn.prop('disabled', false);
    })
    .fail(function(xhr) {
        var error = 'An error occurred while adding to cart.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            error = xhr.responseJSON.message;
        }
        showAlert(error, 'error');

        // Reset button state
        $btn.html(originalText);
        $btn.prop('disabled', false);
    });
});

// Wishlist functionality
$('.wishlist-btn').on('click', function(e) {
    e.preventDefault();

    var $btn = $(this);
    var productId = $btn.data('product-id');
    var productName = $btn.data('product-name');
    var originalText = $btn.html();

    // Show loading state
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
    $btn.prop('disabled', true);

    $.post('/wishlist/add', {
        product_id: productId
    })
    .done(function(response) {
        showAlert(productName + ' added to wishlist successfully!', 'success');
        
                       // Update button state to show it's added
               $btn.removeClass('btn-outline').addClass('added');
               $btn.html('<i class="fas fa-heart"></i>');
               $btn.prop('disabled', false);
    })
    .fail(function(xhr) {
        var error = 'An error occurred while adding to wishlist.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            error = xhr.responseJSON.message;
        }
        showAlert(error, 'error');

        // Reset button state
        $btn.html(originalText);
        $btn.prop('disabled', false);
    });
});

    // Update cart count
    function updateCartCount() {
        $.get('/cart/count')
        .done(function(response) {
            $('.cart-count').text(response.count);
        });
    }

    // Show alerts
    function showAlert(message, type) {
        var alertClass = 'alert-' + type;
        var alertHtml = '<div class="alert ' + alertClass + '">' + message + '</div>';
        
        $('.alerts-container').html(alertHtml);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }

    // Form validation
    $('form').on('submit', function() {
        var isValid = true;
        
        $(this).find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            showAlert('Please fill in all required fields.', 'error');
            return false;
        }
        
        return true;
    });

    // Remove error class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('error');
    });

    // Product image gallery (if exists)
    $('.product-thumbnail').on('click', function() {
        var mainImage = $(this).data('image');
        $('.product-main-image').attr('src', mainImage);
        $('.product-thumbnail').removeClass('active');
        $(this).addClass('active');
    });

    // Quantity increment/decrement
    $('.quantity-btn').on('click', function() {
        var input = $(this).siblings('input[name="quantity"]');
        var currentVal = parseInt(input.val());
        
        if ($(this).hasClass('quantity-plus')) {
            input.val(currentVal + 1);
        } else if ($(this).hasClass('quantity-minus') && currentVal > 1) {
            input.val(currentVal - 1);
        }
    });

    // Search functionality
    $('.search-form').on('submit', function(e) {
        var query = $('.search-input').val().trim();
        if (!query) {
            e.preventDefault();
            showAlert('Please enter a search term.', 'error');
        }
    });

    // Filter toggles
    $('.filter-toggle').on('click', function() {
        $(this).next('.filter-content').slideToggle();
        $(this).toggleClass('active');
    });

    // Sort functionality
    $('.sort-select').on('change', function() {
        var currentUrl = new URL(window.location);
        currentUrl.searchParams.set('sort', $(this).val());
        window.location = currentUrl;
    });

    // Pagination loading
    $('.pagination a').on('click', function(e) {
        if ($(this).attr('href') !== '#') {
            $('.loading-overlay').show();
        }
    });

    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.nav-links').toggleClass('mobile-open');
        $(this).toggleClass('active');
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });

    // Initialize tooltips
    $('[data-tooltip]').each(function() {
        $(this).tooltip({
            position: { my: 'left+5 center', at: 'right center' }
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Add to wishlist functionality
    $('.add-to-wishlist').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        
        $.post('/wishlist/add', { product_id: productId })
        .done(function(response) {
            showAlert('Product added to wishlist!', 'success');
            $(this).addClass('added').text('Added to Wishlist');
        })
        .fail(function() {
            showAlert('Please login to add items to wishlist.', 'error');
        });
    });

    // Newsletter subscription
    $('.newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var email = $('.newsletter-email').val();
        
        $.post('/newsletter/subscribe', { email: email })
        .done(function(response) {
            showAlert('Thank you for subscribing!', 'success');
            $('.newsletter-email').val('');
        })
        .fail(function() {
            showAlert('An error occurred. Please try again.', 'error');
        });
    });

    // Image error handling
    $('img').on('error', function() {
        var $img = $(this);
        var $placeholder = $img.next('.image-placeholder');
        
        if ($placeholder.length) {
            $img.hide();
            $placeholder.show();
        } else {
            // Fallback for images without placeholders
            $img.attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMDAgMTI1QzExMi41IDEyNSAxMjIgMTE1LjUgMTIyIDEwM0MxMjIgOTAuNSAxMTIuNSA4MSAxMDAgODFDODcuNSA4MSA3OCA5MC41IDc4IDEwM0M3OCAxMTUuNSA4Ny41IDEyNSAxMDAgMTI1WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMTUwIDE2NUg1MEM0Ny4yNCAxNjUgNDUgMTYyLjc2IDQ1IDE2MFYxNDBDNDUgMTM3LjI0IDQ3LjI0IDEzNSA1MCAxMzVIMTUwQzE1Mi43NiAxMzUgMTU1IDEzNy4yNCAxNTUgMTQwVjE2MEMxNTUgMTYyLjc2IDE1Mi43NiAxNjUgMTUwIDE2NVoiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+');
        }
    });

    // Initialize any plugins or additional functionality here
    console.log('Grozzoery Ecommerce initialized successfully!');
    
    // Flash Sale Timer
    function updateFlashSaleTimer() {
        const timerElement = document.getElementById('flash-sale-timer');
        if (timerElement) {
            let hours = 23;
            let minutes = 59;
            let seconds = 59;
            
            const countdown = setInterval(function() {
                if (seconds > 0) {
                    seconds--;
                } else if (minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else if (hours > 0) {
                    hours--;
                    minutes = 59;
                    seconds = 59;
                } else {
                    clearInterval(countdown);
                    timerElement.textContent = 'Sale Ended!';
                    timerElement.style.background = '#dc2626';
                    return;
                }
                
                timerElement.textContent = 
                    (hours < 10 ? '0' : '') + hours + ':' +
                    (minutes < 10 ? '0' : '') + minutes + ':' +
                    (seconds < 10 ? '0' : '') + seconds;
            }, 1000);
        }
    }
    
    // Cart Preview Functionality
    function updateCartPreview() {
        $.get('/cart/preview')
            .done(function(response) {
                const cartItems = $('#cart-preview-items');
                const totalAmount = $('.total-amount');
                
                if (response.items && response.items.length > 0) {
                    let itemsHtml = '';
                    response.items.forEach(function(item) {
                        itemsHtml += `
                            <div class="cart-preview-item">
                                <div class="cart-preview-image">
                                    <img src="${item.image}" alt="${item.name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="cart-preview-placeholder" style="display: none;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                </div>
                                <div class="cart-preview-details">
                                    <div class="cart-preview-name">${item.name}</div>
                                    <div class="cart-preview-quantity">Qty: ${item.quantity}</div>
                                </div>
                                <div class="cart-preview-price">$${item.price}</div>
                            </div>
                        `;
                    });
                    cartItems.html(itemsHtml);
                    totalAmount.text('$' + response.total);
                } else {
                    cartItems.html('<p class="empty-cart">Your cart is empty</p>');
                    totalAmount.text('$0.00');
                }
            })
            .fail(function() {
                console.error('Failed to fetch cart preview');
            });
    }
    
    // Initialize cart preview
    updateCartPreview();
    
    // Update cart preview when cart changes
    $(document).on('cart:updated', function() {
        updateCartPreview();
        updateCartCount();
    });
    
    // Update cart count
    function updateCartCount() {
        $.get('/cart/count')
            .done(function(response) {
                $('#cart-count').text(response.count);
            })
            .fail(function() {
                console.error('Failed to fetch cart count');
            });
    }
    
    // Initialize cart count
    updateCartCount();
    
    // Initialize flash sale timer
    updateFlashSaleTimer();
    
    // Mobile menu toggle for small screens
    $('.mobile-menu-toggle').on('click', function() {
        $('.nav-actions').toggleClass('mobile-open');
        $(this).toggleClass('active');
    });
    
    // Search suggestions (if needed)
    $('.search-input').on('input', function() {
        const query = $(this).val();
        if (query.length > 2) {
            // You can implement search suggestions here
            // $.get('/search/suggestions', { q: query })
        }
    });
    
    // Category navigation scroll
    $('.category-list').on('scroll', function() {
        const scrollLeft = $(this).scrollLeft();
        const scrollWidth = this.scrollWidth;
        const clientWidth = this.clientWidth;
        
        if (scrollLeft + clientWidth >= scrollWidth - 10) {
            $(this).addClass('scrolled-end');
        } else if (scrollLeft <= 10) {
            $(this).removeClass('scrolled-end');
        }
    });

    // Image zoom functionality
    $('.main-image, .thumbnail-image').on('click', function() {
        const imgSrc = $(this).attr('src');
        const imgAlt = $(this).attr('alt');
        
        // Create modal if it doesn't exist
        if ($('.image-zoom-modal').length === 0) {
            $('body').append(`
                <div class="image-zoom-modal">
                    <div class="image-zoom-close">&times;</div>
                    <img src="${imgSrc}" alt="${imgAlt}">
                </div>
            `);
        } else {
            $('.image-zoom-modal img').attr('src', imgSrc).attr('alt', imgAlt);
        }
        
        $('.image-zoom-modal').fadeIn(300);
    });

    // Close modal on click
    $(document).on('click', '.image-zoom-modal, .image-zoom-close', function() {
        $('.image-zoom-modal').fadeOut(300);
    });

    // Close modal on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.image-zoom-modal').is(':visible')) {
            $('.image-zoom-modal').fadeOut(300);
        }
    });

    // Quantity controls
    $('.quantity-minus').on('click', function() {
        const input = $(this).siblings('.quantity-input');
        const currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
        }
    });

    $('.quantity-plus').on('click', function() {
        const input = $(this).siblings('.quantity-input');
        const currentVal = parseInt(input.val());
        const maxVal = parseInt(input.attr('max'));
        if (currentVal < maxVal) {
            input.val(currentVal + 1);
        }
    });

    // Thumbnail click to change main image
    $('.thumbnail-image').on('click', function() {
        const imgSrc = $(this).attr('src');
        const imgAlt = $(this).attr('alt');
        
        $('.main-image').attr('src', imgSrc).attr('alt', imgAlt);
        
        // Update active thumbnail
        $('.thumbnail-item').removeClass('active');
        $(this).closest('.thumbnail-item').addClass('active');
    });
});
