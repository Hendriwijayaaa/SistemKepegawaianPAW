 <!DOCTYPE html>
 <html lang="id">

 <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="description" content="ngawurNews">
     <meta name="keywords" content="ngawurNews, Javascript, NodeJS, ExpressJS">
     <meta name="author" content="Frans Bachtiar">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <style>
         @import "https://fonts.googleapis.com/css2?family=Open+Sans&display=swap";

         * {
             font-family: "Open Sans", sans-serif;
             box-sizing: border-box;
         }

         .auth-title {
             text-align: center;
             color: white;
             margin: 0;
             margin-top: 30px;
             margin-bottom: 10px;
         }

         .auth-content {
             border: 2px solid #046d20;
             border-radius: 3px;
             line-height: 30px;
             max-width: 800px;
             margin: 0 auto;
             margin-bottom: 30px;
             padding: 25px;
         }

         .auth-button {
             background: #046d20;
             text-decoration: none;
             text-align: center;
             border-radius: 5px;
             font-weight: bold;
             margin: 0 auto;
             padding: 5px;
             display: block;
             width: 150px;
         }

         .btn-key {
             width: 50px;
             padding: 8px;
             border: none;
             border-radius: 4px;
             background-color: #046d20 !important;
             color: white !important;
             text-decoration: none;
         }

         .auth-content .atur {
             display: block;
             margin: auto;
             margin-left: auto;
             margin-right: auto;
             text-align: center;
         }

         .text-banyuasin {
             text-align: center;
         }
     </style>
     <title>Verify Your Account!</title>
 </head>

 <body style="background: #046d20 !important; padding: 20px;">
     <h1 class="auth-title">
         Aktivasi Akun Anda
     </h1>
     <div class="auth-content" style="background: white;">
         {{-- <img src="{{ asset('user-asset/img/PPID_BANYUASIN_logo.png') }}" alt="" width="50px"> --}}
         <p style="font-size: 20px; text-align: center; font-weight: 700; text-transform: capitalize;">Hello👋🏻
             SELAMAT DATANG DI CS PT ANUGERAH MEGA LESTARI</p>
         <hr>
         <p style="text-transform: capitalize; text-align: center;">
             untuk melanjutkan Reset Password klik tautan di bawah ini hanya berlaku dalam 5 Menit kedepan:
         </p>

         <div class="atur">
             <a href="{{ route('reset.pw', ['email' => $email, 'token' => $token]) }}" class="btn-key">
                 Reset Password
             </a>
         </div>


         <p>
             Link alternatif: <a href="{{ route('reset.pw', ['email' => $email, 'token' => $token]) }}">klik</a>
         </p>
         <hr>

         @php
             use Carbon\Carbon;

             $nomor = Carbon::now();
         @endphp

         <div class="img-banner" style="margin-top: 150px;">
             {{-- <img src="{{ asset('template/assets/images/aml_perusahaan.jpg') }}" width="50%"> --}}
             <p style="font-size: 10px;" class="text-banyuasin">Copyright &copy; {{ $nomor->year }} PT Anugerah Mega
                 Lestari</p>
         </div>
     </div>
 </body>

 </html>
