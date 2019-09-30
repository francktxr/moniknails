<?php

namespace App\Controller;

use App\Entity\Tarif;
use App\Entity\Galerie;
use App\Form\TarifType;
use App\Form\GalerieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    //*************CRUD GALERIE *****************

    /**
     * @Route("/admin/galerie", name="admin_galerie")
     */
    public function adminGalerie()
    {
        // Récupérer tous les tarifs
        $repo = $this -> getDoctrine() -> getRepository(Galerie::Class);
        $galeries = $repo ->findAll();

        // Afficher la vue

        return $this->render('admin/galerie_list.html.twig', [
            'galeries' => $galeries
        ]);
    }

        /**
     * @Route("/admin/galerie/add/", name="admin_galerie_add")
     * localhost:8000/admin/galerie/add/
     */
    public function admingalerieAdd(Request $request) {
        $galerie = new Galerie; // objet vide
        $em = $this->getDoctrine()->getManager();

        //1: Récuperer le formulaire

        $form = $this -> createForm(GalerieType::class, $galerie);     
       

        //3: Traiter les infos du formulaire pour enregistrer le galerie

        $form -> handleRequest($request); 
        //Cette ligne permet de lier définitivement $galerie aux données du formulaire. Elle permet de récupérer les infos en post. (indispensable)

        if($form -> isSubmitted() && $form -> isValid()){

            $em->persist($galerie); // On enregistre l'objet dans le système
            if($galerie -> getFile() != NULL){
                $galerie -> uploadPhoto();
            }

            $em->flush(); // Exécute l'insertion en BDD
            $this -> addFlash('success', 'La photo ' . $galerie->getTitre() . ' a bien été ajouté');
            return $this -> redirectToRoute('admin_galerie');

        }

          //2: Afficher le formulaire dans la vue

        $params = array(
            'galerieForm' => $form -> createView()
        );
        return $this->render('admin/galerie_form.html.twig', $params);

    }

    /**
     * @Route("/admin/galerie/update/{id}/", name="admin_galerie_update")
     * 
     */
    public function admingalerieUpdate($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $galerie = $em -> find(Galerie::class, $id);

        //1: Récuperer le formulaire

        $form = $this -> createForm(GalerieType::class, $galerie);
   
        //3: Traiter les infos du formulaire pour enregistrer le galerie

        $form -> handleRequest($request); 
        //Cette ligne permet de lier définitivement $galerie aux données du formulaire. Elle permet de récupérer les infos en post. (indispensable)

        if($form -> isSubmitted() && $form -> isValid()){
            $em->persist($galerie); // On enregistre l'objet dans le système
            if($galerie -> getFile() != NULL){
                $galerie -> removePhoto();
                $galerie -> uploadPhoto();
            }
            $em->flush(); // Exécute l'insertion en BDD
            $this -> addFlash('success', 'La photo ' . $galerie->getTitre() . ' a bien été modifié');
            return $this -> redirectToRoute('admin_galerie');
        }

          //2: Afficher le formulaire dans la vue         
        $params = array(
            'galerieForm' => $form -> createView()
        );
        return $this->render('admin/galerie_form.html.twig', $params);
    }

    /**
     * @Route("/admin/galerie/delete/{id}/", name="admin_galerie_delete")
     * 
     */
    public function admingalerieDelete(Request $request, $id) {
        //1: Récuperer le galerie ($id)
        $em = $this -> getDoctrine() -> getManager();
        $galerie = $em -> find(Galerie::class, $id);

        //2: Supprimer le galerie
        $em -> remove($galerie);
        $galerie -> removePhoto();
        $em -> flush();

        //3: Message + redirection
       
         
            $this->addFlash('success','La photo '. $id.' a bien été supprimé ! ');

            return $this-> redirectToRoute('admin_galerie');
    }



    // **********CRUD TARIF***********

    /**
     * @Route("/admin/tarif", name="admin_tarif")
     */
    public function adminTarif()
    {
        // Récupérer tous les tarifs
        $repo = $this -> getDoctrine() -> getRepository(Tarif::Class);
        $tarifs = $repo ->findAll();

        // Afficher la vue

        return $this->render('admin/tarif_list.html.twig', [
            'tarifs' => $tarifs
        ]);
    }

    /**
     * @Route("/admin/tarif/add/", name="admin_tarif_add")
     */
    public function adminTarifAdd(Request $request) {
        $tarif = new Tarif; // objet vide
        $em = $this->getDoctrine()->getManager();

        // Récuperer le formulaire
        $form = $this->createForm(TarifType::class, $tarif);

        // Traiter les infos du formulaire pour enregistrer le tarif

        $form -> handleRequest($request);
        //Cette ligne permet de lier définitivement $tarif aux données du formulaire. Elle permet de récupérer les infos en post. (indispensable)

        if($form -> isSubmitted() && $form -> isValid()){

            $em->persist($tarif); //On enregistre l'objet dans le système

            $em->flush(); // Exécute l'insertion en BDD
            $this -> addFlash('success', 'Le tarif ' . $tarif->getPrestation() . ' a bien été ajouté');
            return $this -> redirectToRoute('admin_tarif');
        }

        // Afficher le formulaire dans la vue

        $params = array(
            'tarifForm' => $form -> createView()
        );
        return $this->render('admin/tarif_form.html.twig', $params);

    }

     /**
     * @Route("/admin/tarif/update/{id}/", name="admin_tarif_update")
     * localhost:8000/admin/tarif/update/
     */
    public function adminTarifUpdate($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $tarif = $em -> find(Tarif::class, $id);

        //1: Récuperer le formulaire
        $form = $this -> createForm(TarifType::class, $tarif);
  
        //3: Traiter les infos du formulaire pour enregistrer le tarif
        $form -> handleRequest($request); 
        //Cette ligne permet de lier définitivement $tarif aux données du formulaire. Elle permet de récupérer les infos en post. (indispensable)

        if($form -> isSubmitted() && $form -> isValid()){
            $em->persist($tarif); // On enregistre l'objet dans le système

            $em->flush(); // Exécute l'insertion en BDD
            $this -> addFlash('success', 'Le tarif ' . $tarif->getPrestation() . ' a bien été modifié');
            return $this -> redirectToRoute('admin_tarif');
        }

          //2: Afficher le formulaire dans la vue         
        $params = array(
            'tarifForm' => $form -> createView()
        );
        return $this->render('admin/tarif_form.html.twig', $params);
    }


    /**
     * @Route("/admin/tarif/delete/{id}/", name="admin_tarif_delete")
     * 
     */
    public function admintarifDelete(Request $request, $id) {
        //1: Récuperer le tarif ($id)
        $em = $this -> getDoctrine() -> getManager();
        $tarif = $em -> find(Tarif::class, $id);

        //2: Supprimer le tarif
        $em -> remove($tarif);
        $em -> flush();

        //3: Message + redirection
             
            $this->addFlash('success','Le tarif '. $id.' a bien été supprimé ! ');
            return $this-> redirectToRoute('admin_tarif');
    }

    







}
