<?php

require_once __DIR__ . '/../utils/GenPassword.php';

class PasswordGenerator {

    public function generate(int $length, array $options): string {
        return generate_password($length, $options);
    }

    public function generateMultiple(int $count, int $length, array $options): array {
        return generate_passwords($count, $length, $options);
    }
}
