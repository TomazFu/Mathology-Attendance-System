let currentStudents = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize student selector
    initializeStudentSelector();
    
    // Initialize weekly view
    initializeWeeklyView();
    
    // Initialize list view below the weekly view
    initializeListView();
    
    // Add click handler for enlarging timetable
    const weeklyTimetable = document.querySelector('.weekly-timetable');
    weeklyTimetable.addEventListener('click', function(e) {
        // Don't enlarge if clicking a class slot
        if (!e.target.closest('.class-slot')) {
            this.classList.toggle('enlarged');
        }
    });
    
    // Add escape key handler to exit enlarged view
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            weeklyTimetable.classList.remove('enlarged');
        }
    });
});

function initializeStudentSelector() {
    const studentSelect = document.getElementById('student-select');
    if (!studentSelect) return;

    studentSelect.addEventListener('change', function() {
        fetchScheduleData().then(data => {
            populateWeeklySchedule(data);
            populateListView(data);
        });
    });
}

async function fetchScheduleData() {
    try {
        console.log('Fetching schedule data...');
        showLoading();
        
        const studentId = document.getElementById('student-select')?.value;
        const url = new URL('../parent/includes/fetch-timetable-data-process.php', window.location.href);
        if (studentId) {
            url.searchParams.append('student_id', studentId);
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
        console.log('Received data:', data);

        if (!data.success) {
            throw new Error(data.error || 'Failed to fetch schedule data');
        }

        // Update student selector if needed
        if (data.students && !currentStudents.length) {
            currentStudents = data.students;
            updateStudentSelector(data.students, data.selected_student_id);
        }

        if (!data.timetable || data.timetable.length === 0) {
            console.log('No timetable data found');
            return [];
        }

        // Transform the data
        const transformedData = data.timetable.map(item => ({
            id: item.id,
            subject: item.title,
            teacher: item.instructor,
            room: item.room,
            day: getDayFromTime(item.time),
            startTime: getStartTime(item.time),
            endTime: getEndTime(item.time),
            subject_id: item.subject_id,
            class_name: item.class_name
        }));

        console.log('Transformed data:', transformedData);
        return transformedData;

    } catch (error) {
        console.error('Error fetching schedule data:', error);
        showError('Unable to load timetable data: ' + error.message);
        return [];
    } finally {
        hideLoading();
    }
}

function updateStudentSelector(students, selectedId) {
    const selector = document.getElementById('student-select');
    if (!selector) return;

    selector.innerHTML = students.map(student => 
        `<option value="${student.student_id}" ${student.student_id == selectedId ? 'selected' : ''}>
            ${student.name}
        </option>`
    ).join('');
}

function initializeWeeklyView() {
    const weeklyGrid = document.querySelector('.weekly-grid');
    weeklyGrid.innerHTML = '';
    
    // Create a wrapper for scrolling
    const scrollWrapper = document.createElement('div');
    scrollWrapper.className = 'timetable-scroll-wrapper';
    
    // Create grid container
    const gridContainer = document.createElement('div');
    gridContainer.className = 'grid-container';
    
    // Create the header row with times
    const headerRow = document.createElement('div');
    headerRow.className = 'header-row';
    
    // Add empty cell for top-left corner
    const cornerCell = document.createElement('div');
    cornerCell.className = 'corner-cell';
    cornerCell.textContent = 'Time';
    headerRow.appendChild(cornerCell);
    
    // Add time headers
    for (let hour = 8; hour <= 22; hour++) {
        const timeHeader = document.createElement('div');
        timeHeader.className = 'time-header';
        timeHeader.textContent = `${hour.toString().padStart(2, '0')}:00`;
        headerRow.appendChild(timeHeader);
    }
    
    gridContainer.appendChild(headerRow);
    
    // Add day rows
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    days.forEach(day => {
        const dayRow = document.createElement('div');
        dayRow.className = 'day-row';
        
        // Add day label
        const dayLabel = document.createElement('div');
        dayLabel.className = 'day-label';
        dayLabel.textContent = day;
        dayRow.appendChild(dayLabel);
        
        // Add time slots for this day
        for (let hour = 8; hour <= 22; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.className = 'time-slot';
            dayRow.appendChild(timeSlot);
        }
        
        gridContainer.appendChild(dayRow);
    });
    
    scrollWrapper.appendChild(gridContainer);
    weeklyGrid.appendChild(scrollWrapper);

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

function getDayFromTime(timeString) {
    if (!timeString) return 'monday';
    return timeString.split(' ')[0].toLowerCase().trim();
}

function getStartTime(timeString) {
    if (!timeString) return '09:00';
    const timePart = timeString.split(' ')[1];
    if (!timePart) return '09:00';
    return timePart.split('-')[0].trim();
}

function getEndTime(timeString) {
    if (!timeString) return '10:00';
    const timePart = timeString.split(' ')[1];
    if (!timePart) return '10:00';
    return timePart.split('-')[1].trim();
}

function showError(message) {
    const container = document.querySelector('.timetable-container');
    const existingError = container.querySelector('.error-message');
    
    if (existingError) {
        existingError.remove();
    }

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    container.insertBefore(errorDiv, container.firstChild);
}

function populateWeeklySchedule(data) {
    console.log('Populating schedule with data:', data);
    
    // Clear existing schedule items
    document.querySelectorAll('.class-slot').forEach(slot => slot.remove());

    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    const dayRows = document.querySelectorAll('.day-row');
    
    data.forEach(item => {
        const dayIndex = days.indexOf(item.day.toLowerCase());
        if (dayIndex === -1) {
            console.warn('Invalid day for item:', item);
            return;
        }

        const dayRow = dayRows[dayIndex];
        if (!dayRow) {
            console.warn('Day row not found for:', item.day);
            return;
        }

        const classSlot = createClassSlot(item);
        if (classSlot) {
            dayRow.appendChild(classSlot);
        }
    });
}

function populateListView(data) {
    const scheduleList = document.querySelector('.schedule-list');
    if (!scheduleList) return;

    scheduleList.innerHTML = ''; // Clear existing items
    
    if (!data || data.length === 0) {
        scheduleList.innerHTML = '<div class="no-data">No classes scheduled</div>';
        return;
    }

    // Define days array
    const daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    
    // Sort the data
    const sortedData = [...data].sort((a, b) => {
        const dayDiff = daysOrder.indexOf(a.day.toLowerCase()) - daysOrder.indexOf(b.day.toLowerCase());
        if (dayDiff !== 0) return dayDiff;
        return timeToMinutes(a.startTime) - timeToMinutes(b.startTime);
    });

    sortedData.forEach(item => {
        const scheduleItem = createScheduleItem(item);
        scheduleList.appendChild(scheduleItem);
    });
}

function createClassSlot(item) {
    if (!item) return null;

    const slot = document.createElement('div');
    slot.className = 'class-slot';
    
    // Get CSS variables
    const style = getComputedStyle(document.querySelector('.weekly-timetable'));
    const cellWidth = parseInt(style.getPropertyValue('--cell-width'));
    const firstColumnWidth = parseInt(style.getPropertyValue('--first-column-width'));
    const minutesPerCell = parseInt(style.getPropertyValue('--minutes-per-cell'));
    
    // Calculate start and end times in minutes since 8:00
    const startMinutes = timeToMinutes(item.startTime) - (8 * 60); // Offset from 8:00
    const endMinutes = timeToMinutes(item.endTime) - (8 * 60);
    
    // Calculate position and width
    const left = (startMinutes / minutesPerCell * cellWidth) + firstColumnWidth;
    const width = ((endMinutes - startMinutes) / minutesPerCell) * cellWidth;
    
    // Apply positioning
    slot.style.left = `${left}px`;
    slot.style.width = `${width}px`;
    
    // Add content
    slot.innerHTML = `
        <div class="class-item">
            <div class="subject-name">${item.subject || 'Unknown Subject'}</div>
            <div class="class-details">
                <div class="room-info">${item.room || 'TBA'}</div>
                <div class="time-info">${item.startTime} - ${item.endTime}</div>
            </div>
        </div>
    `;

    // Add tooltip for overflow content
    slot.title = `${item.subject}\n${item.room}\n${item.startTime} - ${item.endTime}`;
    
    return slot;
}

function createScheduleItem(item) {
    const scheduleItem = document.createElement('div');
    scheduleItem.className = 'schedule-item';
    
    // Calculate end time (1.5 hours after start time)
    const endTime = calculateEndTime(item.startTime, 1.5);
    
    scheduleItem.innerHTML = `
        <div class="schedule-time">${item.startTime || '09:00'} - ${endTime}</div>
        <div class="schedule-details">
            <h4>${item.subject || 'Unknown Subject'}</h4>
            <p>${item.teacher || 'TBA'} | ${item.room || 'TBA'}</p>
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
    if (!start || !end) return 1; // default duration

    try {
        const startMinutes = timeToMinutes(start);
        const endMinutes = timeToMinutes(end);
        return (endMinutes - startMinutes) / 60; // Convert back to hours
    } catch (error) {
        console.error('Error calculating duration:', error);
        return 1; // default duration
    }
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
    if (!time) return 0;
    try {
        const [hours, minutes] = time.split(':').map(Number);
        return (hours * 60) + (minutes || 0);
    } catch (error) {
        console.error('Error converting time to minutes:', error, time);
        return 0;
    }
}

// Add this helper function to show loading state
function showLoading() {
    const container = document.querySelector('.timetable-container');
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'loading-message';
    loadingDiv.textContent = 'Loading timetable...';
    container.insertBefore(loadingDiv, container.firstChild);
}

// Add this helper function to remove loading state
function hideLoading() {
    const loadingDiv = document.querySelector('.loading-message');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Debug function to log data structure
function logDataStructure(data) {
    console.log('Data structure:', {
        totalItems: data.length,
        sampleItem: data[0],
        timeFormat: data[0]?.time,
        parsedTime: {
            day: getDayFromTime(data[0]?.time),
            start: getStartTime(data[0]?.time),
            end: getEndTime(data[0]?.time)
        }
    });
}

// Add this new helper function to calculate end time
function calculateEndTime(startTime, duration) {
    const [hours, minutes] = startTime.split(':').map(Number);
    const totalMinutes = hours * 60 + minutes + (duration * 60);
    const endHours = Math.floor(totalMinutes / 60);
    const endMinutes = totalMinutes % 60;
    return `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;
}