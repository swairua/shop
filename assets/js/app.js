$(document).ready(function() {
    updateCartCount();

    /* ─── STICKY NAV SHRINK ─── */
    var nav = document.getElementById('mainNav');
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 80) {
                nav.classList.add('navbar-shrink');
            } else {
                nav.classList.remove('navbar-shrink');
            }
            var backTop = document.getElementById('backToTop');
            if (backTop) {
                backTop.classList.toggle('visible', window.scrollY > 400);
            }
        }, { passive: true });
    }

    /* ─── SEARCH TOGGLE ─── */
    window.toggleSearch = function() {
        var toggle = document.querySelector('.search-toggle');
        var expanded = document.getElementById('searchExpanded');
        if (expanded) {
            expanded.classList.toggle('active');
            if (expanded.classList.contains('active')) {
                toggle.style.display = 'none';
                expanded.querySelector('input').focus();
            } else {
                toggle.style.display = 'flex';
            }
        }
    };

    /* ─── SCROLL ANIMATIONS (AOS-like) ─── */
    function aosObserve() {
        var els = document.querySelectorAll('[data-aos]');
        if (els.length === 0) return;
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .1 });
        els.forEach(function(el) { observer.observe(el); });
    }
    aosObserve();

    /* ─── ADD TO CART ─── */
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        var qty = 1;
        var qtyInput = document.getElementById('qty');
        if (qtyInput) qty = parseInt(qtyInput.value) || 1;

        btn.addClass('btn-loading');
        $.post(baseUrl + 'api/cart/add', {
            product_id: productId,
            quantity: qty
        }, function(res) {
            btn.removeClass('btn-loading');
            if (res.success) {
                updateCartCount();
                showToast('Added to cart!', 'success');
                btn.html('<i class="bi bi-check"></i> Added').removeClass('btn-primary btn-outline-primary').addClass('btn-success');
                setTimeout(function() {
                    btn.html('<i class="bi bi-cart-plus"></i> Add to Cart').removeClass('btn-success').addClass('btn-primary');
                }, 2000);
            } else {
                showToast(res.message || 'Error adding to cart', 'error');
            }
        }).fail(function() {
            btn.removeClass('btn-loading');
            showToast('Network error', 'error');
        });
    });

    /* ─── CART UPDATE ─── */
    $(document).on('click', '.update-cart', function() {
        var btn = $(this);
        var itemId = btn.data('item-id');
        var input = btn.closest('.qty-stepper').find('.cart-qty');
        var qty = parseInt(input.val());

        if (btn.data('action') === 'minus') qty--;
        else qty++;

        if (qty < 1) qty = 1;
        input.val(qty);

        $.post(baseUrl + 'api/cart/update', { item_id: itemId, quantity: qty }, function(res) {
            if (res.success) location.reload();
        });
    });

    $(document).on('change', '.cart-qty', function() {
        var input = $(this);
        var itemId = input.data('item-id');
        var qty = parseInt(input.val()) || 1;
        if (qty < 1) qty = 1;
        input.val(qty);

        $.post(baseUrl + 'api/cart/update', { item_id: itemId, quantity: qty }, function(res) {
            if (res.success) location.reload();
        });
    });

    /* ─── REMOVE ITEM (handled by modal in cart view) ─── */

    /* ─── COUPON ─── */
    $('#applyCoupon').click(function() {
        var code = $('#couponCode').val();
        if (!code) return;
        $.post(baseUrl + 'api/cart/apply-coupon', { code: code }, function(res) {
            if (res.success) {
                $('#couponMessage').removeClass('text-danger').addClass('text-success').text(res.message);
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                $('#couponMessage').removeClass('text-success').addClass('text-danger').text(res.message);
            }
        });
    });

    $('#removeCoupon').click(function() {
        $.post(baseUrl + 'api/cart/remove-coupon', {}, function(res) {
            if (res.success) location.reload();
        });
    });

    /* ─── WISHLIST ─── */
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        $.post(baseUrl + 'api/wishlist/toggle', { product_id: productId }, function(res) {
            if (res.success) {
                var msg = res.action === 'added' ? 'Added to wishlist' : 'Removed from wishlist';
                showToast(msg, res.action === 'added' ? 'success' : 'info');
                btn.find('i').toggleClass('bi-heart bi-heart-fill');
                btn.toggleClass('active');
            }
        });
    });

    /* ─── COMPARE ─── */
    $(document).on('click', '.add-to-compare', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        $.post(baseUrl + 'api/compare/toggle', { product_id: productId }, function(res) {
            if (res.success) {
                showToast(res.action === 'added' ? 'Added to compare' : 'Removed from compare', 'info');
            }
        });
    });

    /* ─── SEARCH ─── */
    $('#searchInput').on('input', function() {
        var q = $(this).val();
        if (q.length < 2) { $('#searchResults').hide(); return; }
        $.get(baseUrl + 'api/search', { q: q }, function(res) {
            var html = '';
            if (res.data && res.data.length) {
                res.data.forEach(function(p) {
                    html += '<a href="' + baseUrl + 'product/' + p.slug + '" class="dropdown-item d-flex align-items-center gap-2">';
                    html += '<img src="' + (p.featured_image ? baseUrl + 'uploads/products/' + p.featured_image : 'https://via.placeholder.com/40') + '" width="40" height="40" style="object-fit:cover;border-radius:6px;">';
                    html += '<div class="flex-grow-1"><div class="fw-medium small">' + p.name + '</div><small class="text-muted">' + formatPrice(p.selling_price) + '</small></div>';
                    html += '</a>';
                });
            } else {
                html = '<div class="dropdown-item text-muted small">No results found</div>';
            }
            $('#searchResults').html(html).show();
        });
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('.search-form-expanded, .search-bar').length) {
            $('#searchResults').hide();
        }
    });

    /* ─── REVIEW FORM ─── */
    $('#reviewForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $('button[type="submit"]', form).addClass('btn-loading');
        $.post(baseUrl + 'api/submit-review', form.serialize(), function(res) {
            $('button[type="submit"]', form).removeClass('btn-loading');
            if (res.success) {
                showToast(res.message, 'success');
                form[0].reset();
            } else {
                showToast(res.message || 'Error submitting review', 'error');
            }
        });
    });

    /* ─── CART COUNT ─── */
    function updateCartCount() {
        $.get(baseUrl + 'api/cart/get-count', function(res) {
            var qty = res.qty || 0;
            $('#cartCount').text(qty);
            $('#cartCountMobile').text(qty);
            $('#cartCountOffcanvas').text(qty);
            var badge = document.querySelector('.badge-count');
            if (badge && qty > 0) {
                badge.classList.remove('bounce');
                void badge.offsetWidth;
                badge.classList.add('bounce');
            }
        });
    }
    window.updateCartCount = updateCartCount;

    /* ─── TOAST ─── */
    window.showToast = function(message, type) {
        var bg = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-info');
        var icon = type === 'success' ? 'bi-check-circle' : (type === 'error' ? 'bi-exclamation-circle' : 'bi-info-circle');
        var container = document.getElementById('toastContainer');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white ' + bg + ' border-0 show';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = '<div class="d-flex"><div class="toast-body"><i class="bi ' + icon + ' me-2"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        container.appendChild(toast);
        setTimeout(function() {
            toast.classList.add('hiding');
            setTimeout(function() { toast.remove(); }, 300);
        }, 3000);
    };

    function formatPrice(amount) {
        var symbol = 'KSh ';
        return symbol + parseFloat(amount).toFixed(2);
    }
    window.formatPrice = formatPrice;

    /* ─── FLOATING LABEL FIX ─── */
    document.querySelectorAll('.floating-label-group input, .floating-label-group textarea').forEach(function(el) {
        if (el.value) el.closest('.floating-label-group').classList.add('filled');
        el.addEventListener('input', function() {
            if (this.value) this.closest('.floating-label-group').classList.add('filled');
            else this.closest('.floating-label-group').classList.remove('filled');
        });
        el.addEventListener('blur', function() {
            if (!this.value) this.closest('.floating-label-group').classList.remove('filled');
        });
    });
});