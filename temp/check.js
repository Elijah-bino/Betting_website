const betNowBtn = document.querySelector('.bet-now-button');

betNowBtn.addEventListener('click', () => {

  // Check logged in status
  if(!isUserLoggedIn()) {
    window.location.href = '/register.html';
    return;
  }

  // User is logged in, allow betting
  window.location.href = '/betting.html';

});

function isUserLoggedIn() {
  // Check for presence of auth token/session cookie
  return false; 
}