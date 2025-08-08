<?php
session_start();
require_once "connect.php";

// Verificar si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

// Construir consulta con filtros (misma lógica que admin.php)
$search = $_GET['search'] ?? '';
$conditions = [];
$params = [];
$paramTypes = "";

// Filtro de búsqueda
if (!empty($search)) {
    $conditions[] = "(firstName LIKE ? OR lastName LIKE ? OR email LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $paramTypes .= "sss";
}

// Filtro por rol
if (!empty($_GET['role_filter'])) {
    $conditions[] = "role = ?";
    $params[] = $_GET['role_filter'];
    $paramTypes .= "s";
}

// Filtro por estado
if (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') {
    $conditions[] = "is_active = ?";
    $params[] = $_GET['status_filter'];
    $paramTypes .= "i";
}

// Filtro por fecha
if (!empty($_GET['date_filter'])) {
    switch ($_GET['date_filter']) {
        case 'today':
            $conditions[] = "DATE(created_at) = CURDATE()";
            break;
        case 'week':
            $conditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $conditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case 'year':
            $conditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }
}

$whereClause = "";
if (!empty($conditions)) {
    $whereClause = "WHERE " . implode(" AND ", $conditions);
}

// Consulta para obtener usuarios
$sql = "SELECT id, firstName, lastName, email, role, is_active, created_at, last_login FROM users $whereClause ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Configurar headers para descarga CSV
$filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Crear archivo CSV
$output = fopen('php://output', 'w');

// BOM para UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Encabezados CSV
fputcsv($output, [
    'ID',
    'Nombre',
    'Apellido', 
    'Email',
    'Rol',
    'Estado',
    'Fecha de Registro',
    'Último Login'
]);

// Datos de usuarios
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['firstName'],
        $row['lastName'],
        $row['email'],
        $row['role'] === 'admin' ? 'Administrador' : 'Usuario',
        $row['is_active'] ? 'Activo' : 'Inactivo',
        date('d/m/Y H:i', strtotime($row['created_at'])),
        $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : 'Nunca'
    ]);
}

fclose($output);
$stmt->close();

// Registrar actividad
logSystemActivity('export_users', 'Exportación de usuarios a CSV', $_SESSION['user_id']);
?> 