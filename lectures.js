document.addEventListener('DOMContentLoaded', function() {
    loadLectures();

    document.getElementById('createLectureForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createLecture();
    });

    // Implement update, delete functionality as needed
    // Reload lectures periodically
    setInterval(loadLectures, 10000);  // Refresh lectures every 10 seconds
});

function loadLectures() {
    $.ajax({
        url: 'lectures.php',
        method: 'GET',
        data: { action: 'read' },
        success: function(data) {
            displayLectures(data);
        },
        error: function(error) {
            console.error('Error fetching lectures:', error);
        }
    });
}

function displayLectures(lectures) {
    const lecturesList = document.getElementById('lecturesList');
    lecturesList.innerHTML = '';  // Clear previous lectures

    lectures.forEach(lecture => {
        const lectureItem = document.createElement('div');
        lectureItem.className = 'lecture-item';
        lectureItem.innerHTML = `
            <h3>${lecture.course_code}</h3>
            <p><strong>Lecturer:</strong> ${lecture.lecturer_name}</p>
            <p><strong>Start Date:</strong> ${lecture.start_date}</p>
            <p><strong>Class Dates:</strong> ${lecture.class_dates}</p>
            <p><a href="${lecture.jitsi_link}" target="_blank">Join Class</a></p>
            <button onclick="deleteLecture(${lecture.id})">Delete</button>
        `;
        lecturesList.appendChild(lectureItem);
    });
}

function createLecture() {
    const formData = new FormData(document.getElementById('createLectureForm'));

    $.ajax({
        url: 'lectures.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            alert(data);
            loadLectures();  // Refresh lectures
        },
        error: function(error) {
            console.error('Error creating lecture:', error);
        }
    });
}

function deleteLecture(id) {
    if (confirm('Are you sure you want to delete this lecture?')) {
        $.ajax({
            url: 'lectures.php',
            method: 'POST',
            data: { action: 'delete', id: id },
            success: function(data) {
                alert(data);
                loadLectures();  // Refresh lectures
            },
            error: function(error) {
                console.error('Error deleting lecture:', error);
            }
        });
    }
}