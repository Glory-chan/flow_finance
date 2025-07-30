@extends('layouts.app')

@section('title', 'Tableau de Bord - FlowFinance')

@section('content')
<div class="dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="greeting">
                    <h1>Bonjour, {{ auth()->user()->name ?? 'Robert' }} 👋</h1>
                    <p>Voici un aperçu de vos finances du {{ date('d M Y') }}</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Ajouter une transaction
                    </button>
                    <button class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                        Générer un rapport
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card balance-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Solde Total</h3>
                        <div class="stat-meta">Tous comptes confondus</div>
                    </div>
                    <div class="stat-icon balance">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">€4,790.05</div>
                <div class="stat-change positive">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                    </svg>
                    +2.5% ce mois
                </div>
            </div>

            <div class="stat-card income-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Revenus</h3>
                        <div class="stat-meta">Ce mois-ci</div>
                    </div>
                    <div class="stat-icon income">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">€3,240.00</div>
                <div class="stat-change positive">+12.3% vs mois dernier</div>
            </div>

            <div class="stat-card expenses-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Dépenses</h3>
                        <div class="stat-meta">Ce mois-ci</div>
                    </div>
                    <div class="stat-icon expenses">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">€2,185.50</div>
                <div class="stat-change negative">+8.1% vs mois dernier</div>
            </div>

            <div class="stat-card savings-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Épargne</h3>
                        <div class="stat-meta">Objectif: €500</div>
                    </div>
                    <div class="stat-icon savings">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zM1 5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5H1z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">€1,054.50</div>
                <div class="stat-change positive">210% de l'objectif</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Transactions -->
            <div class="dashboard-card transactions-card">
                <div class="card-header">
                    <h2>Transactions récentes</h2>
                    <a href="#" class="view-all">Voir tout</a>
                </div>
                <div class="transactions-list">
                    <div class="transaction-item">
                        <div class="transaction-icon expense">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Électricité EDF</div>
                            <div class="transaction-meta">Énergie • Aujourd'hui 14:30</div>
                        </div>
                        <div class="transaction-amount expense">-€75.00</div>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-icon expense">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12z"/>
                            </svg>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Courses Carrefour</div>
                            <div class="transaction-meta">Alimentation • Hier 18:45</div>
                        </div>
                        <div class="transaction-amount expense">-€135.20</div>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-icon income">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                            </svg>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Virement Salaire</div>
                            <div class="transaction-meta">Revenu • 28 Nov 09:00</div>
                        </div>
                        <div class="transaction-amount income">+€2,450.00</div>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-icon expense">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.5 5.5a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V9a.5.5 0 0 0 1 0V7.5H10a.5.5 0 0 0 0-1H8.5V5.5z"/>
                                <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4z"/>
                            </svg>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Abonnement Netflix</div>
                            <div class="transaction-meta">Divertissement • 25 Nov 12:00</div>
                        </div>
                        <div class="transaction-amount expense">-€15.99</div>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-icon expense">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                            </svg>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">Essence Station Total</div>
                            <div class="transaction-meta">Transport • 24 Nov 16:20</div>
                        </div>
                        <div class="transaction-amount expense">-€62.30</div>
                    </div>
                </div>
            </div>

            <!-- Budget Overview -->
            <div class="dashboard-card budget-card">
                <div class="card-header">
                    <h2>Budget du mois</h2>
                    <div class="period-selector">
                        <select class="period-select">
                            <option>Novembre 2024</option>
                            <option>Octobre 2024</option>
                            <option>Septembre 2024</option>
                        </select>
                    </div>
                </div>
                <div class="budget-categories">
                    <div class="budget-item">
                        <div class="budget-info">
                            <div class="budget-category">
                                <span class="category-icon">🛒</span>
                                <span>Alimentation</span>
                            </div>
                            <div class="budget-amounts">
                                <span class="spent">€486</span>
                                <span class="budget">/ €600</span>
                            </div>
                        </div>
                        <div class="budget-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 81%"></div>
                            </div>
                            <span class="progress-text">81%</span>
                        </div>
                    </div>

                    <div class="budget-item">
                        <div class="budget-info">
                            <div class="budget-category">
                                <span class="category-icon">🚗</span>
                                <span>Transport</span>
                            </div>
                            <div class="budget-amounts">
                                <span class="spent">€234</span>
                                <span class="budget">/ €300</span>
                            </div>
                        </div>
                        <div class="budget-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 78%"></div>
                            </div>
                            <span class="progress-text">78%</span>
                        </div>
                    </div>

                    <div class="budget-item">
                        <div class="budget-info">
                            <div class="budget-category">
                                <span class="category-icon">🎬</span>
                                <span>Divertissement</span>
                            </div>
                            <div class="budget-amounts">
                                <span class="spent">€89</span>
                                <span class="budget">/ €150</span>
                            </div>
                        </div>
                        <div class="budget-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 59%"></div>
                            </div>
                            <span class="progress-text">59%</span>
                        </div>
                    </div>

                    <div class="budget-item warning">
                        <div class="budget-info">
                            <div class="budget-category">
                                <span class="category-icon">⚡</span>
                                <span>Énergie</span>
                            </div>
                            <div class="budget-amounts">
                                <span class="spent">€156</span>
                                <span class="budget">/ €120</span>
                            </div>
                        </div>
                        <div class="budget-progress">
                            <div class="progress-bar">
                                <div class="progress-fill warning" style="width: 100%"></div>
                            </div>
                            <span class="progress-text">130%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Savings Goals -->
            <div class="dashboard-card goals-card">
                <div class="card-header">
                    <h2>Objectifs d'épargne</h2>
                    <button class="btn btn-secondary btn-sm">Ajouter</button>
                </div>
                <div class="goals-list">
                    <div class="goal-item">
                        <div class="goal-icon">🏠</div>
                        <div class="goal-info">
                            <div class="goal-title">Apport maison</div>
                            <div class="goal-progress">
                                <div class="progress-bar large">
                                    <div class="progress-fill" style="width: 68%"></div>
                                </div>
                                <div class="goal-amounts">
                                    <span class="current">€34,000</span>
                                    <span class="target">/ €50,000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="goal-item">
                        <div class="goal-icon">✈️</div>
                        <div class="goal-info">
                            <div class="goal-title">Vacances Japon</div>
                            <div class="goal-progress">
                                <div class="progress-bar large">
                                    <div class="progress-fill" style="width: 43%"></div>
                                </div>
                                <div class="goal-amounts">
                                    <span class="current">€2,150</span>
                                    <span class="target">/ €5,000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="goal-item completed">
                        <div class="goal-icon">💻</div>
                        <div class="goal-info">
                            <div class="goal-title">Nouveau MacBook</div>
                            <div class="goal-progress">
                                <div class="progress-bar large">
                                    <div class="progress-fill completed" style="width: 100%"></div>
                                </div>
                                <div class="goal-amounts">
                                    <span class="current">€2,500</span>
                                    <span class="target">/ €2,500</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card actions-card">
                <div class="card-header">
                    <h2>Actions rapides</h2>
                </div>
                <div class="actions-grid">
                    <button class="action-btn">
                        <div class="action-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                        <span>Ajouter une dépense</span>
                    </button>

                    <button class="action-btn">
                        <div class="action-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                            </svg>
                        </div>
                        <span>Exporter les données</span>
                    </button>

                    <button class="action-btn">
                        <div class="action-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>
                                <path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>
                            </svg>
                        </div>
                        <span>Inviter un ami</span>
                    </button>

                    <button class="action-btn">
                        <div class="action-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </div>
                        <span>Planifier un budget</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .dashboard {
        padding: 2rem 0;
        background: #f8fafc;
    }

    .dashboard-header {
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

    .greeting h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .greeting p {
        color: #718096;
        font-size: 1.1rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 14px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-left: 4px solid transparent;
    }

    .balance-card { border-left-color: #10b981; }
    .income-card { border-left-color: #3b82f6; }
    .expenses-card { border-left-color: #ef4444; }
    .savings-card { border-left-color: #f59e0b; }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-info h3 {
        font-size: 0.875rem;
        font-weight: 500;
        color: #718096;
        margin-bottom: 0.25rem;
    }

    .stat-meta {
        font-size: 0.75rem;
        color: #a0aec0;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .stat-icon.balance { background: #10b981; }
    .stat-icon.income { background: #3b82f6; }
    .stat-icon.expenses { background: #ef4444; }
    .stat-icon.savings { background: #f59e0b; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-change.positive {
        color: #10b981;
    }

    .stat-change.negative {
        color: #ef4444;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .dashboard-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
    }

    .view-all {
        color: #10b981;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    /* Transactions */
    .transactions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .transaction-item:hover {
        background: #f1f5f9;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .transaction-icon.expense {
        background: #ef4444;
    }

    .transaction-icon.income {
        background: #10b981;
    }

    .transaction-details {
        flex: 1;
    }

    .transaction-title {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .transaction-meta {
        font-size: 0.875rem;
        color: #718096;
    }

    .transaction-amount {
        font-weight: 700;
        font-size: 1rem;
    }

    .transaction-amount.expense {
        color: #ef4444;
    }

    .transaction-amount.income {
        color: #10b981;
    }

    /* Budget */
    .period-select {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .budget-categories {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .budget-item {
        padding: 1rem;
        border-radius: 12px;
        background: #f8fafc;
    }

    .budget-item.warning {
        background: #fef7ed;
        border: 1px solid #fed7aa;
    }

    .budget-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .budget-category {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        color: #1a202c;
    }

    .category-icon {
        font-size: 1.25rem;
    }

    .budget-amounts {
        font-size: 0.875rem;
    }

    .spent {
        font-weight: 600;
        color: #1a202c;
    }

    .budget {
        color: #718096;
    }

    .budget-progress {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .progress-bar {
        flex: 1;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar.large {
        height: 10px;
    }

    .progress-fill {
        height: 100%;
        background: #10b981;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-fill.warning {
        background: #f59e0b;
    }

    .progress-fill.completed {
        background: #10b981;
    }

    .progress-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #718096;
        min-width: 40px;
        text-align: right;
    }

    /* Goals */
    .goals-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .goal-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        background: #f8fafc;
    }

    .goal-item.completed {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .goal-icon {
        font-size: 2rem;
        width: 48px;
        text-align: center;
    }

    .goal-info {
        flex: 1;
    }

    .goal-title {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.75rem;
    }

    .goal-progress {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .goal-amounts {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
    }

    .current {
        font-weight: 600;
        color: #1a202c;
    }

    .target {
        color: #718096;
    }

    /* Actions */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        padding: 1.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .action-btn:hover {
        border-color: #10b981;
        background: #f0fdf4;
        transform: translateY(-1px);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #10b981;
    }

    .action-btn span {
        font-size: 0.875rem;
        font-weight: 500;
        color: #4a5568;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
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

        .header-actions .btn {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .greeting h1 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bars
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });

        // Action buttons functionality
        const actionBtns = document.querySelectorAll('.action-btn');
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.querySelector('span').textContent;
                console.log('Action clicked:', action);
                // Implement actual functionality here
            });
        });
    });
</script>
@endpush
@endsection