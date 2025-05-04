let checkIn = null;
let checkOut = null;
let calendar;

document.addEventListener('DOMContentLoaded', function () {
  let calendarEl = document.getElementById('calendar');

  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    validRange: {
        start: new Date().toISOString().split('T')[0]  // disallow past dates
      },
    select: function (info) {
      if (!checkIn || (checkIn && checkOut)) {
        // Reset selection
        checkIn = info.startStr;
        checkOut = null;
        calendar.removeAllEvents();
        calendar.addEvent({
          title: 'Check-In',
          start: checkIn,
          allDay: true,
          backgroundColor: '#6d72c3'
        });
        document.getElementById("checkin-date").innerText = checkIn;
        document.getElementById("checkout-date").innerText = 'None';
      } else if (!checkOut && info.startStr > checkIn) {
        checkOut = info.startStr;
        calendar.addEvent({
          title: 'Check-Out',
          start: checkOut,
          allDay: true,
          backgroundColor: '#6d72c3'
        });

        // Highlight intermediate days
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        let current = new Date(start);
        current.setDate(current.getDate() + 1);

        while (current < end) {
          calendar.addEvent({
            title: '',
            start: current.toISOString().split('T')[0],
            allDay: true,
            display: 'background',
            backgroundColor: '#ffc107'
          });
          current.setDate(current.getDate() + 1);
        }

        document.getElementById("checkout-date").innerText = checkOut;
      } else {
        alert("Please select a check-out date after check-in.");
      }
    }
  });

  calendar.render();
});