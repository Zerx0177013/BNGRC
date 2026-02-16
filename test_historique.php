<?php
$config = include 'app/config/config.php';
try {
    $pdo = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_name'], $config['db_user'], $config['db_pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lire et exécuter le fichier SQL
    $sql = file_get_contents('script/insertion/2026-02-13_04_test_historique.sql');
    $pdo->exec($sql);
    echo 'Données de test insérées avec succès.' . PHP_EOL;

    // Tester la vue
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM v_historique_objet');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Nombre d\'entrées dans v_historique_objet: ' . $result['total'] . PHP_EOL;

    // Test détaillé
    echo PHP_EOL . '=== Test de la vue v_historique_objet ===' . PHP_EOL;
    $stmt = $pdo->query('SELECT * FROM v_historique_objet ORDER BY idItem, dateEchange LIMIT 10');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "Objet: {$row['nom_objet']} (ID: {$row['idItem']}) - Ancien: {$row['ancien_proprietaire']} -> Nouveau: {$row['nouveau_proprietaire']} ({$row['dateEchange']})" . PHP_EOL;
    }

} catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . PHP_EOL;
}
?>