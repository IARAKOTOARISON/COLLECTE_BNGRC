document.addEventListener('DOMContentLoaded', function () {
    // Helper selectors
    const villeSelect = document.getElementById('villeFilter');
    const table = document.querySelector('.card .table');

    // Filter rows by city name (client-side)
    function filterByCity(id) {
        if (!table) return;
        const rows = Array.from(table.tBodies[0].rows);
        if (!id) {
            rows.forEach(r => r.style.display = '');
            return;
        }
        // find city name from select option
        const opt = villeSelect.querySelector('option[value="' + id + '"]');
        const name = opt ? opt.textContent.trim() : null;
        if (!name) { rows.forEach(r => r.style.display = ''); return; }
        rows.forEach(r => {
            const villeCell = r.cells[1]; // second column is Ville
            const cellText = villeCell ? villeCell.textContent.trim() : '';
            if (cellText === name) r.style.display = ''; else r.style.display = 'none';
        });
    }

    if (villeSelect) {
        villeSelect.addEventListener('change', function () {
            filterByCity(this.value);
        });
    }

    // Show details modal when clicking 'Détails' buttons
    function attachDetailButtons() {
        const detailButtons = document.querySelectorAll('a[href*="/achats/"]');
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function (ev) {
                // prevent navigation and show modal with row info
                ev.preventDefault();
                const tr = this.closest('tr');
                if (!tr) return;
                showDetailModalFromRow(tr);
            });
        });
    }

    function showDetailModalFromRow(row) {
        // remove existing modal
        const prev = document.getElementById('achatDetailModal');
        if (prev) prev.remove();

        const cells = row.cells;
        const date = cells[0]?.textContent.trim() || '';
        const ville = cells[1]?.textContent.trim() || '';
        const produit = cells[2]?.textContent.trim() || '';
        const qte = cells[3]?.textContent.trim() || '';
        const cout = cells[4]?.textContent.trim() || '';
        const frais = cells[5]?.textContent.trim() || '';
        const total = cells[6]?.textContent.trim() || '';

        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'achatDetailModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de l'achat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Date:</strong> ${date}</p>
                        <p><strong>Ville:</strong> ${ville}</p>
                        <p><strong>Produit:</strong> ${produit}</p>
                        <p><strong>Quantité:</strong> ${qte}</p>
                        <p><strong>Coût:</strong> ${cout}</p>
                        <p><strong>Frais:</strong> ${frais}</p>
                        <p><strong>Total:</strong> ${total}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    attachDetailButtons();

});
