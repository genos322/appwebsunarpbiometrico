'use strict';
document.addEventListener('DOMContentLoaded', function () {
    let btn = document.getElementById('sendExcel');
    let btnDownload = document.getElementById('downloadFormat');
    let form = document.getElementById('excelForm');
    btnDownload.addEventListener('click', function (e) {
        //descargar el excel que está en assets
        fetch(`${window.location}assets/FORMATO-ASISTENCIA.xlsx`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob();
        })
        .then(blob => {
            // Crear un enlace temporal para descargar el archivo
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'formato asistencia.xls'; // Nombre del archivo a descargar
            document.body.appendChild(a);
            a.click();
            a.remove(); // Eliminar el enlace temporal
            window.URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Hubo un problema con la descarga:', error);
        });
    });       

    
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        document.querySelector('.loader').style.display = 'block';

        const inputExtra = document.createElement('input');
        inputExtra.type = 'hidden';
        inputExtra.name = 'txttolerancia';
        inputExtra.value = confirmTolerancia();
        form.appendChild(inputExtra);
        form.submit();

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
    let updateButton = document.getElementById("updateDetails");
    let cancelButton = document.getElementById("cancelT");
    let favDialog = document.getElementById("favDialog");

    document.getElementById('idclose').addEventListener('click', ()=>{
            favDialog.close();
    });

    // Update button opens a modal dialog
    updateButton.addEventListener("click", function () {
      favDialog.showModal();
    });

    // Form cancel button closes the dialog box
    cancelButton.addEventListener("click", function () {
        let tolerancia = document.getElementById("tolerancia");
        let txtTolerancia = document.getElementById('txtTolerancia');
        tolerancia.value=0;
        txtTolerancia.textContent = '';
        txtTolerancia.value='';
        favDialog.close();
    });

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