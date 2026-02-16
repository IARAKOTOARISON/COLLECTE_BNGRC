<?php
namespace app\controllers;

use App\models\Setting;
use flight\Engine;

class SettingsController {
    private Engine $app;
    private \PDO $db;

    public function __construct(\PDO $db, Engine $app) {
        $this->app = $app;
        $this->db = $db;
    }

    /** Afficher le formulaire de configuration des achats */
    public function afficherFormulaireAchats(): void {
        $setting = new Setting($this->db);
        $frais = $setting->get('achats.frais_percent', null);

        $success = $_SESSION['success_message'] ?? null;
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('settingsAchats', [
            'frais_percent' => $frais,
            'success' => $success,
            'error' => $error
        ]);
    }

    /** Enregistrer la nouvelle valeur du pourcentage de frais */
    public function enregistrerAchats(): void {
        try {
            $frais = $_POST['frais_percent'] ?? null;
            if ($frais === null || !is_numeric($frais) || $frais < 0) {
                throw new \Exception('Valeur de frais invalide.');
            }

            $setting = new Setting($this->db);
            $ok = $setting->set('achats.frais_percent', (string)round(floatval($frais), 2));
            if (!$ok) throw new \Exception('Impossible d\'enregistrer la configuration.');

            $_SESSION['success_message'] = 'Configuration des frais mise à jour.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        $this->app->redirect('/admin/achats/config');
    }
}
