document.addEventListener('DOMContentLoaded', function () {
    // Helpers
    function qs(selector, ctx = document) { return ctx.querySelector(selector); }
    function qsa(selector, ctx = document) { return Array.from(ctx.querySelectorAll(selector)); }

    // Calculate estimated cost for a row (prix * quantite)
    function rowEstimatedCost(row) {
        const cells = row.cells;
        // expected structure: 0 checkbox,1 ville,2 produit,3 quantite,4 prixUnitaire,5 cout
        const quantCell = cells[3];
        const prixCell = cells[4];
        const q = parseFloat(quantCell?.textContent.trim().replace(/\s/g, '')) || 0;
        const p = parseFloat(prixCell?.textContent.replace(/[^0-9\-,.]/g, '').replace(',', '.')) || 0;
        return q * p;
    }

    // Update summary of selected total cost
    function updateSelectionSummary() {
        const table = qs('form[action$="/achats/auto/valider"] table');
        if (!table) return;
        const checkboxes = qsa('input[name="selected_besoins[]"]:checked', table);
        let total = 0;
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            total += rowEstimatedCost(row);
        });
        // create or update a summary element
        let summary = qs('#selection-summary');
        if (!summary) {
            summary = document.createElement('div');
            summary.id = 'selection-summary';
            summary.className = 'text-end mt-2 fw-bold';
            const footer = qs('.card-footer.d-flex.justify-content-between');
            if (footer) footer.parentNode.insertBefore(summary, footer.nextSibling);
            else qs('main').appendChild(summary);
        }
        summary.textContent = 'Coût estimé sélection: ' + total.toLocaleString('fr-FR', { maximumFractionDigits: 0 }) + ' Ar';
    }

    // Build proposals from server suggestions: choose first available don if present
    async function buildPropositionsFor(selectedIds) {
        const url = '/achats/auto/proposer?limit=200';
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) throw new Error('Impossible de récupérer les propositions');
        const proposals = await res.json();
        // index by idBesoin
        const map = {};
        proposals.forEach(p => { if (p.idBesoin) map[p.idBesoin] = p; });

        const result = [];
        const missing = [];
        selectedIds.forEach(id => {
            const p = map[id];
            if (p && Array.isArray(p.donsDisponibles) && p.donsDisponibles.length > 0) {
                const don = p.donsDisponibles[0];
                result.push({ idBesoin: id, idDon: don.id || don.ID || don.idDon || null });
            } else {
                missing.push(id);
            }
        });
        return { result, missing };
    }

    // Submit propositions to server (POST form-data with 'propositions' JSON string)
    async function submitPropositions(propositions) {
        const fd = new FormData();
        fd.append('propositions', JSON.stringify(propositions));
        const res = await fetch('/achats/auto/valider', { method: 'POST', body: fd, credentials: 'same-origin' });
        if (!res.ok) throw new Error('Erreur lors de l\'exécution des achats');
        // redirect to purchases page after success
        window.location.href = '/achats';
    }

    // Handle form submit (Acheter sélection)
    const form = document.querySelector('form[action$="/achats/auto/valider"]');
    if (form) {
        form.addEventListener('change', updateSelectionSummary);
        form.addEventListener('submit', async function (ev) {
            ev.preventDefault();
            try {
                const selected = qsa('input[name="selected_besoins[]"]:checked', form).map(cb => parseInt(cb.value, 10));
                if (selected.length === 0) {
                    alert('Aucun besoin sélectionné.');
                    return;
                }
                // build proposals and submit
                const { result, missing } = await buildPropositionsFor(selected);
                if (missing.length > 0) {
                    const cont = confirm('Certaines sélections n\'ont pas de dons financiers disponibles (ids: ' + missing.join(',') + '). Continuer avec les autres ?');
                    if (!cont) return;
                }
                if (result.length === 0) {
                    alert('Aucune proposition disponible pour les sélections choisies.');
                    return;
                }
                await submitPropositions(result);
            } catch (err) {
                console.error(err);
                alert('Erreur: ' + (err.message || err));
            }
        });
    }

    // Intercept proposer automatique link to show modal with proposals
    const proposerBtn = qs('#btn-proposer-auto');
    if (proposerBtn) {
        proposerBtn.addEventListener('click', async function (ev) {
            ev.preventDefault();
            try {
                const res = await fetch(proposerBtn.href, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Erreur récupération propositions');
                const proposals = await res.json();
                showProposalsModal(proposals);
            } catch (err) {
                console.error(err);
                alert('Erreur: ' + (err.message || err));
            }
        });
    }

    // Create and show a bootstrap modal listing proposals and allowing confirm
    function showProposalsModal(proposals) {
        // remove existing modal if any
        const existing = qs('#proposalsModal');
        if (existing) existing.remove();

        const modalHtml = document.createElement('div');
        modalHtml.className = 'modal fade';
        modalHtml.id = 'proposalsModal';
        modalHtml.tabIndex = -1;
        modalHtml.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Propositions d'achat automatique</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Besoin</th><th>Produit</th><th>Quantité</th><th>Coût estimé</th><th>Dons disponibles</th></tr></thead>
                                <tbody>
                                ${proposals.map(p => `
                                    <tr data-id="${p.idBesoin}">
                                        <td>${p.idBesoin}</td>
                                        <td>${(p.idProduit||'')}</td>
                                        <td>${(p.quantite||'')}</td>
                                        <td>${(p.coutEstime||'')}</td>
                                        <td>${(Array.isArray(p.donsDisponibles)&&p.donsDisponibles.length>0)? p.donsDisponibles.map(d=> (d.id||d.ID||d.idDon||'')).join(', '): '<em>aucun</em>'}</td>
                                    </tr>
                                `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div id="proposalsModalError" class="text-danger"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" id="confirmProposals" class="btn btn-primary">Confirmer toutes</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalHtml);
        const modal = new bootstrap.Modal(modalHtml);
        modal.show();

        qs('#confirmProposals').addEventListener('click', async function () {
            try {
                // build mapping choosing first don when available
                const mapping = [];
                proposals.forEach(p => {
                    if (Array.isArray(p.donsDisponibles) && p.donsDisponibles.length > 0) {
                        const don = p.donsDisponibles[0];
                        mapping.push({ idBesoin: p.idBesoin, idDon: don.id || don.ID || don.idDon || null });
                    }
                });
                if (mapping.length === 0) {
                    qs('#proposalsModalError').textContent = 'Aucune proposition exécutable (dons manquants).';
                    return;
                }
                await submitPropositions(mapping);
            } catch (err) {
                console.error(err);
                qs('#proposalsModalError').textContent = err.message || err;
            }
        });
    }

    // initialize summary once
    updateSelectionSummary();

});
