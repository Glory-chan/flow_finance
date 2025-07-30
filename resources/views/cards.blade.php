<!-- Cards Overview -->
        <div class="cards-overview">
            <div class="overview-stats">
                <div class="stat-item">
                    <div class="stat-label">Total des cartes</div>
                    <div class="stat-value">3</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Dépenses ce mois</div>
                    <div class="stat-value">€2,185.50</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Limite disponible</div>
                    <div class="stat-value">€8,450.00</div>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid">
            <!-- Visa Card -->
            <div class="credit-card visa-card">
                <div class="card-header">
                    <div class="card-brand">VISA</div>
                    <div class="card-type">Premium</div>
                </div>
                <div class="card-chip">
                    <div class="chip"></div>
                    <div class="contactless">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M5 12c0 7 3 7 7 7s7 0 7-7M5 12c0-7 3-7 7-7s7 0 7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="card-number">•••• •••• •••• 1234</div>
                <div class="card-details">
                    <div class="card-holder">
                        <div class="label">Titulaire</div>
                        <div class="value">ROBERT MARTIN</div>
                    </div>
                    <div class="card-expiry">
                        <div class="label">Expire</div>
                        <div class="value">12/26</div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="card-action-btn">Voir détails</button>
                    <button class="card-action-btn">Bloquer</button>
                </div>
            </div>

            <!-- Mastercard -->
            <div class="credit-card mastercard-card">
                <div class="card-header">
                    <div class="card-brand">MASTERCARD</div>
                    <div class="card-type">Gold</div>
                </div>
                <div class="card-chip">
                    <div class="chip"></div>
                    <div class="contactless">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M5 12c0 7 3 7 7 7s7 0 7-7M5 12c0-7 3-7 7-7s7 0 7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="card-number">•••• •••• •••• 5678</div>
                <div class="card-details">
                    <div class="card-holder">
                        <div class="label">Titulaire</div>
                        <div class="value">ROBERT MARTIN</div>
                    </div>
                    <div class="card-expiry">
                        <div class="label">Expire</div>
                        <div class="value">08/25</div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="card-action-btn">Voir détails</button>
                    <button class="card-action-btn">Bloquer</button>
                </div>
            </div>

            <!-- LCL Card -->
            <div class="credit-card lcl-card">
                <div class="card-header">
                    <div class="card-brand">LCL</div>
                    <div class="card-type">Classic</div>
                </div>
                <div class="card-chip">
                    <div class="chip"></div>
                    <div class="contactless">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M5 12c0 7 3 7 7 7s7 0 7-7M5 12c0-7 3-7 7-7s7 0 7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="card-number">•••• •••• •••• 9012</div>
                <div class="card-details">
                    <div class="card-holder">
                        <div class="label">Titulaire</div>
                        <div class="value">ROBERT MARTIN</div>
                    </div>
                    <div class="card-expiry">
                        <div class="label">Expire</div>
                        <div class="value">03/27</div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="card-action-btn">Voir détails</button>
                    <button class="card-action-btn">Bloquer</button>
                </div>
            </div>

            <!-- Add New Card -->
            <div class="add-card-placeholder" onclick="showAddCardModal()">
                <div class="add-card-icon">
                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </div>
                <div class="add-card-text">
                    <h3>Ajouter une carte</h3>
                    <p>Connectez une nouvelle carte bancaire</p>
                </div>
            </div>
        </div>

        <!-- Recent Transactions by Card -->
        <div class="card-transactions">
            <div class="section-header">
                <h2>Transactions récentes par carte</h2>
                <div class="card-filter">
                    <select class="card-select">
                        <option value="all">Toutes les cartes</option>
                        <option value="visa">Visa •••• 1234</option>
                        <option value="mastercard">Mastercard •••• 5678</option>
                        <option value="lcl">LCL •••• 9012</option>
                    </select>
                </div>
            </div>

            <div class="transactions-by-card">
                <!-- Visa Transactions -->
                <div class="card-transaction-group">
                    <div class="card-group-header">
                        <div class="card-mini visa-mini">VISA</div>
                        <span>•••• 1234</span>
                        <div class="transaction-count">8 transactions</div>
                    </div>
                    <div class="transactions-list">
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Amazon France</div>
                                <div class="transaction-date">Aujourd'hui, 14:30</div>
                            </div>
                            <div class="transaction-amount expense">-€89.99</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Station Total</div>
                                <div class="transaction-date">Hier, 18:45</div>
                            </div>
                            <div class="transaction-amount expense">-€62.30</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Carrefour Market</div>
                                <div class="transaction-date">27 Nov, 19:20</div>
                            </div>
                            <div class="transaction-amount expense">-€45.67</div>
                        </div>
                    </div>
                </div>

                <!-- Mastercard Transactions -->
                <div class="card-transaction-group">
                    <div class="card-group-header">
                        <div class="card-mini mastercard-mini">MC</div>
                        <span>•••• 5678</span>
                        <div class="transaction-count">5 transactions</div>
                    </div>
                    <div class="transactions-list">
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Restaurant Le Bistrot</div>
                                <div class="transaction-date">26 Nov, 20:15</div>
                            </div>
                            <div class="transaction-amount expense">-€78.50</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Netflix</div>
                                <div class="transaction-date">25 Nov, 12:00</div>
                            </div>
                            <div class="transaction-amount expense">-€15.99</div>
                        </div>
                    </div>
                </div>

                <!-- LCL Transactions -->
                <div class="card-transaction-group">
                    <div class="card-group-header">
                        <div class="card-mini lcl-mini">LCL</div>
                        <span>•••• 9012</span>
                        <div class="transaction-count">3 transactions</div>
                    </div>
                    <div class="transactions-list">
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">EDF Électricité</div>
                                <div class="transaction-date">24 Nov, 09:00</div>
                            </div>
                            <div class="transaction-amount expense">-€75.00</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-merchant">Pharmacie du Centre</div>
                                <div class="transaction-date">22 Nov, 16:30</div>
                            </div>
                            <div class="transaction-amount expense">-€28.90</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Card Modal -->
