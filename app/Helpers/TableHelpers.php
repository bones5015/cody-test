<?php

namespace App\Helpers;

use Masterminds\HTML5;

class TableHelpers
{
    /**
     * @param string $table
     * @return array
     */
    public function chunk(string $table): array
    {
        $html5 = new HTML5();
        $dom = $html5->loadHTML($table);

        $rows = collect($dom->getElementsByTagName('tr'));

        $headers = $html5->saveHTML($rows->shift());
        $headers = $this->removeWhitespace($headers);

        $headerCount = strlen('<table>' . $headers . '</table>');

        $chunks = [];

        $startHTML = '<table>' . $headers;
        $endHTML = '</table>';

        $chunkCount = 0;
        $chunk = $startHTML;

        foreach ($rows as $row) {
            $tr = $html5->saveHTML($row);
            $tr = $this->removeWhitespace($tr);

            $currentCount = strlen($tr);

            $totalCount = $chunkCount + $currentCount + $headerCount;

            if ($totalCount <= 1000) {
                $chunkCount += $currentCount;
                $chunk .= $tr;
            } else {
                // remove any chunks that only contain one row but still exceed the 1000 character limit
                if ($chunkCount + $headerCount <= 1000) {
                    $chunks[] = $chunk . $endHTML;
                }

                $chunkCount = $currentCount;
                $chunk = $startHTML . $tr;
            }


        }

        return $chunks;
    }

    /**
     * @param string $html
     * @return string
     */
    public function removeWhitespace(string $html): string
    {
        $html = preg_replace("/\n/", "", $html);
        $html = preg_replace("/[\t ]+\</", '<', $html);
        $html = preg_replace("/\>[\t ]+\</", '><', $html);
        $html = preg_replace("/\>[\t ]+$/", '>', $html);

        return $html;
    }
}