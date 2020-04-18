<?php

class CapthaGenerator {

    /**
     * @var int Cost
     */
    private int $cost;

    /**
     * @var false|string Secret
     */
    private string $secret;

    /**
     * @var string Init
     */
    private string $initialization;

    /**
     * @var int Solvant
     */
    private int $solvant;

    /**
     * Construct new CaptchaGenerator
     * CapthaGenerator constructor.
     * @param $cost
     * @throws Exception
     */
    public function __construct(int $cost) {
        $this->cost = $cost * 3000;
        $this->secret = $this->generateSecret(64);
        $this->initialization = $this->generateSecret(16);
        $this->solvant = random_int(1, $this->cost);
    }

    /**
     * Prime
     * @return array
     */
    public function prime(): array
    {
        $iv = hex2bin($this->generateSecret(16));
        return [
            "to_solve" => openssl_encrypt($this->secret, "AES-256-CBC", $this->initialization . "-" . $this->solvant, 0, $iv),
            "initialization" => $this->initialization,
            "max" => $this->cost,
            "iv" => $iv,
        ];
    }

    /**
     * Check if correct
     * @param string $solved
     * @return bool
     */
    public function check(string $solved): bool
    {
        return $solved === $this->secret;
    }

    /**
     * Generate a safe secret
     * @return false|string
     * @throws Exception
     */
    private function generateSecret(int $size): string
    {
        if (function_exists("random_bytes")) {
            return random_bytes($size);
        } else if (function_exists("openssl_random_pseudo_bytes")) {
            $secret = openssl_random_pseudo_bytes($size, $strong);
            if (!$strong) {
                throw new Exception("Could not generate a safe random number");
            } else {
                return $secret;
            }
        }
        throw new Exception("Could not generate a safe random number");
    }

}