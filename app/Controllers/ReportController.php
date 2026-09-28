<?php

class ReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Calcular estadísticas del mes actual
        $currentMonth = date('Y-m');
        
        // Importaciones del mes
        $stmtImports = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM imports WHERE strftime('%Y-%m', created_at) = :month");
        $stmtImports->execute([':month' => $currentMonth]);
        $importsCount = $stmtImports->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
        
        // Ingresos del mes (suma de pagos verificados)
        $stmtIncome = $this->pdo->prepare("SELECT SUM(amount_cents) as total FROM payments WHERE status = 'Verificado' AND strftime('%Y-%m', created_at) = :month");
        $stmtIncome->execute([':month' => $currentMonth]);
        $incomeTotal = $stmtIncome->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Nuevos clientes del mes (contar clientes únicos en pagos)
        $stmtClients = $this->pdo->prepare("SELECT COUNT(DISTINCT client_name) as cnt FROM payments WHERE strftime('%Y-%m', created_at) = :month");
        $stmtClients->execute([':month' => $currentMonth]);
        $newClientsCount = $stmtClients->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
        
        // Tasa de crecimiento (comparar con mes anterior)
        $lastMonth = date('Y-m', strtotime('-1 month'));
        $stmtLastIncome = $this->pdo->prepare("SELECT SUM(amount_cents) as total FROM payments WHERE status = 'Verificado' AND strftime('%Y-%m', created_at) = :month");
        $stmtLastIncome->execute([':month' => $lastMonth]);
        $lastIncomeTotal = $stmtLastIncome->fetch(PDO::FETCH_ASSOC)['total'] ?? 1;
        $growthRate = $lastIncomeTotal > 0 ? (($incomeTotal - $lastIncomeTotal) / $lastIncomeTotal) * 100 : 0;
        
        // Rendimiento mensual (últimos 6 meses)
        $monthlyPerformance = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = $this->getMonthName($month);
            
            $stmtMonth = $this->pdo->prepare("SELECT COUNT(*) as imports, SUM(amount_cents) as income FROM imports LEFT JOIN payments ON imports.id = payments.order_id WHERE strftime('%Y-%m', imports.created_at) = :month");
            $stmtMonth->execute([':month' => $month]);
            $monthData = $stmtMonth->fetch(PDO::FETCH_ASSOC);
            
            // Contar nuevos clientes ese mes
            $stmtMonthClients = $this->pdo->prepare("SELECT COUNT(DISTINCT client_name) as cnt FROM payments WHERE strftime('%Y-%m', created_at) = :month");
            $stmtMonthClients->execute([':month' => $month]);
            $monthClients = $stmtMonthClients->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0;
            
            // Calcular ingresos reales del mes
            $stmtMonthIncome = $this->pdo->prepare("SELECT SUM(amount_cents) as total FROM payments WHERE status = 'Verificado' AND strftime('%Y-%m', created_at) = :month");
            $stmtMonthIncome->execute([':month' => $month]);
            $monthIncome = $stmtMonthIncome->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            $monthlyPerformance[] = [
                'month' => $monthName,
                'imports' => $monthData['imports'] ?? 0,
                'income' => $monthIncome,
                'clients' => $monthClients
            ];
        }
        
        // Productos más importados
        $stmtTopProducts = $this->pdo->query("SELECT product_name, COUNT(*) as count, SUM(quantity) as total_quantity FROM imports GROUP BY product_name ORDER BY count DESC LIMIT 5");
        $topProducts = $stmtTopProducts->fetchAll(PDO::FETCH_ASSOC);
        
        // Mejores clientes (por monto de pagos)
        $stmtTopClients = $this->pdo->query("SELECT client_name, COUNT(*) as orders, SUM(amount_cents) as total_amount FROM payments WHERE status = 'Verificado' GROUP BY client_name ORDER BY total_amount DESC LIMIT 5");
        $topClients = $stmtTopClients->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/../Views/reports.php';
    }
    
    private function getMonthName($yearMonth) {
        $months = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        $month = substr($yearMonth, 5, 2);
        return $months[$month] ?? 'Desconocido';
    }
    
    public function export() {
        // Generar CSV con todos los datos
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte-' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8
        
        // Encabezados
        fputcsv($output, ['Tipo', 'ID', 'Cliente', 'Producto/Orden', 'Cantidad/Monto', 'Estado', 'Fecha']);
        
        // Importaciones
        $imports = $this->pdo->query("SELECT * FROM imports ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($imports as $imp) {
            fputcsv($output, [
                'Importación',
                $imp['id'],
                $imp['client_name'],
                $imp['product_name'],
                $imp['quantity'],
                $imp['status'],
                $imp['created_at']
            ]);
        }
        
        // Pagos
        $payments = $this->pdo->query("SELECT * FROM payments ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($payments as $pay) {
            fputcsv($output, [
                'Pago',
                $pay['id'],
                $pay['client_name'],
                $pay['order_id'],
                '$' . number_format($pay['amount_cents'] / 100, 2),
                $pay['status'],
                $pay['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
