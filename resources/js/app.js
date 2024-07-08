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
            
            // const url = window.URL.createObjectURL(blob);
            // const link = document.createElement('a');
            // link.href = url;
            // link.download = fileName;
            
            // document.body.appendChild(link);
            // link.click();
            // document.body.removeChild(link);
            // window.URL.revokeObjectURL(url);
        
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
    
});