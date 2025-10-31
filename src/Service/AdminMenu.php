<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatWorkServerBundle\Entity\ServerMessage;

/**
 * 企业微信服务端消息管理菜单
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('企业微信')) {
            $item->addChild('企业微信');
        }

        $wechatWorkMenu = $item->getChild('企业微信');
        if (null === $wechatWorkMenu) {
            return;
        }

        // 添加服务端子菜单
        if (null === $wechatWorkMenu->getChild('服务端管理')) {
            $wechatWorkMenu->addChild('服务端管理')
                ->setAttribute('icon', 'fas fa-server')
            ;
        }

        $serverMenu = $wechatWorkMenu->getChild('服务端管理');
        if (null === $serverMenu) {
            return;
        }

        $serverMenu->addChild('服务端消息')
            ->setUri($this->linkGenerator->getCurdListPage(ServerMessage::class))
            ->setAttribute('icon', 'fas fa-envelope')
        ;
    }
}
