<?php
include_once 'generique/vue_generique.php';

class VueNoteFinalProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllNoteAndEtudiant($allNoteFinalAndEtudiant)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4 text-center">Notes Finales</h1>

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Groupe</th>
                    <th scope="col">Note Finale</th>
                    <th scope="col">Modifier</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($allNoteFinalAndEtudiant)): ?>
                    <?php foreach ($allNoteFinalAndEtudiant as $etudiant): ?>
                        <tr>
                            <td><?= htmlspecialchars($etudiant['nom_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['email_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['nom_groupe']) ?></td>
                            <td class="<?= $etudiant['note_finale'] >= 10 ? 'text-success' : 'text-danger' ?>">
                                <?= htmlspecialchars($etudiant['note_finale']) ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalModifierNote<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">
                                    Modifier
                                </button>

                                <div class="modal fade" id="modalModifierNote<?= htmlspecialchars($etudiant['id_utilisateur']) ?>" tabindex="-1" aria-labelledby="modalLabel<?= htmlspecialchars($etudiant['id_utilisateur']) ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">Modifier la Note</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="index.php?module=notefinalprof&action=modifierNoteFinal" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">
                                                    <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($etudiant['id_groupe']) ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nouvelle Note</label>
                                                        <input type="number" class="form-control" id="noteFinale<?= htmlspecialchars($etudiant['id_utilisateur']) ?>" name="note_finale" value="<?= htmlspecialchars($etudiant['note_finale']) ?>" required step="any" min="0" max="20">
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                                </div>
                                            </form>
                                            <div class="text-center mb-4">
                                                <form action="index.php?module=notefinalprof&action=reinitialisernoteFinal" method="POST">
                                                    <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($etudiant['id_utilisateur']) ?>">
                                                    <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($etudiant['id_groupe']) ?>">
                                                    <button type="submit" class="btn btn-warning text-white" style="background-color: #f0ad4e; border-color: #eea236;">
                                                        Réinitialiser
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune donnée disponible</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

}