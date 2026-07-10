const students = [
{ name: "Rahul", branch: "CSE", cgpa: 8.4 },
{ name: "Ankit", branch: "ECE", cgpa: 7.9 },
{ name: "Priya", branch: "IT", cgpa: 9.1 }
];
let html = "";
for (let i = 0; i < students.length; i++) {
html += `
<div class="student-card">
        <div class="photo-container">
            <img src="img/666201.png" alt="Student Photo" class="student-photo">
        </div>
        <div class="student-name">${students[i].name}</div>
        <div class="student-title">${students[i].branch}</div>
        <div class="detail-row">CGPA: ${students[i].cgpa}</div>
    </div>`;
}
document.getElementById("container").innerHTML = html;