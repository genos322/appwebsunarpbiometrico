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
                                <th>Nombre</th>
                                <th>DNI</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($dni9 as $item)
                            <tr>
                                <td>{{$item->nombre}}</td>
                                <td>{{$item->dni}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table id="horarioJ" class="display bg-white rounded-md">
                        <thead class="text-black">
                            <tr>
                                <th>Nombre</th>
                                <th>DNI</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                            @foreach ($dniJ as $item)
                            <tr>
                                <td>{{$item->nombre}}</td>
                                <td>{{$item->dni}}</td>
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