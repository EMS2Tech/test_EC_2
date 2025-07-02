<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-dark'>
                    <span class="logo-sm">
                        <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}" alt="" height="40">
                    </span>
                </a>
            </div><br><br>

            <ul id="side-menu">
                @php
                    $user = auth()->user();
                    $applicationCompleted = $user ? \App\Models\Application::where('user_id', $user->id)->value('application_completed') : 0;
                @endphp

                <li>
                    <a href="{{ $applicationCompleted ? route('profile.edit') : '#' }}"
                       @if(!$applicationCompleted) class="disabled-link text-muted" @endif>
                        <i data-feather="user"></i>
                        <span> Profile </span>
                    </a>
                    <div class="collapse" id="Profile"> </div>
                </li>

                <li>
                    <a href="{{ !$applicationCompleted ? route('user.dashboard') : '#' }}"
                       @if($applicationCompleted) class="disabled-link text-muted" @endif>
                        <i data-feather="file-text"></i>
                        <span> Application </span>
                    </a>
                    <div class="collapse" id="Application"> </div>
                </li>

                <li>
                    <a href="{{ $applicationCompleted ? route('payment.verify') : '#' }}"
                       @if(!$applicationCompleted) class="disabled-link text-muted" @endif>
                        <i data-feather="dollar-sign"></i>
                        <span> Payments </span>
                    </a>
                    <div class="collapse" id="Payments"> </div>
                </li>

                <li>
                    <a href="{{ $applicationCompleted ? route('course-application.create') : '#' }}"
                       @if(!$applicationCompleted) class="disabled-link text-muted" @endif>
                        <i data-feather="book"></i>
                        <span> Courses </span>
                    </a>
                    <div class="collapse" id="Courses"> </div>
                </li>

            </ul>
        
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>

<style>
    .disabled-link {
        pointer-events: none;
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>