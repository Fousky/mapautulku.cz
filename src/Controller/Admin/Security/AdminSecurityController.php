<?php declare(strict_types = 1);

namespace App\Controller\Admin\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class AdminSecurityController extends Controller
{
    /** @var AuthenticationUtils */
    protected $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route(name="_admin_login", path="/admin/login")
     *
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return Response
     */
    public function loginAction(AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted('ROLE_SONATA_ADMIN', $this->getUser())) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('admin/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route(name="_admin_login_check", path="/admin/login/check")
     */
    public function loginCheckAction(): void
    {
        throw new \RuntimeException('Must be configured by firewall in security.yml');
    }

    /**
     * @Route(name="sonata_user_admin_security_logout", path="/admin/logout")
     * @Route(name="_admin_logout", path="/admin/logout")
     */
    public function logoutAction(): void
    {
        throw new \RuntimeException('Must be configured by firewall in security.yml');
    }
}
