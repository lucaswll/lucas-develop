<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <title>Lucas</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,700" rel="stylesheet">
    
    <!-- Scripts -->
    <script>
    	window.Laravel = <?php echo json_encode([ 'token' => csrf_token(), 'url' => url('/') ]); ?>;
    </script>
    
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Lucas</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            	@php
            		
            		$menu = [
            			[ 'name' => 'Produtos', 'url' => 'products', 'checked' => Route::is('product') ],
            			[ 'name' => 'Clientes', 'url' => 'customers', 'checked' => Route::is('customer') ],
            			[ 'name' => 'Vendas', 'url' => 'sales', 'checked' => Route::is('sale') ],
            		];
            		
            	@endphp
            
                <ul class="navbar-nav ml-auto">
                	
                	@foreach ($menu as $item)
						<li class="nav-item {{ $item['checked'] ? 'active' : '' }}"><a class="nav-link" href="{{ url($item['url']) }}">{{ $item['name'] }}</a></li>                	
                	@endforeach
                	
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>
    
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/scripts.min.js') }}"></script>
    
</body>
</html>