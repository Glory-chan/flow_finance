@extends('layouts.app')

@section('title', 'Paramètres - FlowFinance')

@section('content')
<div class="settings-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>Paramètres</h1>
                    <p>Gérez votre profil, sécurité et préférences</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                        Exporter mes données
                    </button>
                    <button class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                        Sauvegarder
                    </button>
                </div>
            </div>
        </div>

        <div class="settings-layout">
            <!-- Settings Navigation -->
            <div class="settings-nav">
                <div class="nav-section">
                    <h3>Compte</h3>
                    <ul class="nav-list">
                        <li><a href="#profile" class="nav-link active" data-section="profile">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                            Profil
                        </a></li>
                        <li><a href="#security" class="nav-link" data-section="security">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                            </svg>
                            Sécurité
                        </a></li>
                        <li><a href="#notifications" class="nav-link" data-section="notifications">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            Notifications
                        </a></li>
                        <li><a href="#accounts" class="nav-link" data-section="accounts">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                            </svg>
                            Comptes bancaires
                        </a></li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Préférences</h3>
                    <ul class="nav-list">
                        <li><a href="#preferences" class="nav-link" data-section="preferences">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                            </svg>
                            Général
                        </a></li>
                        <li><a href="#billing" class="nav-link" data-section="billing">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>
                                <path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>
                            </svg>
                            Facturation
                        </a></li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Données</h3>
                    <ul class="nav-list">
                        <li><a href="#data" class="nav-link" data-section="data">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                            </svg>
                            Export & Données
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">
                <!-- Profile Section -->
                <div id="profile-section" class="settings-section active">
                    <div class="section-header">
                        <h2>Informations personnelles</h2>
                        <p>Gérez vos informations de profil et préférences personnelles</p>
                    </div>

                    <form class="settings-form">
                        <div class="form-section">
                            <h3>Photo de profil</h3>
                            <div class="avatar-section">
                                <div class="current-avatar">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="avatar-img">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ auth()->user()->initials ?? strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="avatar-actions">
                                    <button type="button" class="btn btn-secondary">Modifier la photo</button>
                                    <button type="button" class="btn btn-outline">Supprimer</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Informations de base</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Nom complet</label>
                                    <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Adresse e-mail</label>
                                    <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Téléphone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="date_of_birth">Date de naissance</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ auth()->user()->date_of_birth?->format('Y-m-d') ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Adresse</h3>
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label for="address">Adresse</label>
                                    <input type="text" id="address" name="address" value="{{ auth()->user()->address ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="city">Ville</label>
                                    <input type="text" id="city" name="city" value="{{ auth()->user()->city ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="postal_code">Code postal</label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ auth()->user()->postal_code ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="country">Pays</label>
                                    <select id="country" name="country">
                                        <option value="France" {{ (auth()->user()->country ?? '') === 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Belgique" {{ (auth()->user()->country ?? '') === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                        <option value="Suisse" {{ (auth()->user()->country ?? '') === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                        <option value="Canada" {{ (auth()->user()->country ?? '') === 'Canada' ? 'selected' : '' }}>Canada</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Préférences</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="currency">Devise</label>
                                    <select id="currency" name="currency">
                                        <option value="EUR" {{ (auth()->user()->currency ?? 'EUR') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="USD" {{ (auth()->user()->currency ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="GBP" {{ (auth()->user()->currency ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                        <option value="CHF" {{ (auth()->user()->currency ?? '') === 'CHF' ? 'selected' : '' }}>CHF</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="timezone">Fuseau horaire</label>
                                    <select id="timezone" name="timezone">
                                        <option value="Europe/Paris" {{ (auth()->user()->timezone ?? 'Europe/Paris') === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                        <option value="Europe/London" {{ (auth()->user()->timezone ?? '') === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="America/New_York" {{ (auth()->user()->timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="America/Montreal" {{ (auth()->user()->timezone ?? '') === 'America/Montreal' ? 'selected' : '' }}>America/Montreal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="language">Langue</label>
                                    <select id="language" name="language">
                                        <option value="fr" {{ (auth()->user()->language ?? 'fr') === 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ (auth()->user()->language ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ (auth()->user()->language ?? '') === 'es' ? 'selected' : '' }}>Español</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary">Annuler</button>
                            <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                        </div>
                    </form>
                </div>

                <!-- Security Section -->
                <div id="security-section" class="settings-section">
                    <div class="section-header">
                        <h2>Sécurité</h2>
                        <p>Gérez votre mot de passe et les paramètres de sécurité</p>
                    </div>

                    <div class="security-overview">
                        <div class="security-status">
                            <div class="status-indicator good">
                                <span class="status-dot"></span>
                                <div class="status-info">
                                    <h4>Sécurité du compte</h4>
                                    <p>Votre compte est bien sécurisé</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Modifier le mot de passe</h3>
                        <form class="password-form">
                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Nouveau mot de passe</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
                        </form>
                    </div>

                    <div class="form-section">
                        <h3>Authentification à deux facteurs</h3>
                        <div class="two-factor-section">
                            <div class="two-factor-status">
                                @if(auth()->user()->two_factor_enabled ?? false)
                                    <div class="status-enabled">
                                        <span class="status-icon">✅</span>
                                        <div>
                                            <h4>Authentification à deux facteurs activée</h4>
                                            <p>Votre compte est protégé par l'authentification à deux facteurs</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary">Désactiver</button>
                                @else
                                    <div class="status-disabled">
                                        <span class="status-icon">⚠️</span>
                                        <div>
                                            <h4>Authentification à deux facteurs désactivée</h4>
                                            <p>Ajoutez une couche de sécurité supplémentaire à votre compte</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary">Activer</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Sessions actives</h3>
                        <div class="sessions-list">
                            <div class="session-item current">
                                <div class="session-info">
                                    <div class="session-device">
                                        <span class="device-icon">💻</span>
                                        <div>
                                            <h4>Windows - Chrome</h4>
                                            <p>Session actuelle • Paris, France</p>
                                        </div>
                                    </div>
                                    <div class="session-time">Maintenant</div>
                                </div>
                            </div>
                            <div class="session-item">
                                <div class="session-info">
                                    <div class="session-device">
                                        <span class="device-icon">📱</span>
                                        <div>
                                            <h4>iPhone - Safari</h4>
                                            <p>Lyon, France</p>
                                        </div>
                                    </div>
                                    <div class="session-time">Il y a 2 heures</div>
                                </div>
                                <button class="btn btn-outline btn-sm">Déconnecter</button>
                            </div>
                        </div>
                        <button class="btn btn-secondary">Déconnecter toutes les autres sessions</button>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div id="notifications-section" class="settings-section">
                    <div class="section-header">
                        <h2>Notifications</h2>
                        <p>Choisissez quand et comment vous souhaitez être notifié</p>
                    </div>

                    <div class="form-section">
                        <h3>Notifications par e-mail</h3>
                        <div class="notification-options">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Alertes de budget</h4>
                                    <p>Recevez des alertes quand vous approchez de vos limites de budget</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Nouvelles transactions</h4>
                                    <p>Soyez notifié de chaque nouvelle transaction</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Rapports mensuels</h4>
                                    <p>Recevez un résumé mensuel de vos finances</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Conseils et astuces</h4>
                                    <p>Recevez des conseils pour améliorer votre gestion financière</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Notifications push</h3>
                        <div class="notification-options">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Transactions importantes</h4>
                                    <p>Notifications push pour les transactions importantes</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h4>Objectifs d'épargne</h4>
                                    <p>Notifications de progression de vos objectifs</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other sections placeholder -->
                <div id="accounts-section" class="settings-section">
                    <div class="section-header">
                        <h2>Comptes bancaires</h2>
                        <p>Gérez vos comptes bancaires connectés</p>
                    </div>
                    <div class="coming-soon">
                        <h3>🚧 Fonctionnalité à venir</h3>
                        <p>La gestion des comptes bancaires sera disponible prochainement.</p>
                    </div>
                </div>

                <div id="preferences-section" class="settings-section">
                    <div class="section-header">
                        <h2>Préférences générales</h2>
                        <p>Personnalisez votre expérience FlowFinance</p>
                    </div>
                    <div class="coming-soon">
                        <h3>🎨 Préférences</h3>
                        <p>Les options de personnalisation seront disponibles prochainement.</p>
                    </div>
                </div>

                <div id="billing-section" class="settings-section">
                    <div class="section-header">
                        <h2>Facturation et abonnement</h2>
                        <p>Gérez votre abonnement et vos moyens de paiement</p>
                    </div>
                    <div class="subscription-status">
                        <div class="current-plan">
                            <h3>Plan actuel: {{ ucfirst(auth()->user()->subscription_type ?? 'Free') }}</h3>
                            @if((auth()->user()->subscription_type ?? 'free') === 'premium')
                                <p class="plan-status success">✅ Abonnement Premium actif</p>
                            @else
                                <p class="plan-status">📦 Abonnement gratuit</p>
                                <button class="btn btn-primary">Passer au Premium</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="data-section" class="settings-section">
                    <div class="section-header">
                        <h2>Export et données</h2>
                        <p>Exportez vos données ou supprimez votre compte</p>
                    </div>

                    <div class="form-section">
                        <h3>Exporter mes données</h3>
                        <p>Téléchargez une copie de toutes vos données FlowFinance</p>
                        <div class="export-options">
                            <div class="export-item">
                                <div class="export-info">
                                    <h4>📊 Données complètes</h4>
                                    <p>Toutes vos transactions, budgets, objectifs et paramètres</p>
                                </div>
                                <button class="btn btn-secondary">Exporter (JSON)</button>
                            </div>
                            <div class="export-item">
                                <div class="export-info">
                                    <h4>📈 Transactions uniquement</h4>
                                    <p>Export CSV de toutes vos transactions</p>
                                </div>
                                <button class="btn btn-secondary">Exporter (CSV)</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-section danger-zone">
                        <h3>Zone de danger</h3>
                        <div class="danger-actions">
                            <div class="danger-item">
                                <div class="danger-info">
                                    <h4>Supprimer mon compte</h4>
                                    <p>Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>
                                </div>
                                <button class="btn btn-danger" onclick="confirmDeleteAccount()">Supprimer le compte</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>⚠️ Supprimer le compte</h2>
            <button class="modal-close" onclick="hideDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="delete-warning">
                <p><strong>Cette action est irréversible !</strong></p>
                <p>En supprimant votre compte, vous perdrez :</p>
                <ul>
                    <li>Toutes vos transactions</li>
                    <li>Vos budgets et objectifs</li>
                    <li>Vos préférences et paramètres</li>
                    <li>Vos rapports et analyses</li>
                </ul>
                <p>Tapez <strong>SUPPRIMER</strong> pour confirmer :</p>
                <input type="text" id="deleteConfirmation" placeholder="Tapez SUPPRIMER">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled onclick="deleteAccount()">Supprimer définitivement</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .settings-page {
        padding: 2rem 0;
        background: #6b7280;
    }

    .page-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .header-info h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .header-info p {
        color: #718096;
        font-size: 1.1rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    /* Settings Layout */
    .settings-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
    }

    /* Settings Navigation */
    .settings-nav {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        height: fit-content;
        position: sticky;
        top: 120px;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-section:last-child {
        margin-bottom: 0;
    }

    .nav-section h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #718096;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #4a5568;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
    }

    .nav-link:hover {
        background: #f7fafc;
        color: #2d3748;
    }

    .nav-link.active {
        background: #10b981;
        color: white;
    }

    .nav-link svg {
        flex-shrink: 0;
    }

    /* Settings Content */
    .settings-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .settings-section {
        display: none;
        padding: 2rem;
    }

    .settings-section.active {
        display: block;
    }

    .section-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .section-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .section-header p {
        color: #718096;
        font-size: 1rem;
    }

    /* Form Styles */
    .settings-form {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .form-section {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 2rem;
    }

    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .form-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .form-group input,
    .form-group select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Avatar Section */
    .avatar-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .current-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e2e8f0;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .avatar-actions {
        display: flex;
        gap: 1rem;
    }

    /* Security Section */
    .security-overview {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .security-status {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #10b981;
    }

    .status-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .status-info p {
        color: #16a34a;
        font-size: 0.875rem;
    }

    /* Two Factor Authentication */
    .two-factor-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
    }

    .two-factor-status {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-enabled,
    .status-disabled {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-icon {
        font-size: 1.5rem;
    }

    .status-enabled h4 {
        color: #16a34a;
    }

    .status-disabled h4 {
        color: #dc2626;
    }

    /* Sessions */
    .sessions-list {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .session-item:last-child {
        border-bottom: none;
    }

    .session-item.current {
        background: #f0fdf4;
    }

    .session-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex: 1;
        margin-right: 1rem;
    }

    .session-device {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .device-icon {
        font-size: 1.25rem;
    }

    .session-device h4 {
        font-size: 1rem;
        font-weight: 500;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .session-device p {
        font-size: 0.875rem;
        color: #718096;
    }

    .session-time {
        font-size: 0.875rem;
        color: #718096;
    }

    /* Notifications */
    .notification-options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }

    .notification-info h4 {
        font-size: 1rem;
        font-weight: 500;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .notification-info p {
        font-size: 0.875rem;
        color: #718096;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #10b981;
    }

    input:focus + .toggle-slider {
        box-shadow: 0 0 1px #10b981;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Coming Soon */
    .coming-soon {
        text-align: center;
        padding: 3rem 2rem;
        color: #718096;
    }

    .coming-soon h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Subscription Status */
    .subscription-status {
        background: #f8fafc;
        border-radius: 12px;
        padding: 2rem;
    }

    .current-plan h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .plan-status {
        margin-bottom: 1rem;
    }

    .plan-status.success {
        color: #16a34a;
    }

    /* Export Options */
    .export-options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1rem;
    }

    .export-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }

    .export-info h4 {
        font-size: 1rem;
        font-weight: 500;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .export-info p {
        font-size: 0.875rem;
        color: #718096;
    }

    /* Danger Zone */
    .danger-zone {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .danger-zone h3 {
        color: #dc2626;
    }

    .danger-actions {
        margin-top: 1rem;
    }

    .danger-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .danger-info h4 {
        color: #dc2626;
        margin-bottom: 0.25rem;
    }

    .danger-info p {
        color: #991b1b;
        font-size: 0.875rem;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
        border: none;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 2rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .modal-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #dc2626;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #718096;
        cursor: pointer;
        padding: 0.5rem;
        line-height: 1;
    }

    .modal-body {
        padding: 2rem;
    }

    .delete-warning {
        margin-bottom: 2rem;
    }

    .delete-warning p {
        margin-bottom: 1rem;
    }

    .delete-warning ul {
        margin: 1rem 0;
        padding-left: 1.5rem;
        color: #dc2626;
    }

    .delete-warning input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #dc2626;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .settings-layout {
            grid-template-columns: 1fr;
        }

        .settings-nav {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            justify-content: stretch;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .avatar-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .two-factor-section,
        .export-item,
        .danger-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .session-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation entre les sections
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.settings-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links and sections
                navLinks.forEach(l => l.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding section
                const sectionId = this.getAttribute('data-section') + '-section';
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            });
        });

        // Gestion du formulaire de profil
        const profileForm = document.querySelector('.settings-form');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Profil mis à jour avec succès !');
            });
        }

        // Gestion du changement de mot de passe
        const passwordForm = document.querySelector('.password-form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (newPassword !== confirmPassword) {
                    alert('Les mots de passe ne correspondent pas !');
                    return;
                }
                
                alert('Mot de passe modifié avec succès !');
                this.reset();
            });
        }

        // Confirmation de suppression de compte
        const deleteConfirmationInput = document.getElementById('deleteConfirmation');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        if (deleteConfirmationInput && confirmDeleteBtn) {
            deleteConfirmationInput.addEventListener('input', function() {
                if (this.value === 'SUPPRIMER') {
                    confirmDeleteBtn.disabled = false;
                } else {
                    confirmDeleteBtn.disabled = true;
                }
            });
        }
    });

    // Fonctions pour le modal de suppression de compte
    function confirmDeleteAccount() {
        document.getElementById('deleteAccountModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function hideDeleteModal() {
        document.getElementById('deleteAccountModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('deleteConfirmation').value = '';
        document.getElementById('confirmDeleteBtn').disabled = true;
    }

    function deleteAccount() {
        alert('Fonctionnalité de suppression de compte non implémentée pour des raisons de sécurité.');
        hideDeleteModal();
    }

    // Fermer le modal en cliquant à l'extérieur
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('deleteAccountModal');
        if (e.target === modal) {
            hideDeleteModal();
        }
    });
</script>
@endpush