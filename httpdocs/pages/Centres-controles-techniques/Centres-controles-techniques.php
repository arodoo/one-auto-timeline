<?php
// Function pour supprimer les accents
function lettre_sans_accent($chaine)
{
    $normalized = \Normalizer::normalize($chaine, \Normalizer::FORM_D);
    $sans_accent = preg_replace('/[\p{Mn}]/u', '', $normalized);
    return $sans_accent;
}



?>
<script>
    $(document).ready(function() {
        let page = 1;

        function fetchAnnonces(page = 1, append = false) {
            let motsCles = $('#mots_cles_marketplace').val();
            let minPrix = $('#min-price').val();
            let maxPrix = $('#max-price').val();
            let idDepartement = $('#id_departement_annonce').val() === 'Tous les départements' ? '' : $('#id_departement_annonce').val();

            $.ajax({
                url: '/pages/Centres-controles-techniques/Centres-controles-techniques-filtres-ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    mots_cles_marketplace: motsCles,
                    min_prix: minPrix,
                    max_prix: maxPrix,
                    id_departement_annonce: idDepartement,
                    page: page
                },
                success: function(response) {
                    if (response.status === "success") {
                        let annoncesHtml = '';

                        if (response.data.length > 0) {
                            response.data.forEach(annonce => {
                                let cleanTitle = annonce.annonce_nom.normalize('NFD').replace(/[\u0300-\u036f]/g, "").split(' ')[0].toLowerCase();
                                let avgNote = Math.round(annonce.avg_note);

                                annoncesHtml += `
                                    <div class="col-lg-12 col-xl-6 col-xxl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row m-b-30">
                                                    <div class="col-md-5 col-xxl-12">
                                                        <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                                                            <div class="new-arrivals-img-contnent">
                                                                <a href="/Page-centre-controle-technique/${cleanTitle}/${annonce.annonce_id}" 
                                                                   title="${annonce.annonce_nom}">
                                                                    <img class="img-fluid" src="/images/membres/${annonce.pseudo}/${annonce.image_name}" 
                                                                         alt="${annonce.annonce_nom}">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 col-xxl-12">
                                                        <div class="new-arrival-content position-relative">
                                                            <h4><a href="/Page-centre-controle-technique/${cleanTitle}/${annonce.annonce_id}" 
                                                                   title="${annonce.annonce_nom}">
                                                                   ${annonce.annonce_nom}
                                                               </a></h4>
                                                            <div class="comment-review star-rating">
                                                                <ul>
                                                                    ${[...Array(5)].map((_, i) => `<li><i class="fa fa-star" style="color: ${i < avgNote ? '#ffc107' : '#d8d8d8'}"></i></li>`).join('')}
                                                                </ul>
                                                                <span class="review-text">(${annonce.total_reviews} avis)</span>
                                                                <p class="price">€${annonce.annonce_prix}</p>
                                                            </div>
                                                            <p>Centre: <span class="item"><a href="/Fiche/${annonce.pseudo}/8">${annonce.pseudo}</a></span></p>
                                                            <p class="text-content">${annonce.annonce_description}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else if (!append) {
                            annoncesHtml = `<p class="text-center">Aucun résultat trouvé.</p>`;
                        }

                        if (append) {
                            $('#annonces-results').append(annoncesHtml);
                        } else {
                            $('#annonces-results').html(annoncesHtml);
                        }
                    } else {
                        $('#annonces-results').html(`<p class="text-center text-danger">${response.message}</p>`);
                    }
                },
                error: function() {
                    $('#annonces-results').html(`<p class="text-center text-danger">Une erreur s'est produite.</p>`);
                }
            });
        }

        $('#filterForm').on('submit', function(event) {
            event.preventDefault();
            page = 1; // Reiniciar la página a 1 cuando se envía el formulario
            fetchAnnonces();
        });

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                fetchAnnonces(++page, true);
            }
        });

        fetchAnnonces();
    });
</script>


