<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE ASISTENCIA</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{asset('plugins/datatables/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/tooltip/hint.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/notify/toastify.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('../build/appm.css')}}"> --}}
    <script src="{{asset('plugins/notify/toastify.min.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/reporte.png') }}">
</head>
<body class="bg-cover bg-fondo">
    <main>
        @if(Session::has('error'))
            @foreach(Session::get('error') as $error)
            <h1 class="bg-white text-9xl">{{$error}}</h1>
            <script>
                Toastify({
                text: `{{$error}}`,  // Usa data.message en lugar de response['message']
                duration: 2000,
                newWindow: true,
                gravity: "top",
                position: "left",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(90deg, rgba(231,93,93,1) 0%, rgba(200,43,71,1) 47%, rgba(252,12,195,1) 100%)",
                },
                onClick: function(){}
            }).showToast();
            </script>
            @endforeach
        @endif
        <div class="flex flex-col wrap items-center w-[900px] mx-auto h-full pt-[8rem]">
            <h1 class="text-yellow-300 text-4xl font-black font-sans">GENERADOR DE REPORTES</h1>
            <section class="w-[800px] mx-auto flex flex-row mt-32 items-center justify-between">
                <div class=" flex flex-col items-start wrap">
                    {{-- <form id="excelForm" action="{{url('/upload')}}" method="POST" enctype="multipart/form-data">
                        <div class="grid w-full max-w-xs items-center gap-1.5">
                            <input
                            class="flex w-full rounded-md border border-blue-300 border-input bg-white text-sm text-gray-900 file:border-0 file:bg-blue-400 file:text-white file:text-sm file:font-medium"
                            type="file"
                            id="file"
                            name="file"
                            />
                        </div>                      
                    </form> --}}
                    <form class="file-upload-form text-white" id="excelForm" action="{{url('/upload')}}" method="POST" enctype="multipart/form-data">
                        <label for="file" class="file-upload-label drop-zone" id="drop-area">
                            <div class="file-upload-design">
                                @csrf                        
                                <svg viewBox="0 0 640 512" height="1em" style="fill:rgb(255 255 255);">
                                    <path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z"></path>
                                </svg>
                                <p>Suelta o selecciona el archivo</p>
                                <span class="browse-button">Buscar archivo</span>
                                <span id="file-name" class="file-name"></span>
                            </div>
                            <input id="file" type="file" name="file" style="display:none;"/>
                        </label>
                    </form>                        
                </div>
                <div class="loader hidden">
                    <div class="center-body">
                        <div class="loader-circle-9 italic font-mono">
                          Cargando
                          <span></span>
                        </div>
                      </div>
                </div>
                <div>
                    <button id="downloadFormat" class="button" type="submit">
                        <span class="button_lg" title="info">
                            <span class="button_sl hint--top hint--success hint--large hint--rounded" 
                                aria-label="
                                ➡Mínimo debe haber 5 registros
                                ➡Son obligatorios los campos nombre, dni y fecha
                                ➡El campo fecha solo se admite en ese formato" 
                                style="margin-right: 20px">💡</span>
                            <span class="button_text">Descargar formato de asistencia</span>
                        </span>
                    </button>
                    <button id="sendExcel" class="button" type="submit" style="margin-top: 20px">
                        <span class="button_lg">
                            <span class="button_sl"></span>
                            <span class="button_text">Descargar excel de asistencia</span>
                        </span>
                    </button>
                </div>
            </section>
            <div class="mt-10 pt-6 flex flex-wrap justify-around items-start item backdrop-invert-[35%] backdrop-blur-md w-[1200px] h-[720px] rounded-lg">
                <h1 class="w-1/2 text-center text-white text-3xl font-bold font-mono">PERSONAL ENTRADA 9 AM</h1>
                <h1 class="w-1/2 text-center text-white text-3xl font-bold font-mono">JEFES</h1>
                <div class="w-full text-white flex flex-row flex-wrap justify-around items-start gap-8">
                    <table id="horario9" class="bg-white display rounded-md min-h-[380px] max-h-[380px]">
                        <thead class="text-black">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($data['dni_list'] as $item)
                                @if($item['rol'] == 'horario9')
                                <tr>
                                    <td id="{{$item['id'].'+dni'}}" contenteditable="true" onkeyup="update('{{$item['id']}}','{{$item['dni']}}','{{$item['nombre']}}','horario9')">{{$item['dni']}}</td>
                                    <td id="{{$item['id'].'+name'}}" contenteditable="true" onkeyup="update('{{$item['id']}}','{{$item['dni']}}','{{$item['nombre']}}','horario9')">{{$item['nombre']}}</td>
                                    <td class="flex flex-row justify-around gap-2">
                                        <span class="hint--bottom" aria-label="ELMINAR" onclick="deleteRow('{{$item['id']}}')">                                        <svg id="Layer_1" class="cursor-pointer" title="elminiar Usuario" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg></span>
                                        <i class="cursor-pointer invisible hint--bottom" aria-label="Guardar" id="{{$item['id'].'+save'}}">✔</i>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-black">
                            <tr>
                                <td colspan="3" class="!text-center"  title="Añadir Usuario"><i id="idAddUser" class=" cursor-pointer hint--top" aria-label="Añadir Usuario"s>➕</i></td>
                            </tr>
                        </tfoot>
                    </table>
                    <table id="horarioJ" class="display bg-white rounded-md min-h-[380px]">
                        <thead class="text-black">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($data['dni_list'] as $item)
                                @if($item['rol'] == 'jefe')
                                <tr>
                                    <td id="{{$item['id'].'+dni'}}" contenteditable="true" onkeyup="update('{{$item['id']}}','{{$item['dni']}}','{{$item['nombre']}}','horario9')">{{$item['dni']}}</td>
                                    <td id="{{$item['id'].'+name'}}" contenteditable="true" onkeyup="update('{{$item['id']}}','{{$item['dni']}}','{{$item['nombre']}}','horario9')">{{$item['nombre']}}</td>
                                    <td class="flex flex-row justify-around gap-2">
                                        <span class="hint--bottom" aria-label="ELMINAR" onclick="deleteRow('{{$item['id']}}')"><svg class="cursor-pointer" aria-label="Thank you!"  id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg></span>
                                        <i class="cursor-pointer invisible hint--bottom" aria-label="Guardar" id="{{$item['id'].'+save'}}">✔</i>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-black">
                            <tr>
                                <td colspan="3" class="!text-center"  title="Añadir Usuario"><i id="idAddUser1" class=" cursor-pointer hint--top" aria-label="Añadir Usuario"s>➕</i></td>
                            </tr>
                        </tfoot>
                    </table>
            </div>
        </div>
    </main>
    <footer>
        <div class="flex flex-row justify-center items-center w-full h-16 bg-black text-white">
            <p class="text-center">© {{date('Y')}} - SUNARP-ZRXIV - Todos los derechos reservados</p>
        </div>
    </footer>
</body>
</html>
<script src="{{asset('plugins/jquery.min.js')}}"></script>
<script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
<script src="{{asset('../resources/js/app.js')}}"></script>
<script>
    let table = new DataTable('#horario9',{
        info:false,
        pageLength: 5,
        language: {
            url: '{{asset('plugins/datatables/language.json')}}', 
        },
    });
    let table2 = new DataTable('#horarioJ',{
        info:false,
        pageLength: 5,
        language: {
            url: '{{asset('plugins/datatables/language.json')}}',
        },
    });

    document.getElementById('idAddUser').addEventListener('click', function() {
    // Añadir una nueva fila a la tabla 'horario9'
    let id = uuidv4();                
    table.row.add([
        `<div id="${id}+dni" contenteditable="true">@</div>`,
        `<div id="${id}+name" contenteditable="true">@</div>`,
        `<div class="flex flex-row justify-around gap-2" >
            <span class="hint--bottom" aria-label="ELMINAR" onclick="deleteRow('${id}')"><svg class="cursor-pointer" aria-label="Thank you!"  id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg></span>
            <i class="cursor-pointer hint--bottom" aria-label="Guardar" onclick="insert('${id}','horario9')">✔</i>
        </div>
        `
    ]).draw(false).node();
    //para que la nueva fila aparezca al principio
    table.order([0, 'desc']).draw();

            });

    document.getElementById('idAddUser1').addEventListener('click', function() {
    // Añadir una nueva fila a la tabla jefes
    let id = uuidv4();                
    table2.row.add([
    `<div id="${id}+dni" contenteditable="true">@</div>`,
    `<div id="${id}+name" contenteditable="true">@</div>`,
    `<div class="flex flex-row justify-around gap-2">
        <span class="hint--bottom" aria-label="ELMINAR" onclick="deleteRow('${id}')"><svg class="cursor-pointer" aria-label="Thank you!" id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg></span>
        <i class="cursor-pointer hint--bottom" aria-label="Guardar" onclick="insert('${id}','jefe')">✔</i>
    </div>`
    ]).draw(false);

    // Ordenar la tabla para que la nueva fila aparezca al principio
    table2.order([0, 'desc']).draw();
    });
            
   
    // Función para generar un UUID v4
    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function insert(id, rol) {
    const dni = document.getElementById(id+'+dni').innerText;
    const name = document.getElementById(id+'+name').innerText;
    
    fetch(window.location+'insert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: id,
            dni: dni,
            name: name,
            rol: rol
        })
    })
    .then(response => response.json())  // Parsea la respuesta como JSON
    .then(data => {
        console.log(data);  // Ahora esto mostrará el objeto parseado
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
    })
    .catch(error => {
        console.log('Error:', error);
        Toastify({
            text: "Error en la operación",
            duration: 2000,
            newWindow: true,
            gravity: "top",
            position: "left",
            stopOnFocus: true,
            style: {
                background: "linear-gradient(to right, #ff6b6b, #ff9ff3)",
            },
            onClick: function(){}
        }).showToast();
    });
}

