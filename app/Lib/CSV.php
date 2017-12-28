<?php

namespace App\Lib;

/*
    A class that makes handling CSVs easier
*/
class CSV implements \Iterator
{
    private $lines = [];
    private $index = 0;
    private $filepath;
    private $ignore_first_line = false;

    public function __construct($filepath_data_array = [])
    {
        if (is_array($filepath_data_array)) {
            $this->setData($filepath_data_array);
        } elseif (is_string($filepath_data_array)) {
            $this->readFile($filepath_data_array);
        }
    }

    public function setData($array, $header_keys = false)
    {
        $header = null;

        foreach ($array as $line) {
            if ($header_keys and empty($header)) {
                $header = array_keys($line);
                $this->lines[] = $header;
            }

            $this->lines[] = array_values($line);
        }
    }

    /*
    ** Sets whether to ignore the first line (generally the header) of a CSV file
    */
    public function ignoreFirstLine($boolean)
    {
        $this->ignore_first_line = ($boolean ? true : false);
    }

    /*
    ** Reads in a CSV file
    */
    public function readFile($filepath)
    {
        $this->filepath = $filepath;

        $file_headers = @get_headers($filepath);
        $url_exists = ($file_headers[0] == 'HTTP/1.1 200 OK');

        if (!$url_exists && !file_exists($filepath)) {
            $this->lines = [];
            return false;
        }

        $handle = fopen($filepath, 'r');

        // reset
        $this->lines = [];

        while (!feof($handle)) {
            $line = fgetcsv($handle);

            // handle 1 element lines
            if (!is_array($line)) {
                $line = [$line];
            }

            // skip empty lines
            $skip_line = true;
            foreach ($line as $element) {
                if (!empty($element)) {
                    $skip_line = false;
                }
            }

            if ($skip_line) {
                continue;
            }

            $this->addLine($line);
        }

        fclose($handle);
    }

    /*
    ** Adds an array to the array of lines
    */
    public function addLine($array)
    {

        // we always want to add an array, even if the line
        // has only a single element
        if (!is_array($array)) {
            $array = [$array];
        }

        $this->lines[] = $array;
    }

    public function addRow($array)
    {
        $this->addLine($array);
    }

    /*
    ** Returns the number of lines
    */
    public function numberOfLines()
    {
        $count = count($this->lines);
        if ($this->ignore_first_line) {
            $count--;
        }

        return $count;
    }

    /*
    ** Saves the lines as a CSV file
    */
    public function save($filepath = null)
    {
        if (!$filepath) {
            $filepath = $this->filepath;
        }

        if (empty($filepath)) {
            throw new \Exception('Invalid file.');
        }

        file_put_contents($filepath, $this->csvString());
    }

    /*
    ** Returns a CSV string
    */
    public function csvString()
    {
        $temp = 'php://temp';

        $handle = fopen($temp, 'w+');

        foreach ($this->lines as $line) {
            fputcsv($handle, $line);
        }

        rewind($handle); // position = 0

        $output = stream_get_contents($handle);

        fclose($handle);

        return $output;
    }

    /* Iterator Methods */
    public function current()
    {
        return $this->lines[$this->index];
    }

    public function next()
    {
        $this->index ++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->lines[$this->key()]);
    }

    public function rewind()
    {
        $this->index = 0 + ($this->ignore_first_line ? 1 : 0);
    }

    public function reverse()
    {
        $this->lines = array_reverse($this->lines);
        $this->rewind();
    }
}
