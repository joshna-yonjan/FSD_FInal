// Add to cart
function addToCart(itemId) {
    fetch('cart_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&item_id=${itemId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.count);
            showNotification('Item added to cart!', 'success');
        } else {
            showNotification(data.message || 'Failed to add item', 'error');
        }
    })
    .catch(() => showNotification('An error occurred', 'error'));
}

// Update cart quantity
function updateCart(itemId, quantity) {
    fetch('cart_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&item_id=${itemId}&quantity=${quantity}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.count);
            location.reload();
        }
    });
}

// Remove from cart
function removeFromCart(itemId) {
    if (confirm('Remove this item from cart?')) {
        fetch('cart_ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&item_id=${itemId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartBadge(data.count);
                location.reload();
            }
        });
    }
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `flash-message flash-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '250px';
    notification.style.animation = 'slideIn 0.3s ease';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
