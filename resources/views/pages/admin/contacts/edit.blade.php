@extends('layouts.admin_layout')

@section('title', 'Contacts Settings')

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Contact Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Update Contacts</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Oops! We found some issues:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Contact Update Form -->
                <form action="{{ route('admin.contacts.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="card card-info shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ([
                                    ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $contact->name],
                                    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $contact->email],
                                    ['label' => 'Phone', 'name' => 'phone', 'type' => 'text', 'icon' => 'fas fa-phone', 'value' => $contact->phone],
                                    ['label' => 'Alternative Phone', 'name' => 'alternative_phone', 'type' => 'text', 'icon' => 'fas fa-phone-alt', 'value' => $contact->alternative_phone]
                                ] as $field)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="{{ $field['name'] }}"><i class="{{ $field['icon'] }} mr-1"></i> {{ $field['label'] }}</label>
                                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control" value="{{ $field['value'] }}" required>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="card card-primary shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i> Address Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ([
                                    ['label' => 'Address Line 1', 'name' => 'address_line1', 'type' => 'text', 'icon' => 'fas fa-home', 'value' => $contact->address_line1],
                                    ['label' => 'Address Line 2', 'name' => 'address_line2', 'type' => 'text', 'icon' => 'fas fa-map-pin', 'value' => $contact->address_line2],
                                    ['label' => 'City', 'name' => 'city', 'type' => 'text', 'icon' => 'fas fa-city', 'value' => $contact->city],
                                    ['label' => 'Country', 'name' => 'country', 'type' => 'text', 'icon' => 'fas fa-globe', 'value' => $contact->country],
                                    ['label' => 'State', 'name' => 'state', 'type' => 'text', 'icon' => 'fas fa-flag', 'value' => $contact->state],
                                    ['label' => 'Postal Code', 'name' => 'postal_code', 'type' => 'text', 'icon' => 'fas fa-mail-bulk', 'value' => $contact->postal_code]
                                ] as $field)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="{{ $field['name'] }}"><i class="{{ $field['icon'] }} mr-1"></i> {{ $field['label'] }}</label>
                                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control" value="{{ $field['value'] }}" required>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Section -->
                    <div class="card card-success shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-share-alt mr-2"></i> Social Media Links</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach (['facebook' => 'fab fa-facebook', 'twitter' => 'fab fa-twitter', 'instagram' => 'fab fa-instagram', 'linkedin' => 'fab fa-linkedin', 'youtube' => 'fab fa-youtube', 'whatsapp' => 'fab fa-whatsapp', 'tiktok' => 'fab fa-tiktok', 'snapchat' => 'fab fa-snapchat'] as $social => $icon)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="{{ $social }}"><i class="{{ $icon }} mr-1"></i> {{ ucfirst($social) }}</label>
                                            <input type="text" name="{{ $social }}" class="form-control" value="{{ $contact->$social }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Other Information Section -->
                    <div class="card card-secondary shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info mr-2"></i> Other Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ([
                                    ['label' => 'Website', 'name' => 'website', 'type' => 'url', 'icon' => 'fas fa-globe', 'value' => $contact->website],
                                    ['label' => 'Google Map URL', 'name' => 'google_map_url', 'type' => 'text', 'icon' => 'fas fa-map-marked-alt', 'value' => $contact->google_map_url],
                                    ['label' => 'Contact Person', 'name' => 'contact_person', 'type' => 'text', 'icon' => 'fas fa-user-tie', 'value' => $contact->contact_person],
                                    ['label' => 'Additional Information', 'name' => 'additional_info', 'type' => 'textarea', 'icon' => 'fas fa-info-circle', 'value' => $contact->additional_info]
                                ] as $field)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="{{ $field['name'] }}"><i class="{{ $field['icon'] }} mr-1"></i> {{ $field['label'] }}</label>
                                            @if($field['type'] === 'textarea')
                                                <textarea name="{{ $field['name'] }}" class="form-control">{{ $field['value'] }}</textarea>
                                            @else
                                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control" value="{{ $field['value'] }}">
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

{{--                    <!-- Status Section -->--}}
{{--                    <div class="card card-dark shadow-sm mb-4">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title"><i class="fas fa-toggle-on mr-2"></i> Status</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="is_active"><i class="fas fa-toggle-on mr-1"></i> Active Status</label>--}}
{{--                                <select name="is_active" class="form-control">--}}
{{--                                    <option value="1" {{ $contact->is_active ? 'selected' : '' }}>Active</option>--}}
{{--                                    <option value="0" {{ !$contact->is_active ? 'selected' : '' }}>Inactive</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <!-- Submit Button -->
                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-lg btn-block mb-3"><i class="fas fa-save mr-2"></i> Update Contact Information</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
