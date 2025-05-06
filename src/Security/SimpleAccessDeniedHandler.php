<?php
// src/Security/SimpleAccessDeniedHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class SimpleAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(\Symfony\Component\HttpFoundation\Request $request, AccessDeniedException $accessDeniedException): \Symfony\Component\HttpFoundation\Response
    {
        return new RedirectResponse('/login');
    }
}
?>