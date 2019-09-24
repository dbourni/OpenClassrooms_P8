<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('username', [$this, 'formatUsername'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public function formatUsername(User $user)
    {
        $username = $user->getUsername();

        if ($this->security->isGranted('ROLE_ADMIN') && $username != 'Anonyme') {
            return '<a href="/users/' . $user->getId() . '/edit">' . $username . '</a>';
        }

        return $username;
    }
}
