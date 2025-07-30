@extends('layouts.app')

@section('title', 'Connexion - FlowFinance')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="branding-content">
                <div class="logo-large">
                    <div class="logo-icon-large">FL</div>
                    <h1>FlowFinance</h1>
                </div>
                <div class="branding-text">
                    <h2>Reprenez le contrôle de vos finances</h2>
                    <p>Suivez vos dépenses, optimisez votre budget et atteignez vos objectifs financiers avec notre plateforme intuitive.</p>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">✅</div>
                        <span>Synchronisation bancaire sécurisée</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <span>Analyses détaillées en temps réel</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🎯</div>
                        <span>Objectifs d'épargne personnalisés</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <span>Sécurité de niveau bancaire</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="auth-form-container">
            <div class="auth-form">
                <div class="form-header">
                    <h2>Bon retour !</h2>
                    <p>Connectez-vous à votre compte FlowFinance</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l1.326-.795 5.64 3.47A1 1 0 0 1 14 13h-2.5a.5.5 0 0 0 0 1H14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                            </svg>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="votre@email.com" 
                                   required 
                                   autofocus
                                   class="@error('email') error @enderror">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                            </svg>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Votre mot de passe" 
                                   required
                                   class="@error('password') error @enderror">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg id="password-show" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                                <svg id="password-hide" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="display: none;">
                                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                                    <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Se souvenir de moi
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        Se connecter
                    </button>
                </form>

                <div class="divider">
                    <span>ou continuer avec</span>
                </div>

                <!-- Social Login Buttons -->
                <div class="social-login">
                    <button type="button" class="social-btn google-btn" onclick="loginWithGoogle()">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>

                    <button type="button" class="social-btn microsoft-btn" onclick="loginWithMicrosoft()">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#f25022" d="M1 1h10v10H1z"/>
                            <path fill="#00a4ef" d="M13 1h10v10H13z"/>
                            <path fill="#7fba00" d="M1 13h10v10H1z"/>
                            <path fill="#ffb900" d="M13 13h10v10H13z"/>
                        </svg>
                        Microsoft
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Pas encore de compte ? 
                        <a href="{{ route('register') }}">Créer un compte gratuitement</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .auth-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
    }

    /* Left Side - Branding */
    .auth-branding {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        color: white;
    }

    .branding-content {
        max-width: 500px;
    }

    .logo-large {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .logo-icon-large {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
        backdrop-filter: blur(10px);
    }

    .logo-large h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .branding-text {
        margin-bottom: 3rem;
    }

    .branding-text h2 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .branding-text p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .features-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .feature-icon {
        font-size: 1.5rem;
    }

    /* Right Side - Form */
    .auth-form-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        background: white;
    }

    .auth-form {
        width: 100%;
        max-width: 450px;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .form-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: #718096;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 1;
    }

    .form-group input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-group input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-group input.error {
        border-color: #ef4444;
    }

    .form-group input.error:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: #6b7280;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message::before {
        content: '⚠️';
        font-size: 0.75rem;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 0.875rem;
        color: #4b5563;
        gap: 0.5rem;
    }

    .checkbox-container input {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        position: relative;
        transition: all 0.2s ease;
    }

    .checkbox-container input:checked + .checkmark {
        background: #10b981;
        border-color: #10b981;
    }

    .checkbox-container input:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .forgot-password {
        color: #10b981;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .forgot-password:hover {
        color: #059669;
        text-decoration: underline;
    }

    .btn-full {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        border-radius: 12px;
    }

    .divider {
        text-align: center;
        margin: 2rem 0;
        position: relative;
        color: #9ca3af;
        font-size: 0.875rem;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e5e7eb;
    }

    .divider span {
        background: white;
        padding: 0 1rem;
        position: relative;
    }

    .social-login {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        transition: all 0.2s ease;
    }

    .social-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        transform: translateY(-1px);
    }

    .google-btn:hover {
        border-color: #4285f4;
        background: #f8f9ff;
    }

    .microsoft-btn:hover {
        border-color: #0078d4;
        background: #f8f9ff;
    }

    .auth-footer {
        text-align: center;
        margin-top: 2rem;
    }

    .auth-footer p {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .auth-footer a {
        color: #10b981;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .auth-footer a:hover {
        color: #059669;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .auth-container {
            grid-template-columns: 1fr;
        }

        .auth-branding {
            display: none;
        }

        .auth-form-container {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .auth-form-container {
            padding: 1.5rem;
        }

        .form-header h2 {
            font-size: 1.75rem;
        }

        .social-login {
            grid-template-columns: 1fr;
        }

        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    /* Loading state */
    .btn.loading {
        position: relative;
        color: transparent;
        pointer-events: none;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const showIcon = document.getElementById('password-show');
        const hideIcon = document.getElementById('password-hide');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            showIcon.style.display = 'none';
            hideIcon.style.display = 'block';
        } else {
            passwordField.type = 'password';
            showIcon.style.display = 'block';
            hideIcon.style.display = 'none';
        }
    }

    // Social login functions
    function loginWithGoogle() {
        console.log('Login with Google');
        // window.location.href = '/auth/google';
    }

    function loginWithMicrosoft() {
        console.log('Login with Microsoft');
        // window.location.href = '/auth/microsoft';
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.login-form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            if (!submitBtn.disabled) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Reset after timeout (safety measure)
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 10000);
            }
        });

        // Real-time validation
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');

        emailField.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                const errorMsg = this.parentNode.parentNode.querySelector('.error-message');
                if (errorMsg && !errorMsg.textContent.includes('{{ __("") }}')) {
                    errorMsg.remove();
                }
            }
        });

        passwordField.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                const errorMsg = this.parentNode.parentNode.querySelector('.error-message');
                if (errorMsg && !errorMsg.textContent.includes('{{ __("") }}')) {
                    errorMsg.remove();
                }
            }
        });
    });
</script>
@endpush
@endsection