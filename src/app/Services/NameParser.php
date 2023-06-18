<?php


namespace App\Services;

use App\Models\Person;

class NameParser{

    private array $data;

    private array $titles;

    private array $separators;

    private string $matchedSeparator = '';


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->titles = config('app.titles');
        $this->separators = config('app.separators');
    }

    public function handle()
    {
        $persons = [];
//        here we check if each individual line is valid and then parse it
        foreach ($this->data as $line) {
            if(!$this->checkIfStringContainsTitle($line)){
                continue;
            }
            $persons[] = $this->parseNames($line);
        }

//        flattening the array to allow for easy storage in the database
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
        $str = $this->removeDots($str);
//      check if string contains any of the separators and then split by that separator
        $stringContainsSeparator = $this->checkIfStringContainsSeparators($str);

        $strings = $stringContainsSeparator ? explode($this->matchedSeparator, $str) : [$str];

//        loop through the strings and convert to array of persons
        return $this->convertToPersonArrays($strings);
    }

    public function removeDots(string $str): string
    {
        return str_replace('.', '', $str);
    }

//    here we check if a string contains any of the separators like 'and' or '&'
    public function checkIfStringContainsSeparators(string $str): bool
    {
        $this->matchedSeparator = '';
        foreach ($this->separators as $separator){
            if (strpos($str, $separator) !== false){
                $this->matchedSeparator = $separator;
                return true;
            }
        }
        return false;
    }


    public function convertToPersonArrays(array $strings): array
    {
//        checking if there are multiple people after the split with the separator
        $multiplePartners = count($strings) > 1;

//        we split the first string into an array of words and the second string if it exists
        $person1Array = str_word_count($strings[0], 1);
        $person2Array = [];
        if($multiplePartners){
            $person2Array = str_word_count($strings[1], 1);
        }

//        we get the initial and the rest of the string for each person
        $person1details = $this->getInitialAndSeparate($person1Array)[1];
        $person1Initial = $this->getInitialAndSeparate($person1Array)[0];
        $person2Initial = '';
        if($multiplePartners){
            $person2details = $this->getInitialAndSeparate($person2Array)[1];
            $person2Initial = $this->getInitialAndSeparate($person2Array)[0];
        }

//        we begin creating the person arrays
        $person1['initial'] = $person1Initial ?: null;
        $person1['title'] = $person1details[0];

//        we check if the person has a first name and a last name by seeing how fields are in the details array
        switch (count($person1details)){
            case 1:
                $person1['first_name'] = null;
                $person1['last_name'] = end($person2details);
                break;
            case 2:
                $person1['last_name'] = end($person1details);
                break;
            case 3:
                $person1['first_name'] = $person1details[1] ?: null;
                $person1['last_name'] = end($person1details);
                break;
        }

//        we do the same for the second person if they exist
        $person2 = [];
        if($multiplePartners && count($person2details) > 0){
            $person2['title'] = $person2details[0];
            $person2['initial'] = $person2Initial ?: null;
            if(count($person2details) >= 2){
                $person2['first_name'] = $person2details[1] ?: null;
            }
            $person2['last_name'] =  end($person2details) ;
        }

        return count($strings) > 1 ?  [$person1, $person2] : [$person1];
    }

// Here we get check if the row contains an initial and store it separately to make the rest of the array easier to process
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

//    check if string contains any of the titles
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
