<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ mix('css/tempusdominus-bootstrap-4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/font-awesome.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @auth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-capitalize" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-capitalize" href="{{ route('cdrs', ['disposition' => 0, 'year' => 0, 'month' => 0, 'src' => 0]) }}">{{ __('app.cdrs') }}</a>
                                    <a class="dropdown-item text-capitalize" href="{{ route('wake_up_call') }}">{{ __('app.wakeup') }}</a>
                                    <a class="dropdown-item text-capitalize" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('app.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/datatables.js') }}"></script>
<script src="{{ mix('js/moment.js') }}"></script>
<script src="{{ mix('js/tempusdominus-bootstrap-4.js') }}"></script>
<script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
@isset($validatorLogin)
  {!! $validatorLogin->selector('form#login') !!}
@endisset
@isset($validatorWakeUpCall)
  {!! $validatorWakeUpCall->selector('form#wake-up-call') !!}
@endisset
<script>
$(document).ready(function() {
  $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      sEmptyTable: "Não foi encontrado nenhum registo",
      sLoadingRecords: "A carregar...",
      sProcessing: "A processar...",
      sLengthMenu: "Mostrar _MENU_ registos",
      sZeroRecords: "Não foram encontrados resultados",
      sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registos",
      sInfoEmpty: "Mostrando de 0 até 0 de 0 registos",
      sInfoFiltered: "(filtrado de _MAX_ registos no total)",
      sInfoPostFix: "",
      sSearch: "Procurar:",
      sUrl: "",
      oPaginate: {
        sFirst: "Primeiro",
        sPrevious: "Anterior",
        sNext: "Seguinte",
        sLast: "Último"
      },
      oAria: {
        sSortAscending : ": Ordenar colunas de forma ascendente",
        sSortDescending: ": Ordenar colunas de forma descendente"
      }
    }
  });

  $.fn.dataTable.ext.legacy.ajax = true;

  if ($('table#cdrs').length) {
    let options = {
      ajax: {
        url: "{{ route('ajax.cdrs.index') }}",
        data: {
          disposition: "{{ request()->disposition ?? 0 }}",
          year: "{{ request()->year ?? 0 }}",
          month: "{{ request()->month ?? 0 }}",
          src: "{{ request()->src ?? 0 }}"
        }
      },
      iDisplayLength: 500,
      lengthMenu: [100, 250, 500, 1000],
      responsive: true,
      serverSide: true,
      stateSave: true,
      columns: [
        {
          data: 'src'
        },
        {
          data: 'dst'
        },
        {
          data: 'disposition'
        },
        {
          data: 'calldate'
        },
        {
          data: null,
          render: function(data, type, { duration, disposition }, meta) {
            if (disposition !== 'ANSWERED') {
              return '--';
            } else {
              const minutes = Math.floor(duration / 60);

              const seconds = Math.round(duration % 60);

              return `${minutes} min ${seconds} s`;
            }
          }
        }
      ]
    };

    $('table#cdrs').DataTable(options);
  }

  if ($('table#wake-up-calls').length) {
    let options = {
      ajax: "{{ route('ajax.wake_up_call.index') }}",
      iDisplayLength: 10,
      lengthMenu: [10, 50, 100],
      responsive: true,
      serverSide: true,
      columns: [
        {
          data: null,
          render: function(date, type, { datetime }, meta) {

            var dateTimeString =  moment.unix(datetime).format("YYYY-MM-DD HH:mm");

            return dateTimeString;
          }
        },
        {
          data: 'ext'
        },
        {
          data: 'tries'
        },
        {
          data: 'waittime'
        },
        {
          data: 'retrytime'
        },
        {
          data: 'supervisor'
        },
        {
          data: null,
          render: function(data, type, { id }, meta) {
            return `<button type='button' data-id="${id}" class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button>`;
          }
        }
      ]
    };

    $(document).on('click', '.btn-danger', function() {
      $.ajax({
        url: 'ajax/wake-up-call/' + $(this).data('id'),
        type: 'DELETE',
        success: function() {
          location.reload();
        }
      });
    });

    $('table#wake-up-calls').DataTable(options);
  }

  if($('#datetimepicker').length) {
    $('#datetimepicker').datetimepicker({
      format: 'YYYY-MM-DD HH:mm',
      date: moment(),
      locale: 'pt'
    });
  };
});
</script>
</html>
