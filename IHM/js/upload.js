// alert("Hey");
document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const fileInput = document.getElementById('fileUpload');
        const statusDiv = document.getElementById('uploadStatus');
    
        if (fileInput.files.length === 0) {
            statusDiv.innerHTML = '<p class="text-danger">Veuillez sélectionner un fichier.</p>';
            return;
        }
    
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('fileUpload', file);
    
        if (!file.name.endsWith('.txt')) {
            statusDiv.innerHTML = '<p class="text-danger">Seuls les fichiers .txt sont acceptés.</p>';
            return;
        }
    
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusDiv.innerHTML = `<p class="text-success">${data.message}</p>`;
            } else {
                statusDiv.innerHTML = `<p class="text-danger">${data.message}</p>`;
            }
        })
        .catch(error => {
            statusDiv.innerHTML = '<p class="text-danger">Erreur lors du téléversement du fichier.</p>';
        });
    });
    

});