<div id="addCardModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ajouter une nouvelle carte</h2>
            <button class="modal-close" onclick="hideAddCardModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form class="add-card-form">
                <div class="form-group">
                    <label for="cardNumber">Numéro de carte</label>
                    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiryDate">Date d'expiration</label>
                        <input type="text" id="expiryDate" placeholder="MM/AA" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label for="cvv">Code CVV</label>
                        <input type="text" id="cvv" placeholder="123" maxlength="3">
                    </div>
                </div>
                <div class="form-group">
                    <label for="cardName">Nom sur la carte</label>
                    <input type="text" id="cardName" placeholder="ROBERT MARTIN">
                </div>
                <div class="form-group">
                    <label for="cardAlias">Nom personnalisé (optionnel)</label>
                    <input type="text" id="cardAlias" placeholder="Ex: Carte principale">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="hideAddCardModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter la carte</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .cards-page {
        padding: 2rem 0;
        background: #f8fafc;
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

    /* Cards Overview */
    .cards-overview {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .overview-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        border-radius: 12px;
        background: #f8fafc;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
    }

    /* Cards Grid */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .credit-card {
        height: 240px;
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .credit-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .visa-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .mastercard-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .lcl-card {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-brand {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .card-type {
        font-size: 0.875rem;
        opacity: 0.8;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
    }

    .card-chip {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .chip {
        width: 50px;
        height: 35px;
        background: linear-gradient(45deg, #ffd700, #ffed4e);
        border-radius: 8px;
        position: relative;
    }

    .chip::after {
        content: '';
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        bottom: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    .contactless {
        opacity: 0.7;
    }

    .card-number {
        font-size: 1.5rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 3px;
        margin-bottom: 1.5rem;
    }

    .card-details {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .card-holder,
    .card-expiry {
        display: flex;
        flex-direction: column;
    }

    .label {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-bottom: 0.25rem;
    }

    .value {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .card-actions {
        display: flex;
        gap: 1rem;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem 2rem;
        background: rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .credit-card:hover .card-actions {
        transform: translateY(0);
    }

    .card-action-btn {
        flex: 1;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .card-action-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Add Card Placeholder */
    .add-card-placeholder {
        height: 240px;
        border: 3px dashed #cbd5e0;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .add-card-placeholder:hover {
        border-color: #10b981;
        background: #f0fdf4;
        transform: translateY(-4px);
    }

    .add-card-icon {
        color: #cbd5e0;
        transition: color 0.3s ease;
    }

    .add-card-placeholder:hover .add-card-icon {
        color: #10b981;
    }

    .add-card-text {
        text-align: center;
    }

    .add-card-text h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .add-card-text p {
        color: #718096;
        font-size: 0.875rem;
    }

    /* Card Transactions */
    .card-transactions {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .section-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
    }

    .card-select {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .transactions-by-card {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .card-transaction-group {
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-group-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: #f8fafc;
        font-weight: 500;
        color: #4a5568;
    }

    .card-mini {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .visa-mini { background: #1e3c72; }
    .mastercard-mini { background: #f5576c; }
    .lcl-mini { background: #182848; }

    .transaction-count {
        margin-left: auto;
        font-size: 0.875rem;
        color: #718096;
    }

    .transactions-list {
        padding: 0 1.5rem 1rem;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f8fafc;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-merchant {
        font-weight: 500;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .transaction-date {
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
        color: #1a202c;
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

    .modal-close:hover {
        color: #4a5568;
    }

    .modal-body {
        padding: 2rem;
    }

    .add-card-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #4a5568;
    }

    .form-group input {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .modal-actions .btn {
        flex: 1;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .cards-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .cards-grid {
            grid-template-columns: 1fr;
        }

        .overview-stats {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .modal-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Card number formatting
    document.getElementById('cardNumber').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
        if (formattedValue.length > 19) {
            formattedValue = formattedValue.substr(0, 19);
        }
        e.target.value = formattedValue;
    });

    // Expiry date formatting
    document.getElementById('expiryDate').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substr(0, 2) + '/' + value.substr(2, 2);
        }
        e.target.value = value;
    });

    // CVV numeric only
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Modal functions
    function showAddCardModal() {
        document.getElementById('addCardModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function hideAddCardModal() {
        document.getElementById('addCardModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    document.getElementById('addCardModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideAddCardModal();
        }
    });

    // Form submission
    document.querySelector('.add-card-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const cardNumber = document.getElementById('cardNumber').value;
        const expiryDate = document.getElementById('expiryDate').value;
        const cvv = document.getElementById('cvv').value;
        const cardName = document.getElementById('cardName').value;
        const cardAlias = document.getElementById('cardAlias').value;

        // Basic validation
        if (!cardNumber || !expiryDate || !cvv || !cardName) {
            alert('Veuillez remplir tous les champs obligatoires');
            return;
        }

        // Here you would normally send the data to your backend
        console.log('Adding card:', {
            cardNumber,
            expiryDate,
            cvv,
            cardName,
            cardAlias
        });

        // Show success message and close modal
        alert('Carte ajoutée avec succès !');
        hideAddCardModal();
        
        // Reset form
        this.reset();
    });

    // Card filter functionality
    document.querySelector('.card-select').addEventListener('change', function(e) {
        const selectedCard = e.target.value;
        const cardGroups = document.querySelectorAll('.card-transaction-group');
        
        cardGroups.forEach(group => {
            if (selectedCard === 'all') {
                group.style.display = 'block';
            } else {
                const cardType = group.querySelector('.card-mini').textContent.toLowerCase();
                if (selectedCard.includes(cardType) || selectedCard === 'all') {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            }
        });
    });

    // Card hover effects
    document.querySelectorAll('.credit-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) rotateY(5deg)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotateY(0deg)';
        });
    });

    // Card action buttons
    document.querySelectorAll('.card-action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const action = this.textContent.trim();
            const cardNumber = this.closest('.credit-card').querySelector('.card-number').textContent;
            
            if (action === 'Voir détails') {
                console.log('Viewing details for card:', cardNumber);
                // Implement card details modal
            } else if (action === 'Bloquer') {
                if (confirm('Êtes-vous sûr de vouloir bloquer cette carte ?')) {
                    console.log('Blocking card:', cardNumber);
                    // Implement card blocking functionality
                }
            }
        });
    });
</script>
@endpush
@endsection@extends('layouts.app')

@section('title', 'Mes Cartes - FlowFinance')

@section('content')
<div class="cards-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>Mes Cartes</h1>
                    <p>Gérez vos cartes bancaires et suivez vos dépenses</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                        Filtrer
                    </button>
                    <button class="btn btn-primary" onclick="showAddCardModal()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Ajouter une carte
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards Overview -->
        <div class="cards-overview">
            <div class="overview-stats">
                <div class="stat-item">
                    <div class="stat-label">Total des cartes</div>
                    <div class="stat-value">3</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Dépenses ce mois</div>
                    <div class="stat-value">€2,185.50</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Limite disponible</div>
                    <div class="stat-value">€8,450.00</div>
                </div>
            </div>