<?php define('_ACCESS_OK', true);
require_once 'init.php';
require_once 'vendor/autoload.php';

$repository = Gitonomy\Git\Admin::init(__DIR__, false);
// echo '<pre>';
// echo $size = $repository->getSize();echo "kb";

$head = $repository->getHead(); // Commit or Reference
$head = $repository->getHeadCommit(); // Commit

if ($repository->isHeadDetached()) {
    echo "Sorry man\n";
}

// print_r($head);

$log = $repository->getLog('master');
// $log = $repository->getLog('master', 'vendors/cruzersoftwares/css/style.css', 0, 10);
// print_r($log);
// $revision = $repository->getRevision('master@{12 days ago}');
// Returns 100 lasts commits
// $log = $revision->getLog(null, 10);


$blame = $repository->getBlame('master', 'README.md');

foreach ($blame->getLines() as $lineNumber => $line) {
    $commit = $line->getCommit();
    echo $lineNumber.': '.$line->getContent()."\r\n";
    echo $commit->getMessage();
    echo $commit->getAuthorName().' ['.$commit->getAuthorEmail().']'.' on '.$commit->getAuthorDate()->format('d M Y h:iA')."\n";
}
// print_r($log);
// die;


$diff = $repository->getDiff('master@{2 days ago}..master');

$files = $diff->getFiles();
echo sprintf("%s files modified", count($files));echo "<br/>";
// echo '<pre>';
foreach ($files as $file) {
    echo sprintf("Old name: (%s) %s\n", $file->getOldMode(), $file->getOldName());echo "<br/>";
    echo sprintf("New name: (%s) %s\n", $file->getNewMode(), $file->getNewName());echo "<br/>";
    
    $changes = $file->getChanges();
    foreach ($changes as $change) {
        foreach ($change->getLines() as $data) {
            list ($type, $line) = $data;
            if ($type === 0) {
                echo ' '.htmlspecialchars($line)."\n";
            } elseif ($type === 1) {
                echo '+'.htmlspecialchars($line)."\n";
            } else {
                echo '-'.htmlspecialchars($line)."\n";
            }
        }
    }
}

// print_r($diff);
echo('end');