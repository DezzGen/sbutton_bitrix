<?

	// пространство имен для подключений ланговых файлов
	use Bitrix\Main\Localization\Loc;
	// пространство имен для управления (регистрации/удалении) модуля в системе/базе
	use Bitrix\Main\ModuleManager;
	// пространство имен для работы с параметрами модулей хранимых в базе данных
	use Bitrix\Main\Config\Option;
	// пространство имен с абстрактным классом для любых приложений, любой конкретный класс приложения является наследником этого абстрактного класса
	use Bitrix\Main\Application;
	// пространство имен для работы с директориями
	use Bitrix\Main\IO\Directory;
	// подключение ланговых файлов
	Loc::loadMessages(__FILE__);

	Class apecoder_test extends CModule{

		var $MODULE_ID = "apecoder.test";
		var $MODULE_VERSION;
		var $MODULE_VERSION_DATE;
		var $MODULE_NAME;
		var $MODULE_DESCRIPTION;

		public function __construct(){

			$arModuleVersion = array();

			include(__DIR__.'/version.php');

			$this->MODULE_ID = 'apecoder.test';

			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

			$this->MODULE_NAME = Loc::getMessage("APECODER_TEST_INSTALL_NAME");
			$this->MODULE_DESCRIPTION = Loc::getMessage("APECODER_TEST_INSTALL_DESCRIPTION");

			$this->PARTNER_NAME = "Макаров Денис Юрьевич";
			$this->PARTNER_URI = "https://google.com";

		}





		function DoInstall()
		{
			$this->InstallFiles();
			$this->InstallDB();
	
			return true;
		}

		function DoUninstall()
		{
			$this->UnInstallDB();
			return true;
		}

		function InstallDB()
		{
			ModuleManager::RegisterModule( $this->MODULE_ID );
			return true;
		}

		function UnInstallDB()
		{	
			ModuleManager::UnRegisterModule( $this->MODULE_ID );
			return true;
		}

		function InstallEvents()
		{
			return true;
		}

		function UnInstallEvents()
		{
			return true;
		}

		function InstallFiles()
		{
			return true;
		}

		function UnInstallFiles()
		{
			return true;
		}

	}
?>