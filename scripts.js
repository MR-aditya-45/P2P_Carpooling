// Open Register Modal
document.getElementById("registerBtnMain").addEventListener("click", function() {
    document.getElementById("registerModal").style.display = "flex";
});

// Close Modal on click outside modal content
window.addEventListener("click", function(e) {
    if (e.target === document.getElementById("registerModal")) {
        document.getElementById("registerModal").style.display = "none";
    }
});

// Register as Driver
document.getElementById("driverBtn").addEventListener("click", function() {
    window.location.href = "driver_register.html";  // Redirect to Driver registration page
});

// Register as Passenger
document.getElementById("passengerBtn").addEventListener("click", function() {
    window.location.href = "passenger_register.html";  // Redirect to Passenger registration page
});
// Handle Passenger Registration Form Submission
document.getElementById("passengerForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting immediately

    // Get form values
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    // Simple form validation
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    if (name && email && phone && password) {
        // Normally, you would send the data to a server for registration.
        // For now, we will just show an alert.
        alert("Registration successful!");
        // You could redirect to another page like Dashboard or Login.
        window.location.href = "login.html";
    } else {
        alert("Please fill out all fields.");
    }
});
// Handle Driver Login Form Submission
document.getElementById("driverLoginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (email && password) {
        // Here you would normally verify credentials with a backend.
        alert("Driver login successful!");
        // Redirect to driver dashboard or homepage
        window.location.href = "driver_dashboard.html";
    } else {
        alert("Please enter your email and password.");
    }
});

// Handle Passenger Login Form Submission
document.getElementById("passengerLoginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (email && password) {
        // Here you would normally verify credentials with a backend.
        alert("Passenger login successful!");
        // Redirect to passenger dashboard or homepage
        window.location.href = "passenger_dashboard.html";
    } else {
        alert("Please enter your email and password.");
    }
});
