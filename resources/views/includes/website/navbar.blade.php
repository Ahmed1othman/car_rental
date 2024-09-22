
{{--الجزء التعريفي--}}
<nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-dark">
    <div class="container flex-lg-row flex-nowrap align-items-center">
        <div class="navbar-brand w-100">
            <a href="./index.html">
                <img class="logo-dark" src="{{asset('website/img/lawyer_logo.webp')}}" style="max-width: 80px; max-height: 80px" srcset="{{asset('website/img/logo-dark@2x.png 2x')}}" alt="" />
                <img class="logo-light" src="{{asset('website/img/lawyer_logo.webp')}}" style="max-width: 80px; max-height: 80px" srcset="{{asset('website/img/logo-light@2x.png 2x')}}" alt="" />
            </a>
        </div>
        <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            <div class="offcanvas-header d-lg-none">
                <h3 class="text-white fs-30 mb-0">العدالة</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">الرئيسية</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">من نحن</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">خدماتنا</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">شهادات المكتب</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">عملائنا</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">فريق العمل</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">حجز موعد</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">إتصل بنا</a>
                    </li>

                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link" href="{{route('home')}}">برشور</a>
                    </li>

                </ul>
                <!-- /.navbar-nav -->
                <div class="offcanvas-footer d-lg-none">
                    <div>
                        <a href="mailto:first.last@email.com" class="link-inverse">info@email.com</a>
                        <br /> +9665214521457 <br />
                        <nav class="nav social social-white mt-4">
                            <a href="#"><i class="uil uil-twitter"></i></a>
                            <a href="#"><i class="uil uil-facebook-f"></i></a>
                            <a href="#"><i class="uil uil-dribbble"></i></a>
                            <a href="#"><i class="uil uil-instagram"></i></a>
                            <a href="#"><i class="uil uil-youtube"></i></a>
                        </nav>
                        <!-- /.social -->
                    </div>
                </div>
                <!-- /.offcanvas-footer -->
            </div>
            <!-- /.offcanvas-body -->
        </div>
        <!-- /.navbar-collapse -->
        <div class="navbar-other w-100 d-flex ms-auto">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item"><a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-info"><i class="uil uil-info-circle"></i></a></li>
                <li class="nav-item d-lg-none">
                    <button class="hamburger offcanvas-nav-btn"><span></span></button>
                </li>
            </ul>
            <!-- /.navbar-nav -->
        </div>
        <!-- /.navbar-other -->
    </div>
    <!-- /.container -->
</nav>
