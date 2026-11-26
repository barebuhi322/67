const pathParts = window.location.pathname.split('/');
const cleanParts = pathParts.filter(p => p.length > 0);
const currentFolder = cleanParts.length > 0 ? cleanParts[0] : '';
window.BASE_URL = currentFolder ? '/' + currentFolder + '/' : '/';

console.log('index.js загружен, BASE_URL:', window.BASE_URL);

const regButton = document.getElementById('openRegisterModal');
const profileBlock = document.getElementById('profileBlock');
const profileLoginSpan = document.getElementById('profileLogin');
const avatarImg = document.getElementById('avatarImg');
const logoutBtn = document.getElementById('logoutBtn');

const modal = document.getElementById('authModal');
const switchRegister = document.getElementById('switchRegister');
const switchLogin = document.getElementById('switchLogin');
const registerFormDiv = document.getElementById('registerForm');
const loginFormDiv = document.getElementById('loginForm');

const regLogin = document.getElementById('regLogin');
const regPassword = document.getElementById('regPassword');
const regEmail = document.getElementById('regEmail');
const regLoginErr = document.getElementById('regLoginError');
const regPassErr = document.getElementById('regPasswordError');
const regEmailErr = document.getElementById('regEmailError');

const loginLogin = document.getElementById('loginLogin');
const loginPassword = document.getElementById('loginPassword');
const loginLoginErr = document.getElementById('loginLoginError');
const loginPassErr = document.getElementById('loginPasswordError');

async function fetchAPI(endpoint, data = null) {
    const url = window.BASE_URL + endpoint;
    console.log('Запрос к:', url);
    
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    };
    
    if (data) {
        const formData = new URLSearchParams();
        for (let key in data) {
            formData.append(key, data[key]);
        }
        options.body = formData;
    }
    
    try {
        const response = await fetch(url, options);
        
        if (!response.ok) {
            throw new Error(`HTTP ошибка: ${response.status}`);
        }
        
        const text = await response.text();
        
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Невалидный JSON:', text);
            throw new Error('Сервер вернул невалидный JSON');
        }
    } catch (error) {
        console.error('Ошибка fetch:', error);
        throw error;
    }
}

window.addEventListener('load', async () => {
    try {
        const data = await fetchAPI('pages/check-session.php');
        if (data.logged_in) {
            showProfile(data.user.login, data.user.avatar);
        }
    } catch (error) {
        console.error('Ошибка проверки сессии:', error);
    }
});

if (regButton) {
    regButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (modal) {
            modal.classList.add('active');
            resetRegisterForm();
            resetLoginForm();
            setActiveTab('register');
        }
    });
}

if (modal) {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
}

if (switchRegister) {
    switchRegister.addEventListener('click', () => setActiveTab('register'));
}
if (switchLogin) {
    switchLogin.addEventListener('click', () => setActiveTab('login'));
}

function setActiveTab(tab) {
    if (!switchRegister || !switchLogin || !registerFormDiv || !loginFormDiv) return;
    
    if (tab === 'register') {
        switchRegister.classList.add('active');
        switchLogin.classList.remove('active');
        registerFormDiv.classList.add('visible-form');
        registerFormDiv.classList.remove('hidden-form');
        loginFormDiv.classList.add('hidden-form');
        loginFormDiv.classList.remove('visible-form');
        resetLoginForm();
    } else {
        switchLogin.classList.add('active');
        switchRegister.classList.remove('active');
        loginFormDiv.classList.add('visible-form');
        loginFormDiv.classList.remove('hidden-form');
        registerFormDiv.classList.add('hidden-form');
        registerFormDiv.classList.remove('visible-form');
        resetRegisterForm();
    }
}

function showProfile(login, avatar = 'img/default-avatar.png') {
    if (regButton) regButton.style.display = 'none';
    if (profileBlock) {
        profileBlock.style.display = 'flex';
        if (profileLoginSpan) {
            profileLoginSpan.textContent = login;
            profileLoginSpan.style.cursor = 'pointer';
            profileLoginSpan.onclick = function() {
                window.location.href = window.BASE_URL + 'pages/profile.php';
            };
        }
        
        if (avatarImg) {
            avatarImg.style.cursor = 'pointer';
            avatarImg.onclick = function() {
                window.location.href = window.BASE_URL + 'pages/profile.php';
            };
            
            let avatarPath = window.BASE_URL + avatar;
            avatarImg.src = avatarPath;
            
            avatarImg.onerror = function() {
                this.src = window.BASE_URL + 'img/default-avatar.png';
                this.onerror = null;
            };
        }
    }
}

function hideProfile() {
    if (regButton) regButton.style.display = 'inline-block';
    if (profileBlock) {
        profileBlock.style.display = 'none';
        if (profileLoginSpan) profileLoginSpan.textContent = '';
        if (avatarImg) avatarImg.src = window.BASE_URL + 'img/default-avatar.png';
    }
}

