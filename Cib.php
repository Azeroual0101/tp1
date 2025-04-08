<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST["first-name"]) && isset($_POST["last-name"]) && isset($_POST["email"]) 
        && isset($_POST["password"]) && isset($_POST["phone-number"]) && isset($_POST["dob"]) 
        && isset($_POST["gender"]))
    {
        $fullName = $_POST["first-name"] . " " . $_POST["last-name"];
        
        echo "<h1>Hello Mr/Mme <span style='color: red;'>".$fullName."</span></h1>";
        echo "
        <table style='border: 1px solid;'>
        <thead>
            <tr style='border: 1px solid;'>
                <th style='border: 1px solid;'> Email</th>
                <th style='border: 1px solid;'> Date de naissance</th>
                <th style='border: 1px solid;'> Téléphone</th>
                <th style='border: 1px solid;'> Genre</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='border: 1px solid;'>".$_POST["email"]."</td>
                <td style='border: 1px solid;'>".$_POST["dob"]."</td>
                <td style='border: 1px solid;'>".$_POST["phone-number"]."</td>
                <td style='border: 1px solid;'>".$_POST["gender"]."</td>
            </tr>
        </tbody>
        </table>";
    }
    else {
        echo "<div style='color: red; text-align: center; margin-top: 20px;'>
              <h2>Erreur !!!!</h2>
              <p>Tous les champs sont requis</p>
              </div>";
    }
}
else {
    echo "<div style='color: red; text-align: center; margin-top: 20px;'>
          <h2>Erreur de requête !!!</h2>
          <p>La méthode doit être POST</p>
          </div>";
}
?>