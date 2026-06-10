document.addEventListener('DOMContentLoaded', () => {
    const calendarDate = document.querySelector('calendar-date.cally');
    if (!calendarDate) return;

    calendarDate.addEventListener('change', function () {
        const [year, month, day] = this.value.split('-');
        document.getElementById('date_publication').value = this.value;
        document.getElementById('cally-btn').innerText = `${day}/${month}/${year}`;
        document.getElementById('cally-popover-date').hidePopover();
    });
});
