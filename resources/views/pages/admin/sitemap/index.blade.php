@extends('layouts.admin_layout')

@section('title', 'Mange Sitemap')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><Man></Man></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Manage Sitemap</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Translation Management</h3>
                            <div>
                                <button id="generateSitemap" class="btn btn-primary">Generate Sitemap</button>
                                <a href="{{ route('admin.sitemap.download') }}" class="btn btn-success">Download Sitemap</a>
                                <button id="notifySearchEngines" class="btn btn-warning">Notify Google & Bing</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.getElementById('generateSitemap').addEventListener('click', function() {
            fetch("{{ route('admin.sitemap.generate') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => alert("Error generating sitemap!"));
        });

        document.getElementById('notifySearchEngines').addEventListener('click', function() {
            fetch("{{ route('admin.sitemap.notify') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => alert("Error notifying search engines!"));
        });
    </script>
@endsection
