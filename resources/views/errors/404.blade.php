<!DOCTYPE html>
<html lang="{{ session('locale') === 'ar' ? 'ar' : 'en-US' }}" dir="{{ session('locale') === 'ar' ? 'rtl' : 'ltr' }}">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ session('locale') === 'ar' ? '404 - الصفحة غير موجودة' : '404 - Page Not Found' }} - Click And Fix</title>

    <link rel="icon" sizes="128x128" href="{{ asset('assets/img/favicons/click_and_fix_favico.png') }}">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" />

  </head>

  <body>

    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}"><img src="{{ asset('assets/img/logo.png') }}" height="100" alt="logo" /></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
              <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}">{{ session('locale') === 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
            </ul>
          </div>
        </div>
      </nav>

      <section style="padding-top: 12rem; padding-bottom: 6rem;">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
              <h1 class="display-1 fw-bold mb-0" style="font-size: 8rem; color: #374151;">404</h1>
              <h2 class="fw-bold mt-3 mb-3" style="color: #374151;">{{ session('locale') === 'ar' ? 'الصفحة غير موجودة' : 'Page Not Found' }}</h2>
              <p class="fw-medium fs-1 mb-4" style="color: #6b7280;">{{ session('locale') === 'ar' ? 'عذراً، الصفحة التي تبحث عنها غير موجودة.' : 'Sorry, the page you are looking for does not exist.' }}</p>
              <a href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}" class="btn btn-dark btn-lg">{{ session('locale') === 'ar' ? 'العودة إلى الرئيسية' : 'Back to Home' }}</a>
            </div>
          </div>
        </div>
      </section>

      <section class="pb-0 pb-lg-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-7 col-12 mb-4 mb-md-6 mb-lg-0 order-0"> <img class="mb-4" src="{{ asset('assets/img/logo.png') }}" width="150" alt="logo" />
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0 order-lg-1 order-md-2">
              <h4 class="footer-heading-color fw-bold font-sans-serif mb-3 mb-lg-4">Click And Fix</h4>
              <ul class="list-unstyled mb-0">
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}#service">{{ session('locale') === 'ar' ? 'الخدمات' : 'Services' }}</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}#screenshots">{{ session('locale') === 'ar' ? 'لقطات الشاشة' : 'Screenshots' }}</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url(session('locale') === 'ar' ? '/ar' : '/') }}#contactus">{{ session('locale') === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/privacy-policy') }}">{{ session('locale') === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/delete-account-info') }}">{{ session('locale') === 'ar' ? 'حذف الحساب' : 'Account Deletion' }}</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/support') }}">{{ session('locale') === 'ar' ? 'دعم العملاء' : 'Support' }}</a></li>
              </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0 order-lg-2 order-md-3">
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0 order-lg-3 order-md-4">
            </div>
            <div class="col-lg-3 col-md-5 col-12 mb-4 mb-md-6 mb-lg-0 order-lg-4 order-md-1">
              <div class="icon-group mb-4"> 
                <a class="text-decoration-none icon-item shadow-social" id="facebook" href="https://www.facebook.com/Click-And-Fix-QA-105210212064388"><i class="fab fa-facebook-f"> </i></a>
                <a class="text-decoration-none icon-item shadow-social" id="instagram" href="https://www.instagram.com/clickandfixqa/"><i class="fab fa-instagram"> </i></a>
                <a class="text-decoration-none icon-item shadow-social" id="twitter" href="https://twitter.com/clickandfixqa"><i class="fab fa-twitter"> </i></a>
                <a class="text-decoration-none icon-item shadow-social" id="tiktok" href="https://vm.tiktok.com/ZSeHUp2FT/"><svg hight="14" width="14" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg></a>
                <a class="text-decoration-none icon-item shadow-social" id="snapchat" href="https://www.snapchat.com/add/clickandfixqa?share_id=PUQ3G70XjhU&locale=ar-JO"><svg hight="20" width="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path d="M0 0h24v24H0z" fill="none"/><path d="M11.871 21.764c-1.19 0-1.984-.561-2.693-1.056-.503-.357-.976-.696-1.533-.79a4.568 4.568 0 0 0-.803-.066c-.472 0-.847.071-1.114.125-.17.03-.312.058-.424.058-.116 0-.263-.032-.32-.228-.05-.16-.081-.312-.112-.459-.08-.37-.147-.597-.286-.62-1.489-.227-2.38-.57-2.554-.976-.014-.044-.031-.09-.031-.125-.01-.125.08-.227.205-.25 1.181-.196 2.242-.824 3.138-1.858.696-.803 1.035-1.579 1.066-1.663 0-.01.009-.01.009-.01.17-.351.205-.65.102-.895-.191-.46-.825-.656-1.257-.79-.111-.03-.205-.066-.285-.093-.37-.147-.986-.46-.905-.892.058-.312.472-.535.811-.535.094 0 .174.014.24.05.38.173.723.262 1.017.262.366 0 .54-.138.584-.182a24.93 24.93 0 0 0-.035-.593c-.09-1.365-.192-3.059.24-4.03 1.298-2.907 4.053-3.14 4.869-3.14L12.156 3h.05c.815 0 3.57.227 4.868 3.139.437.971.33 2.67.24 4.03l-.008.067c-.01.182-.023.356-.032.535.045.035.205.169.535.173.286-.008.598-.102.954-.263a.804.804 0 0 1 .312-.066c.125 0 .25.03.357.066h.009c.299.112.495.321.495.54.009.205-.152.517-.914.825-.08.03-.174.067-.285.093-.424.13-1.057.335-1.258.79-.111.24-.066.548.103.895 0 .01.009.01.009.01.049.124 1.337 3.049 4.204 3.526a.246.246 0 0 1 .205.25c0 .044-.009.089-.031.129-.174.41-1.057.744-2.555.976-.138.022-.205.25-.285.62a6.831 6.831 0 0 1-.112.459c-.044.147-.138.227-.298.227h-.023c-.102 0-.24-.013-.423-.049a5.285 5.285 0 0 0-1.115-.116c-.263 0-.535.023-.802.067-.553.09-1.03.433-1.534.79-.717.49-1.515 1.051-2.697 1.051h-.254z"/></g></svg></a>
              </div>
              <h4 class="fw-medium font-sans-serif text-secondary mb-3">{{ session('locale') === 'ar' ? 'حمل التطبيق الان' : 'Discover our app' }}</h4>
              <div class="d-flex align-items-center"> 
                <a href="https://play.google.com/store/apps/details?id=com.clickandfix.qa"> <img class="me-2" src="{{ asset('assets/img/play-store.png') }}" alt="play store" /></a>
                <a href="https://apps.apple.com/jo/app/click-and-fix/id1603972575"> <img src="{{ asset('assets/img/apple-store.png') }}" alt="apple store" /></a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="py-5 text-center">
        <p class="mb-0 text-secondary fs--1 fw-medium">{{ session('locale') === 'ar' ? 'جميع الحقوق محفوظه' : 'All rights' }} <span> Click and fix </span></p>
      </div>
    </main>

    <script src="{{ asset('vendors/@popperjs/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">

  </body>

</html>
