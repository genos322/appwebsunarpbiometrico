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
            if (response.headers.get('Content-Type').includes('application/json')) {
                return response.json();
            } else {
                return response.blob();
            }
        })
        .then(data => {
            document.querySelector('.loader').style.display = 'none';
            if (data instanceof Blob) {
                // Si es un archivo, lo descargamos
                const url = window.URL.createObjectURL(data);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // Usamos un nombre de archivo predeterminado, ya que no podemos obtener el nombre real
                a.download = 'exported_file.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            } else {
                // Si es JSON, manejamos la respuesta (por ejemplo, mostrar un mensaje)
                Toastify({
                    text: data.message,  // Usa data.message en lugar de response['message']
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
                console.log('Respuesta del servidor:', data);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            document.querySelector('.loader').style.display = 'none';
            // Aquí puedes manejar los errores
        });
    });
});