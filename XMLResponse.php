<?php

class XMLResponse
{
    public function out($message)
    {
        header('Content-Type: text/json');

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