if (document.getElementById('registerSubmit')) {
    document.getElementById('registerSubmit').addEventListener('click', async (e) => {
        e.preventDefault();
        
        let isValid = true;
        
        if (regLogin.value.trim() === '') {
            regLoginErr.textContent = 'Логин обязателен';
            regLogin.classList.add('error');
            isValid = false;
        } else if (/\d/.test(regLogin.value.trim())) {
            regLoginErr.textContent = 'Логин не должен содержать цифры';
            regLogin.classList.add('error');
            isValid = false;
        } else {
            regLogin.classList.remove('error');
            regLoginErr.textContent = '';
        }
        
        if (regPassword.value === '') {
            regPassErr.textContent = 'Пароль обязателен';
            regPassword.classList.add('error');
            isValid = false;
        } else if (regPassword.value.length <= 6) {
            regPassErr.textContent = 'Пароль должен быть более 6 символов';
            regPassword.classList.add('error');
            isValid = false;
        } else {
            regPassword.classList.remove('error');
            regPassErr.textContent = '';
        }
        
        if (regEmail.value.trim() === '') {
            regEmailErr.textContent = 'Email обязателен';
            regEmail.classList.add('error');
            isValid = false;
        } else if (!regEmail.value.includes('@')) {
            regEmailErr.textContent = 'Некорректный email';
            regEmail.classList.add('error');
            isValid = false;
        } else {
            regEmail.classList.remove('error');
            regEmailErr.textContent = '';
        }
        
        if (!isValid) return;
        
        try {
            const data = await fetchAPI('pages/register.php', {
                login: regLogin.value.trim(),
                password: regPassword.value,
                email: regEmail.value.trim()
            });
            
            if (data.success) {
                showProfile(data.user.login, data.user.avatar);
                if (modal) modal.classList.remove('active');
                resetRegisterForm();
                alert('Регистрация успешна!');
            } else {
                alert(data.message || 'Ошибка регистрации');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Ошибка соединения с сервером');
        }
    });
}

if (document.getElementById('loginSubmit')) {
    document.getElementById('loginSubmit').addEventListener('click', async (e) => {
        e.preventDefault();
        
        let isValid = true;
        
        if (loginLogin.value.trim() === '') {
            loginLoginErr.textContent = 'Введите логин или email';
            loginLogin.classList.add('error');
            isValid = false;
        } else {
            loginLogin.classList.remove('error');
            loginLoginErr.textContent = '';
        }
        
        if (loginPassword.value === '') {
            loginPassErr.textContent = 'Введите пароль';
            loginPassword.classList.add('error');
            isValid = false;
        } else {
            loginPassword.classList.remove('error');
            loginPassErr.textContent = '';
        }
        
        if (!isValid) return;
        
        try {
            const data = await fetchAPI('pages/login.php', {
                login: loginLogin.value.trim(),
                password: loginPassword.value
            });
            
            if (data.success) {
                showProfile(data.user.login, data.user.avatar);
                if (modal) modal.classList.remove('active');
                resetLoginForm();
                alert('Вход выполнен успешно!');
            } else {
                alert(data.message || 'Ошибка входа');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Ошибка соединения с сервером');
        }
    });
}

if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
        try {
            const data = await fetchAPI('pages/logout.php');
            if (data.success) {
                hideProfile();
                window.location.href = window.BASE_URL + 'index.php';
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    });
}

function resetRegisterForm() {
    if (regLogin) regLogin.value = ''; 
    if (regPassword) regPassword.value = ''; 
    if (regEmail) regEmail.value = '';
    if (regLoginErr) regLoginErr.textContent = ''; 
    if (regPassErr) regPassErr.textContent = ''; 
    if (regEmailErr) regEmailErr.textContent = '';
    if (regLogin) regLogin.classList.remove('error'); 
    if (regPassword) regPassword.classList.remove('error'); 
    if (regEmail) regEmail.classList.remove('error');
}

function resetLoginForm() {
    if (loginLogin) loginLogin.value = ''; 
    if (loginPassword) loginPassword.value = '';
    if (loginLoginErr) loginLoginErr.textContent = ''; 
    if (loginPassErr) loginPassErr.textContent = '';
    if (loginLogin) loginLogin.classList.remove('error'); 
    if (loginPassword) loginPassword.classList.remove('error');
}

if (regLogin) {
    regLogin.addEventListener('input', () => { 
        regLogin.classList.remove('error'); 
        if (regLoginErr) regLoginErr.textContent = ''; 
    });
}

if (regPassword) {
    regPassword.addEventListener('input', () => { 
        regPassword.classList.remove('error'); 
        if (regPassErr) regPassErr.textContent = ''; 
    });
}

if (regEmail) {
    regEmail.addEventListener('input', () => { 
        regEmail.classList.remove('error'); 
        if (regEmailErr) regEmailErr.textContent = ''; 
    });
}

if (loginLogin) {
    loginLogin.addEventListener('input', () => { 
        loginLogin.classList.remove('error'); 
        if (loginLoginErr) loginLoginErr.textContent = ''; 
    });
}

if (loginPassword) {
    loginPassword.addEventListener('input', () => { 
        loginPassword.classList.remove('error'); 
        if (loginPassErr) loginPassErr.textContent = ''; 
    });
}

window.fetchAPI = fetchAPI;
window.showProfile = showProfile;
window.hideProfile = hideProfile;
window.setActiveTab = setActiveTab;
window.resetRegisterForm = resetRegisterForm;
window.resetLoginForm = resetLoginForm;