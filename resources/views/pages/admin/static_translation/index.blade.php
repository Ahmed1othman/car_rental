@extends('layouts.admin_layout')

@section('title', 'Manage Static Translations')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Static Translations</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Static Translations</li>
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
                                <button type="button" class="btn btn-primary" onclick="table.addNewRow()">
                                    <i class="fas fa-plus"></i> Add New Row
                                </button>
                                <button type="button" class="btn btn-success ml-2" onclick="table.saveTranslations()">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="translation-table"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Include Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <style>
        .tabulator {
            border: 1px solid #dee2e6;
        }
        .tabulator-cell {
            padding: 8px !important;
        }
        .tabulator-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
    </style>

    <!-- Include Tabulator JS -->
    <script src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>

    <script>
        var table = {
            tabulatorInstance: null,
            activeLanguages: @json($activeLanguages),

            init: function() {
                const translations = @json($staticTranslations);

                // Transform data
                const translationMap = {};
                translations.forEach(translation => {
                    if (!translationMap[translation.key]) {
                        translationMap[translation.key] = {
                            id: translation.id,
                            key: translation.key,
                            section: translation.section,
                            created_at: translation.created_at,
                            updated_at: translation.updated_at
                        };
                    }
                    translationMap[translation.key][translation.locale] = translation.value;
                });

                const tableData = Object.values(translationMap);

                // Define columns
                const columns = [
                    { title: "Key", field: "key", editor: "input", minWidth: 150 },
                    { title: "Section", field: "section", editor: "input", minWidth: 120 },
                ];

                // Add language columns
                this.activeLanguages.forEach(lang => {
                    columns.push({
                        title: lang.name,
                        field: lang.code,
                        editor: "input",
                        minWidth: 150,
                    });
                });

                // Add timestamp columns
                columns.push(
                    { title: "Created At", field: "created_at", editor: false, minWidth: 130 },
                    { title: "Updated At", field: "updated_at", editor: false, minWidth: 130 }
                );

                // Initialize Tabulator
                this.tabulatorInstance = new Tabulator("#translation-table", {
                    data: tableData,
                    layout: "fitColumns",
                    columns: columns,
                    height: "500px",
                });
            },

            addNewRow: function() {
                const newRow = {
                    key: "",
                    section: "",
                };

                this.activeLanguages.forEach(lang => {
                    newRow[lang.code] = "";
                });

                this.tabulatorInstance.addRow(newRow, true);
            },

            saveTranslations: function() {
                const updatedData = this.tabulatorInstance.getData();
                const transformedData = [];

                updatedData.forEach(row => {
                    this.activeLanguages.forEach(lang => {
                        if (row.key && row.key.trim()) {
                            transformedData.push({
                                id: row.id || null,
                                key: row.key.trim(),
                                locale: lang.code,
                                value: row[lang.code] || "",
                                section: row.section || "",
                            });
                        }
                    });
                });

                fetch('{{ url("admin/static-translations/save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ translations: transformedData }),
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Translations saved successfully!');
                            location.reload();
                        } else {
                            alert('Error saving translations: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving translations. Please try again.');
                    });
            }
        };

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            table.init();
        });
    </script>
@endsection
