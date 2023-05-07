<?php
namespace App\Contracts;

interface ResourceContract{
    //public function getContent($url);
    public function favLanguage();
    public function getLanguage($json);
}