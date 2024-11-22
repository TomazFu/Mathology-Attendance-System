document.addEventListener('DOMContentLoaded', function() {
    // Initialize weekly view
    initializeWeeklyView();
    
    // Initialize list view below the weekly view
    initializeListView();
});

function initializeWeeklyView() {
    const timeSlots = document.querySelector('.time-slots');
    const weeklyGrid = document.querySelector('.weekly-grid');
    
    // Clear existing content
    timeSlots.innerHTML = '<div class="time-header">Time</div>';
    weeklyGrid.innerHTML = '';
    
    // Add time slots (1-hour intervals)
    const times = [];
    for (let hour = 8; hour <= 18; hour++) {
        times.push(`${hour.toString().padStart(2, '0')}:00`);
    }
    
    times.forEach(time => {
        const slot = document.createElement('div');
        slot.className = 'time-slot';
        slot.textContent = time;
        timeSlots.appendChild(slot);
    });

    // Create grid container
    const gridContainer = document.createElement('div');
    gridContainer.className = 'grid-container';
    weeklyGrid.appendChild(gridContainer);

    // Create day columns
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    days.forEach(day => {
        const dayColumn = document.createElement('div');
        dayColumn.className = 'day-column';
        
        const header = document.createElement('div');
        header.className = 'day-header';
        header.textContent = day;
        dayColumn.appendChild(header);
        
        times.forEach(() => {
            const slot = document.createElement('div');
            slot.className = 'time-slot';
            dayColumn.appendChild(slot);
        });
        
        gridContainer.appendChild(dayColumn);
    });

    // Fetch and populate schedule
    fetchScheduleData().then(data => {
        populateWeeklySchedule(data);
    });
}

function initializeListView() {
    const timetableContainer = document.querySelector('.timetable-container');
    
    // Create list view section
    const listViewSection = document.createElement('div');
    listViewSection.className = 'list-view-section';
    listViewSection.innerHTML = `
        <div class="section-header">
            <h2><i class="fas fa-list"></i> Class List</h2>
        </div>
    `;
    
    // Create filters section
    const filtersDiv = document.createElement('div');
    filtersDiv.className = 'list-filters';
    
    // Day filter
    const dayFilter = document.createElement('select');
    dayFilter.id = 'day-filter';
    dayFilter.innerHTML = `
        <option value="all">All Days</option>
        <option value="monday">Monday</option>
        <option value="tuesday">Tuesday</option>
        <option value="wednesday">Wednesday</option>
        <option value="thursday">Thursday</option>
        <option value="friday">Friday</option>
    `;
    
    // Subject filter
    const subjectFilter = document.createElement('select');
    subjectFilter.id = 'subject-filter';
    subjectFilter.innerHTML = '<option value="all">All Subjects</option>';
    
    // Add filters to container
    filtersDiv.appendChild(dayFilter);
    filtersDiv.appendChild(subjectFilter);
    
    // Create schedule list container
    const scheduleList = document.createElement('div');
    scheduleList.className = 'schedule-list';
    
    // Add everything to list view section
    listViewSection.appendChild(filtersDiv);
    listViewSection.appendChild(scheduleList);
    
    // Add list view section to timetable container
    timetableContainer.appendChild(listViewSection);
    
    // Add event listeners
    dayFilter.addEventListener('change', updateListView);
    subjectFilter.addEventListener('change', updateListView);
    
    // Populate subject filter and initial list
    fetchScheduleData().then(data => {
        const subjects = [...new Set(data.map(item => item.subject))];
        subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectFilter.appendChild(option);
        });
        
        populateListView(data);
    });
}

function generateTimeSlots() {
    const slots = [];
    // Generate slots from 8:00 to 18:00 with 30-minute intervals
    for (let hour = 8; hour <= 18; hour++) {
        slots.push(`${hour.toString().padStart(2, '0')}:00`);
        if (hour < 18) {
            slots.push(`${hour.toString().padStart(2, '0')}:30`);
        }
    }
    return slots;
}

async function fetchScheduleData() {
    // This would be replaced with actual API call
    return [
        {
            subject: 'Mathematics',
            teacher: 'Mr. Smith',
            room: 'Room 101',
            day: 'Monday',
            startTime: '09:00',
            endTime: '10:30'
        },
        // Add more schedule items
    ];
}

