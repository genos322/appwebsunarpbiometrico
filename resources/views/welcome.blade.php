<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
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
            <div class="mt-36 flex flex-row gap-8">
                <textarea readonly name="" id="" cols="30" rows="5" class="rounded-lg p-12 resize-none min-w[40px] width[250px]">
                    @foreach ($dniHorario9 as $item)
                    {{$item}}
                    @endforeach
                </textarea>
                <textarea readonly name="" id="" cols="30" rows="5" class="rounded-lg p-12 resize min-w[40px] width[250px]">
                    @foreach ($dniHorario9 as $item)
                    {{$item}}
                    @endforeach
                </textarea>
            </div>
        </div>
    </main>
</body>
</html>
<script src="{{asset('../resources/js/app.js')}}"></script>