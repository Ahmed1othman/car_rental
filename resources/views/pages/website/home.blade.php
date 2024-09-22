@extends('layouts.website_layout')

@section('title', 'Home Page')

@section('content')

        <div class="content-wrapper">

            <section class="video-wrapper bg-overlay bg-overlay-gradient px-0 mt-0 min-vh-80">
                <video poster="{{asset('website/img/photos/lawyer.jpg')}}" src="{{asset('website/media/lawyer2.mp4')}}" autoplay loop playsinline muted></video>
                <div class="video-content">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-lg-8 col-xl-6 text-center text-white mx-auto">
                                <h1 class="display-1 fs-54 text-white mb-5"><span class="rotator-zoom">مكتب الاستاذ المحامي مصطفي محمود,تحصيل الديون,التقاضى,تقسيم التركات,صياغة العقود,التوثيق,خدمات شركات القطاع الخاص,تسوية النزاعات بالتحكيم او الوساطة والصلح,الافلاس</span></h1>
                                <p class="lead fs-24 mb-0 mx-xxl-8">فريق قانوني يتمتع بخبرة قانونية طويلة
                                    ومعايير قانونية قياسية وأساليب تعامل تقنية حديثة</p>
                            </div>
                            <!-- /column -->
                        </div>
                        <!--/column -->
                    </div>
                    <!-- /.video-content -->
                </div>
                <!-- /.content-overlay -->
            </section>
            <!-- /section -->
            <section class="wrapper bg-light">
                <div class="container py-15 py-md-17">
                    <div class="row text-center mb-10">
                        <div class="col-md-10 col-lg-9 col-xxl-8 mx-auto">
                            <h2 class="fs-16 text-uppercase text-muted mb-3">من نحن ؟</h2>
                            <h3 class="display-3 px-xl-10 mb-0">فريق قانوني يتمتع بخبرة قانونية طويلة
                                    ومعايير قانونية قياسية وأساليب تعامل تقنية حديثة</h3>
                        </div>
                        <!-- /column -->
                    </div>
                    <div class="row text-center mb-10">
                        <div class="swiper-container text-center mb-6" data-margin="30" data-dots="true" data-items-xl="3" data-items-md="2" data-items-xs="1">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="rounded-circle w-20 mx-auto mb-4" src="{{asset('website/img/avatars/t1.jpg')}}" srcset="{{asset('assets/img/avatars/t1@2x.jpg 2x')}}" alt="" />
                                        <h4 class="mb-1">المحامي محمود مصطفي</h4>
                                        <div class="meta mb-2">محام ومستشار قانوني</div>
                                        <p class="mb-2">المؤسس والمدير التنفيذي</p>
                                        <!-- /.social -->
                                    </div>
                                    <!--/.swiper-slide -->
                                    <div class="swiper-slide">
                                        <img class="rounded-circle w-20 mx-auto mb-4" src="{{asset('website/img/avatars/t1.jpg')}}" srcset="{{asset('assets/img/avatars/t1@2x.jpg 2x')}}" alt="" />
                                        <h4 class="mb-1">المحامي محمود مصطفي</h4>
                                        <div class="meta mb-2">محام ومستشار قانوني</div>
                                        <p class="mb-2">المؤسس والمدير التنفيذي</p>
                                        <!-- /.social -->
                                    </div>
                                    <!--/.swiper-slide -->
                                    <div class="swiper-slide">
                                        <img class="rounded-circle w-20 mx-auto mb-4" src="{{asset('website/img/avatars/t1.jpg')}}" srcset="{{asset('assets/img/avatars/t1@2x.jpg 2x')}}" alt="" />
                                        <h4 class="mb-1">المحامي محمود مصطفي</h4>
                                        <div class="meta mb-2">محام ومستشار قانوني</div>
                                        <p class="mb-2">المؤسس والمدير التنفيذي</p>
                                        <!-- /.social -->
                                    </div>
                                    <!--/.swiper-slide -->
                                    <div class="swiper-slide">
                                        <img class="rounded-circle w-20 mx-auto mb-4" src="{{asset('website/img/avatars/t1.jpg')}}" srcset="{{asset('assets/img/avatars/t1@2x.jpg 2x')}}" alt="" />
                                        <h4 class="mb-1">المحامي محمود مصطفي</h4>
                                        <div class="meta mb-2">محام ومستشار قانوني</div>
                                        <p class="mb-2">المؤسس والمدير التنفيذي</p>
                                        <!-- /.social -->
                                    </div>
                                    <!--/.swiper-slide -->
                                </div>
                                <!--/.swiper-wrapper -->
                            </div>
                            <!-- /.swiper -->
                        </div>
                        <!-- /.swiper-container -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container -->
            </section>
            <!-- /section -->

            <section id="snippet-3" class="wrapper bg-light wrapper-border">
                <div class="container pt-15 pt-md-17 pb-13 pb-md-15 text-center">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                            <h2 class="fs-15 text-uppercase text-muted mb-3">خدماتنا</h2>
                            <h3 class="display-4 mb-10 px-xl-10">نقدم في مكتب العدالة للمحاماه مجموعة من الخدمات في مختلف التخصصات القانونية</h3>
                        </div>
                        <!-- /column -->
                    </div>
                    <!-- /.row -->
                    <div class="position-relative">
                        <div class="shape rounded-circle bg-soft-blue rellax w-16 h-16" data-rellax-speed="1" style="bottom: -0.5rem; right: -2.2rem; z-index: 0;"></div>
                        <div class="shape bg-dot yellow rellax w-16 h-17" data-rellax-speed="1" style="top: -0.5rem; left: -2.5rem; z-index: 0;"></div>
                        <div class="row gx-md-5 gy-5 text-center">
                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/law.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>التقاضي</h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها العميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/2.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>تحصيل الديون</h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها العميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/3.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>قسمة التركات </h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها العميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/4.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>ضياغة العقود</h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها لعميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/5.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>التقاضي</h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها العميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <img src="{{asset('website/img/icons/lawyer/law.png')}}" class="svg-inject icon-svg icon-svg-md text-yellow mb-3" alt="" />
                                        <h4>التوثيق</h4>
                                        <p class="mb-2">يقدم المكتب هذه الخدمة التي تتضمن تقديم الرأي القانوني أو المشورة وتقديم الحلول القانونية الممكنة حول أي مسائل أو وقائع يسأل عنها العميل، حيث يتم توضيح الموقف القانوني للعميل والاجابة عن استفساراته وتساؤلاته وفقًا للأنظمة ...</p>
                                        <a href="#" class="more hover link-yellow">اعرف المزيد</a>
                                    </div>
                                    <!--/.card-body -->
                                </div>
                                <!--/.card -->
                            </div>
                            <!--/column -->

                        </div>
                        <!--/.row -->
                    </div>
                    <!-- /.position-relative -->
                </div>
                <!-- /.container -->
            </section>
            <!-- /section -->

            <section class="wrapper bg-light">
                <div class="container py-15 py-md-17">
                    <div class="row gx-lg-8 gx-xl-12 gy-10 gy-lg-0 mb-15">
                        <div class="col-lg-4 mt-lg-2">
                            <h2 class="fs-16 text-uppercase text-muted mb-3">عملائنا</h2>
                            <h3 class="display-3 mb-3 pe-xxl-5">اكثر من 1500 عميل لدينا</h3>
                            <p class="lead fs-lg mb-0 pe-xxl-7">قدم مكتبنا الكثير من الخدمات القانوية والقضائية لكثير من العملاء منهم:</p>
                        </div>
                        <!-- /column -->
                        <div class="col-lg-8">
                            <div class="row row-cols-2 row-cols-md-4 gx-0 gx-md-8 gx-xl-12 gy-12">
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_1.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_2.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_3.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_2.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_3.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_4.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_5.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                                <div class="col">
                                    <figure class="px-3 px-md-0 px-xxl-2"><img src="{{asset('website/img/icons/lawyer/client_6.png')}}" alt="" /></figure>
                                </div>
                                <!--/column -->
                            </div>
                            <!--/.row -->
                        </div>
                        <!-- /column -->
                    </div>
                    <!-- /.row -->

                    <div class="row gx-lg-8 gx-xl-12 gy-10 gy-lg-0">
                        <div class="col-xl-10 mx-auto">
                            <div class="card image-wrapper bg-full bg-image bg-overlay" data-image-src="{{asset('website/img/photos/bg2.jpg')}}">
                                <div class="card-body p-9 p-xl-10">
                                    <div class="row align-items-center counter-wrapper gy-4 text-center text-white">
                                        <div class="col-6 col-lg-3">
                                            <h3 class="counter counter-lg text-white">1500 +</h3>
                                            <p>عميل تم خدمته</p>
                                        </div>
                                        <!--/column -->
                                        <div class="col-6 col-lg-3">
                                            <h3 class="counter counter-lg text-white">5000 +</h3>
                                            <p>استشارة قانونية</p>
                                        </div>
                                        <!--/column -->
                                        <div class="col-6 col-lg-3">
                                            <h3 class="counter counter-lg text-white">2500 +</h3>
                                            <p>فض نزاع بالتراضي</p>
                                        </div>
                                        <!--/column -->
                                        <div class="col-6 col-lg-3">
                                            <h3 class="counter counter-lg text-white">100 +</h3>
                                            <p>مرافعة بالمحكمة</p>
                                        </div>
                                        <!--/column -->
                                    </div>
                                    <!--/.row -->
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.card -->
                        </div>
                        <!-- /column -->
                    </div>
                </div>
                <!-- /.row -->
            </section>
            <!-- /section -->

        </div>
        <!-- /.content-wrapper -->
        <footer class="bg-dark text-inverse">
            <div class="container py-13 py-md-15">
                <div class="row gy-6 gy-lg-0">
                    <div class="col-md-4 col-lg-3">
                        <div class="widget">
                            <img class="mb-4" src="{{asset('website/img/lawyer_logo.webp')}}" srcset="{{asset('website/img/lawyer_logo@2x.webp 2x')}}" alt="" style="max-width: 100px; max-height: 100px" />
                            <p class="mb-4">© 2024 - العدالة. <br class="d-none d-lg-block" />جميع الحقوق محفوظة</p>
                            <nav class="nav social social-white">
                                <a href="#"><i class="uil uil-twitter"></i></a>
                                <a href="#"><i class="uil uil-facebook-f"></i></a>
                                <a href="#"><i class="uil uil-dribbble"></i></a>
                                <a href="#"><i class="uil uil-instagram"></i></a>
                                <a href="#"><i class="uil uil-youtube"></i></a>
                            </nav>
                            <!-- /.social -->
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /column -->
                    <div class="col-md-4 col-lg-3">
                        <div class="widget">
                            <h4 class="widget-title text-white mb-3">قابلنا</h4>
                            <address class="pe-xl-15 pe-xxl-17">الرياض - السعودية - 14 شارع الملك عبد العزيز</address>
                            <a href="mailto:#">info@email.com</a><br /> +9965225214257
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /column -->
                    <div class="col-md-4 col-lg-3">
                        <div class="widget">
                            <h4 class="widget-title text-white mb-3">اعرف اكثر</h4>
                            <ul class="list-unstyled  mb-0">
                                <li><a href="#">من نحن</a></li>
                                <li><a href="#">خدماتنا</a></li>
                                <li><a href="#">المدونة</a></li>
                                <li><a href="#">الاسئلة الشائعة</a></li>
                                <li><a href="#">عملائنا</a></li>
                            </ul>
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /column -->
                    <div class="col-md-12 col-lg-3">
                        <div class="widget">
                            <h4 class="widget-title text-white mb-3">اخبارنا</h4>
                            <p class="mb-5">اشترك معنا ليصلك كل جديدنا وعروضنا المستمرة</p>
                            <div class="newsletter-wrapper">
                                <!-- Begin Mailchimp Signup Form -->
                                <div id="mc_embed_signup2">
                                    <form action="https://elemisfreebies.us20.list-manage.com/subscribe/post?u=aa4947f70a475ce162057838d&amp;id=b49ef47a9a" method="post" id="mc-embedded-subscribe-form2" name="mc-embedded-subscribe-form" class="validate dark-fields" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll2">
                                            <div class="mc-field-group input-group form-floating">
                                                <input type="email" value="" name="EMAIL" class="required email form-control" placeholder="Email Address" id="mce-EMAIL2">
                                                <label for="mce-EMAIL2">البريد الالكتروني</label>
                                                <input type="submit" value="إشترك" name="subscribe" id="mc-embedded-subscribe2" class="btn btn-primary ">
                                            </div>
                                            <div id="mce-responses2" class="clear">
                                                <div class="response" id="mce-error-response2" style="display:none"></div>
                                                <div class="response" id="mce-success-response2" style="display:none"></div>
                                            </div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ddc180777a163e0f9f66ee014_4b1bcfa0bc" tabindex="-1" value=""></div>
                                            <div class="clear"></div>
                                        </div>
                                    </form>
                                </div>
                                <!--End mc_embed_signup-->
                            </div>
                            <!-- /.newsletter-wrapper -->
                        </div>
                        <!-- /.widget -->
                    </div>
                    <!-- /column -->
                </div>
                <!--/.row -->
            </div>
            <!-- /.container -->
        </footer>
    <!-- /.page-frame -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
@endsection
