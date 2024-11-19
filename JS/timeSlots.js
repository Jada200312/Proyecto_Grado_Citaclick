document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedSalon = urlParams.get('salon');
    document.getElementById('salon-name').textContent = selectedSalon;

    const timeSlotsContainer = document.querySelector('.time-slots');
    document.querySelectorAll('.date-btn').forEach(dateBtn => {
        dateBtn.addEventListener('click', () => {
            const selectedDate = dateBtn.dataset.date;
            generateTimeSlots(selectedSalon, selectedDate);
        });
    });

    function generateTimeSlots(salon, date) {
        timeSlotsContainer.innerHTML = ''; // Clear previous slots
        const startTime = 10; // Starting at 10:00 AM
        const endTime = 20; // Ending at 8:00 PM
        const interval = 15; // Interval of 15 minutes

        for (let hour = startTime; hour < endTime; hour++) {
            for (let min = 0; min < 60; min += interval) {
                const time = `${String(hour).padStart(2, '0')}:${String(min).padStart(2, '0')}`;
                const timeSlot = document.createElement('button');
                timeSlot.textContent = time;
                timeSlot.classList.add('time-slot');
                timeSlot.addEventListener('click', () => selectTime(salon, date, time));
                timeSlotsContainer.appendChild(timeSlot);
            }
        }
    }

    function selectTime(salon, date, time) {
        window.location.href = `agendar3.html?salon=${salon}&date=${date}&time=${time}`;
    }
});