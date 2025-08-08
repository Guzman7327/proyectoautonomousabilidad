<?php
session_start();
require_once "connect.php";

// Verificar si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Obtener estadísticas en tiempo real
$userStats = getUserStats();

// Parámetros de búsqueda y filtros
$search = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'firstName';
$sortOrder = $_GET['order'] ?? 'ASC';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Construir consulta con filtros avanzados
$whereClause = "";
$params = [];
$paramTypes = "";

$conditions = [];

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

if (!empty($conditions)) {
    $whereClause = "WHERE " . implode(" AND ", $conditions);
}

// Consulta para contar total de registros
$countSql = "SELECT COUNT(*) as total FROM users $whereClause";
if (!empty($params)) {
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param($paramTypes, ...$params);
    $countStmt->execute();
    $totalResult = $countStmt->get_result();
    $totalUsers = $totalResult->fetch_assoc()['total'];
    $countStmt->close();
} else {
    $totalResult = $conn->query($countSql);
    $totalUsers = $totalResult->fetch_assoc()['total'];
}

$totalPages = ceil($totalUsers / $perPage);

// Consulta principal con paginación
$sql = "SELECT id, firstName, lastName, email, role, is_active, created_at FROM users $whereClause ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;
$paramTypes .= "ii";

$stmt = $conn->prepare($sql);
if (!empty($paramTypes)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador - Portal Turístico</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
            body {
            font-family: 'Verdana', 'Geneva', sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
      min-height: 100vh;
      padding: 20px;
    }

    .main-container {
      max-width: 1200px;
      margin: 0 auto;
      background: white;
      border-radius: 15px;
      border: 2px solid rgba(76, 175, 80, 0.2);
      overflow: hidden;
    }

    .header {
      background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
      color: white;
      padding: 30px;
      text-align: center;
      position: relative;
    }

    .header h1 {
      margin: 0 0 10px 0;
      font-size: 2.5rem;
    }

    .header .subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255,255,255,0.2);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

          .header-actions {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 10px;
      }

      .config-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
      }

      .config-btn:hover {
        background: rgba(255,255,255,0.3);
      }

      .logout-btn:hover {
        background: rgba(255,255,255,0.3);
      }

    .content {
      padding: 30px;
    }

    /* Estadísticas */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #e1e5e9;
      text-align: center;
      border-left: 4px solid #4caf50;
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      border-color: #4caf50;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: #4caf50;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #6c757d;
      font-size: 0.9rem;
    }

    /* Filtros y búsqueda */
    .filters-section {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      border: 1px solid #e9ecef;
    }

    .filters-grid {
      display: grid;
      grid-template-columns: 1fr auto auto auto;
      gap: 15px;
      align-items: end;
    }

    .search-group {
      position: relative;
    }

    .search-input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      outline: none;
      border-color: #4caf50;
    }

    .search-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }

    .filter-select {
      padding: 12px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      background: white;
      font-size: 14px;
      cursor: pointer;
    }

    .filter-btn {
      background: #4caf50;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .filter-btn:hover {
      background: #2e7d32;
    }

    .clear-filters {
      background: #6c757d;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .clear-filters:hover {
      background: #5a6268;
      text-decoration: none;
      color: white;
    }

    /* Filtros avanzados */
    .filters-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .filters-header h3 {
      margin: 0;
      color: #2e7d32;
      font-size: 1.2rem;
    }

    .toggle-filters-btn {
      background: #4caf50;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .toggle-filters-btn:hover {
      background: #2e7d32;
    }

    .advanced-filters {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      border: 1px solid #e9ecef;
      margin-bottom: 15px;
    }

    .filter-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .export-btn {
      background: #17a2b8;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .export-btn:hover {
      background: #138496;
      text-decoration: none;
      color: white;
    }

    .quick-filters {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      padding: 15px 0;
      border-top: 1px solid #e9ecef;
    }

    .quick-filter-label {
      font-weight: 600;
      color: #495057;
      font-size: 14px;
    }

    .quick-filter-btn {
      background: #e9ecef;
      color: #495057;
      border: none;
      padding: 8px 15px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .quick-filter-btn:hover {
      background: #4caf50;
      color: white;
    }

    .quick-filter-btn.active {
      background: #4caf50;
      color: white;
    }

    /* Tabla */
    .table-container {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      border: 1px solid #e1e5e9;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #e9ecef;
    }

    th {
      background: #f8f9fa;
      font-weight: 600;
      color: #495057;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    th:hover {
      background: #e9ecef;
    }

    th.sortable::after {
      content: '↕';
      margin-left: 5px;
      opacity: 0.5;
    }

    th.sort-asc::after {
      content: '↑';
      opacity: 1;
    }

    th.sort-desc::after {
      content: '↓';
      opacity: 1;
    }

    tr:hover {
      background-color: #f8f9fa;
    }

    .edit-input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ced4da;
      border-radius: 4px;
      font-size: 14px;
    }

    .edit-input:focus {
      outline: none;
      border-color: #4caf50;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-save {
      background: #28a745;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s ease;
    }

    .btn-save:hover {
      background: #218838;
    }

    .btn-cancel {
      background: #6c757d;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s ease;
    }

    .btn-cancel:hover {
      background: #5a6268;
    }

    .btn-delete {
      background: #dc3545;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s ease;
    }

    .btn-delete:hover {
      background: #c82333;
    }

    .status-badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
    }

    /* Paginación */
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 30px;
      padding: 20px;
    }

    .page-btn {
      padding: 10px 15px;
      border: 1px solid #dee2e6;
      background: white;
      color: #4caf50;
      text-decoration: none;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .page-btn:hover {
      background: #4caf50;
      color: white;
    }

    .page-btn.active {
      background: #2e7d32;
      color: white;
    }

    .page-btn.disabled {
      color: #6c757d;
      cursor: not-allowed;
    }

    /* Mensajes */
    .message {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      animation: slideIn 0.3s ease;
    }

    .message.success {
      background: #e8f5e8;
      color: #2e7d32;
      border: 2px solid #4caf50;
    }

    .message.error {
      background: #ffebee;
      color: #c62828;
      border: 2px solid #f44336;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .filters-grid {
        grid-template-columns: 1fr;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      table, tbody, tr, td, th {
        display: block;
        width: 100%;
      }
      
      tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
      }
      
      th {
        background: none;
        color: #00796b;
        font-weight: bold;
        border-bottom: none;
      }
      
      .action-buttons {
        justify-content: center;
      }
    }

    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="main-container">
            <div class="header">
          <div class="header-actions">
            <button class="config-btn" onclick="location.href='admin_config.php'" title="Configuración Avanzada">
              <i class="fas fa-cogs"></i> Configuración
            </button>
            <button class="logout-btn" onclick="location.href='logout.php'">
              <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
          </div>
          <h1>PORTAL TURÍSTICO E.C</h1>
          <div class="subtitle">Panel de Administración</div>
          <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong> 👋</p>
        </div>

    <div class="content">
      <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success">
          <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error">
          <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

      <!-- Estadísticas en tiempo real -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-number"><?= $userStats['total_users'] ?></div>
          <div class="stat-label">Total de Usuarios</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?= $userStats['users_today'] ?></div>
          <div class="stat-label">Registrados Hoy</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?= $userStats['users_month'] ?></div>
          <div class="stat-label">Registrados Este Mes</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?= $totalPages ?></div>
          <div class="stat-label">Páginas</div>
        </div>
      </div>

      <!-- Filtros y Búsqueda Avanzados -->
      <div class="filters-section">
        <div class="filters-header">
          <h3><i class="fas fa-filter"></i> Filtros Avanzados</h3>
          <button type="button" class="toggle-filters-btn" onclick="toggleAdvancedFilters()">
            <i class="fas fa-chevron-down"></i> Mostrar/Ocultar Filtros
          </button>
        </div>
        
        <div class="advanced-filters" id="advancedFilters" style="display: none;">
          <form method="GET" class="filters-grid" id="filtersForm">
            <!-- Búsqueda básica -->
            <div class="search-group">
              <input type="text" name="search" class="search-input" 
                     placeholder="Buscar por nombre, apellido o email..." 
                     value="<?= htmlspecialchars($search) ?>"
                     onkeyup="applyFilters()">
              <i class="fas fa-search search-icon"></i>
            </div>
            
            <!-- Filtro por rol -->
            <select name="role_filter" class="filter-select" onchange="applyFilters()">
              <option value="">Todos los roles</option>
              <option value="user" <?= ($_GET['role_filter'] ?? '') === 'user' ? 'selected' : '' ?>>Usuarios</option>
              <option value="admin" <?= ($_GET['role_filter'] ?? '') === 'admin' ? 'selected' : '' ?>>Administradores</option>
            </select>
            
            <!-- Filtro por estado -->
            <select name="status_filter" class="filter-select" onchange="applyFilters()">
              <option value="">Todos los estados</option>
              <option value="1" <?= ($_GET['status_filter'] ?? '') === '1' ? 'selected' : '' ?>>Activos</option>
              <option value="0" <?= ($_GET['status_filter'] ?? '') === '0' ? 'selected' : '' ?>>Inactivos</option>
            </select>
            
            <!-- Filtro por fecha -->
            <select name="date_filter" class="filter-select" onchange="applyFilters()">
              <option value="">Todas las fechas</option>
              <option value="today" <?= ($_GET['date_filter'] ?? '') === 'today' ? 'selected' : '' ?>>Hoy</option>
              <option value="week" <?= ($_GET['date_filter'] ?? '') === 'week' ? 'selected' : '' ?>>Esta semana</option>
              <option value="month" <?= ($_GET['date_filter'] ?? '') === 'month' ? 'selected' : '' ?>>Este mes</option>
              <option value="year" <?= ($_GET['date_filter'] ?? '') === 'year' ? 'selected' : '' ?>>Este año</option>
            </select>
            
            <!-- Ordenamiento -->
            <select name="sort" class="filter-select" onchange="applyFilters()">
              <option value="firstName" <?= $sortBy === 'firstName' ? 'selected' : '' ?>>Ordenar por Nombre</option>
              <option value="lastName" <?= $sortBy === 'lastName' ? 'selected' : '' ?>>Ordenar por Apellido</option>
              <option value="email" <?= $sortBy === 'email' ? 'selected' : '' ?>>Ordenar por Email</option>
              <option value="created_at" <?= $sortBy === 'created_at' ? 'selected' : '' ?>>Ordenar por Fecha</option>
            </select>
            
            <select name="order" class="filter-select" onchange="applyFilters()">
              <option value="ASC" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>Ascendente</option>
              <option value="DESC" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Descendente</option>
            </select>
            
            <!-- Botones de acción -->
            <div class="filter-actions">
              <button type="button" class="filter-btn" onclick="applyFilters()">
                <i class="fas fa-filter"></i> Aplicar Filtros
              </button>
              <button type="button" class="clear-filters" onclick="clearAllFilters()">
                <i class="fas fa-times"></i> Limpiar Todo
              </button>
              <button type="button" class="export-btn" onclick="exportUsers()">
                <i class="fas fa-download"></i> Exportar
              </button>
            </div>
          </form>
        </div>
        
        <!-- Filtros rápidos -->
        <div class="quick-filters">
          <span class="quick-filter-label">Filtros rápidos:</span>
          <button type="button" class="quick-filter-btn" onclick="quickFilter('recent')">
            <i class="fas fa-clock"></i> Recientes
          </button>
          <button type="button" class="quick-filter-btn" onclick="quickFilter('inactive')">
            <i class="fas fa-user-slash"></i> Inactivos
          </button>
          <button type="button" class="quick-filter-btn" onclick="quickFilter('admins')">
            <i class="fas fa-user-shield"></i> Administradores
          </button>
        </div>
      </div>

      <!-- Tabla de Usuarios -->
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th class="sortable" onclick="sortTable('firstName')">Nombre</th>
              <th class="sortable" onclick="sortTable('lastName')">Apellido</th>
              <th class="sortable" onclick="sortTable('email')">Correo</th>
              <th class="sortable" onclick="sortTable('role')">Rol</th>
              <th>Estado</th>
              <th>Fecha Registro</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['id'] ?>">
              <td>
                <span class="display-text"><?= htmlspecialchars($row['firstName']) ?></span>
                <input type="text" class="edit-input" style="display:none;" 
                       value="<?= htmlspecialchars($row['firstName']) ?>" name="firstName">
              </td>
              <td>
                <span class="display-text"><?= htmlspecialchars($row['lastName']) ?></span>
                <input type="text" class="edit-input" style="display:none;" 
                       value="<?= htmlspecialchars($row['lastName']) ?>" name="lastName">
              </td>
              <td>
                <span class="display-text"><?= htmlspecialchars($row['email']) ?></span>
                <input type="email" class="edit-input" style="display:none;" 
                       value="<?= htmlspecialchars($row['email']) ?>" name="email">
              </td>
              <td>
                <span class="display-text"><?= ucfirst(htmlspecialchars($row['role'])) ?></span>
                <select class="edit-input" style="display:none;" name="role">
                  <option value="user" <?= $row['role'] === 'user' ? 'selected' : '' ?>>Usuario</option>
                  <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
              </td>
              <td>
                <span class="status-badge <?= $row['is_active'] ? 'status-active' : 'status-inactive' ?>">
                  <?= $row['is_active'] ? 'Activo' : 'Inactivo' ?>
                </span>
              </td>
              <td>
                <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
              </td>
              <td>
                <div class="action-buttons">
                  <button class="btn-save" onclick="editUser(<?= $row['id'] ?>)">
                    <i class="fas fa-edit"></i> Editar
                  </button>
                  <button class="btn-save" onclick="saveUser(<?= $row['id'] ?>)" style="display:none;">
                    <i class="fas fa-save"></i> Guardar
                  </button>
                  <button class="btn-cancel" onclick="cancelEdit(<?= $row['id'] ?>)" style="display:none;">
                    <i class="fas fa-times"></i> Cancelar
                  </button>
                  <button class="btn-delete" onclick="deleteUser(<?= $row['id'] ?>)">
                    <i class="fas fa-trash"></i> Eliminar
                  </button>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&sort=<?= $sortBy ?>&order=<?= $sortOrder ?>" 
             class="page-btn">Anterior</a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++): ?>
          <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sortBy ?>&order=<?= $sortOrder ?>" 
             class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&sort=<?= $sortBy ?>&order=<?= $sortOrder ?>" 
             class="page-btn">Siguiente</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function sortTable(column) {
      const urlParams = new URLSearchParams(window.location.search);
      const currentSort = urlParams.get('sort');
      const currentOrder = urlParams.get('order');
      
      let newOrder = 'ASC';
      if (currentSort === column && currentOrder === 'ASC') {
        newOrder = 'DESC';
      }
      
      urlParams.set('sort', column);
      urlParams.set('order', newOrder);
      urlParams.set('page', '1'); // Reset to first page
      
      window.location.href = 'admin.php?' + urlParams.toString();
    }

    function editUser(userId) {
      const row = document.querySelector(`tr[data-id="${userId}"]`);
      const displayTexts = row.querySelectorAll('.display-text');
      const editInputs = row.querySelectorAll('.edit-input');
      const editBtn = row.querySelector('.btn-save');
      const saveBtn = row.querySelector('.btn-save:last-of-type');
      const cancelBtn = row.querySelector('.btn-cancel');
      
      displayTexts.forEach(text => text.style.display = 'none');
      editInputs.forEach(input => input.style.display = 'block');
      editBtn.style.display = 'none';
      saveBtn.style.display = 'inline-block';
      cancelBtn.style.display = 'inline-block';
    }

    function saveUser(userId) {
      const row = document.querySelector(`tr[data-id="${userId}"]`);
      const inputs = row.querySelectorAll('.edit-input');
      const formData = new FormData();
      
      formData.append('id', userId);
      inputs.forEach(input => {
        formData.append(input.name, input.value);
      });

      fetch('editar_usuario.php', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.mensaje, 'success');
          setTimeout(() => location.reload(), 1500);
        } else {
          showNotification(data.mensaje, 'error');
        }
      })
      .catch(error => {
        showNotification('Error de conexión', 'error');
      });
    }

    function cancelEdit(userId) {
      const row = document.querySelector(`tr[data-id="${userId}"]`);
      const displayTexts = row.querySelectorAll('.display-text');
      const editInputs = row.querySelectorAll('.edit-input');
      const editBtn = row.querySelector('.btn-save');
      const saveBtn = row.querySelector('.btn-save:last-of-type');
      const cancelBtn = row.querySelector('.btn-cancel');
      
      displayTexts.forEach(text => text.style.display = 'inline');
      editInputs.forEach(input => input.style.display = 'none');
      editBtn.style.display = 'inline-block';
      saveBtn.style.display = 'none';
      cancelBtn.style.display = 'none';
    }

    function deleteUser(userId) {
      if (confirm('¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
        fetch('eliminar_usuario.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification(data.mensaje, 'success');
            setTimeout(() => location.reload(), 1500);
          } else {
            showNotification(data.mensaje, 'error');
          }
        })
        .catch(error => {
          showNotification('Error de conexión', 'error');
        });
      }
    }

    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = `message ${type}`;
      notification.textContent = message;
      notification.style.position = 'fixed';
      notification.style.top = '20px';
      notification.style.right = '20px';
      notification.style.zIndex = '1000';
      notification.style.animation = 'slideIn 0.3s ease';
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.remove();
      }, 5000);
    }

    // Auto-hide messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const messages = document.querySelectorAll('.message');
      messages.forEach(message => {
        setTimeout(() => {
          message.style.opacity = '0';
          setTimeout(() => message.remove(), 300);
        }, 5000);
      });
    });

    // Funciones para filtros avanzados
    function toggleAdvancedFilters() {
      const filtersDiv = document.getElementById('advancedFilters');
      const toggleBtn = document.querySelector('.toggle-filters-btn i');
      
      if (filtersDiv.style.display === 'none') {
        filtersDiv.style.display = 'block';
        toggleBtn.className = 'fas fa-chevron-up';
      } else {
        filtersDiv.style.display = 'none';
        toggleBtn.className = 'fas fa-chevron-down';
      }
    }

    function applyFilters() {
      const form = document.getElementById('filtersForm');
      const formData = new FormData(form);
      const params = new URLSearchParams();
      
      for (let [key, value] of formData.entries()) {
        if (value) {
          params.append(key, value);
        }
      }
      
      window.location.href = 'admin.php?' + params.toString();
    }

    function clearAllFilters() {
      window.location.href = 'admin.php';
    }

    function quickFilter(type) {
      const params = new URLSearchParams();
      
      switch (type) {
        case 'recent':
          params.append('date_filter', 'week');
          params.append('sort', 'created_at');
          params.append('order', 'DESC');
          break;
        case 'inactive':
          params.append('status_filter', '0');
          break;
        case 'admins':
          params.append('role_filter', 'admin');
          break;
      }
      
      window.location.href = 'admin.php?' + params.toString();
    }

    function exportUsers() {
      const form = document.getElementById('filtersForm');
      const formData = new FormData(form);
      const params = new URLSearchParams();
      
      for (let [key, value] of formData.entries()) {
        if (value) {
          params.append(key, value);
        }
      }
      
      params.append('export', '1');
      window.location.href = 'export_users.php?' + params.toString();
    }
      </script>
    
    <!-- Botón de accesibilidad flotante -->
    <div class="accessibility-float">
      <button class="accessibility-btn" onclick="location.href='accessibility.php'" title="Configuración de Accesibilidad">
        <i class="fas fa-universal-access"></i>
      </button>
    </div>
  </body>
  </html>
