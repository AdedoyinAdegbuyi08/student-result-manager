<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

// 1. Only accept form submissions. Opening this page directly goes back to the form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// 2. Validate the input.
$checked = validateInput($_POST);
$errors  = $checked['errors'];
$name    = $checked['name'];
$scores  = $checked['scores'];

// 3. If anything is wrong, show the form again with messages and the old values.
if (!empty($errors)) {
    $old = ['name' => $name, 'scores' => $scores];
    require __DIR__ . '/form.php';
    exit;
}

// 4. All good: calculate.
$total   = calculateTotal($scores);
$average = calculateAverage($scores);
$grade   = calculateGrade($average);
$remark  = gradeRemark($grade);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result Sheet: <?= e($name) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="card">
    <h1>Result Sheet</h1>
    <p class="muted">Student: <strong><?= e($name) ?></strong></p>

    <table>
        <thead>
            <tr><th>Subject</th><th class="num">Score</th></tr>
        </thead>
        <tbody>
            <?php foreach ($scores as $subject => $score): ?>
                <tr>
                    <td><?= e($subject) ?></td>
                    <td class="num"><?= e(number_format((float) $score, 2)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td>Total</td><td class="num"><?= e(number_format($total, 2)) ?></td></tr>
            <tr><td>Average</td><td class="num"><?= e(number_format($average, 2)) ?></td></tr>
            <tr><td>Grade</td><td class="num"><?= e($grade) ?> (<?= e($remark) ?>)</td></tr>
        </tfoot>
    </table>

    <a class="button" href="index.php">Enter another student</a>
</main>
</body>
</html>
