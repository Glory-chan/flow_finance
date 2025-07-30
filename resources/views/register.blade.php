@extends('layouts.app')

@section('title', 'Inscription - FlowFinance')

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
                    <h2>Commencez votre voyage financier</h2>
                    <p>Rejoignez des milliers d'utilisateurs qui ont déjà transformé leur relation avec l'argent grâce à FlowFinance.</p>
                </div>
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Utilisateurs actifs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">€50M+</div>
                        <div class="stat-label">Transactions suivies</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.9/5</div>
                        <div class="stat-label">Note utilisateurs</div>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"FlowFinance a complètement changé ma façon de gérer mon budget. Je recommande vivement !"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">M</div>
                            <div class="author-info">
                                <div class="author-name">Marie Dubois</div>
                                <div class="author-role">Utilisatrice depuis 2 ans</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="auth-form-container">
            <div class="auth-form">
                <div class="form-header">
                    <h2>Créer votre compte</h2>
                    <p>Rejoignez FlowFinance gratuitement en quelques minutes</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="register-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Jean Dupont" 
                                   required 
                                   autofocus
                                   class="@error('name') error @enderror">
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

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
                                   placeholder="jean@example.com" 
                                   required
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
                                   placeholder="Minimum 8 caractères" 
                                   required
                                   class="@error('password') error @enderror">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
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
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Saisissez un mot de passe</div>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                            </svg>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirmer votre mot de passe" 
                                   required
                                   class="@error('password_confirmation') error @enderror">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <svg id="confirm-show" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                                <svg id="confirm-hide" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="display: none;">
                                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                                    <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" required class="@error('terms') error @enderror">
                            <span class="checkmark"></span>
                            J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> et la <a href="#" target="_blank">politique de confidentialité</a>
                        </label>
                        @error('terms')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="newsletter">
                            <span class="checkmark"></span>
                            Je souhaite recevoir des conseils financiers et les actualités FlowFinance
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        Créer mon compte gratuitement
                    </button>
                </form>

                <div class="divider">
                    <span>ou s'inscrire avec</span>
                </div>

                <!-- Social Login Buttons -->
                <div class="social-login">
                    <button type="button" class="social-btn google-btn" onclick="registerWithGoogle()">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>

                    <button type="button" class="social-btn microsoft-btn" onclick="registerWithMicrosoft()">
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
                    <p>Déjà un compte ? 
                        <a href="{{ route('login') }}">Se connecter</a>
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
        animation: slideInLeft 0.8s ease-out;
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

    .stats-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: transform 0.2s ease;
    }

    .stat-item:hover {
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .testimonial {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        backdrop-filter: blur(10px);
    }

    .testimonial-content p {
        font-size: 1.1rem;
        font-style: italic;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .author-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .author-role {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    /* Right Side - Form */
    .auth-form-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        background: white;
        overflow-y: auto;
    }

    .auth-form {
        width: 100%;
        max-width: 450px;
        animation: slideInRight 0.8s ease-out;
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

    .form-group.has-error input {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .form-group.has-success input {
        border-color: #10b981;
        background-color: #f0fdf4;
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

    .password-strength {
        margin-top: 0.5rem;
    }

    .strength-bar {
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .strength-fill.weak { 
        background: #ef4444; 
        width: 25%; 
    }

    .strength-fill.fair { 
        background: #f59e0b; 
        width: 50%; 
    }

    .strength-fill.good { 
        background: #3b82f6; 
        width: 75%; 
    }

    .strength-fill.strong { 
        background: #10b981; 
        width: 100%; 
    }

    .strength-text {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .strength-text.weak { color: #ef4444; }
    .strength-text.fair { color: #f59e0b; }
    .strength-text.good { color: #3b82f6; }
    .strength-text.strong { color: #10b981; }

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

    .checkbox-container {
        display: flex;
        align-items: flex-start;
        cursor: pointer;
        font-size: 0.875rem;
        color: #4b5563;
        gap: 0.75rem;
        line-height: 1.5;
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
        flex-shrink: 0;
        margin-top: 2px;
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

    .checkbox-container a {
        color: #10b981;
        text-decoration: none;
    }

    .checkbox-container a:hover {
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

    .social-btn:focus {
        outline: 2px solid #10b981;
        outline-offset: 2px;
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

        .stats-list {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .branding-text h2 {
            font-size: 1.5rem;
        }

        .testimonial {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .auth-form-container {
            padding: 1rem;
        }

        .form-header h2 {
            font-size: 1.5rem;
        }

        .form-group input {
            font-size: 16px; /* Prevents zoom on iOS */
        }

        .btn-full {
            padding: 1.25rem;
            font-size: 1.1rem;
        }

        .social-btn {
            padding: 1.25rem;
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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const showIcon = document.getElementById(fieldId === 'password' ? 'password-show' : 'confirm-show');
        const hideIcon = document.getElementById(fieldId === 'password' ? 'password-hide' : 'confirm-hide');
        
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

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        // Remove all strength classes
        strengthFill.className = 'strength-fill';
        strengthText.className = 'strength-text';
        
        if (password.length === 0) {
            strengthText.textContent = 'Saisissez un mot de passe';
            strengthText.style.color = '#6b7280';
        } else if (strength <= 2) {
            strengthFill.classList.add('weak');
            strengthText.classList.add('weak');
            strengthText.textContent = 'Mot de passe faible';
        } else if (strength <= 3) {
            strengthFill.classList.add('fair');
            strengthText.classList.add('fair');
            strengthText.textContent = 'Mot de passe moyen';
        } else if (strength <= 4) {
            strengthFill.classList.add('good');
            strengthText.classList.add('good');
            strengthText.textContent = 'Mot de passe bon';
        } else {
            strengthFill.classList.add('strong');
            strengthText.classList.add('strong');
            strengthText.textContent = 'Mot de passe excellent';
        }
    }

    // Social register functions
    function registerWithGoogle() {
        console.log('Register with Google');
        showNotification('Inscription avec Google bientôt disponible !', 'info');
    }

    function registerWithMicrosoft() {
        console.log('Register with Microsoft');
        showNotification('Inscription avec Microsoft bientôt disponible !', 'info');
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; font-size: 1.2rem; cursor: pointer; padding: 0 0 0 10px;">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.register-form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        
        // Password strength monitoring
        passwordField.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            validatePasswordMatch();
            
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                this.parentNode.parentNode.classList.remove('has-error');
                removeFieldError(this);
            }
        });

        // Password confirmation validation
        confirmPasswordField.addEventListener('input', function() {
            validatePasswordMatch();
            
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                this.parentNode.parentNode.classList.remove('has-error');
                removeFieldError(this);
            }
        });

        function validatePasswordMatch() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
            
            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordField.classList.add('error');
                confirmPasswordField.parentNode.parentNode.classList.add('has-error');
                showFieldError(confirmPasswordField, 'Les mots de passe ne correspondent pas');
            } else if (confirmPassword && password === confirmPassword) {
                confirmPasswordField.classList.remove('error');
                confirmPasswordField.parentNode.parentNode.classList.remove('has-error');
                confirmPasswordField.parentNode.parentNode.classList.add('has-success');
                removeFieldError(confirmPasswordField);
            }
        }

        // Real-time validation for all fields
        const fields = ['name', 'email', 'password', 'password_confirmation'];
        fields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                        this.parentNode.parentNode.classList.remove('has-error');
                        removeFieldError(this);
                    }
                    
                    if (this.value.trim() && !this.classList.contains('error')) {
                        this.parentNode.parentNode.classList.add('has-success');
                    }
                });

                // Email validation
                if (fieldName === 'email') {
                    field.addEventListener('blur', function() {
                        if (this.value && !isValidEmail(this.value)) {
                            this.classList.add('error');
                            this.parentNode.parentNode.classList.add('has-error');
                            this.parentNode.parentNode.classList.remove('has-success');
                            showFieldError(this, 'Adresse e-mail invalide');
                        }
                    });
                }
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(error => {
                if (!error.textContent.includes('{{')) {
                    error.remove();
                }
            });
            document.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
            document.querySelectorAll('.has-error').forEach(group => group.classList.remove('has-error'));

            // Validate required fields
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const termsAccepted = document.querySelector('input[name="terms"]').checked;

            if (!name) {
                showFieldError(document.getElementById('name'), 'Le nom complet est requis');
                isValid = false;
            } else if (name.length < 2) {
                showFieldError(document.getElementById('name'), 'Le nom doit contenir au moins 2 caractères');
                isValid = false;
            }

            if (!email) {
                showFieldError(document.getElementById('email'), 'L\'adresse e-mail est requise');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showFieldError(document.getElementById('email'), 'Adresse e-mail invalide');
                isValid = false;
            }

            if (!password) {
                showFieldError(document.getElementById('password'), 'Le mot de passe est requis');
                isValid = false;
            } else if (password.length < 8) {
                showFieldError(document.getElementById('password'), 'Le mot de passe doit contenir au moins 8 caractères');
                isValid = false;
            }

            if (!confirmPassword) {
                showFieldError(document.getElementById('password_confirmation'), 'Veuillez confirmer votre mot de passe');
                isValid = false;
            } else if (password !== confirmPassword) {
                showFieldError(document.getElementById('password_confirmation'), 'Les mots de passe ne correspondent pas');
                isValid = false;
            }

            if (!termsAccepted) {
                showFieldError(document.querySelector('input[name="terms"]').parentNode, 'Vous devez accepter les conditions d\'utilisation');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
                return;
            }

            // Show loading state
            if (!submitBtn.disabled) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 15000);
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showFieldError(field, message) {
            removeFieldError(field);
            const errorSpan = document.createElement('span');
            errorSpan.className = 'error-message';
            errorSpan.textContent = message;
            
            if (field.tagName === 'INPUT') {
                field.classList.add('error');
                field.parentNode.parentNode.classList.add('has-error');
                field.parentNode.parentNode.classList.remove('has-success');
                field.parentNode.parentNode.appendChild(errorSpan);
            } else {
                field.appendChild(errorSpan);
            }
        }

        function removeFieldError(field) {
            const parent = field.tagName === 'INPUT' ? field.parentNode.parentNode : field;
            const existingError = parent.querySelector('.error-message');
            if (existingError && !existingError.textContent.includes('{{')) {
                existingError.remove();
            }
        }

        // Initialize password strength
        checkPasswordStrength('');

        // Add floating label effect
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentNode.classList.remove('focused');
                }
            });

            if (input.value) {
                input.parentNode.classList.add('focused');
            }
        });
    });

    // Add notification styles
    if (!document.querySelector('style[data-notification-styles]')) {
        const notificationStyles = document.createElement('style');
        notificationStyles.setAttribute('data-notification-styles', 'true');
        notificationStyles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 400px;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                animation: slideInNotification 0.3s ease-out;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }

            .notification.success {
                background: #10b981;
                color: white;
            }

            .notification.error {
                background: #ef4444;
                color: white;
            }

            .notification.info {
                background: #3b82f6;
                color: white;
            }

            @keyframes slideInNotification {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @media (max-width: 768px) {
                .notification {
                    left: 20px;
                    right: 20px;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(notificationStyles);
    }
</script>
@endpush
@endsection