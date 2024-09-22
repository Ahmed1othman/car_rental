@extends('layouts.admin_layout')

@section('title', ' عرض'. $modelName)

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                            <li class="breadcrumb-item active">{{$modelName}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{$modelName}}</h3>
                                <a href="{{ route('admin.'.$modelName.'.create') }}" class="btn btn-primary float-right">
                                    <i class="fas fa-plus"></i> إضافة {{$modelName}}
                                </a>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>الرقم</th>
                                        <th> اسم ال{{$modelName}} (بالإنجليزية)</th>
                                        <th> اسم ال{{$modelName}} (بالعربية)</th>
                                        <th>الشعار</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name_en }}</td>
                                            <td>{{ $item->name_ar }}</td>
                                            <td>
                                                @if($item->category_image)
                                                    <img src="{{ asset('storage/' . $item->category_image) }}"
                                                         alt="{{ $item->name_en }}" class="brand-logo" style="width: 100px;">
                                                @else
                                                    لا يوجد صورة
                                                @endif
                                            </td>
                                            <td>

                                                <a href="{{ route('admin.'.$modelName.'.show', $item->id) }}"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.'.$modelName.'.edit', $item->id) }}"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.'.$modelName.'.destroy', $item->id) }}"
                                                      method="POST"
                                                      class="delete-form"
                                                      style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $items->links() }} <!-- Pagination links -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Handle delete confirmation
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'هل أنت متأكد من الحذف؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، حذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form
                }
            })
        });
    </script>
@endpush
