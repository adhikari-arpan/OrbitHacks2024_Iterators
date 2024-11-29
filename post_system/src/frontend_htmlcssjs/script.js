// Function to show the booking form
function showBookingForm(doctorId) {
    document.getElementById('bookingForm').style.display = 'block';
    document.querySelector('[name="doctor_id"]').value = doctorId;
}
