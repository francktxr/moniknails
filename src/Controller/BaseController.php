<?php

namespace App\Controller;

use App\Entity\Tarif;
use App\Form\UserType;
use App\Entity\Galerie;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    /**
     * @Route("/tarifs", name="tarifs")
     */
    public function tarifs()
    {

        $repository = $this -> getDoctrine() -> getRepository(Tarif::class);
        $tarifs = $repository -> findAll();

        return $this->render('base/tarifs.html.twig', [
            'tarifs' => $tarifs
        ]);
    }

    /**
     * @Route("/galerie", name="galerie")
     */
    public function galerie()
    {
        //1 : Récupérer les données à afficher (tous les produits et la liste de catégories)

        // Select * from produit
        $repository = $this -> getDoctrine() -> getRepository(Galerie::class);
        $galeries = $repository -> findAll();

        return $this->render('base/galerie.html.twig', [
            'galeries' => $galeries
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this -> createForm(ContactFormType::class, NULL);
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$data = $form -> getData();
			// $data contient le prenom, le nom, l'objet, l'email de l'expediteur, le message... (le $_POST finalement)
			
			if($this -> sendEmail($data, $mailer)){
				$this -> addFlash('success', 'Votre email a été envoyé');
			}
			else{
				$this -> addFlash('errors', 'Erreur dans l\'envoie de l\'email');
			}
			
			
			//redirection
			
		}
		
		$params = array(
			'contactForm' => $form -> createView()
		);
		return $this -> render('base/contact.html.twig', $params);
    }


    /**
     * 
     * 
     */
    public function sendEmail($data, \Swift_Mailer $mailer){
        // On créer l'email (en précisant l'objet)
        $mail = new \Swift_Message('Mon site : ' . $data['objet']);

        //On configure l'email (Qui, pour qui, quoi, comment)

        $mail
            -> setFrom($data['email'])
            -> setTo('monik.nailss@gmail.com')
            -> setBody(
                $this -> renderView('mail/contact_mail.html.twig', array('data' => $data)), 'text/html'
            )
        ;
        //On demande au mailer d'envoyer l'email
        if(!$mailer -> send($mail)){
            return false;
        }
        return true;
    }


    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function MentionsLegales()
    {
        return $this->render('base/mentions_legales.html.twig');
    }
	


}
