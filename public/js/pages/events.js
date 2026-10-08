(() => {
    const calendarElement = document.getElementById('fullCalendarGrid');
    const dataElement = document.getElementById('calendar-events-data');

    if (!calendarElement || !dataElement || !window.FullCalendar?.Calendar) {
        return;
    }

    let events = [];
    try {
        events = JSON.parse(dataElement.textContent);
        if (!Array.isArray(events)) {
            events = [];
        }
    } catch {
        calendarElement.textContent = 'The event calendar is temporarily unavailable.';
        return;
    }

    const calendar = new window.FullCalendar.Calendar(calendarElement, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek',
        },
        events,
    });

    calendar.render();
})();
