<?php
include_once 'generique/vue_generique.php';
Class VueGerant extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function afficherGerantSAE($gerantSAE) {
        ?>
        <div class="container mt-4">
            <h2>Gestion des Gérants pour la SAE</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom Prénom</th>
                    <th>Rôle</th>
                    <th>Modifier</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($gerantSAE)) {
                    $currentGroup = null;

                    foreach ($gerantSAE as $row) {
                        if ($currentGroup === null || $currentGroup['id_utilisateur'] !== $row['id_utilisateur']) {
                            if ($currentGroup !== null) {
                                echo "<tr>";
                                echo "<td>{$currentGroup['nom_complet']}</td>";
                                echo "<td>{$currentGroup['role_utilisateur']}</td>";
                                echo "<td>
                                    <a href='index.php?module=gerantprof&action=versModifierGerant&idGerant={$currentGroup['id_utilisateur']}' class='btn btn-sm btn-secondary'>
                                        <i class='fas fa-cog'></i>
                                    </a>
                                  </td>";
                                echo "</tr>";
                            }
                            $currentGroup = [
                                'nom_complet' => $row['nom_complet'],
                                'id_utilisateur' => $row['id_utilisateur'],
                                'role_utilisateur' => $row['role_utilisateur']
                            ];
                        }
                    }

                    if ($currentGroup !== null) {
                        echo "<tr>";
                        echo "<td>{$currentGroup['nom_complet']}</td>";
                        echo "<td>{$currentGroup['role_utilisateur']}</td>";
                        echo "<td>
                            <a href='index.php?module=gerantprof&action=versModifierGerant&idGroupe={$currentGroup['id_utilisateur']}' class='btn btn-sm btn-secondary'>
                                <i class='fas fa-cog'></i>
                            </a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun gérant trouvé pour cette SAE</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=gerantprof&action=ajouterGerantFormulaire" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Gérant
                </a>
            </div>
        </div>
        <?php
    }
}