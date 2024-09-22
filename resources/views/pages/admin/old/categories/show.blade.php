@extends('layouts.admin_layout')

@section('title', 'عرض البراند')

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> عرض {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.'.$modelName.'.index') }}">البراندات</a></li>
                            <li class="breadcrumb-item active"> عرض {{$modelName}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> معلومات {{$modelName}}</h3>
                            </div>
                            <div class="card-body">
                                <!-- Brand Information -->
                                <div class="form-group">
                                    <h4><i class="fas fa-info-circle"></i> تفاصيل {{$modelName}}</h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th> اسم {{$modelName}} (بالإنجليزية):</th>
                                            <td>{{$item->name_en}} </td>
                                        </tr>
                                        <tr>
                                            <th>اسم {{$modelName}} (بالعربية):</th>
                                            <td>{{$item->name_ar}} </td>
                                        </tr>

                                        <tr>
                                            <th> وصف  {{$modelName}} (بالإنجليزية):</th>
                                            <td>{{ $item->description_en }}</td>
                                        </tr>
                                        <tr>
                                            <th> وصف  {{$modelName}} (بالعربية):</th>
                                            <td>{{ $item->description_ar }}</td>
                                        </tr>

                                        <tr>
                                            <th> صورة {{$modelName}}:</th>
                                            <td>
                                                @if ($item->category_image)
                                                    <img src="{{ asset('storage/' . $item->category_image) }}" alt="{{$modelName}} image" class="brand-logo img-thumbnail" style="max-height: 100px;">
                                                @else
                                                    <span>لا يوجد صورة مرفوع</span>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- SEO Information -->
                                <div class="form-group mt-4">
                                    <h4><i class="fas fa-search"></i> معلومات السيو</h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th>العنوان التعريفي (بالإنجليزية):</th>
                                            <td>{{ $item->meta_title_en }}</td>
                                        </tr>
                                        <tr>
                                            <th>العنوان التعريفي (بالعربية):</th>
                                            <td>{{ $item->meta_title_ar }}</td>
                                        </tr>
                                        <tr>
                                            <th>الوصف التعريفي (بالإنجليزية):</th>
                                            <td>{{ $item->meta_description_en }}</td>
                                        </tr>
                                        <tr>
                                            <th>الوصف التعريفي (بالعربية):</th>
                                            <td>{{ $item->meta_description_ar }}</td>
                                        </tr>
                                        <tr>
                                            <th>الكلمات المفتاحية (بالإنجليزية):</th>
                                            <td>
                                                @foreach(json_decode($item->meta_keywords_en) as $keyword)
                                                    <span class="badge badge-primary">{{ $keyword->value }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>الكلمات المفتاحية (بالعربية):</th>
                                            <td>
                                                @foreach(json_decode($item->meta_keywords_ar) as $keyword)
                                                    <span class="badge badge-primary">{{ $keyword->value }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('admin.'.$modelName.'.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة</a>
                                <div>
                                    <a href="{{ route('admin.'.$modelName.'.edit', $item->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> تعديل</a>
                                    <form action="{{ route('admin.'.$modelName.'.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا البراند؟');"><i class="fas fa-trash-alt"></i> حذف</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
