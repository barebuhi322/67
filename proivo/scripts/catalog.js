console.log('catalog.js загружен');

if (typeof window.BASE_URL === 'undefined') {
    const pathParts = window.location.pathname.split('/');
    const cleanParts = pathParts.filter(p => p.length > 0);
    const currentFolder = cleanParts.length > 0 ? cleanParts[0] : '';
    window.BASE_URL = currentFolder ? '/' + currentFolder + '/' : '/';
}

console.log('BASE_URL:', window.BASE_URL);

const CART_STORAGE_KEY = 'handmade_cart';

async function checkAuth() {
    try {
        const response = await fetch(window.BASE_URL + '../pages/check-session.php');
        const data = await response.json();
        console.log('Проверка авторизации:', data);
        return data.logged_in;
    } catch (error) {
        console.error('Ошибка проверки авторизации:', error);
        return false;
    }
}

function redirectToHome() {
    window.location.href = window.BASE_URL + 'index.php';
}

function openRegisterModal() {
    console.log('openRegisterModal вызван');
    
    const authRequiredModal = document.getElementById('authRequiredModal');
    if (authRequiredModal) {
        authRequiredModal.classList.remove('active');
    }
    
    const modal = document.getElementById('authModal');
    if (modal) {
        modal.classList.add('active');
        const switchRegister = document.getElementById('switchRegister');
        const switchLogin = document.getElementById('switchLogin');
        const registerForm = document.getElementById('registerForm');
        const loginForm = document.getElementById('loginForm');
        
        if (switchRegister && switchLogin) {
            switchRegister.classList.add('active');
            switchLogin.classList.remove('active');
            registerForm.classList.add('visible-form');
            registerForm.classList.remove('hidden-form');
            loginForm.classList.add('hidden-form');
            loginForm.classList.remove('visible-form');
        }
    }
}

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

function openBuyModal(productId, category) {
    console.log('openBuyModal вызван', {productId, category});
    
    const productCard = document.querySelector(`.product-card[data-category="${category}"][data-id="${productId}"]`);
    if (!productCard) {
        console.error('productCard не найден');
        return;
    }
    
    const title = productCard.querySelector('.product-title').textContent;
    const priceText = productCard.querySelector('.product-price').textContent;
    const price = parseInt(priceText.replace(/[^0-9]/g, ''));
    const imageElement = productCard.querySelector('.product-image');
    
    let imageSrc = 'default-avatar.png';
    const classList = imageElement.className;
    
    if (classList.includes('soft-')) {
        const match = classList.match(/soft-(\d+)/);
        const num = match ? match[1] : productId;
        imageSrc = `soft-toy-${num}.jpg`;
    } else if (classList.includes('wood-')) {
        const match = classList.match(/wood-(\d+)/);
        const num = match ? match[1] : productId;
        imageSrc = `wood-toy-${num}.jpg`;
    } else if (classList.includes('clay-')) {
        const match = classList.match(/clay-(\d+)/);
        const num = match ? match[1] : productId;
        imageSrc = `clay-item-${num}.jpg`;
    }
    
    const buyModal = document.getElementById('buyModal');
    const productImage = document.getElementById('buyProductImage');
    const productTitle = document.getElementById('buyProductTitle');
    const productPrice = document.getElementById('buyProductPrice');
    const checkoutPrice = document.getElementById('buyCheckoutPrice');
    
    if (productImage) {
        productImage.style.backgroundImage = `url('${imageSrc}')`;
    }
    
    if (productTitle) {
        productTitle.textContent = title;
    }
    
    if (productPrice) {
        productPrice.textContent = price.toLocaleString() + ' руб.';
    }
    
    if (checkoutPrice) {
        checkoutPrice.textContent = price.toLocaleString() + ' руб.';
    }
    
    if (buyModal) {
        buyModal.setAttribute('data-product-id', productId);
        buyModal.setAttribute('data-product-category', category);
        buyModal.setAttribute('data-product-price', price);
        buyModal.setAttribute('data-product-title', title);
        buyModal.setAttribute('data-product-image', imageSrc);
    }
    
    if (buyModal) {
        buyModal.classList.add('active');
    }
}

function closeBuyModal() {
    const buyModal = document.getElementById('buyModal');
    if (buyModal) {
        buyModal.classList.remove('active');
    }
}

