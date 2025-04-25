<?php
// Fetch user's vehicle data
$vehicle_data = null;
try {
    $sql = "SELECT * FROM membres_profil_auto WHERE id_membre = :id_membre";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_membre' => $id_oo]);
    $vehicle_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
}

// Fetch documents - Updated to use membres_profil_auto_documents table
$documents = [];
try {
    $sql = "SELECT * FROM membres_profil_auto_documents WHERE id_membre = :id_membre ORDER BY date DESC LIMIT 5";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_membre' => $id_oo]);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
}

// Get document categories
$categories = [];
try {
    $sql = "SELECT * FROM configurations_categorie_documents ORDER BY position ASC";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error
}
?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Mes documents</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                    <i class="fas fa-plus"></i> Ajouter un document
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($documents)): ?>
                    <div class="alert alert-info">
                        <p>Vous n'avez pas encore de documents. Cliquez sur "Ajouter un document" pour commencer.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Catégorie</th>
                                    <th>Nom</th>
                                    <th>Date d'ajout</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $doc): 
                                    // Get category name
                                    $category_name = "";
                                    foreach ($categories as $category) {
                                        if ($category['id'] == $doc['id_categorie']) {
                                            $category_name = $category['nom'];
                                            break;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category_name); ?></td>
                                        <td><?php echo htmlspecialchars($doc['nom']); ?></td>
                                        <td><?php echo date('d/m/Y', $doc['date']); ?></td>
                                        <td>
                                            <a href="<?php echo $doc['lien']; ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger delete-document" data-id="<?php echo $doc['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <a href="/Mes-documents" class="btn btn-link">Voir tous les documents <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Ajouter un document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Catégorie de document</label>
                        <select class="form-select" id="documentType" name="documentType" required>
                            <option value="">Sélectionner</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Fichier (Image)</label>
                        <input type="file" class="form-control" id="documentFile" name="documentFile" accept="image/*" required>
                        <small class="form-text text-muted">Seules les images sont acceptées (JPG, PNG, GIF).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>