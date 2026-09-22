<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User info dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a73e8,#6200ee);
                     display:inline-flex;align-items:center;justify-content:center;
                     color:#fff;font-weight:700;font-size:13px;margin-right:8px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline text-sm font-weight-bold">
                    {{ auth()->user()->name }}
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                <div class="dropdown-header bg-light">
                    <div class="font-weight-bold text-dark" style="font-size:13px;">
                        {{ auth()->user()->name }}
                    </div>
                    <small class="text-muted">
                        @if(auth()->user()->role == 'administrator')
                            <i class="fas fa-user-shield mr-1"></i>Administrator
                        @else
                            <i class="fas fa-user mr-1"></i>User
                        @endif
                    </small>
                </div>
                <div class="dropdown-divider m-0"></div>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="button" class="dropdown-item text-danger" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>

<script>
function confirmLogout() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Keluar dari sistem?',
            text: 'Anda akan diarahkan ke halaman login.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    } else {
        document.getElementById('logoutForm').submit();
    }
}
</script>