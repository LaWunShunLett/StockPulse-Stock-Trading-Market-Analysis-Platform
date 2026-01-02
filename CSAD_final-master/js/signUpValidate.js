
let username = email = password = confirmPassword = "";

function validateSignUpForm(event) {
    event.preventDefault(); // Prevent the form from submitting
    let form = event.target; // Get the form element

    // Get the values from the input fields using form and name attributes
    username = form.elements['username'].value.trim();
    email = form.elements['email'].value.trim();
    password = form.elements['password'].value.trim();
    confirmPassword = form.elements['confirm_password'].value.trim();


    let err = document.getElementById("showErrSignUp");

    // Perform validations
   
    if (validateEmpty(username, email, password, confirmPassword) != "") {
        err.innerHTML = validateEmpty(username, email, password, confirmPassword);
        return false;
    } 


    if (!validateUsername(username)) {
        /*'Invalid username. Must start with a letter, only contain letters and numbers, and be at least 6 
        characters long.'*/
        console.log("Invalid User name");
        err.innerHTML = "Invalid username. Must start with a letter, include only letters, numbers, and at least 6 characters long";
        return false;
    }

    if (!validateEmail(email)) {
        console.log('Invalid email address.');
        err.innerHTML = "Invalid Email Address";
        return false;
    }

    if (!validatePasswordLength(password)) {
        console.log('Password is too short. It must be at least 8 characters long.');
        err.innerHTML = "Password is too short. It must be at least 8 characters long.";
        return false;
    } else {
        if (!validatePasswordMatch(password, confirmPassword)) {
            err.innerHTML = "Passwords do not match.";
            console.log('Passwords do not match.');
            return false;
        }
    }

    
    
    // If all validations pass
    
    console.log('Form is valid!');
    form.submit(); //submit the form if all validations pass
}

function validateEmpty(username, email, password, confirmPassword) {
    if (username === "") return "User name cannot be empty";
    if (email === "") return "Email cannot be empty";
    if (password === "") return "Password cannot be empty";
    if (confirmPassword ==="") return "Confirm Password cannot be empty";
    return "";
}

function validateUsername(username) {
    // Regular expression to match usernames that start with a letter, only contain letters and numbers, and have 
    //a minimum length of 6
    const regex = /^[a-zA-Z][a-zA-Z0-9]{5,}$/;

    // Test the username against the regex
    if (regex.test(username)) {
        return true; // Valid username
    } else {
        return false; // Invalid username
    }
}

function validateEmail(email) {
    // Regular expression to match a valid email address
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Test the email against the regex
    return regex.test(email);
}

function validatePasswordLength(password) {
    // Check if the password length is at least 8 characters
    return password.length >= 8;
}

function validatePasswordMatch(password, confirmPassword) {
    return password === confirmPassword;
}


// // Get the current URL
// let url = new URL(window.location.href);
// // Get the search parameters from the URL
// let params = new URLSearchParams(url.search);

// console.log(params);


// // Check if the 'error' parameter exists
// if (params.has('error')) {
//     let myForm = document.getElementById('signUpForm');
//     myForm['username'].value = username;
//     myForm['email'].value = email;
//     myForm['password'].value = password;
//     myForm['confirm_password'].value = confirmPassword;
// }
