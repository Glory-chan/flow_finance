@extends('layouts.app')

@section('title', 'Analyses - FlowFinance')

@section('content')
<div class="analytics-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>Analyses Financières</h1>
                    <p>Analysez vos dépenses, revenus et tendances financières</p>
                </div>
                <div class="header-actions">
                    <select class="period-selector">
                        <option value="current_month">Ce mois</option>
                        <option value="last_month">Mois dernier</option>
                        <option value="last_3_months">3 derniers mois</option>
                        <option value="last_6_months">6 derniers mois</option>
                        <option value="current_year">Cette année</option>
                        <option value="last_year">Année dernière</option>
                    </select>
                    <button class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                        Exporter PDF
                    </button>
                    <button class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Nouveau rapport
                    </button>
                </div>
            </div>
        </div>

        <!-- Analytics Overview -->
        <div class="analytics-overview">
            <div class="overview-cards">
                <div class="overview-card income">
                    <div class="card-icon">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3>Revenus totaux</h3>
                        <div class="card-value">€3,240.00</div>
                        <div class="card-change positive">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                            </svg>
                            +12.3% vs mois dernier
                        </div>
                    </div>
                </div>

                <div class="overview-card expenses">
                    <div class="card-icon">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3>Dépenses totales</h3>
                        <div class="card-value">€2,185.50</div>
                        <div class="card-change negative">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                            </svg>
                            +8.1% vs mois dernier
                        </div>
                    </div>
                </div>

                <div class="overview-card savings">
                    <div class="card-icon">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3>Épargne nette</h3>
                        <div class="card-value">€1,054.50</div>
                        <div class="card-change positive">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                            </svg>
                            +32.5% ce mois
                        </div>
                    </div>
                </div>

                <div class="overview-card ratio">
                    <div class="card-icon">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3>Taux d'épargne</h3>
                        <div class="card-value">32.5%</div>
                        <div class="card-change positive">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                            </svg>
                            Excellent niveau
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="charts-grid">
                <!-- Income vs Expenses Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Revenus vs Dépenses</h3>
                        <div class="chart-controls">
                            <button class="chart-toggle active" data-chart="line">Ligne</button>
                            <button class="chart-toggle" data-chart="bar">Barres</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="incomeExpensesChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Expenses by Category Chart -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Dépenses par catégorie</h3>
                        <div class="chart-period">Novembre 2024</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoriesChart" width="400" height="200"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #10b981;"></span>
                            <span>Alimentation (€486)</span>
                            <span class="legend-percent">22.3%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #3b82f6;"></span>
                            <span>Transport (€234)</span>
                            <span class="legend-percent">10.7%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #f59e0b;"></span>
                            <span>Énergie (€156)</span>
                            <span class="legend-percent">7.1%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #ef4444;"></span>
                            <span>Divertissement (€89)</span>
                            <span class="legend-percent">4.1%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #8b5cf6;"></span>
                            <span>Autres (€1,220)</span>
                            <span class="legend-percent">55.8%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h3>Tendances mensuelles</h3>
                    <div class="chart-filters">
                        <label class="filter-checkbox">
                            <input type="checkbox" checked data-series="income">
                            <span class="checkmark"></span>
                            Revenus
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" checked data-series="expenses">
                            <span class="checkmark"></span>
                            Dépenses
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" checked data-series="savings">
                            <span class="checkmark"></span>
                            Épargne
                        </label>
                    </div>
                </div>
                <div class="chart-container large">
                    <canvas id="monthlyTrendChart" width="800" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Insights Section -->
        <div class="insights-section">
            <div class="section-header">
                <h2>Analyses et conseils</h2>
                <p>Découvrez des insights personnalisés sur vos habitudes financières</p>
            </div>

            <div class="insights-grid">
                <div class="insight-card positive">
                    <div class="insight-icon">✅</div>
                    <div class="insight-content">
                        <h4>Excellent taux d'épargne</h4>
                        <p>Votre taux d'épargne de 32.5% est bien au-dessus de la moyenne française (17%). Continuez ainsi !</p>
                    </div>
                </div>

                <div class="insight-card warning">
                    <div class="insight-icon">⚠️</div>
                    <div class="insight-content">
                        <h4>Budget énergie dépassé</h4>
                        <p>Vos dépenses énergétiques ont dépassé votre budget de 30%. Considérez des économies d'énergie.</p>
                    </div>
                </div>

                <div class="insight-card info">
                    <div class="insight-icon">💡</div>
                    <div class="insight-content">
                        <h4>Opportunité d'optimisation</h4>
                        <p>En réduisant vos dépenses de divertissement de 20%, vous pourriez économiser €214 par an.</p>
                    </div>
                </div>

                <div class="insight-card positive">
                    <div class="insight-icon">📈</div>
                    <div class="insight-content">
                        <h4>Progression positive</h4>
                        <p>Vos revenus ont augmenté de 12.3% ce mois. Profitez-en pour augmenter votre épargne.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="detailed-analytics">
            <div class="analytics-tabs">
                <button class="tab-btn active" data-tab="spending-patterns">Habitudes de dépenses</button>
                <button class="tab-btn" data-tab="income-analysis">Analyse des revenus</button>
                <button class="tab-btn" data-tab="budget-performance">Performance budget</button>
                <button class="tab-btn" data-tab="goals-progress">Progrès objectifs</button>
            </div>

            <div class="tab-content">
                <!-- Spending Patterns Tab -->
                <div id="spending-patterns-tab" class="tab-panel active">
                    <div class="analysis-grid">
                        <div class="analysis-card">
                            <h4>Dépenses par jour de la semaine</h4>
                            <div class="weekday-chart">
                                <div class="weekday-bar">
                                    <span class="weekday-label">Lun</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 45%"></div>
                                    </div>
                                    <span class="weekday-amount">€95</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Mar</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 60%"></div>
                                    </div>
                                    <span class="weekday-amount">€125</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Mer</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 35%"></div>
                                    </div>
                                    <span class="weekday-amount">€75</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Jeu</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 80%"></div>
                                    </div>
                                    <span class="weekday-amount">€165</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Ven</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 90%"></div>
                                    </div>
                                    <span class="weekday-amount">€185</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Sam</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 100%"></div>
                                    </div>
                                    <span class="weekday-amount">€205</span>
                                </div>
                                <div class="weekday-bar">
                                    <span class="weekday-label">Dim</span>
                                    <div class="weekday-progress">
                                        <div class="weekday-fill" style="height: 70%"></div>
                                    </div>
                                    <span class="weekday-amount">€145</span>
                                </div>
                            </div>
                        </div>

                        <div class="analysis-card">
                            <h4>Top 5 des marchands</h4>
                            <div class="merchants-list">
                                <div class="merchant-item">
                                    <div class="merchant-info">
                                        <span class="merchant-name">Carrefour</span>
                                        <span class="merchant-category">Alimentation</span>
                                    </div>
                                    <div class="merchant-amount">€285.40</div>
                                </div>
                                <div class="merchant-item">
                                    <div class="merchant-info">
                                        <span class="merchant-name">EDF</span>
                                        <span class="merchant-category">Énergie</span>
                                    </div>
                                    <div class="merchant-amount">€156.00</div>
                                </div>
                                <div class="merchant-item">
                                    <div class="merchant-info">
                                        <span class="merchant-name">Station Total</span>
                                        <span class="merchant-category">Transport</span>
                                    </div>
                                    <div class="merchant-amount">€134.70</div>
                                </div>
                                <div class="merchant-item">
                                    <div class="merchant-info">
                                        <span class="merchant-name">Amazon</span>
                                        <span class="merchant-category">Shopping</span>
                                    </div>
                                    <div class="merchant-amount">€89.99</div>
                                </div>
                                <div class="merchant-item">
                                    <div class="merchant-info">
                                        <span class="merchant-name">Netflix</span>
                                        <span class="merchant-category">Divertissement</span>
                                    </div>
                                    <div class="merchant-amount">€15.99</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other tabs content (placeholder) -->
                <div id="income-analysis-tab" class="tab-panel">
                    <div class="coming-soon">
                        <h3>📈 Analyse des revenus</h3>
                        <p>Analyse détaillée de vos sources de revenus à venir...</p>
                    </div>
                </div>

                <div id="budget-performance-tab" class="tab-panel">
                    <div class="coming-soon">
                        <h3>🎯 Performance budget</h3>
                        <p>Analyse de la performance de vos budgets à venir...</p>
                    </div>
                </div>

                <div id="goals-progress-tab" class="tab-panel">
                    <div class="coming-soon">
                        <h3>🏆 Progrès objectifs</h3>
                        <p>Suivi détaillé de vos objectifs d'épargne à venir...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .analytics-page {
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
        align-items: center;
    }

    .period-selector {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 0.875rem;
        color: #4a5568;
    }

    /* Analytics Overview */
    .analytics-overview {
        margin-bottom: 2rem;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .overview-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid transparent;
    }

    .overview-card.income { border-left-color: #10b981; }
    .overview-card.expenses { border-left-color: #ef4444; }
    .overview-card.savings { border-left-color: #f59e0b; }
    .overview-card.ratio { border-left-color: #3b82f6; }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .income .card-icon { background: #10b981; }
    .expenses .card-icon { background: #ef4444; }
    .savings .card-icon { background: #f59e0b; }
    .ratio .card-icon { background: #3b82f6; }

    .card-content h3 {
        font-size: 0.875rem;
        font-weight: 500;
        color: #718096;
        margin-bottom: 0.5rem;
    }

    .card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .card-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .card-change.positive { color: #10b981; }
    .card-change.negative { color: #ef4444; }

    /* Charts Section */
    .charts-section {
        margin-bottom: 2rem;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .chart-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
    }

    .chart-controls {
        display: flex;
        gap: 0.5rem;
    }

    .chart-toggle {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: white;
        color: #4a5568;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .chart-toggle.active {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .chart-period {
        font-size: 0.875rem;
        color: #718096;
    }

    .chart-filters {
        display: flex;
        gap: 1rem;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .filter-checkbox input {
        display: none;
    }

    .filter-checkbox .checkmark {
        width: 16px;
        height: 16px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        position: relative;
        transition: all 0.2s ease;
    }

    .filter-checkbox input:checked + .checkmark {
        background: #10b981;
        border-color: #10b981;
    }

    .filter-checkbox input:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .chart-container {
        position: relative;
        height: 200px;
    }

    .chart-container.large {
        height: 300px;
    }

    .chart-legend {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }

    .legend-percent {
        margin-left: auto;
        font-weight: 600;
        color: #4a5568;
    }

    /* Insights Section */
    .insights-section {
        margin-bottom: 2rem;
    }

    .section-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .section-header p {
        color: #718096;
        font-size: 1rem;
    }

    .insights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .insight-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border-left: 4px solid transparent;
    }

    .insight-card.positive { border-left-color: #10b981; }
    .insight-card.warning { border-left-color: #f59e0b; }
    .insight-card.info { border-left-color: #3b82f6; }

    .insight-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .insight-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .insight-content p {
        color: #718096;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Detailed Analytics */
    .detailed-analytics {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .analytics-tabs {
        display: flex;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .tab-btn {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        background: transparent;
        color: #4a5568;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
    }

    .tab-btn:hover {
        background: #f1f5f9;
        color: #2d3748;
    }

    .tab-btn.active {
        background: white;
        color: #10b981;
        border-bottom-color: #10b981;
    }

    .tab-content {
        padding: 2rem;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    /* Analysis Grid */
    .analysis-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }

    .analysis-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .analysis-card h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1.5rem;
    }

    /* Weekday Chart */
    .weekday-chart {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        height: 200px;
        padding: 1rem 0;
    }

    .weekday-bar {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .weekday-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #718096;
    }

    .weekday-progress {
        width: 100%;
        max-width: 32px;
        height: 120px;
        background: #e2e8f0;
        border-radius: 4px;
        position: relative;
        display: flex;
        align-items: flex-end;
    }

    .weekday-fill {
        width: 100%;
        background: linear-gradient(180deg, #10b981, #059669);
        border-radius: 4px;
        transition: height 0.3s ease;
    }

    .weekday-amount {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1a202c;
    }

    /* Merchants List */
    .merchants-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .merchant-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .merchant-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .merchant-name {
        font-weight: 500;
        color: #1a202c;
    }

    .merchant-category {
        font-size: 0.875rem;
        color: #718096;
    }

    .merchant-amount {
        font-weight: 600;
        color: #ef4444;
        font-size: 1rem;
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

    /* Responsive */
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .analytics-tabs {
            flex-direction: column;
        }

        .tab-btn {
            text-align: left;
            border-bottom: none;
            border-left: 3px solid transparent;
        }

        .tab-btn.active {
            border-left-color: #10b981;
            border-bottom: none;
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
            flex-wrap: wrap;
        }

        .overview-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-filters {
            flex-direction: column;
            gap: 0.5rem;
        }

        .weekday-chart {
            gap: 0.5rem;
        }

        .analysis-grid {
            grid-template-columns: 1fr;
        }

        .insight-card {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .overview-cards {
            grid-template-columns: 1fr;
        }

        .chart-card {
            padding: 1rem;
        }

        .weekday-progress {
            height: 80px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts
        initIncomeExpensesChart();
        initCategoriesChart();
        initMonthlyTrendChart();

        // Tab functionality
        initTabs();

        // Chart controls
        initChartControls();

        // Period selector
        initPeriodSelector();
    });

    // Income vs Expenses Chart
    function initIncomeExpensesChart() {
        const ctx = document.getElementById('incomeExpensesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov'],
                datasets: [{
                    label: 'Revenus',
                    data: [2800, 2950, 3100, 2900, 3200, 3150, 3300, 3100, 3250, 3180, 3240],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Dépenses',
                    data: [2100, 2250, 2300, 2150, 2400, 2200, 2350, 2180, 2300, 2020, 2185],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Categories Chart
    function initCategoriesChart() {
        const ctx = document.getElementById('categoriesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Alimentation', 'Transport', 'Énergie', 'Divertissement', 'Autres'],
                datasets: [{
                    data: [486, 234, 156, 89, 1220],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Monthly Trend Chart
    function initMonthlyTrendChart() {
        const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
        
        window.monthlyTrendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov'],
                datasets: [{
                    label: 'Revenus',
                    data: [2800, 2950, 3100, 2900, 3200, 3150, 3300, 3100, 3250, 3180, 3240],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Dépenses',
                    data: [2100, 2250, 2300, 2150, 2400, 2200, 2350, 2180, 2300, 2020, 2185],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Épargne',
                    data: [700, 700, 800, 750, 800, 950, 950, 920, 950, 1160, 1055],
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Tab functionality
    function initTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all buttons and panels
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanels.forEach(p => p.classList.remove('active'));

                // Add active class to clicked button and corresponding panel
                this.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');
            });
        });
    }

    // Chart controls
    function initChartControls() {
        const chartToggles = document.querySelectorAll('.chart-toggle');
        
        chartToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const chartType = this.getAttribute('data-chart');
                const siblings = this.parentElement.querySelectorAll('.chart-toggle');
                
                siblings.forEach(s => s.classList.remove('active'));
                this.classList.add('active');
                
                // Here you would update the chart type
                console.log('Switching to', chartType, 'chart');
            });
        });

        // Filter checkboxes for monthly trend chart
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox input');
        
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const seriesName = this.getAttribute('data-series');
                const isChecked = this.checked;
                
                if (window.monthlyTrendChart) {
                    const chart = window.monthlyTrendChart;
                    const dataset = chart.data.datasets.find(ds => 
                        ds.label.toLowerCase().includes(seriesName)
                    );
                    
                    if (dataset) {
                        dataset.hidden = !isChecked;
                        chart.update();
                    }
                }
            });
        });
    }

    // Period selector
    function initPeriodSelector() {
        const periodSelector = document.querySelector('.period-selector');
        
        if (periodSelector) {
            periodSelector.addEventListener('change', function() {
                console.log('Period changed to:', this.value);
                // Here you would update all charts with new data
                updateChartsForPeriod(this.value);
            });
        }
    }

    function updateChartsForPeriod(period) {
        // Simulate data update based on period
        console.log('Updating charts for period:', period);
        
        // In a real application, you would fetch new data from the server
        // and update all charts accordingly
    }

    // Animate weekday bars on load
    function animateWeekdayBars() {
        const weekdayFills = document.querySelectorAll('.weekday-fill');
        
        weekdayFills.forEach((fill, index) => {
            setTimeout(() => {
                fill.style.height = fill.style.height;
            }, index * 100);
        });
    }

    // Call animation after DOM is loaded
    setTimeout(animateWeekdayBars, 500);
</script>
@endpush