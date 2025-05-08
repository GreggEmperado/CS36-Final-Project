let checkIn = null;
let checkOut = null;
let calendar;

document.addEventListener('DOMContentLoaded', function () {
  let calendarEl = document.getElementById('calendar');

  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    // validRange: {
    //     start: new Date().toISOString().split('T')[0]  // disallow past dates
    //   },
    select: function (info) {
      if (!checkIn || (checkIn && checkOut)) {

        const selectedDate = new Date(info.startStr);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize to midnight
      
        if (selectedDate < today) {
        //   alert("You cannot select a date before today.");
          return; // Stop selection
        }
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

        calendar.addEvent({
            start: checkIn,
            allDay: true,
            display: 'background',
            backgroundColor: '#ffc107'
          });
        
        document.getElementById("checkin-date").innerText = checkIn;
        document.getElementById("checkout-date").innerText = 'None';
      } else if (!checkOut && info.startStr > checkIn) {
        checkOut = info.startStr;
        calendar.addEvent({
          title: 'Check-Out',
          start: checkOut,
          allDay: true,
          backgroundColor: '#6d72c3',
        });

        calendar.addEvent({
            start: checkOut,
            allDay: true,
            display: 'background',
            backgroundColor: '#ffc107'
          });

        // Highlight intermediate days
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        let current = new Date(start);
        current.setDate(current.getDate()+ 1);

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
    },

    dayCellDidMount: function (info) {
        const cellDate = new Date(info.date);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize time
      
        if (cellDate < today) {
          info.el.style.pointerEvents = 'none';           // Disable clicks
          info.el.style.opacity = '0.4';                  // Gray out
          info.el.style.backgroundColor = '#f8f9fa';       // Light gray (Bootstrap background)
          info.el.style.cursor = 'not-allowed';           // Visual cue for non-clickable
        }else {
          info.el.style.cursor = 'pointer';           // Visual cue for non-clickable
          }
      }
  });

  calendar.render();
});