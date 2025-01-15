<?php

include_once 'generique/vue_generique.php';

class VueGroupeEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherGroupeSAE($grpSAE, $nomGrp, $champARemplir, $idSae) {
        ?>
        <div class="container mt-4">
            <h2 style="text-align: center"> Groupe : <?php echo htmlspecialchars($nomGrp); ?> </h2>

            <table style="margin-bottom: 50px" class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($grpSAE as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <?php if(!empty($champARemplir)) : ?>
            <h3 style="text-align: center" class="mt-4">Champs à remplir :</h3>
            <form method="POST" action="index.php?module=groupeetud&action=updateChamps&idProjet=<?php echo $idSae; ?>">
                <table style="margin-bottom: 50px" class="table table-bordered table-striped mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>Nom du Champ</th>
                        <th>Valeur</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($champARemplir as $champ) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($champ['champ_nom']) . "</td>";
                        if ($champ['champ_valeur'] === NULL) {
                            echo "<td><input type='text' name='champ_" . $champ['id_champ'] . "' class='form-control'></td>";
                        } else {
                            echo "<td><input type='text' name='champ_" . $champ['id_champ'] . "' class='form-control' value='" . htmlspecialchars($champ['champ_valeur']) . "'></td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
            <?php endif; ?>
        </div>
        <?php
    }



}