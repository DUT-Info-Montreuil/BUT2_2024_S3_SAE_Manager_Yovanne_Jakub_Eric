<?php

include_once 'generique/vue_generique.php';
Class VueParametre extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherCompte($compte)
    {
        if (!empty($compte)) {
            ?>
            <h2 style="text-align:center;">Informations du compte</h2>

            <!-- Div qui contient le tableau centré -->
            <div style="display: flex; justify-content: center; align-items: center; height: 75vh;">
                <div style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                    <table border="1" style="border-collapse: collapse; width: 100%; text-align: left;">
                        <tr>
                            <th style="padding: 8px;">Nom</th>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($compte[0]['nom']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 8px;">Prénom</th>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($compte[0]['prenom']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 8px;">Email</th>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($compte[0]['email']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 8px;">Login</th>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($compte[0]['login_utilisateur']); ?></td>
                        </tr>
                        <tr>
                            <th style="padding: 8px;">Mot de passe</th>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($compte[0]['password_utilisateur']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        } else {
            echo "<p>Aucun compte trouvé.</p>";
        }
    }
}