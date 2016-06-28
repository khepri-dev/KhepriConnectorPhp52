# KhepriConnectorPhp52

Khepri-Connector will let you access easily with the Khepri technology It ease the access of the ask / success / reset / dimensions method

git clone https://github.com/khepri-dev/KhepriConnectorPhp52.git


include './KhepriAPI.php';
// use the credentials provided by Khepri
KhepriAPI::init($urlKhepri, $apiKey);
// Use the instanceId settings
$answer = KhepriAPI::ask($instanceId);
$chk = KhepriAPI::success($instanceId, $answer->solution);



