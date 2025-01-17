<?php

include_once 'generique/vue_generique.php';

class VueGroupeEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherGroupeSAE($grpSAE, $nomGrp, $champsPrepares, $idSae) {
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
                <?php foreach ($grpSAE as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (!empty($champsPrepares)): ?>
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
                        <?php foreach ($champsPrepares as $champ): ?>
                            <tr>
                                <td><?php echo $champ['nom']; ?></td>
                                <td>
                                    <input type="text" name="champ_<?php echo $champ['id']; ?>"
                                           class="form-control"
                                           value="<?php echo $champ['valeur']; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }



}