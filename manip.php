<?php
session_start();

// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "db_web";

// Connexion MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Gestion des actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Suppression
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['message'] = "Utilisateur supprimé avec succès!";
    }
    
    // Mise à jour
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = intval($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $number = htmlspecialchars($_POST['number']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        $stmt = $conn->prepare("UPDATE utilisateurs SET name=?, number=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $number, $email, $id);
        $stmt->execute();
        $_SESSION['message'] = "Modifications enregistrées!";
    }
    
    // Nouvel utilisateur
    if (!isset($_POST['action'])) {
        $name = htmlspecialchars($_POST['name']);
        $number = htmlspecialchars($_POST['number']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        $stmt = $conn->prepare("INSERT INTO utilisateurs (name, number, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $number, $email);
        $stmt->execute();
        $_SESSION['message'] = "Utilisateur ajouté avec succès!";
    }
    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Récupération des utilisateurs
$sql = "SELECT * FROM utilisateurs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Adhérents</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    padding: 90px 20px 40px;
    background: #f5f7fa;
    min-height: 100vh;
    color: #333;
    line-height: 1.6;
}

/* Header */
header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to right, #6c5ce7, #8a6af5);
    padding: 1rem 2rem;
    box-shadow: 0 4px 20px rgba(108, 92, 231, 0.2);
    z-index: 1000;
}

.nav-links {
    display: flex;
    gap: 2rem;
    list-style: none;
}

.nav-links a {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s;
    font-weight: 500;
}

.nav-links a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Titre de section */
h2 {
    color: #444;
    margin: 40px 0 20px;
    text-align: center;
    position: relative;
    font-size: 1.8rem;
}

h2:after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: #6c5ce7;
    margin: 10px auto 0;
    border-radius: 2px;
}

/* Formulaire */
form {
    background: white;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    max-width: 550px;
    margin: 30px auto;
    transition: transform 0.3s, box-shadow 0.3s;
}

form:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 24px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

input {
    width: 100%;
    padding: 14px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    margin-top: 5px;
    font-size: 1rem;
    transition: all 0.3s;
}

input:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
    outline: none;
}

button[type="submit"] {
    background: linear-gradient(to right, #6c5ce7, #8a6af5);
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(108, 92, 231, 0.3);
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(108, 92, 231, 0.4);
}

button[type="submit"]:active {
    transform: translateY(1px);
}

/* Tableau */
table {
    width: 100%;
    max-width: 1100px;
    margin: 30px auto;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    overflow: hidden;
}

th, td {
    padding: 16px;
    text-align: left;
}

tr:not(:last-child) td {
    border-bottom: 1px solid #eef0f5;
}

th {
    background: linear-gradient(to right, #6c5ce7, #8a6af5);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

tr:hover td {
    background-color: #f9fafc;
}

/* Boutons d'action améliorés */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.btn {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
    font-size: 0.85rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    min-width: 90px;
    text-align: center;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

.btn-edit {
    background: #22c55e;
    color: white;
}

.btn-delete {
    background: #7c3aed;
    color: white;
}

.btn-save {
    background: #3b82f6;
    color: white;
}

/* Style pour les boutons inline dans les formulaires */
form .btn {
    display: inline-block;
    margin: 0;
}

form[style*="display:inline"] {
    display: inline-block !important;
    margin: 0;
    padding: 0;
    background: none;
    box-shadow: none;
    border-radius: 0;
    max-width: unset;
}

form[style*="display:inline"]:hover {
    transform: none;
    box-shadow: none;
}

form[style*="display:inline"] button {
    min-width: 90px;
}

/* Messages */
.message {
    padding: 16px;
    margin: 25px auto;
    max-width: 550px;
    border-radius: 10px;
    background: #e3f2fd;
    color: #2196F3;
    box-shadow: 0 4px 15px rgba(33, 150, 243, 0.15);
    text-align: center;
    font-weight: 500;
    animation: fadeIn 0.5s ease-out;
    border-left: 5px solid #2196F3;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
    body {
        padding: 80px 15px 30px;
    }
    
    form {
        padding: 25px;
        margin: 20px auto;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 12px;
    }
    
    .action-buttons {
        flex-direction: row;
        gap: 4px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 0.8rem;
        min-width: 70px;
    }
}

/* Style pour le formulaire d'édition */
#adherents tr[id^="edit-"] {
    background-color: #f9f9ff;
}

#adherents tr[id^="edit-"] input {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

#adherents tr[id^="edit-"] td {
    padding: 10px;
}

#adherents tr[id^="edit-"] button {
    margin-right: 4px;
}

/* Animations pour les boutons */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn:focus {
    animation: pulse 0.3s ease-in-out;
    outline: none;
}

/* Style pour les boutons de petite taille dans le tableau */
td .btn {
    height: auto;
    line-height: normal;
}

/* Correction pour les formulaires inline */
td form {
    margin: 0;
    padding: 0;
    display: inline;
}
    </style>
</head>
<body>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form id="inscription" method="POST">
        <div class="form-group">
            <label>Nom complet:</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Téléphone:</label>
            <input type="tel" name="number" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <button type="submit">Enregistrer</button>
    </form>

    <section id="adherents">
        <h2>Liste des adhérents</h2>
        <table>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['number']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <div class="action-buttons">
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-delete">Supprimer</button>
                        </form>
                        <button class="btn btn-edit" onclick="toggleEdit(<?= $row['id'] ?>)">Modifier</button>
                    </div>
                </td>
            </tr>
            
            <!-- Formulaire d'édition -->
            <tr id="edit-<?= $row['id'] ?>" style="display:none">
                <form method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><input type="text" name="number" value="<?= htmlspecialchars($row['number']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                    <td>
                        <button type="submit" class="btn btn-save">Enregistrer</button>
                        <button type="button" class="btn btn-delete" onclick="toggleEdit(<?= $row['id'] ?>)">Annuler</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <script>
        function toggleEdit(id) {
            const row = document.getElementById(`edit-${id}`);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>