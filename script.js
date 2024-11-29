document.addEventListener('DOMContentLoaded', () => {
    const services = document.querySelectorAll('.service');
    services.forEach(service => {
        service.classList.add('animate');
    });
});

function openMagazine(url) {
    window.open(url, '_blank');
}