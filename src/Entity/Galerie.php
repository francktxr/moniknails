<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalerieRepository")
 */
class Galerie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    private $file;
    // Je ne mappe pas cette propriété car elle existe pas dans la BDD. Elle est juste là pour récupérer les octets qui constituent la photo. Objet de la classe UploadedFile.

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    public function getFile(){
        return $this -> file;
    }
    public function setFile(UploadedFile $file){
        $this -> file = $file;
        return $this;
    }

    public function uploadPhoto(){
        // Renome le fichier
        $nom = $this -> file -> getClientOriginalName();
        // $_FILES['photo']['name']
        $new_nom = $this -> renamePhoto($nom);

        // Enregistrer en BDD
        $this -> photo = $new_nom;

        // Enregistrer le fichier sur le serveur 
        $this -> file -> move($this -> dirPhoto(), $new_nom);
        // Arg 1 : Le dossier
        // Arg 2 : nom du fichier
    }
    public function renamePhoto($nom){
        return 'photo_' . time() . '_' . rand(1,99999). '_' . $nom;
        // -> chat.jpg
        // -> photo_15000000000_75642_chat.jpg
    }
    public function dirPhoto(){
        return __DIR__ . '/../../public/photo/';
        // __DIR__ : chemin absolue du dossier dans lequel nous sommes
    }
    public function removePhoto(){
        $file = $this -> dirPhoto() . $this -> getPhoto();
        //Chemin absolu de la photo à supprimer
        if (file_exists($file) ){
            unlink($file);
        }
    }

}
