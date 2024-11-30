document.addEventListener('DOMContentLoaded', () => {
    const services = document.querySelectorAll('.service');
    services.forEach(service => {
        service.classList.add('animate');
    });
<<<<<<< HEAD
  });
   // Set your desired limit here
   const userLimit = 1000;
   let currentCount = 0;

   const counterDisplay = document.getElementById('counter');
   const startButton = document.getElementById('startButton');
   const statusMessage = document.getElementById('status');

   // Function to start the counting process
   function startCounting() {
       startButton.disabled = true;  // Disable button to prevent multiple clicks
       statusMessage.textContent = "Counting users...";

       // Set interval to increment the count every 100 milliseconds
       const interval = setInterval(function() {
           if (currentCount < userLimit) {
               currentCount++;
               counterDisplay.textContent = currentCount;
           } else {
               clearInterval(interval);  // Stop the counting once limit is reached
               statusMessage.textContent = "User count reached the limit!";
               startButton.disabled = false;  // Re-enable the button
           }
       }, 10); // Increase every 10 milliseconds
   }

   // Event listener for the start button
   startButton.addEventListener('click', startCounting);
=======
});

function openMagazine(url) {
    window.open(url, '_blank');
}
>>>>>>> b48aaa86868d41d37f02a9ff2f0c98b3ab0ead00