function processPayment() {
    const buyModal = document.getElementById('buyModal');
    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
    
    if (!buyModal) return;
    
    const productId = buyModal.getAttribute('data-product-id');
    const productCategory = buyModal.getAttribute('data-product-category');
    const productPrice = buyModal.getAttribute('data-product-price');
    const productTitle = buyModal.getAttribute('data-product-title');
    
    console.log('Обработка платежа:', {
        productId,
        productCategory,
        productPrice,
        productTitle,
        paymentMethod
    });
    
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
    
    closeBuyModal();
}

async function addToCart(button, id, category) {
    console.log('addToCart вызван', {id, category});
    
    const isLoggedIn = await checkAuth();
    console.log('Пользователь авторизован:', isLoggedIn);
    
    if (!isLoggedIn) {
        console.log('Пользователь не авторизован, открываем модалку');
        openRegisterModal();
        return;
    }
    
    console.log('Добавляем товар в корзину');
    
    const productCard = button.closest('.product-card');
    if (!productCard) {
        console.error('productCard не найден');
        return;
    }
    
    const title = productCard.querySelector('.product-title').textContent;
    const priceText = productCard.querySelector('.product-price').textContent;
    const price = parseInt(priceText.replace(/[^0-9]/g, ''));
    
    console.log('Товар:', {title, price});
    
    const product = {
        id: `${category}_${id}`,
        category: category,
        itemId: id,
        title: title,
        price: price,
        image: 'img/default-avatar.png'
    };
    
    const cart = getCart();
    console.log('Текущая корзина:', cart);
    
    const existingItem = cart.find(item => item.id === product.id);
    
    if (!existingItem) {
        cart.push(product);
        saveCart(cart);
        console.log('Товар добавлен, новая корзина:', cart);
        
        button.style.backgroundColor = '#45a049';
        button.textContent = 'Добавлено';
        
        setTimeout(() => {
            button.style.backgroundColor = '#2196F3';
            button.textContent = 'В корзину';
        }, 1000);
        
        alert('Товар добавлен в корзину! Перейдите в личный кабинет, чтобы посмотреть.');
    } else {
        console.log('Товар уже в корзине');
        button.style.backgroundColor = '#ff4444';
        button.textContent = 'Уже в корзине';
        
        setTimeout(() => {
            button.style.backgroundColor = '#2196F3';
            button.textContent = 'В корзину';
        }, 1000);
        
        alert('Этот товар уже в корзине');
    }
}

async function buyProduct(id, category) {
    console.log('buyProduct вызван', {id, category});
    
    const isLoggedIn = await checkAuth();
    
    if (!isLoggedIn) {
        openRegisterModal();
        return;
    }
    
    openBuyModal(id, category);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Страница каталога загружена');
    
    const cartButtons = document.querySelectorAll('.btn-cart');
    console.log('Найдено кнопок "В корзину":', cartButtons.length);
    
    const buyButtons = document.querySelectorAll('.btn-buy');
    console.log('Найдено кнопок "Купить":', buyButtons.length);
    
    const authModal = document.getElementById('authModal');
    if (authModal) {
        authModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    }
    
    const buyModal = document.getElementById('buyModal');
    if (buyModal) {
        buyModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBuyModal();
            }
        });
    }
    
    const switchRegister = document.getElementById('switchRegister');
    const switchLogin = document.getElementById('switchLogin');
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    
    if (switchRegister && switchLogin && registerForm && loginForm) {
        switchRegister.addEventListener('click', function() {
            console.log('Переключение на регистрацию');
            switchRegister.classList.add('active');
            switchLogin.classList.remove('active');
            registerForm.classList.add('visible-form');
            registerForm.classList.remove('hidden-form');
            loginForm.classList.add('hidden-form');
            loginForm.classList.remove('visible-form');
        });
        
        switchLogin.addEventListener('click', function() {
            console.log('Переключение на вход');
            switchLogin.classList.add('active');
            switchRegister.classList.remove('active');
            loginForm.classList.add('visible-form');
            loginForm.classList.remove('hidden-form');
            registerForm.classList.add('hidden-form');
            registerForm.classList.remove('visible-form');
        });
    }
});

window.openRegisterModal = openRegisterModal;
window.buyProduct = buyProduct;
window.addToCart = addToCart;
window.logout = logout;
window.redirectToHome = redirectToHome;
window.closeBuyModal = closeBuyModal;
window.processPayment = processPayment;