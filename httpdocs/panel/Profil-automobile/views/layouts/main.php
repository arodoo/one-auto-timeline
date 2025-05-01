<?php
// filepath: panel/Profil-automobile/views/layouts/main.php
global $path_cms_general;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des véhicules</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $path_cms_general; ?>panel/Profil-automobile/assets/css/vehicle.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Gestion de mes véhicules</h2>
                
                <?php include dirname(__DIR__) . '/partials/menu.php'; ?>
                
                <!-- Main Content -->
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo $path_cms_general; ?>panel/Profil-automobile/assets/js/vehicle.js"></script>
    <script src="<?php echo $path_cms_general; ?>panel/Profil-automobile/assets/js/vehicle-enhanced.js"></script>
    <script src="<?php echo $path_cms_general; ?>panel/Profil-automobile/assets/js/datatables-config.js"></script>
    <script src="<?php echo $path_cms_general; ?>panel/Profil-automobile/assets/js/forms.js"></script>
</body>
</html>