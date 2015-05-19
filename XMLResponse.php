<?php

class XMLResponse
{
    public function out($message)
    {
        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

        echo "<response>\n";
        if($message) {
            foreach($message as $key => $val) {
                echo "    <$key>" . htmlspecialchars($val) . "</$key>\n";
            }
        }

        echo "</response>\n";
    }
}
?>