<?php

//Application de découverte du mot mystère
//Trouve un mot de 5 lettres parmi une liste de mot en utilisant uniquement une lettre maximum de chaque mot
//Pour chaque lettre trouvée, les autres lettres du mot sont ajoutées aux lettres interdites
//Tous les mots doivent etre utilisés.


global $listeMots;
global $motsUtilises;
global $tableauLettresInterdites;
global $positionLettre ;
global $motMystere;
global $tableauMotsEssayes;
global $combinaison;
global $combinaisonsEssayees;


$listeMots1 = ['AUCUN','DOSER','CALIN','JUSTE','COLIN','DEMOS','AILEE','VENIR','JABOT','CINES'];
$listeMots2 = ['BUTES','TESLA','BISON','TATOU','MOINS','HUILE','TIRER','VENIN','BOSON','MARIE'];

$listeMots                  = $listeMots1;
$motsUtilises               = [];
$tableauLettresInterdites   = [0=>[],1=>[],2=>[],3=>[],4=>[]];
$positionLettre             = 0;
$motMystere                 = '';
$tableauMotsEssayes         = [];
$combinaison                = [];
$combinaisonsEssayees       = [];



decouvreMotMystere();


/**
 * Fonction principale, lance le jeu
 */
function decouvreMotMystere(){
    global $motMystere, $listeMots, $positionLettre, $motsUtilises;
    while(strlen($motMystere) < 5 ){   
        foreach ($listeMots as $motEnCours){
            if (essayerLettre($motEnCours,$listeMots)){
                $positionLettre++;
                break;
            } if (etaitLeDernierMot($motEnCours,$listeMots)){
                revenirEnArriere();  
                break;     
            } 
        }
    }
    if (count($motsUtilises) != count($listeMots)){
        revenirEnArriere();
        decouvreMotMystere();
    }
}


/**
 * Essaye d'ajouter la lettre de la position actuelle au mot mystère
 */
function essayerLettre($motEnCours){
    global $positionLettre, $motsUtilises, $listeMots;
    if( !in_array($motEnCours, $motsUtilises)) {
        if (verifierLettreAutorisee($motEnCours) && $positionLettre <5){
        
            ajouterLettreAuMotMystere($motEnCours);
            ajouterLettresInterdites($motEnCours);

            if (!in_array($motEnCours, $motsUtilises)){
                array_push($motsUtilises,$motEnCours);
            }
            //Ajout des Mots ayant une letre identique à la même place
            foreach ($listeMots as $mot){
                if ($mot != $motEnCours && $motEnCours[$positionLettre] == $mot[$positionLettre]) {
                    ajouterLettresInterdites($mot);
                    if (!in_array($mot, $motsUtilises)){
                        array_push($motsUtilises,$mot);
                    }
                }
            }  
            return true;
        }    
    }
    return false;
}


/**
 * Vérifie si la lettre en cours est utilisable à la position actuelle
 */
function verifierLettreAutorisee($motAVerifier){
    global $positionLettre, $tableauLettresInterdites, $tableauMotsEssayes,$motMystere,$combinaison, $combinaisonsEssayees;

    $lettreAVerrifier = $motAVerifier[$positionLettre];
    if (isset($tableauLettresInterdites[$positionLettre]) && in_array($lettreAVerrifier, $tableauLettresInterdites[$positionLettre])){
        return false;
    } else if (in_array($motMystere.$lettreAVerrifier, $tableauMotsEssayes)) {
        return false;
    } else if (in_array($combinaison, $combinaisonsEssayees)){
        return false;
    } else {
        return true;
    }
}


/**
 * Ajoute la lettre trouvée au mot mystère possible
 */
function ajouterLettreAuMotMystere($motEnCours){
    global $positionLettre, $motMystere,$combinaison;
    array_push($combinaison,$motEnCours);
    $motMystere .= $motEnCours[$positionLettre];    
    return $motMystere;
}

/**
 * ajouter les autres lettres du mot trouvé mot à la liste des lettres interdites
 */
function ajouterLettresInterdites($motEnCours){    
    global $tableauLettresInterdites, $positionLettre;
    for ($i = 0 ; $i < strlen($motEnCours); $i++) {
        //Ajout des autres lettres du mots à la liste des lettres interdites
        if( $i != $positionLettre && !(in_array($motEnCours[$i],$tableauLettresInterdites[$i] ))) {            
            $tableauLettresInterdites[$i][] = $motEnCours[$i];
        }     
    }    
    return $tableauLettresInterdites;
}


/**
 * Vérifie si le mot était le dernier de la liste
 */
function etaitLeDernierMot($motEncours){
    global $listeMots;
    $dernierePlaceDeLaListe = count($listeMots)-1;
    return $motEncours == $listeMots[$dernierePlaceDeLaListe];
}


/**
 * Permet de revenir en arrière
 * Réinitialise toutes les variables sauf les combinaisons essayées
 * 
 */
function revenirEnArriere(){
    global $motMystere, $tableauLettresInterdites, $positionLettre, $motsUtilises, $tableauMotsEssayes,$combinaisonsEssayees, $combinaison;

    $tableauMotsEssayes[] = $motMystere;
    $motsUtilises = [];
    $motMystere = '';
    $positionLettre = 0;
    $tableauLettresInterdites = [0=>[],1=>[],2=>[],3=>[],4=>[]];
    array_push($combinaisonsEssayees, $combinaison);
    $combinaison = [];
    
}




echo "Le Mot Mystère est encore et jours: " . $motMystere;