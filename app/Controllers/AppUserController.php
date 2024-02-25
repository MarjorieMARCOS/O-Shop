<?php

namespace App\Controllers;

use App\Models\AppUser;
use App\Models\Category;
use Exception;


class AppUserController extends CoreController
{
	/**
	 * Action qui envoie vers la page de connexion
	 *
	 * @return void retour rien : pas de return
	 */
    public function user(): void
    {
        $this->show('connexion/connexion');
    }



    /**
	 * Action qui connecte l'user à compte
	 *
	 * @return void retour rien : pas de return
	 */
    public function login(): void
    {

        try {

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            if(false === $email) {
                // l'email est vide
                // le message d'erreur est enregistrer
                $this->addFlashErrorMessage('email invalide');
                $dataAreValid = false;
            }
    
            if(false === $password) {
                // le password est vide
                // le message d'erreur est en registrer
                $this->addFlashErrorMessage('mot de passe invalide invalide');
                $dataAreValid = false;
            }
    
            $appUser = AppUser::findByEmail($email);
            $appUser->findByEmail($email); 

    
        if (null === $appUser) {
            //dump('echec connexion');
            $this->addFlashErrorMessage('échec de connexion');
            $this->redirect('main-home');
        }
        if ($appUser->getPassword() ===  $password) {
            //dump(' connexion établie');
            $this->addFlashNotification('connexion établie');
            $this->redirect('main-home');
        } else {
            $this->addFlashErrorMessage('login ou mot de passe incorrect');
            $this->redirect('main-home');
        }



        } catch (Exception $exception) {
            $this->addFlashErrorMessage($exception->getMessage());
            $this->redirect('main-home');
            
        }
     


    }

}