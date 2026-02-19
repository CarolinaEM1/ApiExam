<?php

require_once 'PasswordGenerator.php';

class PasswordService {

    private $generator;
    private $minLength = 4;
    private $maxLength = 128;

    public function __construct() {
        $this->generator = new PasswordGenerator();
    }

    public function generateSingle(array $data) {

        $length = $data['length'] ?? 12;

        $this->validateLength($length);

        $options = $this->mapOptions($data);

        return $this->generator->generate($length, $options);
    }

    public function generateMultiple(array $data) {

        $count = $data['count'] ?? 1;
        $length = $data['length'] ?? 12;

        if ($count < 1 || $count > 50) {
            throw new InvalidArgumentException("count debe estar entre 1 y 50.");
        }

        $this->validateLength($length);

        $options = $this->mapOptions($data);

        return $this->generator->generateMultiple($count, $length, $options);
    }

    public function validatePassword(string $password, array $requirements) {

        $score = 0;

        if (strlen($password) >= ($requirements['minLength'] ?? 8)) $score++;

        if (!empty($requirements['requireUppercase']) && preg_match('/[A-Z]/', $password)) $score++;

        if (!empty($requirements['requireLowercase']) && preg_match('/[a-z]/', $password)) $score++;

        if (!empty($requirements['requireNumbers']) && preg_match('/[0-9]/', $password)) $score++;

        if (!empty($requirements['requireSymbols']) && preg_match('/[^a-zA-Z0-9]/', $password)) $score++;

        return [
            "valid" => $score >= 3,
            "score" => $score,
            "message" => $score >= 3 ? "Contraseña segura" : "Contraseña débil"
        ];
    }

    private function validateLength(int $length) {
        if ($length < $this->minLength || $length > $this->maxLength) {
            throw new InvalidArgumentException("Longitud debe estar entre 4 y 128 caracteres.");
        }
    }

    private function mapOptions(array $data): array {
        return [
            'upper' => $data['includeUppercase'] ?? true,
            'lower' => $data['includeLowercase'] ?? true,
            'digits' => $data['includeNumbers'] ?? true,
            'symbols' => $data['includeSymbols'] ?? false,
            'avoid_ambiguous' => $data['excludeAmbiguous'] ?? false,
            'exclude' => $data['exclude'] ?? '',
            'require_each' => true
        ];
    }
}
