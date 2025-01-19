<?php

include_once 'generique/vue_generique.php';

class VueDashboard extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function renderDashboard($projectData, $groups, $evaluations, $resources, $soutenance, $rendu)
    {
        ?>
        <div class="container mt-5">

            <div class="row mb-5 text-center">
                <div class="col-md-12">
                    <h1 class="display-4 text-primary fw-bold"><?php echo htmlspecialchars($projectData['titre']); ?> - Dashboard</h1>
                    <p class="lead text-muted"><?php echo htmlspecialchars($projectData['description_projet']); ?></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header" style="background-color: #FF7043; color: white; text-align: center; padding: 15px;">
                            <h5 class="mb-0">Informations du Projet</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Année Universitaire:</strong> <?php echo htmlspecialchars($projectData['annee_universitaire']); ?></p>
                            <p><strong>Semestre:</strong> <?php echo htmlspecialchars($projectData['semestre']); ?></p>
                            <p><strong>Responsable du Projet:</strong> <?php echo htmlspecialchars($projectData['responsable_projet']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header bg-success text-white text-center py-3">
                            <h5 class="mb-0">Groupes Associés</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="groupsAccordion">
                                <?php foreach ($groups as $index => $group): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($group['nom']); ?>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#groupsAccordion">
                                            <div class="accordion-body">
                                                <p><strong>Modifiable par le groupe :</strong> <?php echo $group['modifiable_par_groupe'] ? 'Oui' : 'Non'; ?></p>

                                                <h6>Membres :</h6>
                                                <ul>
                                                    <?php foreach ($group['membres'] as $member): ?>
                                                        <li><?php echo htmlspecialchars($member['prenom'] . ' ' . $member['nom']); ?> (<?php echo htmlspecialchars($member['email']); ?>)</li>
                                                    <?php endforeach; ?>
                                                </ul>

                                                <!-- Notes des groupes -->
                                                <h6>Notes :</h6>
                                                <table class="table table-bordered table-striped">
                                                    <thead class="table-dark">
                                                    <tr>
                                                        <th>Type d'Évaluation</th>
                                                        <th>Nom du Rendu</th>
                                                        <th>Note</th>
                                                        <th>Membre</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($group['notes'] as $note): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($note['type_evaluation']); ?></td>
                                                            <td>
                                                                <?php echo htmlspecialchars($note['nom_soutenance'] ?? $note['nom_rendu'] ?? 'N/A'); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo htmlspecialchars($note['note']) . '/' . htmlspecialchars($note['note_max']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo htmlspecialchars($note['prenom'] . ' ' . $note['nom_etudiant']); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header bg-warning text-dark text-center py-3">
                            <h5 class="mb-0">Ressources</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($resources as $resource): ?>
                                    <li class="list-group-item">
                                        <a href="<?php echo htmlspecialchars($resource['lien']); ?>" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-link-45deg"></i> <?php echo htmlspecialchars($resource['titre']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header bg-secondary text-white text-center py-3">
                            <h5 class="mb-0">Rendu</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($rendu)): ?>
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date Limite</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($rendu as $rend): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($rend['titre']); ?></td>
                                            <td><?php echo htmlspecialchars($rend['date_limite']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted text-center">Aucun rendu prévu pour ce projet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header bg-danger text-white text-center py-3">
                            <h5 class="mb-0">Soutenances</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($soutenance)): ?>
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($soutenance[0]['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($soutenance[0]['date_soutenance']); ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted text-center">Aucune soutenance programmée pour ce projet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-lg rounded border-0">
                        <div class="card-header" style="background-color: #4223af; color: white; text-align: center; padding: 15px;">                            <h5 class="mb-0">Evaluations</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                <tr>
                                    <th>Type</th>
                                    <th>Nom</th>
                                    <th>Coefficient</th>
                                    <th>Note Max</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($evaluations as $evaluation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($evaluation['type_evaluation']); ?></td>
                                        <td><?php echo htmlspecialchars($evaluation['titre_rendu'] ?: $evaluation['titre_soutenance']); ?></td>
                                        <td><?php echo htmlspecialchars($evaluation['coefficient']); ?></td>
                                        <td><?php echo htmlspecialchars($evaluation['note_max']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }

}
