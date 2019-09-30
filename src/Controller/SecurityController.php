<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
	* @Route("/connexion/", name="connexion")
	*
	*/
	public function connexion(AuthenticationUtils $auth){
		
		$lastUsername = $auth -> getLastUsername();
		// Récupère le username saisi 
		
		$error = $auth -> getLastAuthenticationError();
		// Récupère l'erreur s'il y en a une 
		
		if(!empty($error)){
			$this -> addFlash('errors', 'Problème d\'identifiant !');
			echo  ($error);
		}
		
		$params = array(
			'lastUsername' => $lastUsername
		);
		return $this -> render('security/login.html.twig', $params);
	}	
	
	
	/**
	* @Route("/deconnexion/", name="deconnexion")
	*
	*/
	public function deconnexion(){}
	
	
	/**
	* @Route("/connexion_check/", name="connexion_check")
	*
	*/
	public function connexionCheck(){}
}
