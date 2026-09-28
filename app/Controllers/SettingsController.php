<?php

class SettingsController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Cargar todas las configuraciones
        $settings = $this->getAllSettings();
        
        include __DIR__ . '/../Views/settings.php';
    }
    
    private function getAllSettings() {
        $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM settings");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        // Valores por defecto si no existen
        $defaults = [
            'company_name' => 'ImportaPro',
            'company_email' => 'info@importapro.com',
            'company_phone' => '+1 234 567 8900',
            'company_address' => '123 Business Ave, City',
            'site_banner_text' => 'Importa productos del mundo con confianza',
            'site_description' => 'Simplificamos el proceso de importación para que puedas enfocarte en hacer crecer tu negocio',
            'bank_name' => 'Banco Nacional de Comercio',
            'bank_account_holder' => 'ImportaPro S.A. de C.V.',
            'bank_account_number' => '',
            'bank_clabe' => ''
        ];
        
        return array_merge($defaults, $settings);
    }
    
    public function update() {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        // Lista de configuraciones permitidas
        $allowedSettings = [
            'company_name', 'company_email', 'company_phone', 'company_address',
            'site_banner_text', 'site_description',
            'bank_name', 'bank_account_holder', 'bank_account_number', 'bank_clabe'
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO settings (setting_key, setting_value, updated_at) VALUES (:key, :value, :updated) ON CONFLICT(setting_key) DO UPDATE SET setting_value = :value, updated_at = :updated");
        
        foreach ($allowedSettings as $key) {
            if (isset($_POST[$key])) {
                $stmt->execute([
                    ':key' => $key,
                    ':value' => $_POST[$key],
                    ':updated' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Configuración actualizada correctamente']);
            exit;
        }
        
        $_SESSION['settings_message'] = 'Configuración actualizada correctamente';
        header('Location: ?action=settings');
        exit;
    }
}
