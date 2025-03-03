document.getElementById('price-range').addEventListener('input', function () {
    const priceDisplay = document.getElementById('price-display');
    priceDisplay.textContent = `${this.value.toLocaleString()} HUF`;
});

document.getElementById('apply-filters').addEventListener('click', function () {
    const fuelFilter = document.getElementById('fuel').value;
    const transmissionFilter = document.getElementById('transmission').value;
    const priceFilter = document.getElementById('price-range').value;
    const seatsFilter = document.getElementById('seats').value;

    const cars = document.querySelectorAll('.car-card');

    cars.forEach(car => {
        const carFuel = car.getAttribute('data-fuel');
        const carTransmission = car.getAttribute('data-transmission');
        const carPrice = car.getAttribute('data-price');
        const carSeats = car.getAttribute('data-seats');

        let isMatch = true;

        if (fuelFilter && carFuel !== fuelFilter) {
            isMatch = false;
        }

        if (transmissionFilter && carTransmission !== transmissionFilter) {
            isMatch = false;
        }

        if (priceFilter && carPrice > priceFilter) {
            isMatch = false;
        }

        if (seatsFilter && carSeats !== seatsFilter) {
            isMatch = false;
        }

        if (isMatch) {
            car.style.display = 'block';
        } else {
            car.style.display = 'none';
        }
    });
});

const registerButton = document.getElementById('register');
if (registerButton) {
    registerButton.addEventListener('click', function () {
        window.location.href = 'register.php';
    });
}

const loginButton = document.getElementById('login');
if (loginButton) {
    loginButton.addEventListener('click', function () {
        window.location.href = 'login.php';
    });
}

const logoutButton = document.getElementById('logout');
if (logoutButton) {
    logoutButton.addEventListener('click', function () {
        window.location.href = 'logout.php';
    });
}

const profileButton = document.getElementById('profile');
if (profileButton) {
    profileButton.addEventListener('click', function () {
        window.location.href = 'profile.php';
    });
}

const addCarButton = document.getElementById('add-car');
if (addCarButton) {
    addCarButton.addEventListener('click', function () {
        window.location.href = 'add_car.php';
    });
}