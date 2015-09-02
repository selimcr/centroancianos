<?php
namespace Tecnotek\Bundle\AsiloBundle\Services;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LogoutListener implements LogoutSuccessHandlerInterface {

    private $securityContext;
    private $router;
    private $logger;

    public function __construct($securityContext, $router, $logger) {
        $this->securityContext = $securityContext;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onLogoutSuccess(Request $request) {
        //$user = $this->securityContext->getToken()->getUser();
        $this->logger->error("***** Count of cookies: " . $request->cookies->count());
        $request->headers->remove("REMEMBERME");
        $this->logger->error("***** Count of cookies after REMEMBERME 1: " . $request->cookies->count());
        $request->cookies->remove("REMEMBERME");
        $this->logger->error("***** Count of cookies after REMEMBERME 2: " . $request->cookies->count());
        $request->headers->remove("PHPSESSID");
        $this->logger->error("***** Count of cookies after PHPSESSID 1: " . $request->cookies->count());
        $request->cookies->remove("PHPSESSID");
        $this->logger->error("***** Count of cookies after PHPSESSID 2: " . $request->cookies->count());
        $this->logger->error("***** Count of cookies after remove: " . $request->cookies->count());
        $response =  new RedirectResponse($this->router->generate('_welcome'));
        $response->headers->removeCookie("REMEMBERME", '/', null);
        $response->headers->removeCookie('PHPSESSID');

        $request->getSession()->invalidate();
        $request->getSession()->clear();
        //$session->clear();

        return $response;
    }

    public function logout(Request $request, Response $response, TokenInterface $token) {

    }
}