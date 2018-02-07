<?php

namespace AurynWorkshop\SiteController;

use AurynWorkshop\AurynWorkshopSession;
use AurynWorkshop\Params\AppParams;
use AurynWorkshop\Response\HtmlResponse;
use AurynWorkshop\Response\RedirectResponse;
use AurynWorkshop\TwigSessionRender;
use Birke\Rememberme\Authenticator as RememberMe;
use AurynWorkshop\Params\UserLoginCredentialsParams;
use AurynWorkshop\Repo\UserValidateRepo;
use AurynWorkshop\VariableMap;

class Login
{
    public function getLogin(TwigSessionRender $twig, AurynWorkshopSession $session)
    {
        $html = $twig->render("pages/login.html");
        // TODO - should we destroy the session if they reach here?

        $session->setFlashError('arrggh');

        return new HtmlResponse($html);
    }

    public function postLogin(
        VariableMap $variableMap,
        AurynWorkshopSession $session,
        // RememberMe $rememberMe,
        UserValidateRepo $userValidateRepo
    ) : RedirectResponse {

        $userLoginCredentials = UserLoginCredentialsParams::fromVarMap($variableMap);
        $userPasswordLogin = $userValidateRepo->validateUserFromLoginCredentials($userLoginCredentials);
        if ($userPasswordLogin === null) {
            $session->setFlashError("Login failed");
            return new RedirectResponse('/login');
        }

        $session->setUserLoggedInViaCredentials($userPasswordLogin);

//        if ($userLoginCredentials->getRememberMe()) {
//            // TODO - make adminUserToString?
//            $rememberMe->createCookie($username, [App::ADMIN_USERNAME => $adminUser->getUsername()]);
//        }
//        else {
//            $rememberMe->clearCookie();
//        }

        return new RedirectResponse('/test');
    }

    /**
     * @param AurynWorkshopSession $session
     * @param RememberMe $rememberMe
     * @return RedirectResponse
     */
    public function logout(AurynWorkshopSession $session, RememberMe $rememberMe)
    {
        $session->logout();
        $rememberMe->clearCookie();

        return new RedirectResponse('/login');
    }
}
