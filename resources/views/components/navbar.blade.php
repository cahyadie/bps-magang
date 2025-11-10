<nav
    style="background-color: #2a2a2a; border-bottom: 1px solid #3a3a3a; padding: 1rem 0; position: sticky; top: 0; z-index: 50;">
    <div
        style="max-width: 1280px; margin: 0 auto; padding: 0 1rem; display: flex; justify-content: space-between; align-items: center;">
        <!-- Logo/Brand -->
        <a href="{{ route('magang.index') }}"
            style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
            <div
                style="width: 40px; height: 40px; background: linear-gradient(135deg, #d97757 0%, #e88968 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="color: white; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h1 style="color: white; font-size: 1.25rem; font-weight: 600; margin: 0;">Data Magang</h1>
                <p style="color: #9ca3af; font-size: 0.75rem; margin: 0;">BPS Bantul</p>
            </div>
        </a>

        <!-- User Info & Actions -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            <!-- User Info -->
            <div style="text-align: right; margin-right: 0.5rem;">
                <p style="color: white; font-size: 0.875rem; font-weight: 500; margin: 0;">
                    {{ auth()->user()->name }}
                </p>
                <div
                    style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; margin-top: 0.25rem;">
                    @if(auth()->user()->isAdmin())
                        <span
                            style="background-color: rgba(220, 38, 38, 0.2); color: #f87171; padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                            <i class="fas fa-crown" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>ADMIN
                        </span>
                    @else
                        <span
                            style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                            <i class="fas fa-user" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>USER
                        </span>
                    @endif
                </div>
            </div>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit"
                    style="background-color: #dc2626; color: white; padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    form button:hover {
        background-color: #b91c1c !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
</style>