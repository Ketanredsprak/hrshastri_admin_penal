<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Document</title>
</head>
<body>
     
     <table class="table ">
          <thead>
              <tr>
                  @foreach ($data['columns'] as $date)
                      <th>{{ $date }}</th>
                  @endforeach
              </tr>
          </thead>
          <tbody>

               <tr>
                    <td>1</td>
               </tr>

               {{-- {{dd($data['data'])}} --}}
              @foreach ($data['data'] as $attendance)
              
                  <tr>
                    {{-- {{dd($attendance)}} --}}
                      @foreach ($attendance as $data)
                            @if($data == 'A')
                                 <i class="badge bg-danger p-2  rounded">{{ __('A') }}</i>
                            @elseif($data == 'L')
                                 <i class="badge bg-info p-2  rounded">{{ __('L') }}</i>
                            @elseif($data == 'HD')
                                 <i class="badge bg-secondary p-2  rounded">{{ __('HD') }}</i>
                            @elseif($data == 'H')
                                 <i class="badge bg-warning p-2  rounded">{{ __('H') }}</i>
                            @elseif($data == 'WO')
                                 <i class="badge bg-dark p-2  rounded">{{ __('WO') }}</i>
                            @else
                                 <td>{{ $data }}</td>
                            @endif

                            
                      @endforeach
                  </tr>
              @endforeach
          </tbody>
      </table>
</body>
</html>

