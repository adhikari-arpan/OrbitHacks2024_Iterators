document.addEventListener('DOMContentLoaded', () => {
    const services = document.querySelectorAll('.service');
    services.forEach(service => {
      service.classList.add('animate');
    });
  });