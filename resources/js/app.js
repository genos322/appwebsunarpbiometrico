'use strict';
document.addEventListener('DOMContentLoaded', function () {
    let btn = document.getElementById('sendExcel');
    let form = document.getElementById('excelForm');
    btn.addEventListener('click', function (e) {
        form.submit();
    });
    
    btn.addEventListener('click', function (e) {
        e.preventDefault(); // Previene el envío normal del formulario
        
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
            return response.blob();
        })
        .then(blob => {
            document.querySelector('.loader').style.display = 'none';
            
            // Crear un objeto URL para el blob
            const url = window.URL.createObjectURL(blob);
            
            // Crear un enlace invisible y hacer clic en él para descargar
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = response.headers.get('Content-Disposition') ? 
                response.headers.get('Content-Disposition').split('filename=')[1] : 
                'exported_file.xlsx';
            document.body.appendChild(a);
            a.click();
            
            // Limpiar
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        
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
        .catch((error) => {
            console.error('Error:', error);
            document.querySelector('.loader').style.display = 'none';
            // Aquí puedes manejar los errores
            Toastify({
                text: "Error al descargar el archivo",
                duration: 2000,
                newWindow: true,
                gravity: "top",
                position: "left",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #ff0000, #ff5733)",
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