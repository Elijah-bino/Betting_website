// Get form elements 
const form = document.querySelector('form');
const username = document.getElementById('txt');
const email = document.getElementById('email');
const password = document.getElementById('pswd');

// Email validation regex
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Add submit event listener
form.addEventListener('submit', e => {

  // Prevent default submit
  e.preventDefault();
  
  // Reset errors
  errors = {};
  
  // Validate form  
  let isFormValid = true;

  // Validate username
  if(username.value === '') {
    isFormValid = false;
    errors['username'] = 'Please enter username';
  }

  // Validate email
  if(email.value === '') {
    isFormValid = false;
    errors['email'] = 'Please enter email';
  } else if (!emailRegex.test(email.value)) {
    isFormValid = false;
    errors['email'] = 'Please enter a valid email';
  }

  // Validate password
  if(password.value === ''){
    isFormValid = false;
    errors['password'] = 'Please enter password';
  }  

  // Handle errors and submit
  if(isFormValid) {
    form.submit();
  } else {
    // Show errors 
    Object.keys(errors).forEach(key => {
      const input = form.querySelector(`[name="${key}"]`);
      input.nextElementSibling.textContent = errors[key]; 
    });
  }

});