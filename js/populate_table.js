const mainAppTableBody = document.querySelector('#mainAppTable tbody');
const remarkAppTableBody = document.querySelector('#remarkAppTable tbody');

function hideTables() {
    remarkAppTable.style.display = 'none';
    mainAppTable.style.display = 'none';
}

function showTable(table) {
    hideTables(); // Hide all tables first
    table.style.display = 'block'; // Show the specified table
}

showTable(mainAppTable);

function populateTable(tableBody, data) {
    tableBody.innerHTML = '';

    data.forEach(item => {
        const row = document.createElement('tr');

        const refNumCell = document.createElement('td');
        refNumCell.textContent = item.RefNum;

        const titleCell = document.createElement('td');
        titleCell.textContent = item.ThesisTitle;

        const authorCell = document.createElement('td');
        authorCell.textContent = item.Author;

        const dateCell = document.createElement('td');
        dateCell.textContent = item.DateOfSubmission;

        const remarksCell = document.createElement('td');
        remarksCell.textContent = item.Remarks;

        row.appendChild(refNumCell);
        row.appendChild(titleCell);
        row.appendChild(authorCell);
        row.appendChild(dateCell);
        row.appendChild(remarksCell);

        tableBody.appendChild(row);
    });
}

remarksDropdown.addEventListener('change', function () {
    const selectedStatus = this.value;

    if (selectedStatus === 'clear') {
        location.reload();
    } else if (selectedStatus === 'Processing' || selectedStatus === 'Incomplete' || selectedStatus === 'Complete' || selectedStatus === 'Registered') {
        // Fetch and populate campus branches applications table
        fetch(`get_remarks.php?status=${selectedStatus}`)
            .then(response => response.json())
            .then(data => {
                populateTable(remarkAppTableBody, data, true);
                showTable(remarkAppTable); 
            })
            .catch(error => console.error('Error fetching main applications:', error));
        }
});