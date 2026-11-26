if (typeof window.BASE_URL === 'undefined') {
    const pathParts = window.location.pathname.split('/');
    const cleanParts = pathParts.filter(p => p.length > 0);
    const currentFolder = cleanParts.length > 0 ? cleanParts[0] : '';
    window.BASE_URL = currentFolder ? '/' + currentFolder + '/' : '/';
}

console.log('BASE_URL:', window.BASE_URL);

const CART_STORAGE_KEY = 'handmade_cart';

function logout() {
    console.log('logout вызван');
    fetch(window.BASE_URL + 'pages/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = window.BASE_URL + 'index.php';
            }
        })
        .catch(error => {
            console.error('Ошибка при выходе:', error);
        });
}

function getCart() {
    const cartJson = sessionStorage.getItem(CART_STORAGE_KEY);
    return cartJson ? JSON.parse(cartJson) : [];
}

function saveCart(cart) {
    sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
}

function displayCartItems() {
    console.log('displayCartItems вызван');
    
    const cartContainer = document.getElementById('cartContainer');
    if (!cartContainer) {
        console.error('cartContainer не найден');
        return;
    }
    
    const cart = getCart();
    console.log('Товары в корзине:', cart);
    
    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="cart-empty">
                <p>Ваша корзина пуста</p>
                <p class="cart-empty-hint">Перейдите в <a href="pages/catalog.php">каталог</a>, чтобы добавить товары</p>
            </div>
        `;
        
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.disabled = true;
            checkoutBtn.style.opacity = '0.5';
            checkoutBtn.style.cursor = 'not-allowed';
        }
    } else {
        let html = '';
        let total = 0;
        
        cart.forEach((item) => {
            total += item.price;
            html += `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.title}" class="cart-item-image" onerror="this.src='../img/default-avatar.png'">
                    <div class="cart-item-info">
                        <div class="cart-item-title">${item.title}</div>
                        <div class="cart-item-price">${item.price.toLocaleString()} руб.</div>
                    </div>
                    <button class="cart-item-remove" onclick="removeFromCart('${item.id}')">×</button>
                </div>
            `;
        });
        
        html += `
            <div class="cart-total">
                <span>Итого:</span>
                <span class="cart-total-price">${total.toLocaleString()} руб.</span>
            </div>
        `;
        
        cartContainer.innerHTML = html;
        
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.disabled = false;
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
        }
    }
}

function removeFromCart(itemId) {
    console.log('Удаление товара из корзины, ID:', itemId);
    
    let cart = getCart();
    cart = cart.filter(item => item.id !== itemId);
    saveCart(cart);
    
    const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
    if (cartItem) {
        cartItem.style.transform = 'translateX(100%)';
        cartItem.style.opacity = '0';
        cartItem.style.transition = 'all 0.3s';
        
        setTimeout(() => {
            displayCartItems();
        }, 300);
    } else {
        displayCartItems();
    }
}

function openCartCheckoutModal() {
    console.log('openCartCheckoutModal вызван');
    
    const cart = getCart();
    
    if (cart.length === 0) {
        alert('Корзина пуста');
        return;
    }
    
    const itemsList = document.getElementById('cartCheckoutItemsList');
    const totalElement = document.getElementById('cartCheckoutTotal');
    
    if (!itemsList || !totalElement) {
        console.error('Элементы модального окна не найдены');
        return;
    }
    
    let itemsHtml = '';
    let total = 0;
    
    cart.forEach((item) => {
        total += item.price;
        itemsHtml += `
            <div class="cart-checkout-item">
                <div class="cart-checkout-item-info">
                    <div class="cart-checkout-item-title">${item.title}</div>
                    <div class="cart-checkout-item-price">${item.price.toLocaleString()} руб.</div>
                </div>
            </div>
        `;
    });
    
    itemsList.innerHTML = itemsHtml;
    totalElement.innerHTML = `Итого: <span class="cart-checkout-total-price">${total.toLocaleString()} руб.</span>`;
    
    const modal = document.getElementById('cartCheckoutModal');
    if (modal) {
        modal.classList.add('active');
    }
}

function closeCartCheckoutModal() {
    const modal = document.getElementById('cartCheckoutModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function processCartPayment() {
    const paymentMethod = document.querySelector('input[name="cartPayment"]:checked')?.value;
    const cart = getCart();
    
    if (cart.length === 0) {
        alert('Корзина пуста');
        closeCartCheckoutModal();
        return;
    }
    
    if (!paymentMethod) {
        alert('Выберите способ оплаты');
        return;
    }
    
    let paymentName = '';
    switch(paymentMethod) {
        case 'card':
            paymentName = 'Банковская карта';
            break;
        case 'qiwi':
            paymentName = 'QIWI';
            break;
        case 'yoomoney':
            paymentName = 'ЮMoney';
            break;
        default:
            paymentName = paymentMethod;
    }
    
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    const itemsList = cart.map(item => `• ${item.title} - ${item.price.toLocaleString()} руб.`).join('\n');
    
    alert(`Оформление заказа на сумму ${total.toLocaleString()} руб.\n\nТовары:\n${itemsList}\n\nСпособ оплаты: ${paymentName}\n\nСпасибо за покупку!`);
    
    closeCartCheckoutModal();
}

function handleCheckoutClick() {
    console.log('handleCheckoutClick вызван');
    const cart = getCart();
    
    if (cart.length === 0) {
        alert('Корзина пуста');
        return;
    }
    
    openCartCheckoutModal();
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Страница профиля загружена');
    
    displayCartItems();
    
    const changeAvatarBtn = document.getElementById('changeAvatarBtn');
    if (changeAvatarBtn) {
        changeAvatarBtn.addEventListener('click', changeAvatar);
    }
    
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        console.log('Кнопка оплаты найдена, добавляем обработчик');
        checkoutBtn.addEventListener('click', handleCheckoutClick);
    } else {
        console.error('Кнопка оплаты не найдена!');
    }
    
    const cartCheckoutModal = document.getElementById('cartCheckoutModal');
    if (cartCheckoutModal) {
        cartCheckoutModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCartCheckoutModal();
            }
        });
    }
    
    const cartCheckoutBtn = document.getElementById('cartCheckoutBtn');
    if (cartCheckoutBtn) {
        cartCheckoutBtn.addEventListener('click', processCartPayment);
    }
});

window.logout = logout;
window.changeAvatar = changeAvatar;
window.removeFromCart = removeFromCart;
window.openCartCheckoutModal = openCartCheckoutModal;
window.closeCartCheckoutModal = closeCartCheckoutModal;
window.processCartPayment = processCartPayment;
window.handleCheckoutClick = handleCheckoutClick;