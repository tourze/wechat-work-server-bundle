# 企业微信服务端消息包

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-blue.svg)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-90%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

用于处理企业微信服务端消息和回调事件的 Symfony 包。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
  - [包注册](#包注册)
  - [路由配置](#路由配置)
- [数据库迁移](#数据库迁移)
- [快速开始](#快速开始)
  - [处理服务端消息](#处理服务端消息)
- [使用方法](#使用方法)
  - [消息实体](#消息实体)
  - [控制台命令](#控制台命令)
- [高级用法](#高级用法)
  - [自定义消息处理](#自定义消息处理)
  - [消息过滤](#消息过滤)
- [依赖](#依赖)
- [贡献](#贡献)
  - [开发环境设置](#开发环境设置)
- [许可证](#许可证)

## 功能特性

- 🚀 **服务端消息处理**: 处理企业微信回调消息
- 🔒 **消息加密/解密**: 支持加密消息处理
- 📊 **消息存储**: 使用 Doctrine ORM 存储和查询服务器消息
- 🎯 **事件系统**: 为消息处理分发事件
- 📝 **控制台命令**: 导入和管理服务器消息
- 🔄 **回调控制器**: 处理直接和服务器回调

## 安装

```bash
composer require tourze/wechat-work-server-bundle
```

## 配置

### 包注册

在 Symfony 应用中启用该包：

```php
// config/bundles.php
return [
    // ...
    WechatWorkServerBundle\WechatWorkServerBundle::class => ['all' => true],
];
```

### 路由配置

为服务器回调配置路由：

```yaml
# config/routes.yaml
wechat_work_server:
    resource: '@WechatWorkServerBundle/config/routes.yaml'
    prefix: /wechat-work/server
```

## 数据库迁移

运行以下命令创建必要的数据库表：

```bash
php bin/console doctrine:migrations:migrate
```

## 快速开始

### 处理服务端消息

```php
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            WechatWorkServerMessageRequestEvent::class => 'onServerMessage',
        ];
    }

    public function onServerMessage(WechatWorkServerMessageRequestEvent $event)
    {
        $message = $event->getMessage();
        
        // 根据消息类型进行处理
        switch ($message->getMsgType()) {
            case 'text':
                $this->handleTextMessage($message);
                break;
            case 'event':
                $this->handleEventMessage($message);
                break;
        }
    }
}
```

## 使用方法

### 消息实体

`ServerMessage` 实体用于存储企业微信服务端消息：

```php
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

// 获取消息仓储
$repository = $entityManager->getRepository(ServerMessage::class);

// 从 XML 创建消息
$message = $repository->createFromXML($xmlContent);
if ($message) {
    $entityManager->persist($message);
    $entityManager->flush();
}

// 按类型查找消息
$textMessages = $repository->findBy(['msgType' => 'text']);

// 按用户查找消息
$userMessages = $repository->findBy(['fromUserName' => 'user123']);
```

### 控制台命令

该包提供了用于管理服务器消息的控制台命令：

```bash
# 从日志文件导入服务器消息
php bin/console wechat-work:import-server-message /path/to/message.log
```

**命令详情：**
- **wechat-work:import-server-message** - 从日志文件导入企业微信服务器消息

## 高级用法

### 自定义消息处理

```php
use WechatWorkServerBundle\Controller\ServerCallbackController;
use Symfony\Component\HttpFoundation\Request;

// 自定义回调处理
class CustomCallbackController extends ServerCallbackController
{
    protected function processMessage(array $messageData): void
    {
        // 自定义处理逻辑
        parent::processMessage($messageData);
    }
}
```

### 消息过滤

```php
use WechatWorkServerBundle\Repository\ServerMessageRepository;

class CustomMessageRepository extends ServerMessageRepository
{
    public function findRecentMessages(int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createTime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
```

## 依赖

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+
- tourze/wechat-work-bundle
- tourze/wechat-helper
- tourze/xml-helper

## 贡献

我们欢迎贡献！请遵循以下指南：

1. **问题报告**: 请使用 GitHub Issues 报告 bug 或请求功能
2. **拉取请求**: 
    - Fork 仓库
    - 创建功能分支
    - 进行更改并添加适当的测试
    - 确保 PHPStan 和 PHPUnit 测试通过
    - 提交拉取请求

### 开发环境设置

```bash
# 克隆仓库
git clone https://github.com/your-org/php-monorepo.git
cd php-monorepo

# 安装依赖
composer install

# 运行测试
./vendor/bin/phpunit packages/wechat-work-server-bundle/tests

# 运行静态分析
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-work-server-bundle
```

## 许可证

本项目使用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。