let debounceTimer;
const listenerMap = new Map();

function update(id, dni, nombre) {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        const oldDni = dni;
        const oldName = nombre;

        const icon = document.getElementById(id + '+save');
        const newName = document.getElementById(id + '+name').textContent;
        const newDni = document.getElementById(id + '+dni').textContent;

        icon.style.visibility = 'visible';

        // Remover el listener anterior si existe
        if (listenerMap.has(id)) {
            icon.removeEventListener('click', listenerMap.get(id));
        }

        // Crear un nuevo listener
        const clickHandler = function() {
            $.ajax({
                url: window.location + 'update',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    dni: newDni,
                    name: newName,
                },
                success: function(response) {
                    debugger;
                    Toastify({
                        text: response['message'],
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

                    // Remover el listener después de una actualización exitosa
                    icon.removeEventListener('click', clickHandler);
                    listenerMap.delete(id);
                    icon.style.visibility = 'hidden';
                }
            });
        };

        // Añadir el nuevo listener y guardarlo en el mapa
        icon.addEventListener('click', clickHandler);
        listenerMap.set(id, clickHandler);

        console.log(newName);
    }, 100); // Retraso de 100ms
}

function deleteRow(id) {
    let flag = false;
    let div = document.querySelector(`div[id="${id}+dni"]`);
    if (div) {
        let tr = div.closest('tr');
        tr.remove();
    } else {
        let td = document.getElementById(id + '+dni');
        if (td) {
            // Encuentra el elemento tr padre
            let tr = td.closest('tr');
            
            if (tr) {
                // Opcional: Añadir una confirmación antes de eliminar
                if (confirm('¿Estás seguro de que quieres eliminar a este usuario?')) {
                    // Elimina la fila
                    flag = true;
                    tr.remove();
                    
                    // Aquí puedes añadir código adicional si necesitas hacer algo más,
                    // como una llamada AJAX para eliminar el registro en el servidor
                }
            } else {
                console.error('No se pudo encontrar la fila padre');
            }
        } else {
            console.error('No se pudo encontrar el elemento con ID: ' + id + '+dni');
        }
    }
    if(flag){
        $.ajax({
        url: window.location + 'delete',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: id
        },
        success: function(response) {
            Toastify({
                text: response['message'],
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
        }
    });
    }

}
</script>