<?php
namespace App\Contracts;

interface ResourceTypeContract{
    //public function getContent($url);
    public function getContent($url,$type);
    public function fetchResource($json);
}