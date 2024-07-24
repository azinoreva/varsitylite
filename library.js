document.addEventListener('DOMContentLoaded', function() {
    fetchAllPDFs();
});

function fetchAllPDFs() {
    $.ajax({
        url: 'download.php?action=fetchAll',
        method: 'GET',
        success: function(data) {
            displayPDFs(data);
        },
        error: function(error) {
            console.error('Error fetching PDFs:', error);
        }
    });
}

function displayPDFs(pdfs) {
    const pdfList = document.getElementById('pdfList');
    pdfList.innerHTML = '';  // Clear previous results

    pdfs.forEach(pdf => {
        const pdfItem = document.createElement('div');
        pdfItem.className = 'col-md-4';
        pdfItem.innerHTML = `
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">${pdf.title}</h5>
                    <a href="download.php?download=${pdf.id}" class="btn btn-primary" target="_blank">Download PDF</a>
                </div>
            </div>
        `;
        pdfList.appendChild(pdfItem);
    });
}
