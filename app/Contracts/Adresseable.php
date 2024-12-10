<?php

namespace App\Contracts;

interface Adresseable {
    public function getAdresses(): string;

    public function getCity(): string;

    public function getZipCode(): string;
}
