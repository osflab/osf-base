<?php
namespace Osf\Crypt;

/**
 * OpenStates simple crypt / uncrypt manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2009
 * @version 1.0
 * @since OSF-1.0
 * @package openstates
 * @subpackage crypt
 */
class Crypt
{
    const DEFAULT_KEY = 'OPENSTATES-UNSECURE-KEY';
    const DEFAULT_HASH_ALGO = 'crc32b';
    const SECURE_HASH_ALGO = 'sha256';
    const CIPHER_METHOD = 'AES-256-ECB';

    private $key = null;
    private $mode = null;

    /**
     * @param string $cryptKey
     * @param string $mode
     */
    public function __construct($cryptKey = self::DEFAULT_KEY)
    {
        $this->key = $cryptKey;
    }

    /**
     * Encrypt a string
     * @param string $str
     * @return string
     */
    public function encrypt(string $str): string
    {
        return openssl_encrypt($str, self::CIPHER_METHOD, $this->key, 0, $this->getIv());
    }

    /**
     * Decrypt a string
     * @param string $str
     * @return string
     */
    public function decrypt(string $str): string
    {
        return openssl_decrypt($str, self::CIPHER_METHOD, $this->key, 0, $this->getIv());
    }

    /**
     * @staticvar ?string $iv
     * @return string
     */
    protected function getIv(): string
    {
        static $iv = null;

        if ($iv === null) {
            $ivSize = openssl_cipher_iv_length(self::CIPHER_METHOD);
            $iv = openssl_random_pseudo_bytes($ivSize);
        }
        return $iv;
    }

    /**
     * @param string $h
     * @return string
     */
    protected function hex2bin($h): string
    {
        if (!is_string($h)) {
            return null;
        }
        $r = '';
        for ($a = 0; $a < strlen($h); $a += 2) {
            $r .= chr(hexdec($h{$a} . $h{($a + 1)}));
        }
        return $r;
    }

    /**
     * Bind to bin2hex
     * @param mixed $b
     * @return string
     */
    protected function bin2hex($b): string
    {
        return bin2hex((string) $b);
    }
    
    /**
     * Main hash function. Use passwordHash() for passwords
     * @param string $data
     * @param bool $secure
     * @return string
     */
    public static function hash($data, bool $secure = false): string
    {
        return hash(self::getAlgo($secure), (string) $data);
    }
    
    /**
     * Hash a password with password_hash function
     * @param string $password 72 caractères maximum
     * @param string $algo
     * @return string 60 caractères
     */
    public static function passwordHash(string $password, string $algo = PASSWORD_BCRYPT): string
    {
        return password_hash($password, $algo);
    }
    
    /**
     * Check if a password matches a hash
     * @param string $password 72 caractères maximum
     * @param string $hash
     * @return bool
     */
    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Get a random hash string
     * @param bool $secure
     * @return string
     */
    public static function getRandomHash(bool $secure = false): string
    {
        return self::hash(microtime() . rand(10000, 100000), $secure);
    }
    
    /**
     * @param bool $secure
     * @return string
     */
    protected static function getAlgo(bool $secure): string
    {
        return $secure ? self::SECURE_HASH_ALGO : self::DEFAULT_HASH_ALGO;
    }
}
