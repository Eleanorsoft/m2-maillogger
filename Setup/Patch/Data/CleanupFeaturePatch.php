<?php

namespace Eleanorsoft\MailLogger\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class CleanupFeaturePatch implements DataPatchInterface, PatchRevertableInterface
{

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        ResourceConnection $resourceConnection
    ) {
        $this->configWriter = $configWriter;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('core_config_data');
        $query = "select * from " . $table . " where path = 'dev/maillogger/enable_email_cleanup'";
        $result = $connection->fetchOne($query);
        if (!$result) {
            $this->configWriter->save("dev/maillogger/enable_email_cleanup", 0);
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->configWriter->delete("dev/maillogger/enable_email_cleanup");
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
