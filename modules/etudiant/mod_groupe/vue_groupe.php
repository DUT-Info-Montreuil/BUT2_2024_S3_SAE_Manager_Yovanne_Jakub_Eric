<?php

include_once 'generique/vue_generique.php';

class VueGroupe extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherGroupeSAE($grpSAE, $nomGrp)

    {
        ?>
        <div class="container mt-4">
            <h2> Groupe : <?php echo htmlspecialchars($nomGrp) ?> </h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
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
        </div>
        <?php
    }


}