document.addEventListener("DOMContentLoaded", function () {
    // Calendar Placeholder
    const calendar = document.getElementById("calendar");
    let currentDate = new Date();
    
    // Initialize calendar
    updateCalendar(currentDate);

    // Add event listeners for navigation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('prev-month')) {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar(currentDate);
        } else if (e.target.classList.contains('next-month')) {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendar(currentDate);
        }
    });

    // Animate the circular progress bar
    const progressCircle = document.querySelector('.circle-progress circle:nth-child(2)');
    const percentage = 75;
    const circumference = 251.2;
    const offset = circumference - (percentage / 100) * circumference;
    
    progressCircle.style.transition = 'stroke-dashoffset 1s ease-in-out';
    progressCircle.style.strokeDashoffset = offset;
});

function updateCalendar(date) {
    const calendar = document.getElementById("calendar");
    calendar.innerHTML = generateCalendar(date);
}

// Function to generate a beautiful calendar UI
function generateCalendar(date) {
    const monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"];
    
    let currentMonth = date.getMonth();
    let currentYear = date.getFullYear();
    
    return `
        <div class="calendar-nav">
            <button class="prev-month">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h6>${monthNames[currentMonth]} ${currentYear}</h6>
            <button class="next-month">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <table>
            <thead>
                <tr>
                    ${["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
                        .map(d => `<th>${d}</th>`)
                        .join('')}
                </tr>
            </thead>
            <tbody>${generateCalendarDays(currentYear, currentMonth)}</tbody>
        </table>
    `;
}

function generateCalendarDays(year, month) {
    let firstDay = new Date(year, month, 1).getDay();
    let daysInMonth = new Date(year, month + 1, 0).getDate();
    let today = new Date();
    let isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
    let currentDay = today.getDate();
    let calendarHtml = "<tr>";
    let dayCounter = 1;

    // Add empty cells for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        calendarHtml += '<td class="empty"></td>';
    }

    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        if ((firstDay + day - 1) % 7 === 0 && day !== 1) {
            calendarHtml += "</tr><tr>";
        }

        // Check if it's today
        const isToday = isCurrentMonth && day === currentDay;
        const dayClass = isToday ? 'today' : '';

        // Add special styling for weekends
        const isWeekend = (firstDay + day - 1) % 7 === 0 || (firstDay + day - 1) % 7 === 6;
        const weekendClass = isWeekend ? 'weekend' : '';

        calendarHtml += `<td class="${dayClass} ${weekendClass}">${day}</td>`;
    }

    // Add empty cells for remaining days in the last week
    const remainingDays = 7 - ((firstDay + daysInMonth) % 7);
    if (remainingDays < 7) {
        for (let i = 0; i < remainingDays; i++) {
            calendarHtml += '<td class="empty"></td>';
        }
    }

    calendarHtml += "</tr>";
    return calendarHtml;
}
