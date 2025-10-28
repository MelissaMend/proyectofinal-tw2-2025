@extends('layouts.basedashboard')

@section('titulo', 'Tipos de Usuarios')

@push('CSS')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="bi bi-people-fill me-2"></i>
                        Tipos de Usuarios
                    </h2>
                    <p class="text-muted mb-0">Gestiona los tipos de usuarios del sistema</p>
                </div>
                <div>
                    <a href="{{ route('tipos.create') }}" class="btn btn-success">
                        <i class="bi bi-person-plus-fill me-1"></i>
                        Nuevo Tipo
                    </a>
                </div>
            </div>

            <!-- Tabla de Tipos -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaTipos">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Usuarios Asiganados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tipos as $tipos)
                                <tr>
                                    <td>{{ $tipos->id }}</td>
                                    <td>{{ $tipos->tipo }}</td>
                                    <td>{{-- $tipo->email --}}</td>
                                    

                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Botón Editar -->
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="editarTipo({{ $tipos->id }})"
                                                    title="Editar tipo">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            <!-- Botón Eliminar -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarTipo({{ $tipos->id }}, '{{ $tipos->tipos }}')"
                                                    title="Eliminar tipo">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('JS')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    function editarTipo(id){
        // Redirigir a la página de edición
        window.location.href = `{{ route('tipos.index') }}/${id}/edit`;
    }
    
    function eliminarTipo(id, tipo){
        Swal.fire({
            title: "¿Eliminar tipo?",
            text: `¿Estás seguro de eliminar al el tipo "${tipo}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear formulario para eliminar
                const form = $("<form>", {
                    "method": "POST",
                    "action": `{{ route('tipos.index') }}/${id}`
                });
                
                form.append($("<input>", {
                    "type": "hidden",
                    "name": "_token",
                    "value": $('meta[name="csrf-token"]').attr("content")
                }));
                
                form.append($("<input>", {
                    "type": "hidden",
                    "name": "_method",
                    "value": "DELETE"
                }));
                
                $("body").append(form);
                form.submit();
            }
        });
    }

</script>
@endpush

@push('JSOR')
    // Inicializar DataTables
    $("#tablaTipos").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[0, "asc"]],
        columnDefs: [
            {
                targets: [3], // Columna de acciones
                orderable: false,
                searchable: false
            }
        ]
    });
    
    console.log("DataTables inicializado correctamente");
@endpush