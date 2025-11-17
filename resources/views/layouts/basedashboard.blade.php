<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Dashboard - Sistema de Calificaciones')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- CSS adicionales y específicos -->
    @stack('CSS')
</head>
<body class="bg-light">
    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Sistema de Calificaciones
            </a>
            
            <!-- Toggle para móvil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menú de navegación (Solo para admin y profesores) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                @if(auth()->user()->tipo->tipo !== 'estudiante')
                    <ul class="navbar-nav me-auto">
                        @if(auth()->user()->tipo->tipo === 'admin' || auth()->user()->tipo->tipo === 'profesor')
                            <!-- Usuarios (Solo admin y profesores) -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" 
                                   href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-people-fill me-1"></i>
                                    Usuarios
                                </a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->tipo->tipo === 'admin')
                            <!-- Tipos de Usuario (Solo admin) -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tipos.*') ? 'active' : '' }}" 
                                   href="{{ route('tipos.index') }}">
                                    <i class="bi bi-person-badge-fill me-1"></i>
                                    Tipos de Usuario
                                </a>
                            </li>
                        @endif
                        
                        <!-- Materias (Admin y profesores) -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('materias.*') ? 'active' : '' }}" 
                               href="{{ route('materias.index') }}">
                                <i class="bi bi-book-fill me-1"></i>
                                Materias
                            </a>
                        </li>
                    </ul>
                @else
                    <!-- Espacio vacío para estudiantes -->
                    <div class="navbar-nav me-auto"></div>
                @endif
                
                <!-- Usuario y Logout -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->username }}
                            <span class="badge bg-{{ auth()->user()->tipo->tipo === 'admin' ? 'danger' : (auth()->user()->tipo->tipo === 'profesor' ? 'success' : 'info') }} ms-1">
                                {{ ucfirst(auth()->user()->tipo->tipo) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Información
                                </h6>
                            </li>
                            <li><span class="dropdown-item-text small">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(auth()->user()->tipo->tipo === 'estudiante')
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-book me-1"></i>
                                        Mis Materias
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="btnCerrarSesion">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Contenido Principal -->
    <main class="container-fluid py-4">
        @yield('contenido')
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-code-slash me-1"></i>
                Desarrollado por <strong>Melissa Méndez</strong> en <strong>UNICAES</strong>
            </p>
            <small class="text-muted">© {{ date('Y') }} - Todos los derechos reservados</small>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    
    <!-- JS adicionales y específicos -->
    @stack('JS')
    
    <!-- JavaScript que se ejecuta al cargar el DOM -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            }
        });
        $(document).ready(function(e){
            // Manejo del cierre de sesión
            $("#btnCerrarSesion").on("click", function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: "¿Cerrar sesión?",
                    text: "¿Estás seguro de que deseas salir del sistema?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, cerrar sesión",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Crear formulario para logout
                        const form = $("<form>", {
                            "method": "POST",
                            "action": "{{ route('logout') }}"
                        });
                        
                        form.append($("<input>", {
                            "type": "hidden",
                            "name": "_token",
                            "value": $('meta[name="csrf-token"]').attr("content")
                        }));
                        
                        $("body").append(form);
                        form.submit();
                    }
                });
            });
            
            @stack('JSOR')
        });
    </script>
</body>
</html>