<div class="filter cm-content-box box-primary">
    <div class="content-title SlideToolHeader">
        <div class="cpa">
            <i class="fa-sharp fa-solid fa-filter me-2"></i>Formulaire de recherche
        </div>
    </div>
    <div class="cm-content-body form excerpt">
        <div class="card-body">
            <form id="filterForm" method="POST">
                <div class="row">
                    <div class="col-xl-4 col-sm-6">
                        <label class="form-label">Mots clés</label>
                        <input type="text" class="form-control mb-xl-0 mb-3" id="mots_cles_marketplace" name="mots_cles_marketplace" id="exampleFormControlInput1" placeholder="Mots clés">
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <label class="form-label">Prix Minimum</label>
                        <div class="input-hasicon mb-sm-0 mb-3">
                            <input type="text" id="min-price" name="min_prix" class="form-control" placeholder="Min">
                            <div class="icon"><i class="fas fa-euro-sign"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <label class="form-label">Prix Maximum</label>
                        <div class="input-hasicon mb-sm-0 mb-3">
                            <input type="text" id="max-price" name="max_prix" class="form-control" placeholder="Max">
                            <div class="icon"><i class="fas fa-euro-sign"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <label class="form-label">Département</label>
                        <select name="id_departement_annonce" id="id_departement_annonce" class="form-control selectpicker" data-live-search="true">
                            <option selected="all">Tous les départements</option>
                            <?php
                            $stmt = $bdd->query('SELECT id, code, name FROM dpts');
                            $departments = $stmt->fetchAll();
                            foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['id']) ?>"><?= htmlspecialchars($dept['code']) ?> - <?= htmlspecialchars($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xl-4 col-sm-6 align-self-end">
                        <button class="btn btn-primary me-2" title="Rechercher" type="submit" style="padding: 12px;">Rechercher</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row" id="annonces-results">
    <?php
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 9;
    $offset = ($page - 1) * $limit;

    $stmt = $bdd->prepare("
        SELECT 
            i.id AS image_id,
            i.nom_image AS image_name,
            a.id AS annonce_id,
            a.nom AS annonce_nom,
            a.description AS annonce_description,
            a.prix AS annonce_prix,
            a.pseudo,
            COALESCE(AVG(n.note), 0) AS avg_note, 
            COALESCE(COUNT(n.id), 0) AS total_reviews
        FROM 
            membres_annonces_ct_images i
        INNER JOIN 
            membres_annonces_ct a ON i.id_annonce_service = a.id 
        LEFT JOIN 
            membres_avis n ON a.id = n.id_page
        WHERE 
            a.statut = 'activé'
        GROUP BY 
            a.id, i.id
        ORDER BY 
            a.id DESC 
        LIMIT :limit OFFSET :offset;
    ");

    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $annonces = $stmt->fetchAll();
    foreach ($annonces as $annonce):
        $cleanTitle = lettre_sans_accent($annonce['annonce_nom']);
        $cleanTitle = explode(' ', trim($cleanTitle))[0];
        $cleanTitle = strtolower($cleanTitle);

        // Convertir valores para evitar errores
        $avgNote = (float)$annonce['avg_note'];
        $totalReviews = (int)$annonce['total_reviews'];
    ?>
        <div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-6">
            <div class="card">
                <div class="card-body product-grid-card">
                    <div class="new-arrival-product">
                        <div class="new-arrivals-img-contnent">
                            <a href="/Page-annonce/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($annonce['annonce_id']) ?>"
                                title="<?= htmlspecialchars($annonce['annonce_nom']) ?>">
                                <img class="img-fluid" src="/images/membres/<?= htmlspecialchars($annonce['pseudo']) ?>/<?= htmlspecialchars($annonce['image_name']) ?>"
                                    alt="<?= htmlspecialchars($annonce['annonce_nom']) ?>">
                            </a>
                        </div>
                        <div class="new-arrival-content text-center mt-3">
                            <h4><a href="/Page-annonce/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($annonce['annonce_id']) ?>"
                                    title="<?= htmlspecialchars($annonce['annonce_nom']) ?>">
                                    <?= htmlspecialchars($annonce['annonce_nom']) ?>
                                </a></h4>

                            <!-- Mostrar estrellas -->
                            <ul class="star-rating">
                                <?php
                                $avgNoteRounded = round($avgNote);
                                for ($i = 0; $i < 5; $i++): ?>
                                    <li><i class="fa fa-star" style="color: <?= $i < $avgNoteRounded ? '#ffc107' : '#d8d8d8' ?>"></i></li>
                                <?php endfor; ?>
                            </ul>

                            <!-- Mostrar total de reseñas -->
                            <span class="review-text">(<?= htmlspecialchars($totalReviews) ?> avis)</span>

                            <p>Centre: <span class="item"><a href="/Fiche/<?= htmlspecialchars($annonce['pseudo']) ?>/8"><?= htmlspecialchars($annonce['pseudo']) ?></a></span></p>
                            <p class="text-content"><?= htmlspecialchars($annonce['annonce_description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>