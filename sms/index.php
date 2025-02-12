<?php
require_once "config.php";
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING);

// smazat konkrétní sms
if (isset($_POST['delete_one'])) {
    $id_del = filter_input(INPUT_POST, 'id_del', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM sms_messages WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_del);
    $stmt->execute();
}

$number = isset($_GET['number']) ? $_GET['number'] : null;
if ($number) {
    $sql = "SELECT `id`, `from`, `fromName`, `text`, sent_stamp, received_stamp, sim FROM sms_messages WHERE `from` = ? ORDER BY received_stamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $number);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT `id`, `from`, `fromName`, `text`, sent_stamp, received_stamp, sim FROM sms_messages ORDER BY received_stamp DESC";
    $result = $conn->query($sql);
}



$mesice = [
    1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben', 5 => 'květen',
    6 => 'červen', 7 => 'červenec', 8 => 'srpen', 9 => 'září', 10 => 'říjen',
    11 => 'listopad', 12 => 'prosinec'
];


$mesice = [
    1 => '1.', 2 => '2.', 3 => '3.', 4 => '4.', 5 => '5.',
    6 => '6.', 7 => '7.', 8 => '8.', 9 => '9.', 10 => '10.',
    11 => '11.', 12 => '12.'
];


$messages = [];
while ($row = $result->fetch_assoc()) {

    //$firstLetter = strtolower(substr($row['fromName'], 0, 1)); // Získání prvního písmene a převod na malé
    //$firstLetter = iconv('UTF-8', 'ASCII//TRANSLIT', $firstLetter); // Odstranění diakritiky
    //$imageFile = "images/{$firstLetter}.png";   //cesta

    $from = 'áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ';
    $to = 'acdeeinorstuuyzacdeeinorstuuyz';
    $firstLetter = strtolower(substr($row['fromName'], 0, 1));
    $firstLetter = strtr($firstLetter, $from, $to);
    $imageFile = "images/{$firstLetter}.png";


    $sentStamp = DateTime::createFromFormat('Y-m-d H:i:s', $row['sent_stamp']);
    $receivedStamp = DateTime::createFromFormat('Y-m-d H:i:s', $row['received_stamp']);

    $formattedSentStamp = $sentStamp->format('j') . '.' . $mesice[(int)$sentStamp->format('n')] . ' ' . $sentStamp->format('Y H:i');
    $formattedReceivedStamp = $receivedStamp->format('j') . '.' . $mesice[(int)$receivedStamp->format('n')] . ' ' . $receivedStamp->format('Y H:i');


    // Přidání formátovaných dat do pole
    $messages[] = [
        'id' => $row['id'],
        'from' => $row['from'],
        'fromName' => $row['fromName'],
        'sent' => $formattedSentStamp,
        'received' => $formattedReceivedStamp,
        'text' => $row['text'],
        'sim' => $row['sim'],
        'imageFile' => $imageFile
    ];
}

if (isset($_POST['delete'])) {
    $sql = "TRUNCATE TABLE sms_messages";
    $conn->query($sql);
    // Přesměrování nebo obnovení stránky
    header("Location: index.php");
}

?>


<!DOCTYPE html>

<html lang="cs">

<head>
    <meta charset="utf-8">

    <title>SMS Zprávy</title>

    <!-- plugins -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="my_css.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="chat_container">
                    <div class="job-box">
                        <div class="job-box-filter">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                    <a href="index.php" class="btn btn-primary">Page Refresh</a>
                                </div>

                            </div>
                        </div>
                        <div class="inbox-message">
                            <ul>
                                <?php foreach ($messages as $message) : ?>
                                    <li>

                                        <div class="message-avatar">
                                            <img src="<?= htmlspecialchars($message['imageFile']) ?>" alt="">
                                        </div>
                                        <div class="message-body">
                                            <div class="message-body-heading">
                                                <h5><?= htmlspecialchars($message['fromName']) ?></h5>
                                                <span><?= htmlspecialchars($message['received']) ?>
                                                    <?php htmlspecialchars($message['sent']) ?></span>
                                            </div>
                                            <h5><span class="unread"><?= htmlspecialchars($message['sim']) ?></span><span class="pending"><a href="?number=<?= htmlspecialchars($message['from']) ?>" class="text-white"><?= htmlspecialchars($message['from']) ?></a></span>


                                            </h5>

                                            <p><?= htmlspecialchars($message['text']) ?></p>


                                            <form action="index.php" method="post">
                                                <input type="hidden" name="id_del" value="<?php echo htmlspecialchars($message['id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" name="delete_one">Delete</button>
                                            </form>

                                        </div>


                                    </li>
                                <?php endforeach; ?>

                            </ul>

                            <div class="col-md-6 col-sm-6 mt-3">
                                <form method="post">

                                    <button type="submit" class="btn btn-danger" name="delete">Delete all sms messages</button>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
