<?php


namespace App\Services;

use App\Models\Person;

class NameParser{

    private $data;

    private $titles = [
        'Mr',
        'Mrs',
        'Miss',
        'Ms',
        'Dr',
        'Prof',
        'Mister',
        'Sir',
        'Madam',
        'Master',
        'Madame',
        'Rev',
    ];
    private $seperators = ['and', '&'];

    private $matchedSeperator = '';


    public function __construct(array $data)
    {
        $this->data = $data;
        //
    }

    public function handle()
    {
        $persons = [];
        foreach ($this->data as $line) {
            if(!$this->checkIfStringContainsTitle($line)){
                continue;
            }
            $persons[] = $this->parseNames($line);
        }


        $persons = array_merge(...$persons);

//        store data in persons table
        foreach ($persons as $person){
            Person::create($person);
        }
    }


    /**
     * Parse names string into an array of persons.
     *
     * @param string $str
     * @return array
     */
    private function parseNames(string $str): array
    {
        $result = [];
        $str = $this->removeDots($str);
//      check if string contains any of the seperators and then split by that seperator
        $stringContainsSeperator = $this->checkIfStringContainsSeperators($str);

        $strings = $stringContainsSeperator ? explode($this->matchedSeperator, $str) : [$str];

//        loop through the strings and convert to array of persons
        return $this->convertToPersonArrays($strings);
    }

    public function removeDots(string $str): string
    {
        return str_replace('.', '', $str);
    }

    public function checkIfStringContainsSeperators(string $str): bool
    {
        $this->matchedSeperator = '';
        foreach ($this->seperators as $seperator){
            if (strpos($str, $seperator) !== false){
                $this->matchedSeperator = $seperator;
                return true;
            }
        }
        return false;
    }


    public function convertToPersonArrays(array $strings): array
    {
        $person1Array = str_word_count($strings[0], 1);
        $person2Array = [];
        if(count($strings) > 1){
            $person2Array = str_word_count($strings[1], 1);
        }

        $person1details = $this->getInitialAndSeparate($person1Array)[1];
        $person1Initial = $this->getInitialAndSeparate($person1Array)[0];
        $person2Initial = '';
        if(count($strings) > 1){
            $person2details = $this->getInitialAndSeparate($person2Array)[1];
            $person2Initial = $this->getInitialAndSeparate($person2Array)[0];
        }

        $person1['initial'] = $person1Initial ?: null;
        $person1['title'] = $person1details[0];
       if(count($person1details) === 1) {
           $person1['first_name'] = null;
           $person1['last_name'] = end($person2details);
       }
        if(count($person1details) === 2){
            $person1['last_name'] = end($person1details);
        }
        if(count($person1details) === 3){
            $person1['first_name'] = $person1details[1] ?: null;
            $person1['last_name'] = end($person1details);
        }


        $person2 = [];
        if(count($strings) > 1 && count($person2details) > 0){
            $person2['title'] = $person2details[0];
            $person2['initial'] = $person2Initial ?: null;
            if(count($person2details) >= 2){
                $person2['first_name'] = $person2details[1] ?: null;
            }
            $person2['last_name'] =  end($person2details) ;
        }

        return count($strings) > 1 ?  [$person1, $person2] : [$person1];
    }

    public function getInitialAndSeparate(array $array): array
    {
        $initial  = '';
        $stringsWithoutInitial = [];
        foreach ($array as $value) {
            if (strlen($value) === 1) {
                $initial = $value;
            }
            else{
                $stringsWithoutInitial[] = $value;
            }
        }
        return [$initial, $stringsWithoutInitial];
    }

    public function checkIfStringContainsTitle(string $str): bool
    {
        foreach ($this->titles as $title){
            if (strpos($str, $title) !== false){
                return true;
            }
        }
        return false;
    }
}
