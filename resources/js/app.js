'use strict';
document.addEventListener('DOMContentLoaded', function () {
    // Your code here
    let btn = document.getElementById('sendExcel');
    btn.addEventListener('click', function () {
        let form = document.getElementById('excelForm');
        form.submit();
    });
    let dni = '';
    let name = '';
    let flag = 0;

    document.querySelectorAll('[id$="+dni"]').forEach(function(el) {
        let icon = document.getElementById(el.id.replace('+dni', '+save'));
        let iconClickListenerAdded = false; // Bandera para comprobar si el listener se ha añadido
        el.addEventListener('keyup', function() {
            dni = el.textContent;
            name = document.getElementById(el.id.replace('+dni', '+name')).textContent;
            icon.style.visibility = 'visible';
            
            if (!iconClickListenerAdded) {
                icon.addEventListener('click', function() {
                    console.log(el.id);
                    $.ajax({
                        url: window.location + 'update',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: el.id,
                            dni: el.textContent,
                            name:name
                        },
                        success: function(response) {
                            if(flag == 0){
                            Toastify({
                                text: response['message'],
                                duration: 2000,
                                newWindow: true,
                                gravity: "top", // `top` or `bottom`
                                position: "left", // `left`, `center` or `right`
                                stopOnFocus: true, // Prevents dismissing of toast on hover
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                                },
                            onClick: function(){} // Callback after click
                            }).showToast();
                            flag = 1;}
                        }
                    });
                });
                iconClickListenerAdded = true; // Marca el listener como añadido
            }
        });
    });
    
    document.querySelectorAll('[id$="+name"]').forEach(function(el) {
        let icon = document.getElementById(el.id.replace('+name', '+save'));
        let iconClickListenerAdded = false;
        el.addEventListener('keyup', function() {
            name = el.textContent;
            dni = document.getElementById(el.id.replace('+name', '+dni')).textContent;
            icon.style.visibility = 'visible';
            if (!iconClickListenerAdded) {
                icon.addEventListener('click', function() {
                    $.ajax({
                        url: window.location + 'update',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: el.id,
                            dni: dni,
                            name: el.textContent
                        },
                        success: function(response) {
                            if(flag == 0){
                            Toastify({
                                text: response['message'],
                                duration: 2000,
                                newWindow: true,
                                gravity: "top", // `top` or `bottom`
                                position: "left", // `left`, `center` or `right`
                                stopOnFocus: true, // Prevents dismissing of toast on hover
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                                },
                            onClick: function(){} // Callback after click
                            }).showToast();
                            flag = 1;}
                        }
                    });
    
                });
                iconClickListenerAdded = true; // Marca el listener como añadido
            }
        });
    });
});