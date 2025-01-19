<?php

include_once 'generique/vue_generique.php';

class VueDashboard extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function renderDashboard($projectData, $groups, $evaluations, $resources, $defenses)
    {
        ?>
        <div class="container mt-5">

            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <h1 class="display-4 text-primary"><?php echo htmlspecialchars($projectData['titre']); ?> - Dashboard</h1>
                    <p class="lead"><?php echo htmlspecialchars($projectData['description_projet']); ?></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5>Informations du Projet</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Année Universitaire:</strong> <?php echo htmlspecialchars($projectData['annee_universitaire']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5>Groupes Associés</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Nom du Groupe</th>
                                    <th>Modifiable</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($groups as $group): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($group['nom']); ?></td>
                                        <td><?php echo $group['modifiable_par_groupe'] ? 'Oui' : 'Non'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5>Evaluations</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Coefficient</th>
                                    <th>Note Max</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($evaluations as $evaluation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($evaluation['type_evaluation']); ?></td>
                                        <td><?php echo htmlspecialchars($evaluation['coefficient']); ?></td>
                                        <td><?php echo htmlspecialchars($evaluation['note_max']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5>Ressources</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <?php foreach ($resources as $resource): ?>
                                    <li class="list-group-item">
                                        <a href="<?php echo htmlspecialchars($resource['lien']); ?>" target="_blank">
                                            <?php echo htmlspecialchars($resource['titre']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5>Soutenances</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($defenses as $defense): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($defense['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($defense['date_soutenance']); ?></td>
                                        <td><?php echo htmlspecialchars($defense['heure_passage']); ?></td>
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

