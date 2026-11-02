<?php
// form.php: the input form. Included by index.php (fresh) and result.php (on errors).
require_once __DIR__ . '/functions.php';

$errors = $errors ?? [];
$old    = $old ?? ['name' => '', 'scores' => []];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Result Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="card">
    <h1>Student Result Manager</h1>
    <p class="muted">Enter the student's name and scores (0 to 100).</p>

    <?php if (!empty($errors)): ?>
        <div class="alert" role="alert">Please fix the highlighted fields.</div>
    <?php endif; ?>

    <form action="result.php" method="POST" novalidate>
        <div class="field">
            <label for="name">Student name</label>
            <input type="text" id="name" name="name"
                   value="<?= e($old['name'] ?? '') ?>" maxlength="60">
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?= e($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <?php foreach (SUBJECTS as $subject): ?>
            <div class="field">
                <label for="<?= e($subject) ?>"><?= e($subject) ?></label>
                <input type="text" inputmode="decimal"
                       id="<?= e($subject) ?>"
                       name="scores[<?= e($subject) ?>]"
                       value="<?= e($old['scores'][$subject] ?? '') ?>">
                <?php if (isset($errors[$subject])): ?>
                    <span class="error"><?= e($errors[$subject]) ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit">Generate result</button>
    </form>
</main>
</body>
</html>
