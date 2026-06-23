<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Click And Fix</title>

    <link rel="icon" sizes="128x128" href="{{ asset('assets/img/favicons/click_and_fix_favico.png') }}">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" />

  </head>

  <body>

    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.png') }}" height="100" alt="logo" /></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
              <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="#service">Service</a></li>
              <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="#screenshots">Screenshots</a></li>
              <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="#contactus">Contact Us</a></li>
              <li class="nav-item dropdown px-3 px-lg-0"> <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">EN</a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:0.3rem;" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="{{ url('/') }}">EN</a></li>
                  <li><a class="dropdown-item" href="{{ url('/ar') }}">AR</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <section style="padding-top: 7rem;">
        <div class="bg-holder" style="background-image:url({{ asset('assets/img/hero/hero-bg.svg') }});">
        </div>

        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-5 col-lg-6 order-0 order-md-1 text-end"><img class="pt-7 pt-md-0 hero-img" src="{{ asset('assets/img/hero/hero1.png') }}" alt="hero-header" /></div>
            <div class="col-md-7 col-lg-6 text-md-start text-center py-6">
              <h4 class="fw-bold text-danger mb-3">Click And Fix is an application helps you to</h4>
              <h1 class="hero-title">make a better for your car.</h1>
              <p class="mb-4 fw-medium">it will take one click to do all the things that your car need from maintenance, service, and spare parts. you will be the first person to know what happen in your vehicle. It starts from its reminder feature; you will always know when to do the car service. While you are doing the maintenance, you can also track the history at the same time. So, you won't miss a single important thing to do.
                it's time for change...!</p>
              <div class="text-center text-md-start">
                <div class="w-100 d-block d-md-none"></div><a href="#!" role="button" data-bs-toggle="modal" data-bs-target="#popupVideo"><span class="btn btn-danger round-btn-lg rounded-circle me-3 danger-btn-shadow"> <img src="{{ asset('assets/img/hero/play.svg') }}" width="15" alt="paly"/></span></a><span class="fw-medium">Play Demo</span>
                <div class="modal fade" id="popupVideo" tabindex="-1" aria-labelledby="popupVideo" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <iframe class="rounded" style="width:100%;max-height:500px;" height="500px" src="https://www.youtube.com/embed/PiwYJy-aD9o" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="pt-5 pt-md-9" id="service">

        <div class="container">
          <div class="position-absolute z-index--1 end-0 d-none d-lg-block"><img src="{{ asset('assets/img/category/shape.svg') }}" style="max-width: 200px" alt="service" /></div>
          <div class="mb-7 text-center">
            <h5 class="text-secondary">CATEGORY </h5>
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">We Offer Best Services</h3>
          </div>
          <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6 mb-6">
              <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                <div class="card-body p-xxl-5 p-4"> <img src="{{ asset('assets/img/category/icon1.png') }}" width="75" alt="Service" />
                  <h4 class="mb-3">Emergency</h4>
                  <p class="mb-0 fw-medium">Emergency Services such as Towing, Change Tire, Battery Charging, Fuel Supplying.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-6">
              <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                <div class="card-body p-xxl-5 p-4"> <img src="{{ asset('assets/img/category/icon2.png') }}" width="75" alt="Service" />
                  <h4 class="mb-3">Services</h4>
                  <p class="mb-0 fw-medium">Car Care Services such as Car Check, Car Insurance, Dry Clean.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-6">
              <div class="card service-card shadow-hover rounded-3 text-center align-items-center">
                <div class="card-body p-xxl-5 p-4"> <img src="{{ asset('assets/img/category/icon3.png') }}" width="75" alt="Service" />
                  <h4 class="mb-3">Parts</h4>
                  <p class="mb-0 fw-medium">All of your car parts can be found here like External Parts, Filters, Belts, Oils, and many more.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <section class="pt-5" id="screenshots">
        <div class="container">
          <div class="position-absolute start-100 bottom-0 translate-middle-x d-none d-xl-block ms-xl-n4"><img src="{{ asset('assets/img/dest/shape.svg') }}" alt="destination" /></div>
          <div class="mb-7 text-center">
            <h5 class="text-secondary">Screenshots </h5>
            <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">A Look Into Our App.</h3>
          </div>
          <div class="row">
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest1.jpeg') }}" alt="Click And Fix, Splash Screen" />
              </div>
            </div>
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest2.jpeg') }}" alt="Click And Fix, Home Screen" />
              </div>
            </div>
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest3.jpeg') }}" alt="Click And Fix, Order Screen" />
              </div>
            </div>
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest4.jpeg') }}" alt="Click And Fix, Order History Screen" />
              </div>
            </div>
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest5.jpeg') }}" alt="Click And Fix, Car Details Screen" />
              </div>
            </div>
            <div class="col-md-2 mb-4">
              <div class="card overflow-hidden shadow"> <img class="card-img-top" src="{{ asset('assets/img/dest/dest6.jpeg') }}" alt="Click And Fix, Report Screen" />
              </div>
            </div>
          </div>
        </div>

      </section>

      <section id="contactus">

        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="mb-4 text-start">
                <h5 class="text-secondary">Click And Fix </h5>
                <h3 class="fs-xl-10 fs-lg-8 fs-7 fw-bold font-cursive text-capitalize">Contact Us</h3>
              </div>
              <div class="d-flex align-items-start mb-5">
                <div class="bg-primary me-sm-4 me-3 p-3" style="border-radius: 13px"> <img src="{{ asset('assets/img/steps/phone.svg') }}" width="22" alt="steps" /></div>
                <div class="flex-1">
                  <h5 class="text-secondary fw-bold fs-0">Phone Number</h5>
                  <p>+97477000451</p>
                </div>
              </div>
              <div class="d-flex align-items-start mb-5">
                <div class="bg-danger me-sm-4 me-3 p-3" style="border-radius: 13px"> <img src="{{ asset('assets/img/steps/email.svg') }}" width="22" alt="steps" /></div>
                <div class="flex-1">
                  <h5 class="text-secondary fw-bold fs-0">Email</h5>
                  <p>info@clickandfixqa.com</p>
                </div>
              </div>
              <div class="d-flex align-items-start mb-5">
                <div class="bg-info me-sm-4 me-3 p-3" style="border-radius: 13px"> <img src="{{ asset('assets/img/steps/location.svg') }}" width="22" alt="steps" /></div>
                <div class="flex-1">
                  <h5 class="text-secondary fw-bold fs-0">Address</h5>
                  <p>Aldoha - Area 26 - Str 940 - Najma - Office 201 </p>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center align-items-start">
              <div class="card position-relative shadow" style="max-width: 370px;">
                <div class="position-absolute z-index--1 me-10 me-xxl-0" style="right:-160px;top:-210px;"> <img src="{{ asset('assets/img/steps/bg.png') }}" style="max-width:550px;" alt="shape" /></div>
                <div class="card-body p-3"> <img class="mb-4 mt-2 rounded-2 w-100" src="{{ asset('assets/img/steps/heading-img.jpeg') }}" alt="booking" />
                  <div>
                    <h5 class="fw-medium">Click And Fix</h5>
                    <div class="icon-group mb-4"> 
                      <span class="btn icon-item"> 
                        <a href="tel:+97477000451">
                          <img src="{{ asset('assets/img/steps/phone-black.svg') }}" alt="Click And Fix Phone"/>
                        </a> 
                      </span>
                      <span class="btn icon-item"> 
                        <a href="mailto:info@clickandfixqa.com">
                          <img src="{{ asset('assets/img/steps/email-black.svg') }}" alt="Click And Fix Email"/> 
                        </a> 
                      </span>
                      <span class="btn icon-item"> 
                        <a href="https://www.google.com/maps/place/25%C2%B016'01.7%22N+51%C2%B032'40.2%22E/@25.2671413,51.5466824,17z/data=!3m1!4b1!4m5!3m4!1s0x0:0x2bcab7b9d713d781!8m2!3d25.2671413!4d51.5444937">
                          <img src="{{ asset('assets/img/steps/location-black.svg') }}" alt="Click And Fix Location"/> 
                        </a>  
                      </span>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <section class="pb-0 pb-lg-4">

        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-7 col-12 mb-4 mb-md-6 mb-lg-0 order-0"> <img class="mb-4" src="{{ asset('assets/img/logo.png') }}" width="150" alt="jadoo" />
              
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0 order-lg-1 order-md-2">
              <h4 class="footer-heading-color fw-bold font-sans-serif mb-3 mb-lg-4">Click And Fix</h4>
              <ul class="list-unstyled mb-0">
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="#service">Services</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="#screenshots">Screenshots</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="#contactus">Contact Us</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/delete-account-info') }}">Account Deletion</a></li>
                <li class="mb-2"><a class="link-900 fs-1 fw-medium text-decoration-none" href="{{ url('/support') }}">Support</a></li>
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
              <h4 class="fw-medium font-sans-serif text-secondary mb-3">Discover our app</h4>
              <div class="d-flex align-items-center"> 
                <a href="https://play.google.com/store/apps/details?id=com.emperorsoft.click_and_fix"> <img class="me-2" src="{{ asset('assets/img/play-store.png') }}" alt="play store" /></a>
                <a href="https://apps.apple.com/jo/app/click-and-fix/id1603972575"> <img src="{{ asset('assets/img/apple-store.png') }}" alt="apple store" /></a>
              </div>
            </div>
          </div>
        </div>

      </section>

      <div class="py-5 text-center">
        <p class="mb-0 text-secondary fs--1 fw-medium">All rights <span><a href="https://emperorsoft.org/"> Emperor Soft</a> </span></p>
      </div>
    </main>

    <script src="{{ asset('vendors/@popperjs/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  
    <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '464886501689829');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=464886501689829&ev=PageView&noscript=1"
  /></noscript>

  </body>

</html>
