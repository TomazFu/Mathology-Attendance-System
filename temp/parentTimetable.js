document.addEventListener('DOMContentLoaded', function() {
    // Fetch timetable data
    fetch('fetchTimetableData.php')
        .then(response => response.json())
        .then(data => {
            const timetableBody = document.querySelector('#timetable tbody');
            timetableBody.innerHTML = ''; // Clear any existing rows

            // Populate timetable rows
            data.timetable.forEach(entry => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${entry.subject_id}</td>
                    <td>${entry.title}</td>
                    <td>${entry.room}</td>
                    <td>${entry.instructor}</td>
                    <td>${entry.time}</td>
                `;

                timetableBody.appendChild(row);
            });

            const classList = document.getElementById('enrolled-classes-list');
            classList.innerHTML = '';  // Clear previous content
            data.enrolledClasses.forEach(cls => {
                const li = document.createElement('li');
                li.textContent = cls;
                classList.appendChild(li);
            
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});
