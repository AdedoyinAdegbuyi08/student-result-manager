<?php
declare(strict_types=1);

/**
 * functions.php
 * All reusable logic lives here: validation, calculation, grading.
 */

const SUBJECTS = ['Mathematics', 'English', 'Physics', 'Chemistry', 'Biology'];

/** Escape any value before printing it in HTML (prevents XSS). */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Returns an error message, or null if the name is fine. */
function validateName(string $name): ?string
{
    if ($name === '') {
        return 'Student name is required.';
    }
    if (strlen($name) > 60) {
        return 'Student name must be 60 characters or fewer.';
    }
    if (!preg_match("/^[\p{L}\s'.-]+$/u", $name)) {
        return "Name can only contain letters, spaces, apostrophes, dots and hyphens.";
    }
    return null;
}

/** Returns an error message, or null if the score is valid (0 to 100). */
function validateScore(string $score): ?string
{
    if ($score === '') {
        return 'Score is required.';
    }
    // digits only, optional decimal part (so "-5", "abc", "1e2" are rejected)
    if (!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $score)) {
        return 'Enter a number between 0 and 100.';
    }
    if ((float) $score > 100) {
        return 'Score cannot be more than 100.';
    }
    return null;
}

/**
 * Validates the raw $_POST array safely (no undefined-index warnings).
 * Returns ['errors' => [...], 'name' => string, 'scores' => [subject => string]]
 */
function validateInput(array $post): array
{
    $errors = [];

    $name = isset($post['name']) && is_string($post['name']) ? trim($post['name']) : '';
    if ($error = validateName($name)) {
        $errors['name'] = $error;
    }

    $postedScores = isset($post['scores']) && is_array($post['scores']) ? $post['scores'] : [];
    $scores = [];

    foreach (SUBJECTS as $subject) {
        $raw = $postedScores[$subject] ?? '';
        $raw = is_string($raw) ? trim($raw) : '';
        $scores[$subject] = $raw;

        if ($error = validateScore($raw)) {
            $errors[$subject] = $error;
        }
    }

    return ['errors' => $errors, 'name' => $name, 'scores' => $scores];
}

/** Sum of all scores. */
function calculateTotal(array $scores): float
{
    return array_sum(array_map('floatval', $scores));
}

/** Average score (guards against dividing by zero). */
function calculateAverage(array $scores): float
{
    return count($scores) === 0 ? 0.0 : calculateTotal($scores) / count($scores);
}

/** Converts an average into a letter grade. */
function calculateGrade(float $average): string
{
    if ($average >= 70) return 'A';
    if ($average >= 60) return 'B';
    if ($average >= 50) return 'C';
    if ($average >= 45) return 'D';
    if ($average >= 40) return 'E';
    return 'F';
}

/** Short remark for a letter grade. */
function gradeRemark(string $grade): string
{
    return match ($grade) {
        'A' => 'Excellent',
        'B' => 'Very Good',
        'C' => 'Good',
        'D' => 'Fair',
        'E' => 'Pass',
        default => 'Fail',
    };
}
