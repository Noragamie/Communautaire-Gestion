<?php

return [

    /*
    | URL JSON listant les communes (tableau d'objets avec name/nom, departement optionnel, id/code optionnel).
    | Si vide ou en cas d'échec, le fichier database/data/benin_communes.json est utilisé.
    */
    'sync_url' => env('BENIN_COMMUNES_SYNC_URL'),

];
