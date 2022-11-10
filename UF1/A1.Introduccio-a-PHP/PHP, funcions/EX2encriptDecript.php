<?php
$simple_string = "Frase super secreta <br>";

echo "String original: " . $simple_string;
$ciphering = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($ciphering);
$encryption_iv = '1234567891011121';
$encryption_key = "Clausecreta";
$encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv);

echo "String encriptada: " . $encryption . "<br>";
$decryption_iv = '1234567891011121';
$decryption_key = "Clausecreta";
$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
  
echo "Decrypted String: " . $decryption. "<br>";
