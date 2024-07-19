'use strict';
document.addEventListener('DOMContentLoaded', function () {
    let btn = document.getElementById('sendExcel');
    let form = document.getElementById('excelForm');
    btn.addEventListener('click', function (e) {
        form.submit();
    });
    
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        let formData = new FormData(form);
        
        document.querySelector('.loader').style.display = 'block';
    
        // Primero, hacemos una petición AJAX para iniciar el proceso
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob().then(blob => ({
                blob: blob,
                contentDisposition: response.headers.get('Content-Disposition')
            }));
        })
        .then(({ blob, contentDisposition }) => {
            document.querySelector('.loader').style.display = 'none';
    
            let fileName = 'exported_file.xlsx';
            if (contentDisposition && contentDisposition.indexOf('attachment') !== -1) {
                const fileNameMatch = contentDisposition.match(/filename="?(.+)"?/i);
                if (fileNameMatch) {
                    fileName = fileNameMatch[1];
                }
            }
        
            // Mostrar un mensaje de éxito
            Toastify({
                text: "Archivo descargado con éxito",
                duration: 2000,
                newWindow: true,
                gravity: "top",
                position: "left",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
                onClick: function(){}
            }).showToast();
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.loader').style.display = 'none';
            
            // Mostrar un mensaje de error
            Toastify({
                text: "Error al descargar el archivo",
                duration: 2000,
                newWindow: true,
                gravity: "top",
                position: "left",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #b00020, #c93d3d)",
                },
                onClick: function(){}
            }).showToast();
        });
    });
    
    
    
//reconcer que el archivo se ha subido
    document.getElementById('file').addEventListener('change', function() {
        let fileName = this.files[0].name;
        let fileNameSpan = document.getElementById('file-name');
        fileNameSpan.textContent = 'Archivo seleccionado: ' + fileName;
    });
    
    
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file');
    const fileNameDisplay = document.getElementById('file-name');

    // sirve para prevenir que el navegador abra el archivo
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // 
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    // Handle file select
    fileInput.addEventListener('change', updateFileName);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (fileInput.files.length === 0 || fileInput.files[0].name !== files[0].name) {
            fileInput.files = files; // This will also trigger 'change' event on fileInput
            updateFileName();
        }
    }

    function updateFileName() {
        const fileName = fileInput.files[0]?.name;
        if (fileName) {
            fileNameDisplay.textContent = fileName;
        } else {
            fileNameDisplay.textContent = '';
        }
    }

});