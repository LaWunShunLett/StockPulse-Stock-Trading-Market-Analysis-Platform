
function validateSignInForm(event) {
    event.preventDefault(); // Prevent the form from submitting
    const form = event.target; // Get the form element

    // Get the values from the input fields using form and name attributes
    const username = form.elements['usernameSignIn'].value.trim();
    const password = form.elements['passwordSignIn'].value.trim();


    let err = document.getElementById("showErrSignIn");
    
    if (username === "") {
        console.log("Username cannot be empty");
        err.innerHTML = "Usename cannot be empty";
        return false;
    }

    if (password === "") {
        console.log("Password cannot be empty");
        err.innerHTML = "Password  cannot be empty";
        return false;
    }

    console.log('Form is valid!');
    form.submit(); //submit the form if all validations pass
}



