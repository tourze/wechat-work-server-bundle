<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatWorkServerBundle\Entity\ServerMessage;

#[AdminCrud(routePath: '/wechat_work_server/server_message', routeName: 'wechat_work_server_server_message')]
final class ServerMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServerMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('服务端消息')
            ->setEntityLabelInPlural('服务端消息')
            ->setPageTitle('index', '服务端消息列表')
            ->setPageTitle('detail', '服务端消息详情')
            ->setPageTitle('edit', '编辑服务端消息')
            ->setPageTitle('new', '新建服务端消息')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('toUserName', '企业微信CorpID'))
            ->add(TextFilter::new('fromUserName', '发送方UserID'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(TextFilter::new('msgType', '消息类型'))
            ->add(TextFilter::new('event', '事件类型'))
            ->add(TextFilter::new('changeType', '变更类型'))
            ->add(TextFilter::new('chatId', '群聊ID'))
            ->add(TextFilter::new('externalUserId', '外部联系人ID'))
            ->add(TextFilter::new('userId', '用户ID'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnDetail()
        ;

        yield TextField::new('toUserName', '企业微信CorpID')
            ->setHelp('接收方企业微信CorpID')
        ;

        yield TextField::new('fromUserName', '发送方UserID')
            ->setHelp('发送方成员UserID，可能为空')
            ->hideOnIndex()
        ;

        yield IntegerField::new('createTime', '创建时间')
            ->setHelp('消息创建时间（Unix时间戳）')
            ->formatValue(function ($value) {
                if (!is_int($value)) {
                    return '';
                }

                return date('Y-m-d H:i:s', $value);
            })
        ;

        yield TextField::new('msgType', '消息类型')
            ->setHelp('消息类型，如text、event等')
        ;

        yield TextField::new('event', '事件类型')
            ->setHelp('事件类型，如change_external_contact等')
            ->hideOnIndex()
        ;

        yield TextField::new('changeType', '变更类型')
            ->setHelp('具体的变更类型')
            ->hideOnIndex()
        ;

        yield TextField::new('chatId', '群聊ID')
            ->setHelp('群聊唯一标识')
            ->hideOnIndex()
        ;

        yield TextField::new('externalUserId', '外部联系人ID')
            ->setHelp('外部联系人的userid')
            ->hideOnIndex()
        ;

        yield IntegerField::new('joinScene', '入群场景')
            ->setHelp('成员入群场景')
            ->hideOnIndex()
        ;

        yield IntegerField::new('memChangeCnt', '成员变更数量')
            ->setHelp('成员变更数量')
            ->hideOnIndex()
        ;

        yield IntegerField::new('quitScene', '退群场景')
            ->setHelp('成员退群场景')
            ->hideOnIndex()
        ;

        yield TextField::new('state', '自定义状态')
            ->setHelp('开发者自定义的state参数')
            ->hideOnIndex()
        ;

        yield TextField::new('updateDetail', '更新详情')
            ->setHelp('具体的更新详情')
            ->hideOnIndex()
        ;

        yield TextField::new('userId', '用户ID')
            ->setHelp('企业成员的UserID')
            ->hideOnIndex()
        ;

        yield TextField::new('welcomeCode', '欢迎语code')
            ->setHelp('欢迎语的code')
            ->hideOnIndex()
        ;

        yield AssociationField::new('corp', '企业')
            ->setHelp('关联的企业信息')
            ->hideOnIndex()
        ;

        yield AssociationField::new('agent', '应用')
            ->setHelp('关联的应用信息')
            ->hideOnIndex()
        ;

        yield ArrayField::new('decryptData', '解密数据')
            ->setHelp('Encrypt参数解密后的内容')
            ->onlyOnDetail()
        ;

        yield ArrayField::new('rawData', '原始数据')
            ->setHelp('接收到的原始数据')
            ->onlyOnDetail()
        ;

        yield ArrayField::new('response', '响应数据')
            ->setHelp('处理后的响应数据')
            ->onlyOnDetail()
        ;
    }
}