function populateWeeklySchedule(data) {
    const gridContainer = document.querySelector('.grid-container');
    const times = generateTimeSlots();
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    
    data.forEach(item => {
        const dayIndex = days.indexOf(item.day.toLowerCase());
        const startTimeIndex = times.indexOf(item.startTime);
        
        if (dayIndex !== -1 && startTimeIndex !== -1) {
            const dayColumn = gridContainer.children[dayIndex];
            const slot = dayColumn.children[startTimeIndex + 1]; // +1 for header
            
            const classSlot = createClassSlot(item);
            slot.appendChild(classSlot);
        }
    });
}

function populateListView(data) {
    const scheduleList = document.querySelector('.schedule-list');
    scheduleList.innerHTML = '';

    if (data.length === 0) {
        const emptyMessage = document.createElement('div');
        emptyMessage.className = 'empty-message';
        emptyMessage.textContent = 'No classes found for the selected filters';
        scheduleList.appendChild(emptyMessage);
        return;
    }

    // Sort data by day and time
    const sortedData = data.sort((a, b) => {
        const dayDiff = days.indexOf(a.day.toLowerCase()) - days.indexOf(b.day.toLowerCase());
        if (dayDiff !== 0) {
            return dayDiff;
        }
        return timeToMinutes(a.startTime) - timeToMinutes(b.startTime);
    });

    sortedData.forEach(item => {
        const scheduleItem = createScheduleItem(item);
        scheduleList.appendChild(scheduleItem);
    });
}

function createClassSlot(item) {
    const slot = document.createElement('div');
    slot.className = 'class-slot';
    
    // Calculate position and height
    const duration = calculateDuration(item.startTime, item.endTime);
    const startMinutes = timeToMinutes(item.startTime) - timeToMinutes('08:00');
    const height = duration * 50; // 50px per hour
    const top = (startMinutes / 60) * 50; // 50px per hour
    
    slot.style.height = `${height}px`;
    slot.style.top = `${top}px`;
    
    // Add tooltip for full details
    slot.title = `${item.subject}\n${item.teacher}\n${item.room}\n${formatTime(item.startTime)} - ${formatTime(item.endTime)}`;
    
    slot.innerHTML = `
        <div class="class-item">
            <div class="subject-name">${item.subject}</div>
            <div class="class-details">
                <div class="teacher-name">${item.teacher}</div>
                <div class="room-info">${item.room}</div>
                <div class="time-info">${formatTime(item.startTime)} - ${formatTime(item.endTime)}</div>
            </div>
        </div>
    `;
    
    slot.addEventListener('click', () => showClassDetails(item));
    return slot;
}

function createScheduleItem(item) {
    const scheduleItem = document.createElement('div');
    scheduleItem.className = 'schedule-item';
    scheduleItem.innerHTML = `
        <div class="schedule-time">${item.startTime} - ${item.endTime}</div>
        <div class="schedule-details">
            <h4>${item.subject}</h4>
            <p>${item.teacher} | ${item.room}</p>
        </div>
    `;
    return scheduleItem;
}

function updateListView() {
    const dayFilter = document.getElementById('day-filter').value;
    const subjectFilter = document.getElementById('subject-filter').value;

    fetchScheduleData().then(data => {
        // Filter data based on selected filters
        const filteredData = data.filter(item => {
            const dayMatch = dayFilter === 'all' || item.day.toLowerCase() === dayFilter;
            const subjectMatch = subjectFilter === 'all' || item.subject === subjectFilter;
            return dayMatch && subjectMatch;
        });

        populateListView(filteredData);
    });
}

// Helper functions
function calculateDuration(start, end) {
    const [startHour, startMin] = start.split(':').map(Number);
    const [endHour, endMin] = end.split(':').map(Number);
    return (endHour + endMin/60) - (startHour + startMin/60);
}

function formatTime(time) {
    const [hour, minute] = time.split(':');
    return `${hour}:${minute.padStart(2, '0')}`;
}

function showClassDetails(item) {
    // Implement a modal or tooltip to show more class details
    console.log('Show details for:', item);
}

// Helper function to convert time to minutes
function timeToMinutes(time) {
    const [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
}