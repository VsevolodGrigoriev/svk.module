<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class svk_module extends CModule
{
 public function __construct() 
 	{
        if (is_file(__DIR__.'/version.php')) 
	        {
	            include_once(__DIR__.'/version.php');
	            $this->MODULE_ID           = get_class($this);
	            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
	            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
	            $this->MODULE_NAME         = Loc::getMessage('SVK_MODULE_NAME');
	            $this->MODULE_DESCRIPTION  = Loc::getMessage('SVK_MODULE_DESCRIPTION');
	        } 
        else 
	        {
	            CAdminMessage::showMessage(
	                Loc::getMessage('SVK_MODULE_FILE_NOT_FOUND').' version.php'
	            );
	        }
    }

     public function doInstall() 
     {

        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) 
        {
            // копируем файлы, необходимые для работы модуля
            $this->installFiles();
            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
            // регистрируем обработчики событий
           
        } else 
        {
            CAdminMessage::showMessage(
                Loc::getMessage('SVK_MODULE_INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('SVK_MODULE_INSTALL_TITLE').' «'.Loc::getMessage('SVK_MODULE_NAME').'»',
            __DIR__.'/step.php'
        );
    }
    
    public function installFiles() {
              
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"].'/local/modules/svk_module/install/components',
           $_SERVER["DOCUMENT_ROOT"].'/local/components/'.$this->MODULE_ID,
            true,
            true
        );
    }
    
    public function installDB() {
        return;
    }

   

    public function doUninstall() {

        global $APPLICATION;

        $this->uninstallFiles();
        $this->uninstallDB();
      

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('SVK_MODULE_UNINSTALL_TITLE').' «'.Loc::getMessage('SVK_MODULE_NAME').'»',
            __DIR__.'/unstep.php'
        );

    }

    public function uninstallFiles() {
            Directory::deleteDirectory(
            $_SERVER["DOCUMENT_ROOT"].'/local/components/'.$this->MODULE_ID
        );
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }
    
    public function uninstallDB() {
        return;
    }

}
?>