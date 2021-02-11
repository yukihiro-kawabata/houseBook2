<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        {{-- <link rel="stylesheet" href="{{ asset('/library/musubii/dist/musubii.min.css') }}" /> --}}

        <link rel="icon" type="image/png" href="{{ asset('kabigon_manew_xo.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('kabigon_manew_xo.ico') }}">

        <?php // laravel defult ?>
        <script src="{{ mix('/js/app.js') }}" defer></script>
        <?php ///// 使ってない  <script src="{{ mix('/css/app.js') }}" defer></script> ?>

        <?php // bootstrap ?>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

        <?php // select2 ?>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <?php // animation ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">

        <?php // toast.js ?>
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

        <?php // font-awesome ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />

    </head>
    <body>

        <div class="pos-f-t">
            <div class="collapse" id="navbarToggleExternalContent">
                <div class="bg-dark p-4">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/cash/index') }}" style="color:#fff;">登録ページ</a></li>
                        <li><a href="{{ url('/cash/list') }}" style="color:#fff;">一覧ページ</a></li>
                        <li><a href="{{ url('/cash/index') . '?constant=true' }}" style="color:#fff;">自動登録設定</a></li>
                        <li><a href="{{ url('/cash/constant/list') }}" style="color:#fff;">自動登録一覧</a></li>
                        <li><a href="{{ url('/kamoku/list') }}" style="color:#fff;">科目一覧</a></li>
                        <li><a href="{{ url('/todo/list') }}" style="color:#fff;">ToDo</a></li>
                        <li><a href="{{ url('/mail/import') }}" style="color:#fff;">メール取込</a></li>
                    </ul>
                </div>
            </div>
            <nav class="navbar navbar-light" style="background-color: #e3f2fd;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            </nav>
        </div>

        @yield('body')

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    </body>
</html>

