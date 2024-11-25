<?php
namespace App\Permissions;

use App\DTO\UserDto;
use App\Enum\UserRoleType;
use ReflectionClass;

final class Abilities{

    public const CustomerCreateOwnReceiver = 'CustomerCreateOwnReceiver';
    public const CustomerUpdateOwnReceiver = 'CustomerUpdateOwnReceiver';
    public const CustomerCreateTransaction = 'CustomerCreateTransaction';
    public const CustomerUpdateTransaction = 'CustomerUpdateTransaction';
    public const AgentCreateSender = 'AgentCreateSender';
    public const AgentUpdateSender = 'AgentUpdateSender';
    public const AgentCreateReceiver = 'AgentCreateReceiver';
    public const AgentUpdateReceiver = 'AgentUpdateReceiver';
    public const AgentCreateTransaction = 'AgentCreateTransaction';
    public const AgentUpdateTransaction = 'AgentUpdateTransaction';

    public const ManagerUpdateUser = 'ManagerUpdateUser';
    public const ManagerUpdateTransaction = 'ManagerUpdateTransaction';
    public const ManagerUpdateSender = 'ManagerUpdateSender';
    public const ManagerUpdateReceiver = 'ManagerUpdateReceiver';
    public const ManagerUpdateOutstanding = 'ManagerUpdateOutstanding';

    public const AdminUpdateUser = 'AdminUpdateUser';
    public const AdminUpdateTransaction = 'AdminUpdateTransaction';
    public const AdminUpdateSender = 'AdminUpdateSender';
    public const AdminUpdateReceiver = 'AdminUpdateReceiver';  
    public const AdminUpdateOutstanding = 'AdminUpdateOutstanding'; 


    public static function getAllAbilities(): array
    {
        $newReflectionClass = new ReflectionClass(__CLASS__);
        // Get all class constants (which are your abilities)
        return $newReflectionClass->getConstants();
    }
    

public function getDefaultScope(): array
{
    return [
        self::CustomerCreateOwnReceiver,
        self::CustomerUpdateOwnReceiver,
        self::CustomerCreateTransaction,
        self::CustomerUpdateTransaction,

    ];
}

    public static function getAbilities(UserDto $user): array
    {
        switch($user->userRoleType) {
            case UserRoleType::ADMIN:
                return [
                    self::AdminUpdateOutstanding,
                    self::AdminUpdateUser,
                    self::AdminUpdateTransaction,
                    self::AdminUpdateSender,
                    self::AdminUpdateReceiver,
                ];
                break;

            case UserRoleType::MANAGER:
                return [
                  self::ManagerUpdateOutstanding,
                  self::ManagerUpdateUser,
                  self::ManagerUpdateTransaction,
                  self::ManagerUpdateSender,
                  self::ManagerUpdateReceiver,
                ];
                break;

            case UserRoleType::AGENT:
                return [
                   self::AgentCreateSender,
                   self::AgentUpdateSender,
                   self::AgentCreateReceiver,
                   self::AgentUpdateReceiver,
                   self::AgentCreateTransaction,
                   self::AgentUpdateTransaction,

                ];
                break;

            case UserRoleType::CUSTOMER:
                return [
                    self::CustomerCreateOwnReceiver,
                    self::CustomerUpdateOwnReceiver,
                    self::CustomerCreateTransaction,
                    self::CustomerUpdateTransaction,
                ];
                break;
        }
    }   
        
   


}