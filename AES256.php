<?php

class AES256
{
    private const CIPHER_METHOD = 'aes-256-gcm';
    private string $key;

    /**
     * Constructor.
     * @param string $key The encryption key. MUST be loaded from a secure source (e.g., environment variables).
     */
    public function __construct(string $key)
    {
        if (empty($key)) {
            throw new InvalidArgumentException('Encryption key cannot be empty.');
        }
        $this->key = $key;
    }

    /**
     * Encrypts a string using AES-256-GCM.
     *
     * @param string $plaintext The data to encrypt.
     * @return string|false The base64-encoded ciphertext (IV + tag + ciphertext) or false on failure.
     */
    public function encrypt(string $plaintext)
    {
        // 1. Generate a cryptographically secure, unique IV for each encryption.
        $iv_length = openssl_cipher_iv_length(self::CIPHER_METHOD);
        if ($iv_length === false) {
            return false;
        }
        $iv = openssl_random_pseudo_bytes($iv_length);
        
        // 2. Encrypt the data. GCM provides an authentication tag automatically.
        $tag = null;
        $ciphertext = openssl_encrypt(
            $plaintext,
            self::CIPHER_METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag // The authentication tag is generated and passed by reference.
        );

        if ($ciphertext === false) {
            return false;
        }

        // 3. Prepend the IV and tag to the ciphertext for use during decryption.
        // Base64 encode the final result for safe storage and transmission.
        return base64_encode($iv . $tag . $ciphertext);
    }

    /**
     * Decrypts a string encrypted with AES-256-GCM.
     *
     * @param string $base64_ciphertext The base64-encoded string (IV + tag + ciphertext).
     * @return string|false The original plaintext or false on failure (e.g., invalid format, failed authentication).
     */
    public function decrypt(string $base64_ciphertext)
    {
        $decoded_data = base64_decode($base64_ciphertext, true);
        if ($decoded_data === false) {
            return false; // Not a valid base64 string.
        }

        $iv_length = openssl_cipher_iv_length(self::CIPHER_METHOD);
        $tag_length = 16; // GCM tag is 16 bytes.

        if (strlen($decoded_data) < ($iv_length + $tag_length)) {
            return false; // Ciphertext is too short to be valid.
        }

        // 1. Extract the IV, tag, and ciphertext from the combined string.
        $iv = substr($decoded_data, 0, $iv_length);
        $tag = substr($decoded_data, $iv_length, $tag_length);
        $ciphertext = substr($decoded_data, $iv_length + $tag_length);

        // 2. Decrypt the data. GCM will automatically use the tag to verify integrity.
        // If the tag is invalid (data was tampered with), decryption will fail.
        $plaintext = openssl_decrypt(
            $ciphertext,
            self::CIPHER_METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return $plaintext;
    }
}
?>