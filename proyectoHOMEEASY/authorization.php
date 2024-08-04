<?php

include_once dirname(__FILE__) . '/' . 'phpgen_settings.php';
include_once dirname(__FILE__) . '/' . 'components/application.php';
include_once dirname(__FILE__) . '/' . 'components/security/permission_set.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_authentication/table_based_user_authentication.php';
include_once dirname(__FILE__) . '/' . 'components/security/grant_manager/hard_coded_user_grant_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/table_based_user_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_identity_storage/user_identity_session_storage.php';
include_once dirname(__FILE__) . '/' . 'components/security/recaptcha.php';
include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';

$grants = array('guest' => 
        array()
    ,
    'carlos' => 
        array('usuarios' => new PermissionSet(false, false, false, false),
        'cotizacion' => new AdminPermissionSet(),
        'stock' => new AdminPermissionSet(),
        'total_stock' => new AdminPermissionSet(),
        'contacto' => new AdminPermissionSet(),
        'registro_inicio' => new AdminPermissionSet())
    ,
    'vladimir' => 
        array('usuarios' => new PermissionSet(false, false, false, false),
        'cotizacion' => new PermissionSet(false, false, false, false),
        'stock' => new PermissionSet(false, false, false, false),
        'total_stock' => new PermissionSet(false, false, false, false),
        'contacto' => new PermissionSet(false, false, false, false),
        'registro_inicio' => new PermissionSet(false, false, false, false))
    ,
    'camila' => 
        array('usuarios' => new PermissionSet(false, false, false, false),
        'cotizacion' => new PermissionSet(false, false, false, false),
        'stock' => new PermissionSet(false, false, false, false),
        'total_stock' => new PermissionSet(false, false, false, false),
        'contacto' => new PermissionSet(false, false, false, false),
        'registro_inicio' => new PermissionSet(false, false, false, false))
    ,
    'defaultUser' => 
        array('usuarios' => new PermissionSet(false, false, false, false),
        'cotizacion' => new PermissionSet(true, false, true, false),
        'stock' => new PermissionSet(false, false, false, false),
        'total_stock' => new PermissionSet(true, false, true, false),
        'contacto' => new PermissionSet(false, false, false, false),
        'registro_inicio' => new PermissionSet(false, false, false, false))
    );

$appGrants = array('guest' => new PermissionSet(false, false, false, false),
    'carlos' => new AdminPermissionSet(),
    'vladimir' => new PermissionSet(false, false, false, false),
    'camila' => new PermissionSet(false, false, false, false),
    'defaultUser' => new PermissionSet(false, false, false, false));

$dataSourceRecordPermissions = array();

$tableCaptions = array('usuarios' => 'Usuarios',
'cotizacion' => 'Cotizacion',
'stock' => 'Stock',
'total_stock' => 'Total Stock',
'contacto' => 'Contacto',
'registro_inicio' => 'Registro Inicio');

$usersTableInfo = array(
    'TableName' => 'registro_inicio',
    'UserId' => 'user_id',
    'UserName' => 'user_name',
    'Password' => 'user_password',
    'Email' => 'user_email',
    'UserToken' => 'user_token',
    'UserStatus' => 'user_status'
);

function EncryptPassword($password, &$result)
{

}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{

}

function BeforeUserRegistration($userName, $email, $password, &$allowRegistration, &$errorMessage)
{

}    

function AfterUserRegistration($userName, $email)
{

}    

function PasswordResetRequest($userName, $email)
{

}

function PasswordResetComplete($userName, $email)
{

}

function VerifyPasswordStrength($password, &$result, &$passwordRuleMessage) 
{

}

function CreatePasswordHasher()
{
    $hasher = CreateHasher('SHA256');
    if ($hasher instanceof CustomStringHasher) {
        $hasher->OnEncryptPassword->AddListener('EncryptPassword');
        $hasher->OnVerifyPassword->AddListener('VerifyPassword');
    }
    return $hasher;
}

function CreateGrantManager() 
{
    global $grants;
    global $appGrants;
    
    return new HardCodedUserGrantManager($grants, $appGrants);
}

function CreateTableBasedUserManager() 
{
    global $usersTableInfo;

    $userManager = new TableBasedUserManager(MySqlIConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 
        $usersTableInfo, CreatePasswordHasher(), true);
    $userManager->OnVerifyPasswordStrength->AddListener('VerifyPasswordStrength');

    return $userManager;
}

function GetReCaptcha($formId) 
{
    return null;
}

function SetUpUserAuthorization() 
{
    global $dataSourceRecordPermissions;

    $hasher = CreatePasswordHasher();

    $grantManager = CreateGrantManager();

    $userAuthentication = new TableBasedUserAuthentication(new UserIdentitySessionStorage(), false, $hasher, CreateTableBasedUserManager(), true, true, true);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
