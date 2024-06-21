<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{asset('plugins/datatables/datatables.min.css')}}">
</head>
<body class="fondo bg-cover">
    <main>
        <div class="flex flex-col wrap items-center w-[900px] mx-auto h-full pt-[8rem]">
            <h1 class="text-yellow-300 text-4xl font-black font-sans">GENERADOR DE REPORTES</h1>
            <section class="w-[800px] mx-auto flex flex-row mt-32 items-center justify-between">
                <div class=" flex flex-col items-start wrap">
                <form id="excelForm" action="{{url('/upload')}}" method="POST" enctype="multipart/form-data">
                    <div class="grid w-full max-w-xs items-center gap-1.5">
                        <input
                          class="flex w-full rounded-md border border-blue-300 border-input bg-white text-sm text-gray-900 file:border-0 file:bg-blue-400 file:text-white file:text-sm file:font-medium"
                          type="file"
                          id="file"
                          name="file"
                        />
                      </div>                      
                    @csrf                        
                </form>
                </div>
                <div>
                    <button id="sendExcel" class="button" type="submit">
                        <span class="button_lg">
                            <span class="button_sl"></span>
                            <span class="button_text">Descargar excel</span>
                        </span>
                    </button>
                </div>
            </section>
            <div class="mt-10 pt-6 flex flex-wrap justify-around items-start item backdrop-invert-[35%] backdrop-blur-md w-[1200px] h-[600px] rounded-lg">
                <h1 class="w-1/2 text-center text-white text-3xl font-bold font-mono">PERSONAL ENTRADA 9</h1>
                <h1 class="w-1/2 text-center text-white text-3xl font-bold font-mono">JEFES</h1>
                <div class="w-full text-white flex flex-row flex-wrap justify-around items-start gap-8 -mt-16">
                    <table id="horario9" class="bg-white display rounded-md">
                        <thead class="text-black">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($data['dni_list'] as $item)
                            <tr>
                                <td contenteditable="true">{{$item['dni']}}</td>
                                <td contenteditable="true">{{$item['nombre']}}</td>
                                <td class="flex flex-row justify-around gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="20" height="20">
                                    <g>
                                        <path d="M490.667,234.667H448V192c0-11.782-9.551-21.333-21.333-21.333c-11.782,0-21.333,9.551-21.333,21.333v42.667h-42.667   c-11.782,0-21.333,9.551-21.333,21.333c0,11.782,9.551,21.333,21.333,21.333h42.667V320c0,11.782,9.551,21.333,21.333,21.333   c11.782,0,21.333-9.551,21.333-21.333v-42.667h42.667c11.782,0,21.333-9.551,21.333-21.333   C512,244.218,502.449,234.667,490.667,234.667z"/>
                                        <circle cx="192" cy="128" r="128"/>
                                        <path d="M192,298.667c-105.99,0.118-191.882,86.01-192,192C0,502.449,9.551,512,21.333,512h341.333   c11.782,0,21.333-9.551,21.333-21.333C383.882,384.677,297.99,298.784,192,298.667z"/>
                                    </g>
                                    </svg>
                                    <svg id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table id="horarioJ" class="display bg-white rounded-md">
                        <thead class="text-black">
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($data['dni_list'] as $item)
                            <tr>
                                <td>{{$item['dni']}}</td>
                                <td>{{$item['nombre']}}</td>
                                <td class="flex flex-row justify-around gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="20" height="20">
                                    <g>
                                        <path d="M490.667,234.667H448V192c0-11.782-9.551-21.333-21.333-21.333c-11.782,0-21.333,9.551-21.333,21.333v42.667h-42.667   c-11.782,0-21.333,9.551-21.333,21.333c0,11.782,9.551,21.333,21.333,21.333h42.667V320c0,11.782,9.551,21.333,21.333,21.333   c11.782,0,21.333-9.551,21.333-21.333v-42.667h42.667c11.782,0,21.333-9.551,21.333-21.333   C512,244.218,502.449,234.667,490.667,234.667z"/>
                                        <circle cx="192" cy="128" r="128"/>
                                        <path d="M192,298.667c-105.99,0.118-191.882,86.01-192,192C0,502.449,9.551,512,21.333,512h341.333   c11.782,0,21.333-9.551,21.333-21.333C383.882,384.677,297.99,298.784,192,298.667z"/>
                                    </g>
                                    </svg>
                                    <svg id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m3 6a6 6 0 1 1 6 6 6.006 6.006 0 0 1 -6-6zm6 8a9.01 9.01 0 0 0 -9 9 1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 9.01 9.01 0 0 0 -9-9zm12.414-2 2.293-2.293a1 1 0 0 0 -1.414-1.414l-2.293 2.293-2.293-2.293a1 1 0 0 0 -1.414 1.414l2.293 2.293-2.293 2.293a1 1 0 1 0 1.414 1.414l2.293-2.293 2.293 2.293a1 1 0 0 0 1.414-1.414z"/></svg>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </main>
</body>
</html>
<script src="{{asset('plugins/jquery.min.js')}}"></script>
<script src="{{asset('../resources/js/app.js')}}"></script>
<script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
<script>
    let table = new DataTable('#horario9',{
        info:false,
        language: {
        url: '{{asset('plugins/datatables/language.json')}}',
    },
    });
    let table2 = new DataTable('#horarioJ',{
        info:false,
        language: {
        url: '{{asset('plugins/datatables/language.json')}}',
    },
    });
</script>