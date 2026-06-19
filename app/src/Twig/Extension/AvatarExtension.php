<?php

namespace App\Twig\Extension;

use App\Entity\User;
use App\Repository\AvatarRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AvatarExtension extends AbstractExtension
{
    public function __construct(private readonly AvatarRepository $avatarRepository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_user_avatar', [$this, 'getUserAvatar']),
        ];
    }

    public function getUserAvatar(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        $avatar = $this->avatarRepository->findOneByUser($user);

        return $avatar ? $avatar->getFilename() : null;
    }
}
