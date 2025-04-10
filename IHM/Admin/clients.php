<?php
session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../IHM/Admin/login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoltForce - Administration</title>
    
    <link rel="stylesheet" href="/IHM/css/bootstrap.css">
    <link rel="stylesheet" href="/IHM/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    .menu-i {
        color: white;
        font-size: 25px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        padding: 5px;
        width: 35px;
        height: 35px;
        margin-top: 0;
        margin-left: 0;
        background: linear-gradient(to bottom, #004E89, #0077B6);
        cursor: pointer;
    }

    .menu {
        display: none;
        position: fixed;
        top: 55px;
        left: 0;
        width: 220px;
        height: calc(100vh - 60px);
        background-color: rgb(235, 233, 233);
        box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.3);
        z-index: 10;
        transition: width 0.3s ease;
    }

    .menu.open {
        display: block;
    }

    .menu ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu ul li {
        padding: 10px;
        cursor: pointer;
    }

    .menu ul li a {
        text-decoration: none;
        font-size: 16px;
        color: black;
        display: flex;
        align-items: center;
    }

    .menu ul li a i {
        margin-right: 10px;
        font-size: 20px;
        color: #555;
    }

    .menu ul li:hover {
        background: linear-gradient(to bottom, #004E89, #0077B6);
    }

    .main {
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 80%;
        max-width: 1000px;
        margin: 0 auto 20px auto;
    }

    .page-title {
        color: #004E89;
        margin: 0;
    }

    table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
        max-width: 1000px;
        border: 1px solid #005F99;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #FFFFFF;
    }    
      
    th {
        background-color: #0077B6;
        color: #FFF;
        font-weight: bold;
        padding: 15px;
        text-align: center;
        border-bottom: 2px solid #90E0EF;
    }

    td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #90E0EF;
        color: #333;
    }

    tr:nth-child(even) {
        background-color: #CAF0F8;
    }

    tr:hover {
        background-color: #00B4D8;
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
    }

    table td button {
        text-decoration: none;
        color: #0077B6;
        font-weight: bold;
        margin: 0 5px;
    }

    table td button:hover {
        color: #00B4D8;
        text-decoration: underline;
    }

    @media only screen and (max-width: 768px) {
        .table-header, table {
            width: 95%;
        }

        th, td {
            padding: 10px;
            font-size: 14px;
        }
    }

    .add-new-btn {
        padding: 8px 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .add-new-btn:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body class="hero_area">

    <!-- Header Section -->
    <?php include('header.php') ?>
    <main>
    <?php
        if(isset($message)&& !empty($message)){
            echo "<script>alert('".$message."');</script>";
        }
    ?>
        <div class="main">
            <div class="main-container">
                <div class="table-header">
                <h2 class="page-title">Clients</h2>
                    <button class="add-new-btn" onclick="window.location.href='/IHM/Admin/add_client.php'">Ajouter un client</button>

                </div>
                <?php
                    if(isset($errors)&& !empty($errors)){
                    echo "<span style=\"color: red;\"> $errors </span>";
                    }
                ?>
                <table id="reclamationTable">
                    <thead>
                    <tr>
                        <th>ID Client</th>
                        <th>Numero Compteur</th>
                       
                        <th>Adresse Installation</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                if (count($clients) > 0) {
                    foreach ($clients as $row) {
                        echo "<tr>
                                <td>{$row['id_client']}</td>
                                <td>{$row['numero_compteur']}</td>
                                <td>{$row['adresse_installation']}</td>
                                <td>
                                    <div class='action-buttons'>
                                        <button class='btn-edit' onclick='editClient({$row['id_client']})'>
                                            <i class='fas fa-edit'></i> Modifier
                                        </button>
                                    </div>
                                </td>
                              </tr>";
                    }} else {
                    echo "<tr><td colspan='6' class='text-center'>Aucune réclamation trouvée.</td></tr>";
                }
                ?>
                           
                       
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- Footer Section -->
  <?php include('footer.php') ?>
  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
  <script src="/IHM/js/custom.js"></script>

  <script src="/IHM/js/upload.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/IHM/js/reclamation.js"></script> 
    <script>
        // Fonctions pour gérer les actions
        function editClient(clientId) {
            // Redirection vers la page de modification avec l'ID du client
            window.location.href = '/Traitement/Utilisateurs.php?action=editClient&id=' + clientId;
        }

       
    </script>
    
</body>
</html>